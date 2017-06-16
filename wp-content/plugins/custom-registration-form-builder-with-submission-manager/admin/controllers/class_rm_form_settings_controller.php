<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_rm_form_settings_controller
 *
 * @author Cmshelplive
 */
class RM_Form_Settings_Controller {

    public $mv_handler;

    function __construct() {
        $this->mv_handler = new RM_Model_View_Handler();
    }

    function manage($form,  RM_Services $service, $request) {
        $data = new stdClass();
        if (!isset($request->req['rm_form_id']) || !(int)$request->req['rm_form_id'] || !$form->load_from_db($request->req['rm_form_id'])){
            $view = $this->mv_handler->setView('show_notice');
            $view->render(RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED'));
           return;
        }
                
        $form_options = $form->get_form_options();
        $global_option = new RM_Options;
        $acc_ctrl = $form_options->access_control;
        $data->form_id = (int)$request->req['rm_form_id'];
        $data->all_forms = RM_Utilities::get_forms_dropdown($service);
        unset($data->all_forms[$data->form_id]);
        $data->form = $form;
        $data->form_options = $form_options;
        $data->form_access = RM_UI_Strings::get('FD_LABEL_PUBLIC');
        $data->captcha_sitekey = $global_option->get_value_of('public_key');
        $data->captcha_secret = $global_option->get_value_of('private_key');
        $data->auto_approval = $global_option->get_value_of('user_auto_approval');
        $this->add_form_stats($data);
        $this->add_form_submission_data($data);
        $this->add_form_attchment_data($data);
        
        if(isset($request->req['rm_tr']))
        {
            $data->timerange = $request->req['rm_tr'];            
        }
        else
        {
            $data->timerange = '30';
        }
        
        $this->add_form_timewise_stat($data);
        
        //Include joyride script and style
        wp_enqueue_script('rm_joyride_js', RM_BASE_URL.'admin/js/jquery.joyride-2.1.js');
        wp_enqueue_style('rm_joyride_css', RM_BASE_URL.'admin/css/joyride-2.1.css');
        
        $data->autostart_tour = !RM_Utilities::has_taken_tour('form_setting_dashboard_tour');
        
        $view = $this->mv_handler->setView('form_settings');
        $view->render($data);
    }

    function general($model, $service, $request, $params) {
        if ($this->mv_handler->validateForm("form_sett_general")) {
            $options = array();
            $options['form_type'] = $request->req['form_type'];
            $options['form_name'] = $request->req['form_name'];
            $options['form_description'] = $request->req['form_description'];
            $options['form_custom_text'] = $request->req['form_custom_text'];
            $options['show_total_price'] = $request->req['show_total_price'];

            if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
                $model->load_from_db($request->req['rm_form_id']);
                $model->set($options);
                $model->update_into_db();
                RM_Utilities::redirect('?page=rm_form_sett_manage&rm_form_id='.$request->req['rm_form_id']);
                return;
            } else {
                $model->set($options);
                $form_id = $service->add_user_form();
                RM_Utilities::redirect('?page=rm_form_sett_manage&rm_form_id='.$form_id);
                return;
            }
        }
        //Include joyride script and style
        wp_enqueue_script('rm_joyride_js', RM_BASE_URL.'admin/js/jquery.joyride-2.1.js');
        wp_enqueue_style('rm_joyride_css', RM_BASE_URL.'admin/css/joyride-2.1.css');
        $data = new stdClass();
        $data->autostart_tour = !RM_Utilities::has_taken_tour('form_gensett_tour');
        $view = $this->mv_handler->setView('form_gen_sett');
        if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id'])
            $model->load_from_db($request->req['rm_form_id']);
        $data->model = $model;
        $view->render($data);
    }

    function limits($model, $service, $request, $params) {

        if ($this->mv_handler->validateForm("form_sett_limits")) {
            $options['form_should_auto_expire'] = isset($request->req['form_should_auto_expire']) ? $request->req['form_should_auto_expire'] : null;
            $options['form_expired_by'] = isset($request->req['form_expired_by']) ? $request->req['form_expired_by'] : null;
            $options['form_submissions_limit'] = $request->req['form_submissions_limit'];
            $options['form_expiry_date'] = $request->req['form_expiry_date'];
            
            if(isset( $request->req['form_message_after_expiry']))
                $options['form_message_after_expiry'] = $request->req['form_message_after_expiry'];
            
            $options['enable_captcha'] = isset($request->req['enable_captcha']) ? $request->req['enable_captcha'] : null;
            //$options['allow_multiple_file_uploads'] =isset( $request->req['allow_multiple_file_uploads']) ? $request->req['allow_multiple_file_uploads'] : null;
            $options['sub_limit_antispam'] = isset( $request->req['sub_limit_antispam']) ? $request->req['sub_limit_antispam'] : null;
            $options['post_expiry_action'] = isset( $request->req['post_expiry_action']) ? $request->req['post_expiry_action'] : 'display_message';
            $options['admin_notification'] = isset($request->req['admin_notification']) ? "yes" : null;
            if (isset($request->req['resp_emails']))
                 $options['admin_email'] = implode(",", $request->req['resp_emails']);        
            if(isset( $request->req['post_expiry_form_id']))
                $options['post_expiry_form_id'] = $request->req['post_expiry_form_id'];
            //var_dump($request->req);die;
            if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
                $model->load_from_db($request->req['rm_form_id']);
                $model->set($options);
                if ($model->validate_limits('form_sett_limits')) {
                    $model->update_into_db();
                RM_Utilities::redirect('?page=rm_form_sett_manage&rm_form_id='.$request->req['rm_form_id']);
                    return;
                } else
                    $visited = true;
            } else {
                echo '<div class="rmnotice">' . RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED') . '</div>';
                return;
            }
        }

        if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
            $data = new stdClass();
            if (!isset($visited))
                $model->load_from_db($request->req['rm_form_id']);
            $view = $this->mv_handler->setView('form_limits_sett');
            $data->model = $model;
            $data->form_dropdown = RM_Utilities::get_forms_dropdown($service);
        } else {
            $view = $this->mv_handler->setView('show_notice');
            $data = RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED');
        }
        $view->render($data);
    }

    function post_sub($model, $service, $request, $params) {
        if ($this->mv_handler->validateForm("form_sett_post_sub")) {
            $options['form_success_message'] = $request->req['form_success_message'];
            
            $options['form_redirect'] = isset($request->req['form_redirect']) ? $request->req['form_redirect'] : 'none';
            $options['form_redirect_to_page'] = $request->req['form_redirect_to_page'];
            $options['form_redirect_to_url'] = $request->req['form_redirect_to_url'];
            

            if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
                $model->load_from_db($request->req['rm_form_id']);
                $model->set($options);
                if ($model->validate_post_sub('form_sett_post_sub')) {
                    $model->update_into_db();
                RM_Utilities::redirect('?page=rm_form_sett_manage&rm_form_id='.$request->req['rm_form_id']);
                    return;
                } else
                    $visited = true;
            } else {
                echo '<div class="rmnotice">' . RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED') . '</div>';
                return;
            }
        }
        if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
            $data = new stdClass();
            $view = $this->mv_handler->setView('form_post_sub_sett');
            if (!isset($visited))
                $model->load_from_db($request->req['rm_form_id']);
            $data->model = $model;
            $data->wp_pages = RM_Utilities::wp_pages_dropdown();
        }else {
            $view = $this->mv_handler->setView('show_notice');
            $data = RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED');
        }
        $view->render($data);
    }

    function autoresponder($model, $service, $request, $params) {

        if ($this->mv_handler->validateForm("form_sett_autoresponder")) {

            $options['form_should_send_email'] = isset($request->req['form_should_send_email']) ? $request->req['form_should_send_email'] : null;
            $options['form_email_subject'] = $request->req['form_email_subject'];
            $options['form_email_content'] = $request->req['form_email_content'];
            
            $options['form_nu_notification'] = $request->req['form_nu_notification'];
            $options['form_user_activated_notification'] = $request->req['form_user_activated_notification'];
           // $options['form_activate_user_notification'] = $request->req['form_activate_user_notification'];
            $options['form_admin_ns_notification'] = $request->req['form_admin_ns_notification'];
            
            if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
                $model->load_from_db($request->req['rm_form_id']);
                $model->set($options);
                if ($model->validate_autoresponder('form_sett_autoresponder')) {
                    $model->update_into_db();
                RM_Utilities::redirect('?page=rm_form_sett_manage&rm_form_id='.$request->req['rm_form_id']);
                    return;
                } else {
                    $visited = true;
                }
            } else {
                echo '<div class="rmnotice">' . RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED') . '</div>';
                return;
            }
        }

        if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
            if (!isset($visited))
                $model->load_from_db($request->req['rm_form_id']);
            $data = new stdClass();
            $data->model = $model;
            $view = $this->mv_handler->setView('form_autoresponder_sett');
        }else {
            $view = $this->mv_handler->setView('show_notice');
            $data = RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED');
        }
        $view->render($data);
    }

    function accounts($model, $service, $request, $params) {

        if ($this->mv_handler->validateForm("form_sett_accounts")) {

            $options['form_type'] = isset($request->req['form_type']) ? RM_REG_FORM : RM_CONTACT_FORM;
            $options['default_form_user_role'] = 'subscriber';
            $options['form_user_role'] = isset($request->req['form_user_role']) ? $request->req['form_user_role'] : array();
            $options['form_should_user_pick'] = isset($request->req['form_should_user_pick']) ? $request->req['form_should_user_pick'] : null;
            $options['form_user_field_label'] = '';
            $options['auto_login'] = isset($request->req['auto_login']) ? 1 : null;
            
            if (isset($request->req['rm_form_id'])  && (int)$request->req['rm_form_id']) {
                $model->load_from_db($request->req['rm_form_id']);
                $model->set($options);
                if ($model->validate_accounts('form_sett_accounts')) {
                    $model->update_into_db();
                RM_Utilities::redirect('?page=rm_form_sett_manage&rm_form_id='.$request->req['rm_form_id']);
                    return;
                } else
                    $visited = true;
            } else {
                echo '<div class="rmnotice">' . RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED') . '</div>';
                return;
            }
        }

        if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
            $data = new stdClass();
            $data->roles = RM_Utilities::user_role_dropdown(true);
            if (!isset($visited))
                $model->load_from_db($request->req['rm_form_id']);
            $data->model = $model;
            $view = $this->mv_handler->setView('form_accounts_sett');
        }else {
            $data = RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED');
            $view = $this->mv_handler->setView('show_notice');
        }
        $view->render($data);
    }

    function view($model, $service, $request, $params) {
        if (!$request instanceof RM_Request) {
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            if(isset($request->form_id) && (int)$request->form_id){
                $model = new RM_Forms;
                $model->load_from_db($request->form_id);
                $model->set((array)$request);
                $model->update_into_db();
                echo 'saved';
            }else
                echo 'not saved';
            die;
        }
        if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
            $data = new stdClass();
            $view = $this->mv_handler->setView('form_view_sett');
            $model->load_from_db($request->req['rm_form_id']);
            $data->model = $model;
        } else {
            $data = RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED');
            $view = $this->mv_handler->setView('show_notice');
        }
        $view->render($data);
    }

    function mailchimp($model, $service, $request, $params) {
        if ($this->mv_handler->validateForm("form_sett_mailchimp")) {
            $options = array();
            $options['mailchimp_list'] = $request->req['mailchimp_list'];
            $options['mailchimp_mapped_email'] = isset($request->req['email'])?$request->req['email']:null;
            $options['mailchimp_relations'] = $service->get_mailchimp_mapping($request->req);
            $options['form_is_opt_in_checkbox'] = isset($request->req['form_is_opt_in_checkbox']) ? $request->req['form_is_opt_in_checkbox'] : null;
            $options['form_opt_in_text'] = $request->req['form_opt_in_text'];
            $options['form_opt_in_default_state'] = isset($request->req['form_opt_in_default_state']) ? $request->req['form_opt_in_default_state'] : null;
           
            if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
                $model->load_from_db($request->req['rm_form_id']);
                $model->set($options);
                $model->update_into_db();
                RM_Utilities::redirect('?page=rm_form_sett_manage&rm_form_id='.$request->req['rm_form_id']);
                return;
            } else {
                echo '<div class="rmnotice">' . RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED') . '</div>';
                return;
            }
        }

        if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
            $data = new stdClass();
            $data->form_id = $request->req['rm_form_id'];
            $model->load_from_db($request->req['rm_form_id']);
            $data->mc_form_list = $model->form_options->mailchimp_list;
            
            if($data->mc_form_list)
                $data->mc_fields = $service->mc_field_mapping($data->form_id, $model->form_options);
            else
                $data->mc_fields = null;
            $data->model = $model;
            $mclist = $service->get_list();
            $data->mailchimp_list[''] = RM_UI_Strings::get('SELECT_LIST');
            if($mclist && isset($mclist['lists']))
            {
                foreach ($mclist['lists'] as $mcl) {
                    $data->mailchimp_list[$mcl['id']] = $mcl['name'];
                }
            }
            $view = $this->mv_handler->setView('form_mc_sett');
        }else{
            $data = RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED');
            $view = $this->mv_handler->setView('show_notice');
        }


        $view->render($data);
    }
    
    function access_control($model, $service, $request, $params) {
        if (isset($request->req['rm_form_id'])) {
            $model->load_from_db($request->req['rm_form_id']);
            $data = new stdClass();
            $data->model = $model;
            $data->form_id = $request->req['rm_form_id'];
            $data->all_roles = RM_Utilities::user_role_dropdown();
            $view = $this->mv_handler->setView('form_access_control_sett');
        } else {
            $data = RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED');
           // $view = $this->mv_handler->setView('show_notice');
        }
        $view->render($data);
    }

 function ccontact($model, $service, $request, $params) {
     if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
          
            $model->load_from_db($request->req['rm_form_id']);
            $data = new stdClass();
            $data->form_name = $model->form_name;
            $data->form_id = $request->req['rm_form_id'];
            $view = $this->mv_handler->setView('form_sett_constant_contact');
        }else{
            $data = RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED');
            $view = $this->mv_handler->setView('show_notice');
        }


        $view->render($data);
    }
    function aweber($model, $service, $request, $params) {
       
     if (isset($request->req['rm_form_id']) && (int)$request->req['rm_form_id']) {
          
          $model->load_from_db($request->req['rm_form_id']);
            $data = new stdClass();
            $data->form_name = $model->form_name;
            $data->form_id = $request->req['rm_form_id'];
            $view = $this->mv_handler->setView('form_sett_aw');
        }else{
            $data = RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED');
            $view = $this->mv_handler->setView('show_notice');
        }


        $view->render($data);
    }
    
    private function add_form_stats(&$data){
        
        $stat_service = new RM_Analytics_Service;
                
        $total_entries =  (int)$stat_service->count('STATS', array('form_id' => (int)$data->form_id));

       //Average and failure rate
        $failed_submission = (int)$stat_service->count('STATS', array('form_id' => (int)$data->form_id, 'submitted_on' => null));
        
        $banned_submission = (int)$stat_service->count('STATS', array('form_id' => (int)$data->form_id, 'submitted_on' => 'banned'));
       
        $successful_submission = $total_entries - $failed_submission - $banned_submission;
        
        $data->conversion_rate = $total_entries ? round(($successful_submission / $total_entries)*100,2) : 0;
        $data->avg_time = $stat_service->get_average_filling_time($data->form_id);
        $data->visitors_count = $stat_service->get_visitors_count($data->form_id);
        $data->field_count = $stat_service->count('FIELDS', array('form_id' => $data->form_id), array('%d'));

    }
    
    public function qck_toggle($form, $service, $request){
        $form->load_from_db($request->req['form_id']);
        
        switch($request->req['name']){
            case 'enable_captcha':
                $option_val = $request->req['value'] === 'true' ? 'default' : 'no';
                break;
            case 'user_auto_approval' : 
                $option_val = $request->req['value'] === 'true' ? 'yes' : 'no';
                break;
            default :
                $option_val = $request->req['value'] === 'true' ? 1 : 0;
        }
        
        $form->set(array($request->req['name'] => $option_val));
        
        if($form->update_into_db())
            echo 'successfull';
        else
            echo 'failed';
        
        die;
        
    }
    
    private function add_form_submission_data(&$data){
        $service  = new RM_Submission_Service;
        $latest_subs = $service->get('SUBMISSIONS', array('form_id' => $data->form_id, 'child_id' => 0), array('%d', '%d'), 'results', 0, 6, '*', `submission_id`, true);
        $latest_sub_data = array();
        $i =0;
        if(is_array($latest_subs))
        foreach($latest_subs as $submission){
            $latest_sub_data[$i] = new stdClass();
            $user = get_user_by('email', $submission->user_email);
            if($user instanceof WP_User){
                if(isset($user->first_name) && trim($user->first_name))
                    $latest_sub_data[$i]->user_name = $user->first_name.' '.$user->last_name;
                else
                    $latest_sub_data[$i]->user_name = $user->display_name ? : $user->user_login;
                $latest_sub_data[$i]->user_avatar = get_avatar_url($user->ID) ? : RM_IMG_URL.'placeholder.jpg';
            }
            else{
                $latest_sub_data[$i]->user_name = $submission->user_email;
                $latest_sub_data[$i]->user_avatar = get_avatar_url($submission->user_email) ? : RM_IMG_URL.'placeholder.jpg';
            }
            $latest_sub_data[$i]->is_read = $submission->is_read;
            $latest_sub_data[$i]->submitted_on = RM_Utilities::localize_time($submission->submitted_on);
            $latest_sub_data[$i]->id = $submission->submission_id;
            $i++;
        } 
        $data->latest_subs = $latest_sub_data;
        $data->sub_count = $service->count('SUBMISSIONS', array('form_id' => $data->form_id, 'child_id' => 0), array('%d', '%d'));
    }
    
    private function add_form_attchment_data(&$data){
        $service = new RM_Attachment_Service();
        $data->attachment_count = $service->get_all_form_attachments($data->form_id, 'count');
        $attachments = $service->get_all_form_attachments($data->form_id, 'posts',6);
        //var_dump($attachments);die;
        $data->attachments = array();
        $i = 0;
        if ($attachments)
            foreach ($attachments as $att) {
                $data->attachments[$i] = new stdClass;
                $att_mime = explode('/', $att->post_mime_type);
                $att_type = $att_mime[0];
                switch ($att_type) {
                    case 'image':
                        $data->attachments[$i]->image_url = $att->guid;
                        break;
                    default:
                        $data->attachments[$i]->image_url = RM_IMG_URL . 'attachment-placeholder.png';
                }
                $data->attachments[$i]->name = basename($att->guid);
                $data->attachments[$i]->date = RM_Utilities::localize_time($att->post_date);
                $data->attachments[$i]->is_new = $att->post_date >= RM_Utilities::get_current_time(time() - (60 * 60));
                $i++;
            }
    }
    
    function override($model, $service, $request) {
        $data = new stdClass();
        $view = $this->mv_handler->setView('form_sett_override');
        $data->model = $model;
        $view->render($data);
    }
    
    private function add_form_timewise_stat(&$data){
        
        $service = new RM_Analytics_Service();
        
        if($data->timerange > 90)
             $data->timerange = 90;
         
         $data->day_wise_stat = $service->day_wise_submission_stats($data->form_id, $data->timerange);
    }
}
