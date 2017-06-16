<?php

/* 
 * Creates an object of a form after loading from databse.
 */

class RM_Form_Factory
{
    private $form_id;
    private $backend_form;
    private $backend_field;
    private $frontend_form;
    private $service;
    
    public function __construct()
    {
        $this->form_id = null;
        $this->backend_form = null;
        $this->backend_field = null;
        $this->frontend_form = null;
        $this->service = new RM_Front_Form_Service;
    }
    
    public function create_form($form_id)
    {
        //Load form from database
        $this->backend_form = new RM_Forms;
        $this->backend_form->load_from_db($form_id);
        
        //Update form diary
        global $rm_form_diary;
        if(isset($rm_form_diary[$form_id]))
            $rm_form_diary[$form_id]++;
        else
            $rm_form_diary[$form_id] = 1;
        
        
        $primary_field_req_names = array();
        //Load corresponding fields from db
        $fields = array();
        $db_fields = $this->service->get_all_form_fields($form_id);
        if($db_fields)
        {
            foreach($db_fields as $db_field)
            {
                $field_options = maybe_unserialize($db_field->field_options);
                $form_options = $this->backend_form->get_form_options();

                if(isset($form_options->style_textfield)){
                    $field_options->style_textfield = $form_options->style_textfield;
                }
                if(isset($form_options->style_label)){
                    $field_options->style_label = $form_options->style_label;
                }
                
                $opts = $this->service->set_properties($field_options);
                $field_name= $db_field->field_type."_".$db_field->field_id;
                $field_type= str_replace('-','',strtolower($db_field->field_type));
                
                //remove required check if file field is being edited
                if($field_type === 'file'){
                    if(isset($opts['required']))
                        unset($opts['required']);
                }
                
                $field_factory= new RM_Field_Factory($db_field,$opts);
                
                // Check if this is primary email field
                if($field_type=='email'):
                    if($db_field->is_field_primary):
                        $primary_field_req_names['user_email'] =  $db_field->field_type."_".$db_field->field_id;
                    endif;     
                endif;
                
                if(is_callable(array($field_factory,"create_".$field_type."_field"))){
                     $field = call_user_func_array(array($field_factory,"create_".$field_type."_field"),array());
                     if($field)
                        $fields[$field_name] = $field;
                }else{
                    $field = call_user_func_array(array($field_factory,"create_default_field"),array());
                    if($field)
                     $fields[$field_name] = $field;
                }//end if
            }  //end foreach          
        }    
            
            switch($this->backend_form->get_form_type())
            {
                case RM_REG_FORM:                    
                    $this->frontend_form = new RM_Frontend_Form_Reg($this->backend_form);
                    $primary_field_req_names['username'] = 'username';
                    $primary_field_req_names['password'] = 'password';
                    $this->frontend_form->set_primary_field_index($primary_field_req_names);
                    break;
                
                //Contact form is default case to keep compatibility with previous code
                default:
                    //$this->frontend_form = new RM_Frontend_Form_Multipage($this->backend_form);                    
                    $this->frontend_form = new RM_Frontend_Form_Contact($this->backend_form);
                    $this->frontend_form->set_primary_field_index($primary_field_req_names);
                    break;
            }              
            
            $this->frontend_form->add_fields_array($fields);      
            $this->frontend_form->set_form_number($rm_form_diary[$form_id]);
        
        //Set up FE form object
        
        //Return  new FE form
        return $this->frontend_form;
    }
    
    public function create_form_prefilled($form_id,$submission_id){
        //Load form from database
        $this->backend_form = new RM_Forms;
        $this->backend_form->load_from_db($form_id);
        
        //load submission form database
        $submission = new RM_Submissions;
        $submission->load_from_db($submission_id);
        
        //load previous submission data to fill 
        $prev_sub_data = $submission->get_data();
        
        //Update form diary
        global $rm_form_diary;
        if(isset($rm_form_diary[$form_id]))
            $rm_form_diary[$form_id]++;
        else
            $rm_form_diary[$form_id] = 1;
        
        
        $primary_field_req_names = array();
        //Load corresponding fields from db
        $fields = array();
        $db_fields = $this->service->get_editable_fields($form_id);
        $primary = $this->service->get('FIELDS', array('field_type' => 'Email', 'is_field_primary' => 1, 'form_id' => $form_id), array('%s','%d','%d'), 'row');
        
        $db_fields[$primary->field_id] = $primary;
        
        if($db_fields)
        {
            foreach($db_fields as $db_field)
            {
                if(isset($prev_sub_data[$db_field->field_id])){
                	$prev_entry = $prev_sub_data[$db_field->field_id];
                }else{
                	$prev_entry = new stdClass;
                	$prev_entry->type = null;
                	$prev_entry->value= null;
                	$prev_entry->label= null;
                }
                $field_options = maybe_unserialize($db_field->field_options);
                $form_options = $this->backend_form->get_form_options();

                if(isset($form_options->style_textfield)){
                    $field_options->style_textfield = $form_options->style_textfield;
                }
                if(isset($form_options->style_label)){
                    $field_options->style_label = $form_options->style_label;
                }
                
                $opts = $this->service->set_properties($field_options);
                $opts['value'] = $prev_entry->value;
                $field_factory= new RM_Field_Factory($db_field,$opts);
                $field_name= $db_field->field_type."_".$db_field->field_id;
                $field_type= strtolower($db_field->field_type);
                
                //Check if type of the field has not been changed
                /*if($field_type !== $prev_entry->type)
                    continue;*/
                
                // Check if this is primary email field
                if($field_type=='email'):
                    if($db_field->is_field_primary):
                        $primary_field_req_names['user_email'] =  $db_field->field_type."_".$db_field->field_id;
                        $opts['readonly'] = true;
                    endif;     
                endif;
                $field_factory= new RM_Field_Factory($db_field,$opts);
                $field_type = str_replace('-', '', $field_type);
                if(is_callable(array($field_factory,"create_".$field_type."_field"))){
                     $field = call_user_func_array(array($field_factory,"create_".$field_type."_field"),array());
                     if($field)
                        $fields[$field_name] = $field;
                }else{
                    $field = call_user_func_array(array($field_factory,"create_default_field"),array());
                    if($field)
                     $fields[$field_name] = $field;
                }//end if
            }  //end foreach  
          }  
            
            switch($this->backend_form->get_form_type())
            {
                case RM_REG_FORM:                    
                    $this->frontend_form = new RM_Frontend_Form_Reg($this->backend_form);
                    $primary_field_req_names['username'] = 'username';
                    $primary_field_req_names['password'] = 'password';
                    $this->frontend_form->set_primary_field_index($primary_field_req_names);
                    break;
                
                //Contact form is default case to keep compatibility with previous code
                default:
                    //$this->frontend_form = new RM_Frontend_Form_Multipage($this->backend_form);                    
                    $this->frontend_form = new RM_Frontend_Form_Contact($this->backend_form);
                    $this->frontend_form->set_primary_field_index($primary_field_req_names);
                    break;
            }              
            
            $this->frontend_form->add_fields_array($fields);      
            $this->frontend_form->set_form_number($rm_form_diary[$form_id]);
        
        //Set up FE form object
        
        //Return  new FE form
        return $this->frontend_form;
    }
}
