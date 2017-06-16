<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class RM_Frontend_Form_Base
{

    protected $form_id;
    protected $form_type;
    protected $form_name;
    protected $form_options;
    protected $fields;
    protected $pfbc_form;
    protected $custom_pre_str;
    protected $custom_post_str;
    protected $service;
    protected $contains_price_fields;
    protected $form_number;//Keeps track of the how many of the same forms have been rendered
    protected $ignore_expiration;

    //Submission related function, must be implemented by child class
    abstract function pre_sub_proc($request, $params);

    abstract function post_sub_proc($request, $params);

    public function __construct(RM_Forms $be_form, $ignore_expiration=false)
    {
        $this->fields = array();
        $this->form_type = RM_BASE_FORM;
        $this->custom_pre_str = '';
        $this->custom_post_str = '';
        $this->form_id = $be_form->get_form_id();
        $this->form_name = $be_form->get_form_name();
        $this->form_options = $be_form->get_form_options();
        $this->form_options->form_should_auto_expire = $be_form->get_form_should_auto_expire();
        $this->form_options->form_should_send_email = $be_form->get_form_should_send_email();
        $this->ignore_expiration = $ignore_expiration;

        if (isset($be_form->form_redirect) && $be_form->form_redirect != "none" && $be_form->form_redirect != "")
            $this->form_options->redirection_type = $be_form->form_redirect;
        else
            $this->form_options->redirection_type = null;

        $this->form_options->redirect_page = $be_form->get_form_redirect_to_page();
        $this->form_options->redirect_url = $be_form->get_form_redirect_to_url();        
        $this->primary_field_indices = array();
        $this->service = new RM_Front_Form_Service;
    }
    
    public function set_primary_field_index($pfields)
    {
        foreach($pfields as $pfield=>$pfield_index)
        {
            $this->primary_field_indices[$pfield] = $pfield_index;
        }
    }
    
    public function get_primary_field_index($pfields)
    {
        return $this->primary_field_indices;
    }

    public function get_form_id()
    {
        return $this->form_id;
    }

    public function get_form_name()
    {
        return $this->form_name;
    }

    public function get_form_options()
    {
        return $this->form_options;
    }
    
    public function get_form_number()
    {
        return $this->form_number;
    }

    public function get_form_should_auto_expire()
    {
        return $this->form_options->form_should_auto_expire;
    }

    public function is_expired()
    {
        if($this->ignore_expiration)
            return false;
        
        if (!$this->form_options->form_should_auto_expire)
            return false;
        else
        {
            $criterian = $this->form_options->form_expired_by;
            $submission_limit = $this->form_options->form_submissions_limit;
            return $this->service->is_form_expired_core($this->form_id, $criterian, $submission_limit);
        }
    }

    public function set_form_type($form_type)
    {
        $this->form_type = $form_type;
    }

    public function get_form_type()
    {
        return $this->form_type;
    }
    
    public function set_form_number( $form_number)
    {
        $this->form_number = $form_number;
    }

    public function get_custom_pre_str()
    {
        return $this->custom_pre_str;
    }

    public function get_custom_post_str()
    {
        return $this->custom_post_str;
    }

    public function add_field(RM_Frontend_Field_Base $fe_field)
    {
        $this->fields[$fe_field->get_field_name()] = $fe_field;
    }

    //Add/append fields in batch in existing array of fields
    // Array must be assosiative with field name as index.
    public function add_fields_array($fe_fields_arr)
    {
        if (count($this->fields) === 0)
            $this->fields = $fe_fields_arr;
        else
            $this->fields = $this->fields + $fe_fields_arr;
    }
    
    public function get_fields()
    {
        return $this->fields;
    }
    
    //Add custom msg strings to be shown above form like alerts.
    public function add_content_above_form($string)
    {
        $this->custom_pre_str = $string;
    }

    public function add_content_below_form($string)
    {
        $this->custom_post_str = $string;
    }

    protected function pre_render()
    {
        $important = ' !important';      
        $p_css = str_replace("::-", ' #form_' . $this->form_id . "_" . $this->form_number .' ::-', $this->form_options->placeholder_css);
        $p_css = str_replace("}:-", '} #form_' . $this->form_id . "_" . $this->form_number .' ::-', $p_css);
        echo $p_css;
        echo '<style>';
        if($this->form_options->btn_hover_color)
            echo '.rmagic #form_' . $this->form_id . "_" . $this->form_number .' .buttonarea input[type="button"]:hover{ background-color:'.$this->form_options->btn_hover_color.$important.';}';
        if($this->form_options->field_bg_focus_color || $this->form_options->text_focus_color){
            echo '.rmagic #form_' . $this->form_id . "_" . $this->form_number .' .rmrow input:focus,.rmagic #form_'.$this->form_id.'_'.$this->form_number.' .rmrow select:focus,.rmagic #form_'.$this->form_id.'_'.$this->form_number.' .rmrow textarea:focus{';
            if($this->form_options->field_bg_focus_color)
                echo 'background-color:'.$this->form_options->field_bg_focus_color.$important.';';
            if($this->form_options->text_focus_color)
                echo 'color:'.$this->form_options->text_focus_color.$important.';';
            echo '}';
        }
        echo '</style>';
        
        if (!$this->is_expired())
        {
            $expiry_details = $this->service->get_form_expiry_stats($this);
            
            $exp_str = '<div class="rm_expiry_stat_container">';
            if ($expiry_details->state !== 'perpetual' && $this->service->get_setting('display_progress_bar') == 'yes')
            {
                if ($expiry_details->state === 'expired')
                    $exp_str .= '<div class="rm-formcard-expired">' . 'Expired' . '</div>';
                else
                {
                    switch ($expiry_details->criteria)
                    {
                        case 'both':
                            $message = sprintf(RM_UI_Strings::get('EXPIRY_DETAIL_BOTH'), ($expiry_details->sub_limit - $expiry_details->remaining_subs), $expiry_details->sub_limit, $expiry_details->remaining_days);
                            $exp_str .= '<div class="rm-formcard-expired"><span class="rm_sandclock"></span>' . $message . '</div>';
                            break;
                        case 'subs':
                            $total = $expiry_details->sub_limit;
                            $rem = $expiry_details->remaining_subs;
                            $wtot = 100;
                            $rem = ($rem * 100) / $total;
                            $done = 100 - $rem;
                            $message = sprintf(RM_UI_Strings::get('EXPIRY_DETAIL_SUBS'), ($expiry_details->sub_limit - $expiry_details->remaining_subs), $expiry_details->sub_limit);
                            $exp_str .= '<div class="rm-formcard-expired"><span class="rm_sandclock"></span>' . $message . '</div>';
                            break;

                        case 'date':
                            $message = sprintf(RM_UI_Strings::get('EXPIRY_DETAIL_DATE'), $expiry_details->remaining_days);
                            $exp_str .= '<div class="rm-formcard-expired"><span class="rm_sandclock"></span>' . $message . '</div>';
                            break;
                    }
                }

                $exp_str .= '</div>';
                echo $exp_str;
            }
        }
        echo('<div class="rmcontent">');
        if ($this->custom_pre_str !== '' || $this->custom_pre_str)
            echo $this->custom_pre_str;
    }

    protected function prepare_fields_for_render($form)
    {
        foreach ($this->fields as $field)
        {
            $pf = $field->get_pfbc_field();

            if ($pf === null)
                continue;

            if (is_array($pf))
            {
                foreach ($pf as $f)
                {
                    if (!$f)
                        continue;
                    $form->addElement($f);
                }
            } else
                $form->addElement($pf);
        }
        
      
    }

    protected function prepare_button_for_render($form)
    {
        if ($this->service->get_setting('theme') != 'matchmytheme')
        {
            if(isset($this->form_options->style_btnfield))
                unset($this->form_options->style_btnfield);
        }
        $btn_label = $this->form_options->form_submit_btn_label;
        $form->addElement(new Element_Button($btn_label != "" ? $btn_label : "Submit", "submit", array("style" => isset($this->form_options->style_btnfield)?$this->form_options->style_btnfield:null)));
    }

    protected function base_render($form)
    {
        $this->prepare_fields_for_render($form);

        if (get_option('rm_option_enable_captcha') == "yes")
            $form->addElement(new Element_Captcha());


        $this->prepare_button_for_render($form);



        if (count($this->fields) !== 0)
            $form->render();
        else
            echo RM_UI_Strings::get('MSG_NO_FIELDS');
    }

    protected function post_render()
    {
        if ($this->custom_post_str !== '' || $this->custom_post_str)
            echo $this->custom_post_str;

        echo "</div>";
    }

    public function render($extra_data_may_needed_in_child_class = null)
    {
        global $rm_form_diary;
        echo '<div class="rmagic">';
        
        //$this->form_number = $rm_form_diary[$this->form_id];
        $form = new RM_PFBC_Form('form_' . $this->form_id . "_" . $this->form_number);

        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery", "focus"),
            "action" => "",
            "class" => "rmagic-form",
            "name" => "rm_form",
            "view" => new View_UserForm,
            "number" => $this->form_number,
            "style" => isset($this->form_options->style_form)?$this->form_options->style_form:null
        ));
        
        //Render content above the form
        if (!empty($this->form_options->form_custom_text))
                $form->addElement(new Element_HTML('<div class="rmheader">' . $this->form_options->form_custom_text . '</div>'));
        
        if (!$this->is_expired())
        {
            $this->pre_render();
            $this->base_render($form);
            $this->post_render();
        } else
        {
            if ($this->form_options->form_message_after_expiry)
                echo $this->form_options->form_message_after_expiry;
            else
                echo RM_UI_Strings::get('MSG_FORM_EXPIRY');
        }


        echo '</div>';
    }

    //Get prepared data, depending upon flag 'data_type'.
    // - all = data of all fields.
    // - primary = data of primary fields only.
    // - dbonly = excludes the data of the fields which are not to be saved in db.
    // 
    //Depending upon the flag a different internal function is called.
    //Individual form classes must override these functions in order to customize the data as per the form specifications.

    public function get_prepared_data($request, $data_type = 'all')
    {
        switch ($data_type)
        {
            case 'all': return $this->get_prepared_data_all($request);
            case 'primary': return $this->get_prepared_data_primary($request);
            case 'dbonly': return $this->get_prepared_data_dbonly($request);
        }
    }

    protected function get_prepared_data_all($request)
    {
        $data = array();

        foreach ($this->fields as $field)
        {
            $field_data = $field->get_prepared_data($request);

            if ($field_data === null)
                continue;

            $data[$field_data->field_id] = (object) array('label' => $field_data->label,
                        'value' => $field_data->value,
                        'type' => $field_data->type);
        }

        return $data;
    }

    //in the base class there is no primary fields.
    protected function get_prepared_data_primary($request)
    {
        return array();
    }

    //in the base class there is no db-excluded field.
    protected function get_prepared_data_dbonly($request)
    {
        return $this->get_prepared_data_all($request);
    }

    //Get pricing detail for all the pricing fields.
    public function get_pricing_detail($request)
    {
        $data = new stdClass;

        //To return null in case there is no price field in the form
        $price_flag = false;
        $data->billing = array();
        $data->total_price = 0.0;
        foreach ($this->fields as $field)
        {
            if ($field->get_field_type() === 'Price')
            {
                $price_flag = true;
                $field_pricing_detail = $field->get_pricing_detail($request);

                if ($field_pricing_detail !== null)
                {
                    foreach ($field_pricing_detail->billing as $individual_item)
                        $data->billing[] = $individual_item;

                    $data->total_price += $field_pricing_detail->total_price;
                }
            }
        }

        return $price_flag ? $data : null;
    }

    public function has_price_field()
    {
        foreach ($this->fields as $field)
        {
            if ($field->get_field_type() === 'Price')
                return true;
        }

        return false;
    }

    protected function add_payment_fields($form)
    {        
        return $form;
    }

}
