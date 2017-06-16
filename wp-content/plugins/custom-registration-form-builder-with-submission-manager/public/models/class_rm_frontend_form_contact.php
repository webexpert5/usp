<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RM_Frontend_Form_Contact extends RM_Frontend_Form_Multipage//RM_Frontend_Form_Base
{

    public function __construct(RM_Forms $be_form, $ignore_expiration=false)
    {
        parent::__construct($be_form, $ignore_expiration);
        $this->set_form_type(RM_CONTACT_FORM);
    }

    public function pre_sub_proc($request, $params)
    {
        return true;
    }

    public function post_sub_proc($request, $params)
    {
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

        return null;
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
            "name" => "rm_form",
            "number" => $this->form_number,
            "view" => new View_UserForm,
            "style" => isset($this->form_options->style_form)?$this->form_options->style_form:null
        ));
        
        //Render content above the form
        if (!empty($this->form_options->form_custom_text))
                $form->addElement(new Element_HTML('<div class="rmheader">' . $this->form_options->form_custom_text . '</div>'));

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

        parent::pre_render();
        $this->base_render($form);
        parent::post_render();

        echo '</div>';
    }

    protected function get_prepared_data_primary($request)
    {
        $data = array();

        foreach ($this->fields as $field)
        {
            if ($field->get_field_type() == 'Email' && $field->is_primary())
            {
                $field_data = $field->get_prepared_data($request);

                $data['user_email'] = (object) array('label' => $field_data->label,
                            'value' => $field_data->value,
                            'type' => $field_data->type);

                break;
            }
        }

        return $data;
    }

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

}
