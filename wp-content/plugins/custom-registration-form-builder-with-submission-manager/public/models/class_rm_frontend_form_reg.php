<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RM_Frontend_Form_Reg extends RM_Frontend_Form_Multipage//RM_Frontend_Form_Base
{

    protected $form_user_role;
    protected $default_form_user_role;
    protected $user_exists;
    protected $user_id;
    
    public function __construct(RM_Forms $be_form, $ignore_expiration=false)
    {
        parent::__construct($be_form, $ignore_expiration);
        $this->form_user_role = $be_form->get_form_user_role();
        $this->default_form_user_role = $be_form->get_default_form_user_role();
        $this->set_form_type(RM_REG_FORM);
        $this->user_exists = false;
        $this->user_id = 0;
    }
    
    public function get_registered_user_id()
    {
        return $this->user_id;
    }
    
    //Returning false here will prevent submission in form controller.
    public function pre_sub_proc($request, $params)
    {  
        $form_name = 'form_' . $this->form_id  . "_" . $this->form_number;
        
        if (!is_user_logged_in())
        { 
            $prime_data = $this->get_prepared_data_primary($request);
            
            if(!isset($prime_data['user_email'], $prime_data['username']))
                return false;
            
            $email = $prime_data['user_email']->value;
            $username = $prime_data['username']->value;
            
            if(isset($prime_data['password']))
            {
                                
                $password = $prime_data['password']->value;
                $password_conf = $prime_data['password_confirmation']->value;
                
                if($password !== $password_conf)
                {
                    RM_PFBC_Form::setError($form_name, RM_UI_Strings::get("ERR_PW_MISMATCH"));
                    return false;
                }
            }            
            
              $user = get_user_by('login', $username);
                if (!empty($user))
                {
                    $this->user_exists = true;
                    RM_PFBC_Form::setError($form_name, RM_UI_Strings::get("USERNAME_EXISTS"));
                    return false;
                } 
            
            $user = get_user_by('email', $email);


                if (!empty($user))
                {
                    $this->user_exists = true;
                    RM_PFBC_Form::setError($form_name, RM_UI_Strings::get("USERNAME_EXISTS"));
                    return false;
                } 
            
            
            RM_PFBC_Form::clearErrors($form_name);
            return true;            
        }

        return true;
    }

     public function post_sub_proc($request, $params)
    {   
        $prime_data = $this->get_prepared_data_primary($request);            
        $x = null;
        if (!is_user_logged_in())
        {
            if(isset($params['paystate']))
            {
                if($params['paystate'] == 'pre_payment' || $params['paystate'] == 'na')
                {
                    if(!isset($prime_data['user_email'], $prime_data['username']))
                        return false;

                    $email = $prime_data['user_email']->value;
                    $username = $prime_data['username']->value;

                    if ($this->service->get_setting('auto_generated_password') == 'yes')
                        $password = null;
                    else
                    {
                      if(!isset($prime_data['password']))
                          return false;
                      $password = $prime_data['password']->value;
                    }

                    if($params['paystate'] == 'pre_payment')
                        $user_id = $this->service->register_user($username, $email, $password, false,$this->form_id);
                    else
                        $user_id = $this->service->register_user($username, $email, $password,true,$this->form_id);

                    $this->user_id = $user_id;
                    update_user_meta($user_id, 'RM_UMETA_FORM_ID', $this->form_id);
                    update_user_meta($user_id, 'RM_UMETA_SUB_ID', $params['sub_detail']->submission_id);
                    $x = array('user_id'=>$user_id);
                    
                    $this->service->get_user_service()->set_user_role($user_id, 'subscriber');
                        
                }
                if($params['paystate'] == 'post_payment' || $params['paystate'] == 'na')
                {
                    if($params['paystate'] == 'post_payment' && (isset($params['is_paid']) && $params['is_paid'] === true))
                        $user_id = $this->service->get_user_service()->activate_user_by_id($this->user_id);
                    if ($this->form_options->auto_login)
                    {
                        if($params['paystate'] == 'post_payment' && (isset($params['is_paid']) && $params['is_paid'] === true))
                            $user_id = $this->service->get_user_service()->activate_user_by_id($this->user_id);
                        
                        if ($this->form_options->auto_login)
                        {
                            $_SESSION['RM_SLI_UID'] = $this->user_id;     
                        }
                    }
                }
            }
        }
        
        if(isset($params['paystate']) && $params['paystate'] != 'post_payment')            
            if ($this->service->get_setting('enable_mailchimp') == 'yes')
            {
                if($this->form_options->form_is_opt_in_checkbox == 1 || (isset($this->form_options->form_is_opt_in_checkbox[0]) && $this->form_options->form_is_opt_in_checkbox[0] == 1))
                {
                    if(isset($request['rm_subscribe_mc']))
                        $this->service->subscribe_to_mailchimp($request, $this->get_form_options());
                }
                else
                    $this->service->subscribe_to_mailchimp($request, $this->get_form_options());
            }
        
        return $x;
    }
    protected function hook_pre_field_addition_to_page($form, $page_no)
    {
        //if (1 == $page_no)
        {
            if (!is_user_logged_in())
            { /*
             * Let users choose their role
             */

                if (!empty($this->form_options->form_should_user_pick) || !(isset($this->form_user_role) && !empty($this->form_user_role)))
                {
                    $role_pick = $this->form_options->form_should_user_pick;

                    if ($role_pick)
                    {
                        global $wp_roles;
                        $allowed_roles = array();
                        $default_wp_roles = $wp_roles->get_names();
                        $form_roles = $this->form_user_role;
                        if (is_array($form_roles) && count($form_roles) > 0)
                        {
                            foreach ($form_roles as $val)
                            {
                                if (array_key_exists($val, $default_wp_roles))
                                    $allowed_roles[$val] = $default_wp_roles[$val];
                            }
                        }

                        $role_as = empty($this->form_options->form_user_field_label) ? RM_UI_Strings::get('LABEL_ROLE_AS') : $this->form_options->form_user_field_label;

                        $form->addElement(new Element_Radio("<b>" . $role_as . ":</b>", "role_as", $allowed_roles, array("id" => "rm_", "style" => $this->form_options->style_textfield, "required" => "1","labelStyle" => $this->form_options->style_label)));
                    }
                }

                $form->addElement(new Element_Username("<b>" . RM_UI_Strings::get('LABEL_USERNAME') . "</b>:", "username", array("value" => "","labelStyle" => $this->form_options->style_label, "style" => $this->form_options->style_textfield, "required" => "1", "placeholder" => RM_UI_Strings::get('LABEL_USERNAME'))));

                if ($this->service->get_setting('auto_generated_password') != 'yes')
                {
                    $form->addElement(new Element_Password("<b>" . RM_UI_Strings::get('LABEL_PASSWORD') . "</b>:", "password", array("required" => 1, "id" => "rm_reg_form_pw_".$this->form_id."_".$this->form_number, "longDesc" => RM_UI_Strings::get('HELP_PASSWORD_MIN_LENGTH'), "minlength" => 7,"labelStyle" => $this->form_options->style_label, "style" => $this->form_options->style_textfield, "validation" => new Validation_RegExp("/.{7,}/", "Error: The %element% must be atleast 7 characters long."))));
                    $form->addElement(new Element_Password("<b>" . RM_UI_Strings::get('LABEL_PASSWORD_AGAIN') . "</b>:", "password_confirmation", array("required" => 1,"labelStyle" => $this->form_options->style_label, "style" => $this->form_options->style_textfield, "id" => 'rm_reg_form_pw_reentry')));
                }
            }
        }
        
    }
    
    protected function hook_post_field_addition_to_page($form, $page_no)
    {
        //if (count($this->form_pages) == $page_no)
        { 
            if ($this->has_price_field())
                $this->add_payment_fields($form);
            
            if (get_option('rm_option_enable_captcha') == "yes")
                $form->addElement(new Element_Captcha());
            
            if ($this->service->get_setting('enable_mailchimp') == 'yes' && $this->form_options->form_is_opt_in_checkbox == 1)
            {
                //This outer div is added so that the optin text can be made full width by CSS.
                $form->addElement(new Element_HTML('<div class="rm_optin_text">'));
                
                if($this->form_options->form_opt_in_default_state == 'Checked')
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_mc', array(1 => $this->form_options->form_opt_in_text ? : RM_UI_Strings::get('MSG_SUBSCRIBE')),array("value"=>1)));
                else 
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_mc', array(1 => $this->form_options->form_opt_in_text ? : RM_UI_Strings::get('MSG_SUBSCRIBE'))));
            
                $form->addElement(new Element_HTML('</div>'));             
            }
                        
            if($this->form_options->show_total_price)
            {
                $gopts = new RM_Options;
                $total_price_localized_string = RM_UI_Strings::get('FE_FORM_TOTAL_PRICE');
                $curr_symbol = $gopts->get_currency_symbol();
                $curr_pos = $gopts->get_value_of('currency_symbol_position');
                $price_formatting_data = json_encode(array("loc_total_text" => $total_price_localized_string, "symbol" => $curr_symbol, "pos" => $curr_pos));
                $form->addElement(new Element_HTML("<div class='rmrow rm_total_price' style='{$this->form_options->style_label}' data-rmpriceformat='$price_formatting_data'></div>"));
            }
        }
    }

    protected function base_render($form)
    {        
        //parent::base_render($form);

        $this->prepare_fields_for_render($form);
        
        $this->prepare_button_for_render($form);

        if (count($this->fields) !== 0)
            $form->render();
        else
            echo RM_UI_Strings::get('MSG_NO_FIELDS');
    }

    public function render($data = array())
    {
        global $rm_form_diary;
        echo '<div class="rmagic">';
        
        //$this->form_number = $rm_form_diary[$this->form_id];
        $form = new RM_PFBC_Form('form_' . $this->form_id . "_" . $this->form_number);

        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery", "focus"),
            "action" => "",
            "class" => "rmagic-form",
            "name"=>"rm_form",
            "view" => new View_UserForm,
            "name" => "rm_form",
            "number" => $this->form_number,
            "style" => isset($this->form_options->style_form)?$this->form_options->style_form:null
        ));
        
        //Render content above the form
        if (!empty($this->form_options->form_custom_text))
                $form->addElement(new Element_HTML('<div class="rmheader">' . $this->form_options->form_custom_text . '</div>'));

        //check if form has expired
        if ($this->is_expired())
        {
            if ($this->form_options->form_message_after_expiry)
                echo $this->form_options->form_message_after_expiry;
            else
                echo '<div class="rm-no-default-from-notification">'.RM_UI_Strings::get('MSG_FORM_EXPIRY').'</div>';
            echo '</div>';
            return;
        }

        if (isset($data['stat_id']) && $data['stat_id'])
        {
            $form->addElement(new Element_HTML('<div id="rm_stat_container" style="display:none">'));
            $form->addElement(new Element_Number('RM_Stats', 'stat_id', array('value' => $data['stat_id'], 'style' => 'display:none')));
            $form->addElement(new Element_HTML('</div>'));
        }
        
        if (isset($data['submission_id']) && $data['submission_id'])
        {
            $form->addElement(new Element_HTML('<div id="rm_stdat_container" style="display:none">'));
            $form->addElement(new Element_Textbox('RM_Slug', 'rm_slug', array('value' => 'rm_user_form_edit_sub', 'style' => 'display:none')));
            $form->addElement(new Element_Textbox('RM_form_id', 'form_id', array('value' => $this->form_id, 'style' => 'display:none')));
            $form->addElement(new Element_HTML('</div>'));
        }

        if ($this->user_exists)
        {
            echo '<div class="rm_user_exists_alert">' . RM_UI_Strings::get('USER_EXISTS') . '</div>';
        }   

        
        parent::pre_render();
        $this->base_render($form);
        parent::post_render();

        echo '</div>';
    }
    
    protected function get_jqvalidator_config_JS()
    {
        if(!is_user_logged_in())
        {
        $password_match_error = RM_UI_Strings::get('ERR_PW_MISMATCH');
        $form_num = $this->form_number;
        $form_id = $this->form_id;
$str = <<<JSHD
        jQuery.validator.setDefaults({errorClass: 'rm-form-field-invalid-msg',
                                        ignore:[],
                                       errorPlacement: function(error, element) {
                                                            error.appendTo(element.closest('.rminput'));
                                                          },
                                      rules: {        
        password: {
            required: true,
            minlength: 7
        },
        password_confirmation: {
            required: true,
            equalTo: "#rm_reg_form_pw_{$form_id}_{$form_num}"
        }
            },
        messages: {
        password_confirmation: {
            equalTo: "{$password_match_error}"
        }
            }
                                    });
JSHD;
        return $str;
        }
        else
            return parent::get_jqvalidator_config_JS ();
    }

    //Primary array must be indexed by some unique identifier instead of field_id.
    protected function get_prepared_data_primary($request)
    {
        $data = array();           

        if(isset($this->primary_field_indices['user_email']) && isset($request[$this->primary_field_indices['user_email']]))
        {
            $field = $this->fields[$this->primary_field_indices['user_email']];
            $field_data = $field->get_prepared_data($request);

            $data['user_email'] = (object) array('label' => $field_data->label,
                            'value' => $field_data->value,
                            'type' => $field_data->type);
        }
        
        if (isset($request['password']))
        {
            $data['password'] = (object) array('label' => RM_UI_Strings::get('LABEL_PASSWORD'),
                        'value' => $request['password'],
                        'type' => 'password');
        }
        
        if (isset($request['password_confirmation']))
        {
            $data['password_confirmation'] = (object) array('label' => RM_UI_Strings::get('LABEL_PASSWORD_AGAIN'),
                        'value' => $request['password_confirmation'],
                        'type' => 'password');
        }

        if (isset($request['username']))
        {
            $data['username'] = (object) array('label' => RM_UI_Strings::get('LABEL_USERNAME'),
                        'value' => $request['username'],
                        'type' => 'username');
        }


        return $data;
    }

    //Make sure that this data is indexed by field_id only
    protected function get_prepared_data_dbonly($request)
    {
        $data = array();

        foreach ($this->fields as $field)
        {
            if ($field->get_field_type() == 'HTMLH' || $field->get_field_type() == 'HTMLP')
            {
                continue;
            }
            
            $field_data = $field->get_prepared_data($request);

            if ($field_data === null)
                continue;

            $data[$field_data->field_id] = (object) array('label' => $field_data->label,
                        'value' => $field_data->value,
                        'type' => $field_data->type);   
        }

        return $data;
    }

    //Need to overload the method to add username and password fields as they are not included in the default fields list of the form.
    //Since this method return data for all fields, do not rely on any fixed type of indexing while iterating.
    protected function get_prepared_data_all($request)
    {
        $data = parent::get_prepared_data_all($request);

        if (isset($request['password']))
        {
            $data['password'] = (object) array('label' => RM_UI_Strings::get('LABEL_PASSWORD'),
                        'value' => $request['password'],
                        'type' => 'password');
        }
        
        if (isset($request['password_confirmation']))
        {
            $data['password_confirmation'] = (object) array('label' => RM_UI_Strings::get('LABEL_PASSWORD_AGAIN'),
                        'value' => $request['password_confirmation'],
                        'type' => 'password');
        }

        if (isset($request['username']))
        {
            $data['username'] = (object) array('label' => RM_UI_Strings::get('LABEL_USERNAME'),
                        'value' => $request['username'],
                        'type' => 'username');
        }

        return $data;
    }

}
