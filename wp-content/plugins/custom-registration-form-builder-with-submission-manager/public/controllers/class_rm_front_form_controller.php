<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_rm_front_form_controller
 *
 * @author CMSHelplive
 */
class RM_Front_Form_Controller
{

    private $mv_handler;
    private $form_factory;

    public function __construct()
    {
        $this->mv_handler = new RM_Model_View_Handler();
        $this->form_factory = new RM_Form_Factory();
    }
    
    protected function banned_view()
    {
        $data = new stdClass;
        $data->banned = true;            
        $view = $this->mv_handler->setView("user_form_nexgen", true);
        return $view->read($data,true   );
    }
    
    //New form handling
    public function process($model, $service, $request, $params)
    {
        if($service->is_ip_banned())
        {
            return $this->banned_view();
        }
        
        global $rm_form_diary;
        
        if(count($rm_form_diary)>0 && !isset($params['force_enable_multiform']))
            return;

        if (isset($params['form_id']) && $params['form_id'])
        {
            $form_id = $params['form_id'];
            $fe_form = $this->form_factory->create_form($form_id);
            $form_name = 'form_' . $fe_form->get_form_id();
        } else
        {
            return;
        }             
        
        $fopts = $fe_form->get_form_options();       
        
        if($fe_form->is_expired() && $fopts->post_expiry_action == 'switch_to_another_form')
        {
            $form_id = $fopts->post_expiry_form_id;
            if ($form_id)
            {
                $fe_form = $this->form_factory->create_form($form_id);
                $form_name = 'form_' . $form_id;
                $params['form_id'] = $form_id;
            } else
            {
                return;
            }
        }
        if (isset($request->req['rm_pproc'], $request->req['rm_fid'], $request->req['rm_fno'], $rm_form_diary[$form_id])
                && $request->req['rm_fid'] == $form_id && $request->req['rm_fno'] == $rm_form_diary[$form_id])
        {   $paypal_service= new RM_Paypal_Service();
            ob_start();
            $resp = $paypal_service->callback($request->req['rm_pproc'], isset($request->req['rm_pproc_id'])?$request->req['rm_pproc_id']:null);
            $paypal_callback_msg = ob_get_clean();
            $x = new stdClass;
            $x->form_options = $fe_form->get_form_options();
            $x->form_name = $fe_form->get_form_name();
            $after_sub_msg = $service->after_submission_proc($x);            
            return $paypal_callback_msg.'<br><br>'.$after_sub_msg;
        }
        
        if ($service->is_off_limit_submission($form_id, $fopts))
                return RM_UI_Strings::get("ALERT_SUBMISSIOM_LIMIT");
        
        //Call form specific processing before submission.
        $form_preproc_response = $fe_form->pre_sub_proc($request->req, $params);
        
        if (isset($request->req['stat_id']))
            $stat_id = $request->req['stat_id'];
        else
            $stat_id = null;
        
        if (isset($request->req['rm_form_sub_no']) && $request->req['rm_form_sub_no'])
            $subbed_form_no = $request->req['rm_form_sub_no'];
        else
            $subbed_form_no = null;
        
        if ($subbed_form_no && ($fe_form->get_form_number() == $subbed_form_no) && $form_preproc_response && $this->mv_handler->validateForm($form_name."_".$subbed_form_no) && !$service->is_browser_reload_duplication($stat_id))
        {         
            $primary_data = $fe_form->get_prepared_data($request->req, 'primary');
    
            $db_data = $fe_form->get_prepared_data($request->req, 'dbonly');

            $sub_detail = $service->save_submission($form_id, $db_data, $primary_data['user_email']->value);
            
            if(isset($sub_detail))
                $service->update_stat_entry($stat_id,'update',$sub_detail->submission_id);            

            $form_options = $fe_form->get_form_options();
            
            if((int)($form_options->should_export_submissions) === 1)
            {
                $service->export_to_external_url($form_options->export_submissions_to_url, $db_data);
            }

            if ($form_options->form_is_unique_token)
                $token = $sub_detail->token;
            else
                $token = null;

            if ($form_options->form_should_send_email)
            {
                $parameters = new stdClass; //This is different then the $params in the argument of this function!
                $parameters->req = $request->req;
                $parameters->db_data = $db_data;
                $parameters->email = $primary_data['user_email']->value;
                $parameters->email_content = $form_options->form_email_content;
                $parameters->email_subject = $form_options->form_email_subject;
                $parameters->sub_id = $sub_detail->submission_id;
                $parameters->form_id = $form_id;
                RM_Email_Service::auto_responder($parameters,$token);
            }
            
            $submissions = new RM_Submissions;
            $submissions->load_from_db($sub_detail->submission_id);
            $parameters = new stdClass;
            $parameters->sub_data = $submissions->get_data();
            $parameters->form_name = $fe_form->get_form_name();
            $parameters->sub_id = $sub_detail->submission_id;
            $parameters->form_id = $form_id;
            RM_Email_Service::notify_submission_to_admin($parameters,$token);              
            
            $params['sub_detail'] = $sub_detail;

            /*
             * Check for payment
             */
             //also call Form specific method after submission
            $prevent_redirection = false;
            
            if($fe_form->has_price_field() && $service->get_setting('payment_gateway'))
            {
                $params['paystate'] = 'pre_payment';
                $fe_form->post_sub_proc($request->req, $params, false);               
                $params['is_paid'] = $service->process_payment($fe_form, $request, $params);
                if ($params['is_paid'] === 'do_not_redirect')
                {
                    $params['paystate'] = 'post_payment';
                    $fe_form->post_sub_proc($request->req, $params, false);
                    $this->update_user_profile($primary_data['user_email']->value, $db_data, $service);
                    $prevent_redirection = true;
                }
                else
                {
                    $params['paystate'] = 'post_payment';
                    $fe_form->post_sub_proc($request->req, $params, $params['is_paid']);
                    $this->update_user_profile($primary_data['user_email']->value, $db_data, $service);
                }
            }
            else
            {
                $params['paystate'] = 'na';
                $fe_form->post_sub_proc($request->req, $params);
                $this->update_user_profile($primary_data['user_email']->value, $db_data, $service);
            }

            
            unset($parameters->sub_data);
            $parameters->form_options = $form_options;
            
            if(!$prevent_redirection)
                return $service->after_submission_proc($parameters); //This must be returned as there is no ob_start here at work.
        }else
        {                    
            $data = new stdClass;
            $data->stat_id = $service->create_stat_entry($params);
            $data->fe_form = $fe_form;
            
            $force_multiple_form = isset($params['force_enable_multiform'])?true:false;
            
            $view = $this->mv_handler->setView("user_form_nexgen", true);             
            return $view->read($data, $force_multiple_form);
        }
    }
    
    public function update_user_profile($email, $db_data, $service)
    {
        //Update User profile
        $profile_array = array();
        foreach ($db_data as $field)
        {
            if ($field->type === 'Fname' || $field->type === 'Lname' || $field->type === 'BInfo'|| $field->type === 'Nickname'|| $field->type === 'Website')
            {
                $profile_array[$field->type] = $field->value;
            }
        }

        $service->update_user_profile($email, $profile_array, true);
    }

    public function test_form_access_v2(RM_Frontend_Form_Base $fe_form, $service, $request, $params)
    {
        return null;
    }
    
    //Form to edit prev submission
    public function edit_sub($model, RM_Front_Form_Service $service, $request, $params) {
        if ($service->is_ip_banned()) {
            return $this->banned_view();
        }

        global $rm_form_diary;

        if (count($rm_form_diary) > 0 && !isset($params['force_enable_multiform']))
            return;
        $params['form_id'] = $request->req['form_id'];
        if (isset($params['form_id'],$request->req['submission_id']) && $params['form_id'] && $request->req['submission_id']) {
            $form_id = $params['form_id'];
            $request->req['submission_id'] = $service->get_latest_submission_from_group($request->req['submission_id']);
            $fe_form = $this->form_factory->create_form_prefilled($form_id,$request->req['submission_id']);
            $form_name = 'form_' . $fe_form->get_form_id();
        } else {
            return;
        }

        $fopts = $fe_form->get_form_options();

        //Call form specific processing before submission.
        $form_preproc_response = $fe_form->pre_sub_proc($request->req, $params);
        /*if (isset($request->req['stat_id']))
            $stat_id = $request->req['stat_id'];
        else
            $stat_id = null;*/

        if (isset($request->req['rm_form_sub_no']) && $request->req['rm_form_sub_no'])
            $subbed_form_no = $request->req['rm_form_sub_no'];
        else
            $subbed_form_no = null;

        if ($subbed_form_no && ($fe_form->get_form_number() == $subbed_form_no) && $form_preproc_response && $this->mv_handler->validateForm($form_name . "_" . $subbed_form_no) /*&& !$service->is_browser_reload_duplication($stat_id)*/) {
            
            //$service->update_stat_entry($stat_id);
            
            $primary_data = $fe_form->get_prepared_data($request->req, 'primary');

            $db_data = $fe_form->get_prepared_data($request->req, 'dbonly');

            $sub_detail = $service->save_edited_submission($form_id, $request->req['submission_id'], $db_data, $primary_data['user_email']->value);
            
            $form_options = $fe_form->get_form_options();

            /* if ((int) ($form_options->should_export_submissions) === 1)
              {
              $service->export_to_external_url($form_options->export_submissions_to_url, $db_data);
              } */
            
            if((int)($form_options->should_export_submissions) === 1)
            {
                $service->export_to_external_url($form_options->export_submissions_to_url, $db_data);
            }

            if ($form_options->form_is_unique_token)
                $token = $sub_detail->token;
            else
                $token = null;

            $submissions = new RM_Submissions;
            $submissions->load_from_db($sub_detail->submission_id);
            $parameters = new stdClass;
            $parameters->sub_data = $submissions->get_data();
            $parameters->form_name = $fe_form->get_form_name();
            $parameters->sub_id = $sub_detail->submission_id;
            $parameters->form_id = $form_id;
            RM_Email_Service::notify_submission_to_admin($parameters,$token);

            $params['sub_detail'] = $sub_detail;

            /*
             * Check for payment
             */
            //also call Form specific method after submission
            $prevent_redirection = false;

            $params['paystate'] = 'na';
            $fe_form->post_sub_proc($request->req, $params);
            $this->update_user_profile($primary_data['user_email']->value, $db_data, $service);

            //redirect user to new submissions page
            $form_options->redirection_type = 'url';
            $form_options->redirect_url = add_query_arg( 'submission_id',$sub_detail->submission_id, get_permalink());
            
            unset($parameters->sub_data);
            $parameters->form_options = $form_options;

            if (!$prevent_redirection)
                return $service->after_submission_proc($parameters); //This must be returned as there is no ob_start here at work.
            }//End form valiadtion condition
            else {
                //procedure to render the form if not valid
            $data = new stdClass;
            $data->stat_id = 0;//$service->create_stat_entry($params);
            $data->fe_form = $fe_form;
            $data->submission_id = $request->req['submission_id'];

            $force_multiple_form = isset($params['force_enable_multiform']) ? true : false;

            $view = $this->mv_handler->setView("user_form_nexgen", true);
            return $view->read($data, $force_multiple_form);
        }
    }
    
}
