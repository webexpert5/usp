<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Controller to handle USER related requests
 *
 * @author CMSHelplive
 */
class RM_USER_Controller
{

    public $mv_handler;

    function __construct()
    {
        $this->mv_handler = new RM_Model_View_Handler();
    }

    /*
     * List all the existing user roles
     */

    public function role_manage($model, RM_User_Services $service, $request, $params)
    {
        $roles = $service->get_roles_by_status();

        // To remove existing errors from the form
        if (!isset($request->req['rm_submitted']))
            $this->mv_handler->clearFormErrors("rm_user_role_add_form");
        $view_data = new stdClass();
        $view_data->roles = $roles;
        $view = $this->mv_handler->setView('user_roles_manager');
        $view->render($view_data);
    }

    /*
     * Creation of new Role
     */

    public function role_add($model, RM_User_Services $service, $request, $params)
    {
        if ($this->mv_handler->validateForm("rm_user_role_add_form"))
        {
            if (isset($request->req['rm_role_name']) && $request->req['rm_display_name'] && $request->req['rm_user_capability'])
                if (!$service->create_role($request->req['rm_role_name'], $request->req['rm_display_name'], $request->req['rm_user_capability']))
                {
                    //Role add was not success
                }
        } else
        {

            // Edit for request
            if (isset($request->req['role_id']))
            {
                $model->load_from_db($request->req['role_id']);
            }
        }
        $this->role_manage($model, $service, $request, $params);
    }

    /*
     * Deletion of a role. After deletion all the corresponding users automatically assigns to subscriber role.
     */

    public function role_delete($model, RM_User_Services $service, $request, $params)
    {
        if (isset($request->req['rm_roles']))
        {
            $service->delete_roles($request->req['rm_roles']);
        }

        $this->role_manage($model, $service, $request, $params);
    }

    public function manage($model, RM_User_Services $service, $request, $params)
    {
        $filter= new RM_User_Filter($request,$service);
        $view_data = new stdClass();
        $view_data->filter= $filter;
        $view_data->users = $filter->get_records();
        $view_data->rm_slug = $request->req['page'];
        $view = $this->mv_handler->setView('user_manager');
        $view->render($view_data);
    }

    public function delete($model, RM_User_Services $service, $request, $params)
    {
        if (isset($request->req['rm_users']))
            $users = $service->delete($request->req['rm_users']);
        
        RM_Utilities::redirect('?page=rm_user_manage');
    }

    public function deactivate($model, RM_User_Services $service, $request, $params)
    {
        if (isset($request->req['rm_users']))
            $users = $service->deactivate($request->req['rm_users']);
        RM_Utilities::redirect('?page=rm_user_manage');
    }

    public function activate($model, RM_User_Services $service, $request, $params)
    {

        if (isset($request->req['rm_users']))
            $users = $service->activate($request->req['rm_users']);
            $service->notify_users($request->req['rm_users'],'user_activated');
        RM_Utilities::redirect('?page=rm_user_manage');
    }

    public function view($model, RM_User_Services $service, $request, $params)
    {

        if (isset($request->req['user_id']))
        {
            $curr_user = wp_get_current_user();
            if (isset($curr_user->ID))
                $curr_user_id = $curr_user->ID;
            else
                $curr_user_id = null;

            $user = $service->get_user_by('id', $request->req['user_id']);

            if (!$user instanceof WP_User)
            {
                $view = $this->mv_handler->setView('show_notice');
                $data = RM_UI_Strings::get('MSG_DO_NOT_HAVE_ACCESS');
                $view->render($data);
                return;
            }

            $view_data = new stdClass();
            $view_data->user = $user;
            $view_data->user_meta = get_user_meta($request->req['user_id']);
            $view_data->custom_fields = $service->get_custom_fields($user->user_email);
            $view_data->curr_user = $curr_user_id;
            $view_data->submissions = array();
            $view_data->payments = array();
            $view_data->sent_emails = array();
            
            $sent_emails = $service->get('SENT_EMAILS',array('to' => $user->user_email), array('%s'), 'results', 0, 10, '*', null, true);
            $view_data->sent_emails = $sent_emails;

            $submissions = $service->get_submissions_by_email($user->user_email, 10);
            if ($submissions) {
                $i = 0;
                foreach ($submissions as $submission) {
                    $form_name = $service->get('FORMS', array('form_id' => $submission->form_id), array('%d'), 'var', 0, 1, 'form_name');

                    $view_data->submissions[$i] = new stdClass();
                    $view_data->submissions[$i]->submission_id = $submission->submission_id;
                    $view_data->submissions[$i]->submitted_on = $submission->submitted_on;
                    $view_data->submissions[$i]->form_id = $submission->form_id;
                    $view_data->submissions[$i++]->form_name = $form_name;

                    $result = $service->get('PAYPAL_LOGS', array('submission_id' => $service->get_oldest_submission_from_group($submission->submission_id)), array('%d'), 'row', 0, 10, '*', null, true);
                    if ($result)
                        $view_data->payments[] = array('form_name' => $form_name, 'submission_id' => $submission->submission_id, 'form_id' => $submission->form_id, 'payment' => $result);
                }
            }

            $view = $this->mv_handler->setView('user_view');
            $view->render($view_data);
        } else
                RM_Utilities::redirect('?page=rm_user_manage');
    }

    /*
      public function search($model, RM_User_Services $service, $request, $params)
      {
      $request->user_ids = array();
      if (isset($request->req['rm_to_search']))
      {
      $keyword = $request->req['rm_to_search'];
      $args = array(
      'search' => '*' . $keyword . "*",
      'search_columns' => array('display_name', 'user_email', "user_login")
      );
      $a = new WP_User_Query($args);
      //echo '<pre>';

      $authors = $a->get_results();

      //echo'<pre>';var_dump($authors);die;
      }

      if (isset($request->req['rm_search_by']))
      {
      if (isset($request->req['filter_between']) && is_array($request->req['filter_between']))
      {
      $user_ids = $service->user_search($request->req['filter_between'], 'time');

      $request->user_ids = $user_ids;
      }

      if (isset($request->req['user_status']) && is_array($request->req['user_status']))
      {
      $user_ids = $service->user_search($request->req['user_status'], 'user_status');

      $request->user_ids = $user_ids;
      }

      if (isset($request->req['field_name']) && trim($request->req['rm_search_by']) != "")
      {
      $user_ids = $service->user_search($request->req['rm_search_by'], $request->req['field_name']);

      $request->user_ids = $user_ids;
      }
      }

      $this->manage($model, $service, $request, null);
      }
     */

    public function edit($model, RM_User_Services $service, $request, $params)
    {
        if (isset($request->req['user_id']))
        {
            if ($this->mv_handler->validateForm("rm_edit_user"))
            {
                if (isset($request->req['user_password']) && isset($request->req['user_password_conf']))
                {
                    if ($request->req['user_password'] && $request->req['user_password_conf'] && $request->req['user_id'])
                        $service->reset_user_password($request->req['user_password'], $request->req['user_password_conf'], $request->req['user_id']);
                    $service->set_user_role($request->req['user_id'], $request->req['user_role']);
                } else
                {
                    die(RM_UI_Strings::get('MSG_USER_PASS_NOT_SET'));
                }
                $this->view($model, $service, $request, $params);
            } else
            {
                if (!isset($request->req['rm_submitted']))
                {
                    $this->mv_handler->clearFormErrors("rm_edit_user");
                }
                $view_data = new stdClass();
                $view_data->user = $service->get_user_by('id', $request->req['user_id']);
                $view_data->roles = RM_Utilities::user_role_dropdown(false);
                $view = $this->mv_handler->setView('user_edit');
                $view->render($view_data);
            }
        }
    }

    public function widget($model, RM_User_Services $service, $request, $params)
    {
        if ($params['user'] instanceof WP_User)
        {
            $data = new stdClass;

            $submissions = $service->get_submissions_by_email($params['user']->user_email, 10);

            $sub_data = array();

            $count = 0;
            if ($submissions)
            {
                foreach ($submissions as $submission)
                {
                    //echo "<br>ID: ".$submission->form_id." : ".RM_Utilities::localize_time($submission->submitted_on, 'M dS Y, h:ia')." : ";
                    $name = $service->get('FORMS', array('form_id' => $submission->form_id), array('%d'), 'var', 0, 10, 'form_name');
                    $date = RM_Utilities::localize_time($submission->submitted_on, 'M dS Y, h:ia');
                    $payment_status = $service->get('PAYPAL_LOGS', array('submission_id' => $submission->submission_id), array('%d'), 'var', 0, 10, 'status');

                    $sub_data[] = (object) array('submission_id' => $submission->submission_id, 'name' => $name, 'date' => $date, 'payment_status' => $payment_status);

                    $count++;
                }
            }

            $data->submissions = $sub_data;
            $data->total_sub = $count;

            $view = $this->mv_handler->setView('user_edit_widget');
            $view->render($data);
        }
    }
    
     public function exists($model, $service, $request, $params){
        // Check if form is User Registration type
         if(isset($request->req['form_id']) && $request->req['form_id'])
         {

             $form = new RM_Forms;
             $form->load_from_db($request->req['form_id']);
             if($form->get_form_type() != 1 ):
                   echo "false";
                 die;
             endif;
         }
             
         if(isset($request->req['username']) && $request->req['username']){
             $user= get_user_by('login', $request->req['username']);
             if($user instanceof WP_User)
                 echo "true";
             else
                echo "false";
             die;
             
         }
         
         if(isset($request->req['email']) && $request->req['email']){
             $user= get_user_by('email', $request->req['email']);
             if($user instanceof WP_User)
                 echo "true";
             else
                echo "false";
             die;
             
         }
         
     }

} 
