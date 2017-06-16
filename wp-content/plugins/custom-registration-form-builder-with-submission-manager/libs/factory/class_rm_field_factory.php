<?php

/**
 * Description of RM_Field_Factory
 *
 */
class RM_Field_Factory {
    
    protected $db_field;
    protected $field_name;
    protected $field_options;
    protected $gopts;
    protected $opts;
    protected $x_opts;
            
    public function __construct($db_field,$opts){
        $this->db_field= $db_field;
        $this->gopts= new RM_Options;
        $this->opts= $opts;
        $this->field_options = maybe_unserialize($db_field->field_options);
        $this->field_name= $db_field->field_type."_".$db_field->field_id;
        $this->db_field->field_value = maybe_unserialize($db_field->field_value);
        if(isset($this->field_options->icon))
            $this->x_opts = (object)array('icon' => $this->field_options->icon);
        else
            $this->x_opts = null;
        
    }
    
     public function create_binfo_field(){
        if(is_user_logged_in())
        {
            $current_user = wp_get_current_user();  
            $user_binfo= get_user_meta($current_user->ID, 'description', true);
            $this->opts['value'] = ($user_binfo == '')? null : $user_binfo;
        }
       return new RM_Frontend_Field_Base($this->db_field->field_id, $this->db_field->field_type, $this->db_field->field_label, $this->opts, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
   
     }     
    
    public function create_price_field(){
        $currency_pos = $this->gopts->get_value_of('currency_symbol_position');
        $currency_symbol = $this->gopts->get_currency_symbol();
        return new RM_Frontend_Field_Price($this->db_field->field_id, $this->db_field->field_label, $this->opts, $this->db_field->field_value, $currency_pos, $currency_symbol, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts); 
    }
    
    public function create_file_field(){
        return null;
    }
    
    public function create_select_field(){
        return new RM_Frontend_Field_Select($this->db_field->field_id, $this->db_field->field_label, $this->opts, $this->db_field->field_value, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
    }
    
    public function create_MultiDropdown_field(){
        $this->opts['multiple']='multiple';
        return null;
    }
    
    public function create_base_field(){
        return new RM_Frontend_Field_Base($this->db_field->field_id,'Textbox', $this->db_field->field_label, $this->opts, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
    }
    
    public function create_phone_field(){
       return null;
    }
    
    public function create_mobile_field(){
        return null;
    }
    
    public function create_nickname_field(){
        if(is_user_logged_in())
        {
            $current_user = wp_get_current_user();  
            $user_nickname= get_user_meta($current_user->ID, 'nickname', true);
            $this->opts['value'] = ($user_nickname == '')? null : $user_nickname;
        }
       return new RM_Frontend_Field_Base($this->db_field->field_id,'Nickname', $this->db_field->field_label, $this->opts, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
    }
    
    public function create_image_field(){
        return null;
    }
    
    public function create_facebook_field(){
        return null;
    }
    
    public function create_website_field(){
        $this->opts['Pattern'] = "((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)";
        if(is_user_logged_in())
        {
            $current_user = wp_get_current_user(); 
            $this->opts['value'] = isset($current_user->user_url)? $current_user->user_url : null;
        }
        return new RM_Frontend_Field_Base($this->db_field->field_id,'Website', $this->db_field->field_label, $this->opts, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
    }
    
    public function create_twitter_field(){
        return null;
    }
    
    public function create_google_field(){
        return null;
    }
    
    public function create_instagram_field(){
       return null;
    }
    
    public function create_linked_field(){
        return null;
    }
    
    public function create_soundcloud_field(){
        return null;
    }
    
    public function create_youtube_field(){
        return null;
        
    }
    
    public function create_vkontacte_field(){
        return null;
        
    }
    
    public function create_skype_field(){
        return null;
    }
    
    public function create_bdate_field(){
        return null;
    }
    
    public function create_secemail_field(){
        return null;
    }
    
    public function create_gender_field(){
        return null;
    }
    
    public function create_terms_field(){
        $this->opts['cb_label'] = isset($this->field_options->tnc_cb_label)?$this->field_options->tnc_cb_label:null;
        return new RM_Frontend_Field_Terms($this->db_field->field_id, $this->db_field->field_label, $this->opts, $this->db_field->field_value, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
    }
    
    public function create_language_field(){
       return null;
    }
    
    public function create_radio_field(){
        return new RM_Frontend_Field_Radio($this->db_field->field_id, $this->db_field->field_label, $this->opts, $this->db_field->field_value, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
    }
    
    public function create_checkbox_field(){
        return new RM_Frontend_Field_Checkbox($this->db_field->field_id, $this->db_field->field_label, $this->opts, $this->db_field->field_value, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
    }
    
    public function create_shortcode_field(){
        return null;
    }
    
    public function create_divider_field(){
        return null;
    }
    
    public function create_spacing_field(){
        return null;
    }
    
    public function create_htmlh_field(){
        return new RM_Frontend_Field_Visible_Only($this->db_field->field_id, $this->db_field->field_type, $this->db_field->field_label, $this->opts, $this->db_field->field_value, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
    }
    
    public function create_htmlp_field(){
        return new RM_Frontend_Field_Visible_Only($this->db_field->field_id, $this->db_field->field_type, $this->db_field->field_label, $this->opts, $this->db_field->field_value, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
    }
    
    public function create_time_field(){
        return null;
    }
    
    public function create_rating_field(){
        return null;
    }
    
    public function create_custom_field(){
               return null;
    }
    
    public function create_email_field(){
        // in this case pre-populate the primary email field with logged-in user's email.
        if($this->db_field->is_field_primary)
        {
            if(is_user_logged_in())
            {
                $current_user = wp_get_current_user();                            
                $this->opts['value'] = $current_user->user_email;
            }
        }
         return new RM_Frontend_Field_Base($this->db_field->field_id,$this->db_field->field_type, $this->db_field->field_label, $this->opts, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
    }
    
    public function create_address_field(){
        return null;
    }
    
    public function create_map_field(){
        return null;
    }
    
    public function create_geo_field(){
        return null;
    }
    
    public function create_textbox_field(){
        $field= $this->create_base_field();
        return $field;
    }
    
    
    public function create_fname_field(){
       if(is_user_logged_in())
        {
            $current_user = wp_get_current_user();  
            $user_fname= get_user_meta($current_user->ID, 'first_name', true);
            $this->opts['value'] = ($user_fname == '')? null : $user_fname;
        }
      return new RM_Frontend_Field_Base($this->db_field->field_id, $this->db_field->field_type, $this->db_field->field_label, $this->opts, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
  
    }
    
    public function create_lname_field(){
        if(is_user_logged_in())
        {
            $current_user = wp_get_current_user();  
            $user_lname= get_user_meta($current_user->ID, 'last_name', true);
            $this->opts['value'] = ($user_lname == '')? null : $user_lname;
        }
      return new RM_Frontend_Field_Base($this->db_field->field_id, $this->db_field->field_type, $this->db_field->field_label, $this->opts, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
  
    }
    public function create_default_field(){
        return new RM_Frontend_Field_Base($this->db_field->field_id, $this->db_field->field_type, $this->db_field->field_label, $this->opts, $this->db_field->page_no, $this->db_field->is_field_primary, $this->x_opts);
    }
   
}
