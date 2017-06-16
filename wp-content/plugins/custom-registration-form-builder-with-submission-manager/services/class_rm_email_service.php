<?php
/**
 * Description of RM_Email_Service
 *
 * @author CMSHelplive
 */
class RM_Email_Service
{
    /*
     * Sending submission details to admin
     */                    
    public static function notify_submission_to_admin($params,$token='')
    {
        $gopt = new RM_Options();
        $rm_email= new RM_Email();
        $notification_msg= self::get_notification_message($params->form_id,'form_admin_ns_notification'); 
     
        $email_content='';
        /*
         * Loop through serialized data for submission
         */
        foreach ($params->sub_data as $field_id => $val) {
            $email_content .= '<div class="row"> <span class="key">' . $val->label . ':</span>';

            if (is_array($val->value)) {
                $values = '';
                // Check attachment type field
                if (isset($val->value['rm_field_type']) && $val->value['rm_field_type'] == 'File') {
                    unset($val->value['rm_field_type']);

                    /*
                     * Grab all the attachments as links
                     */
                    foreach ($val->value as $attachment_id) {
                        $values .= wp_get_attachment_link($attachment_id) . '    ';
                    }

                    $email_content .= '<span class="key-val">' . $values . '</span><br/>';
                }elseif (isset($val->value['rm_field_type']) && $val->value['rm_field_type'] == 'Address'){
                    unset($val->value['rm_field_type']);
                    foreach($val->value as $in =>  $value){
                       if(empty($value))
                           unset($val->value[$in]);
                    }
                    $email_content .= '<span class="key-val">' . implode(', ', $val->value) . '</span><br/>';
                } elseif ($val->type == 'Checkbox') {   
                     $email_content .= '<span class="key-val">' . implode(', ',RM_Utilities::get_lable_for_option($field_id, $val->value)) . '</span><br/>';
                }else {
                    $email_content .= '<span class="key-val">' . implode(', ', $val->value) . '</span><br/>';
                }
            } else {
                if ($val->type == 'Radio' || $val->type == 'Select') {   
                   $email_content .= '<span class="key-val">' . RM_Utilities::get_lable_for_option($field_id, $val->value). '</span><br/>';
                }
                else
                    $email_content .= '<span class="key-val">' . $val->value . '</span><br/>';
            }

             $email_content .= "</div>";
        }

        /*
          Set unique token */
        if ($token) {
            $email_content .= '<div class="row"> <span class="key">' . RM_UI_Strings::get('LABEL_UNIQUE_TOKEN_EMAIL') . ':</span>';
            $email_content .= '<span class="key-val">' . $token . '</span><br/>';
            $email_content .= "</div>";
        }

        $notification_msg= str_replace('{{SUBMISSION_DATA}}', $email_content, $notification_msg);
        $rm_email->message($notification_msg);
        // Prepare recipients

        $to = array();
        $header = '';

       
        if ($gopt->get_value_of('admin_notification') == "yes") {
            $to = explode(',',$gopt->get_value_of('admin_email'));
        }
    
        $subject = $params->form_name . " " . RM_UI_Strings::get('LABEL_NEWFORM_NOTIFICATION') . " ";
        $rm_email->subject($subject);
        $from_email = $gopt->get_value_of('senders_email_formatted');
        $rm_email->from($from_email);
          
        foreach($to as $recepient)
        {
            $rm_email->to($recepient);
            if($rm_email->send())
            {
                self::save_sent_emails($params,$rm_email,RM_EMAIL_POSTSUB_ADMIN);
            }
        }
        
    }
    /*
     * Sending Username and Password credentials on new user registration.
     */
    public static function notify_new_user($params)
    {
        $gopt = new RM_Options();
        $rm_email= new RM_Email();
        $notification_msg= self::get_notification_message($params->form_id,'form_nu_notification'); 
        $notification_msg = str_replace('{{SITE_NAME}}', get_bloginfo('name', 'display'), $notification_msg);
        $notification_msg = str_replace('%SITE_NAME%', get_bloginfo('name', 'display'), $notification_msg);
        
        $notification_msg = str_replace('{{USER_NAME}}', $params->username, $notification_msg);
        $notification_msg = str_replace('%USER_NAME%', $params->username, $notification_msg);
        
        $notification_msg = str_replace('{{USER_PASS}}', $params->password, $notification_msg);
        $notification_msg = str_replace('%USER_PASS%', $params->password, $notification_msg);
        
        $rm_email->message($notification_msg);
        $rm_email->subject(RM_UI_Strings::get('MAIL_NEW_USER_DEF_SUB'));
        $rm_email->to($params->email);
        $rm_email->from($gopt->get_value_of('senders_email_formatted'));
        $rm_email->send();
    }
    
    /*
     * Sending user activation link to admin
     */
    public static function notify_admin_to_activate_user($params)
    {
        $gopt = new RM_Options();
        $rm_email= new RM_Email();
        $user_email = $params->email;
        $notification_msg= self::get_notification_message($params->form_id,'form_activate_user_notification'); 
        $notification_msg = str_replace('{{SITE_NAME}}', get_bloginfo('name', 'display'), $notification_msg);
        $notification_msg = str_replace('%SITE_NAME%', get_bloginfo('name', 'display'), $notification_msg);
        
        $notification_msg = str_replace('{{USER_NAME}}', $params->username, $notification_msg);
        $notification_msg = str_replace('%USER_NAME%', $params->username, $notification_msg);
        
        $notification_msg = str_replace('{{USER_EMAIL}}', $user_email, $notification_msg);
        $notification_msg = str_replace('%USER_EMAIL%', $user_email, $notification_msg);
         
        $notification_msg = str_replace('{{ACTIVATION_LINk}}', $params->link, $notification_msg);
        $notification_msg = str_replace('%ACTIVATION_LINk%', $params->link, $notification_msg);
        
        //$email->message = "msg \r\n\r\n--" . $boundary . "\r\n" . $header_text . $msg_text . "\r\n\r\n--" . $boundary . "\r\n" . $header_html . $html_pre .$msg_css . $msg_html . $html_post . "\r\n\r\n--" . $boundary . "--\r\n";
        $rm_email->message($notification_msg);
        $rm_email->subject(RM_UI_Strings::get('MAIL_ACTIVATE_USER_DEF_SUB'));
        $rm_email->to(get_option('admin_email'));
        $rm_email->from($gopt->get_value_of('senders_email_formatted'));
        if($rm_email->send())
        {
            self::save_sent_emails($params,$rm_email,RM_EMAIL_USER_ACTIVATION_ADMIN);
        }
    }
    /*
     *  Send auto reponder message to user on new submission
     */
    public static function auto_responder($params,$token='')
    {
        $gopt = new RM_Options();
        $rm_email= new RM_Email();

       
        $email_content = '<div class="mail-wrapper">';
        /* Preparing content for front end notification */
        $email_content .= wpautop($params->email_content) . '<br><br>';

        /*
          Set unique token */
        if ($token) {
            $email_content .= '<div class="row"> <span class="key">' . RM_UI_Strings::get('LABEL_UNIQUE_TOKEN_EMAIL') . ':</span>';
            $email_content .= '<span class="key-val">' . $token . '</span><br/>';
            $email_content .= "</div>";
        }

        foreach ($params->req as $key => $val) {
            //echo "<pre", var_dump($request->req),die;
            $key_parts = explode('_', $key);
            if (!is_array($val)){                    
                if ($key_parts[0] == 'File' || $key_parts[0] == 'Image') {

                    $field_id = $key_parts[1];
                    //Try to find value in db_data if provided.                        
                    $values='';
                    if(isset($params->db_data, $params->db_data[$field_id]))
                    {
                        /*
                        * Grab all the attachments as links
                        */
                        if(is_array($params->db_data[$field_id]->value) && count($params->db_data[$field_id]->value)>0)
                            foreach ($params->db_data[$field_id]->value as $attachment_id) {
                                if($attachment_id != 'File')
                                $values .= wp_get_attachment_link($attachment_id) . '    ';
                            }

                    }

                    $email_content = str_replace('{{' . $key . '}}', $values, $email_content);

                }
                elseif ($key_parts[0] == 'Radio' || $key_parts[0] == 'Select') {   
                   $values = '';
                   $values =  RM_Utilities::get_lable_for_option($key_parts[1], $val);
                   $email_content = str_replace('{{' . $key . '}}', $values, $email_content);
                }
                else
                    $email_content = str_replace('{{' . $key . '}}', $val, $email_content);                   
            }
            else {
                if (isset($val['rm_field_type']) && $val['rm_field_type'] == 'Address'){
                unset($val['rm_field_type']);
                            foreach ($val as $in => $value) {
                                if (empty($value))
                                    unset($val[$in]);
                            }
                }
                elseif ($key_parts[0] == 'Checkbox') {   
                     $val = RM_Utilities::get_lable_for_option($key_parts[1], $val);
                }
                $email_content = str_replace('{{' . $key . '}}', implode(',', $val), $email_content);
            }
        }

        $out = array();
        $preg_result = preg_match_all('/{{(.*?)}}/', $email_content, $out);

        if ($preg_result) {
            $id_vals = array();

            foreach ($params->req as $key => $val) {
                //$val would be like '{field_type}_{field_id}'

                $key_parts = explode('_', $key);
                $k_c = count($key_parts);
                if ($k_c >= 2 && is_numeric($key_parts[$k_c - 1])) {
                    if (is_array($val))
                        $val = implode(",", $val);

                    if ($key_parts[0] === 'Fname' || $key_parts[0] === 'Lname' || $key_parts[0] === 'BInfo') {
                        $id_vals[$key_parts[0]] = $val;
                    } else
                        $id_vals[$key_parts[1]] = $val;
                }
            }

            foreach ($out[1] as $caught) {
                //echo "<br>".$caught;$parameters
                $x = explode("_", $caught);
                $id = $x[count($x) - 1];
                if (is_numeric($id)) {
                    if (isset($id_vals[(int) $id]))
                        $email_content = str_replace('{{' . $caught . '}}', $id_vals[(int) $id], $email_content);
                }
                else {
                    switch ($caught) {
                        case 'first_name':
                            if (isset($id_vals['Fname']))
                                $email_content = str_replace('{{' . $caught . '}}', $id_vals['Fname'], $email_content);
                            break;

                        case 'last_name':
                            if (isset($id_vals['Lname']))
                                $email_content = str_replace('{{' . $caught . '}}', $id_vals['Lname'], $email_content);
                            break;

                        case 'description':
                            if (isset($id_vals['BInfo']))
                                $email_content = str_replace('{{' . $caught . '}}', $id_vals['BInfo'], $email_content);
                            break;
                    }
                }

                //Blank the placeholder if still any remaining.
                $email_content = str_replace('{{' . $caught . '}}', '', $email_content);
            }
        }
        
        $email_content .=  "</div>";
        $rm_email->message($email_content);
        // Prepare recipients
        $rm_email->subject($params->email_subject? : RM_UI_Strings::get('MAIL_REGISTRAR_DEF_SUB'));
        $rm_email->to($params->email);
        $rm_email->from($gopt->get_value_of('senders_email_formatted'));
        if($rm_email->send())
        {
            self::save_sent_emails($params,$rm_email,RM_EMAIL_AUTORESP);
        }
        
    }
    
    /*
     * Send notification to user as soon as account is activated.
     */
    public static function notify_user_on_activation($params)
    {
        $gopt = new RM_Options();
        $rm_email= new RM_Email();
        $notification_msg= self::get_notification_message($params->form_id,'form_user_activated_notification'); 
        $notification_msg = str_replace('{{SITE_NAME}}',get_bloginfo('name', 'display'), $notification_msg);
        $notification_msg = str_replace('%SITE_NAME%',get_bloginfo('name', 'display'), $notification_msg);
        
        $notification_msg = str_replace('{{SITE_URL}}',get_site_url(),$notification_msg);
        $notification_msg = str_replace('%SITE_URL%',get_site_url(),$notification_msg);
        
        $rm_email->message($notification_msg);
        $rm_email->subject(RM_UI_Strings::get('MAIL_ACOOUNT_ACTIVATED_DEF_SUB'));
        $rm_email->to($params->email);
        $rm_email->from($gopt->get_value_of('senders_email_formatted'));
        if($rm_email->send())
        {
            self::save_sent_emails($params,$rm_email,RM_EMAIL_USER_ACTIVATED_USER);
        }
        
    }
    private static function save_sent_emails($params,$rm_email,$type)
    {
            
        $additional_data = array();
        if(isset($params->sub_id))
            $additional_data['exdata'] = $params->sub_id;
        if(isset($params->form_id))
            $additional_data['form_id'] = $params->form_id;

        if(count($additional_data)>0)
        {
            $sent_on = date('Y-m-d H:i:s');  
            $form_id = null;
            $exdata = null;

            if(is_array($additional_data) && count($additional_data) > 0)
            {
                if(isset($additional_data['form_id'])) $form_id = $additional_data['form_id'];
                if(isset($additional_data['exdata'])) $exdata = $additional_data['exdata'];
            }
            $row_data = array('type' => $type, 'to' => $rm_email->get_to(), 'sub' => htmlspecialchars($rm_email->get_subject()), 'body' => htmlspecialchars($rm_email->get_message()), 'sent_on' => $sent_on, 'headers' => $rm_email->get_header(), 'form_id' => $form_id,'exdata' => $exdata);
            $fmts = array('%d','%s','%s','%s','%s', '%s', '%d', '%s');

            RM_DBManager::insert_row('SENT_EMAILS', $row_data, $fmts);
        }
        
    }
    
    private static function get_notification_message($form_id,$type)
    {
        $form= new RM_Forms();
        $form->load_from_db($form_id);
        if(isset($form->form_options->$type) && trim($form->form_options->$type)!="")
            return $form->form_options->$type;
        else
            return self::get_default_messages($type);
    }
    
    public static function get_default_messages($type)
    {   
        $email_content= '';
        if($type=="form_nu_notification")
        {
            $email_content = '<div class="mail-wrapper">'.RM_UI_Strings::get('MAIL_BODY_NEW_USER_NOTIF').'</div>';
        }elseif($type=="form_user_activated_notification")
        {
             $email_content = '<div style="font-size:14px">';
             $email_content .=  RM_UI_Strings::get('MAIL_ACCOUNT_ACTIVATED');
             $email_content .= '</div>';
        }elseif($type=="form_activate_user_notification")
        {
            $email_content = '<div style="font-size:14px">';
            $email_content .= '<div class="mail-wrapper" style="border: 1px solid black; padding: 20px; box-shadow: .1px .1px 8px .1px grey; font-size: 14px; font-family: monospace;"> <div class="mail_body" style="padding: 20px;">' . RM_UI_Strings::get('MAIL_NEW_USER1') . '.<br/> ' . RM_UI_Strings::get('LABEL_USER_NAME') . ' : {{USER_NAME}} <br/> ' . RM_UI_Strings::get('LABEL_USEREMAIL') . ' : {{USER_EMAIL}} <br/> <br/>' . RM_UI_Strings::get('MAIL_NEW_USER2') . '<br/> <div class="rm-btn-link" style="width: 100%; text-align: center; margin-top: 10px; margin-bottom: 15px;"><a class="rm_btn" href="{{ACTIVATION_LINk}}" style="border: 1px solid; padding: 4px; background-color: powderblue; box-shadow: 1px 1px 3px .1px;">Activate</a></div> <div class="link-div" style="border: 1px dotted; padding: 13px; background-color: white; margin-top: 4px; width: 100%;"> ' . RM_UI_Strings::get('MAIL_NEW_USER3') . '.<br/> <a class="rm-link" href="{{ACTIVATION_LINk}}" style="color: blue; font-size: 11px;">{{ACTIVATION_LINk}}</a> </div> </div> </div>';            
            $email_content .= '</div>';
        } elseif($type=='form_admin_ns_notification')
        {
            $email_content= '{{SUBMISSION_DATA}}';
        }
        
        return $email_content;
    }
    
}


