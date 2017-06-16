<?php
class Element_UserEmail extends Element_Textbox {
	protected $_attributes = array("type" => "email");

	public function render() {
		$this->validation[] = new Validation_Email;
		parent::render();
	}
        
        public function jQueryDocumentReady() {
          $form_id_array= explode('_', $this->_form->form_slug); 
          
          // Form int ID will always be on scond index eg: form_52_1
          $form_id= (int) $form_id_array[1];
          
          $validation_msg= RM_UI_Strings::get("USEREMAIL_EXISTS");  
          echo <<<JS
            
                   
                   jQuery("#{$this->_attributes['id']}").change(function(){
                   var validation_msg= '{$validation_msg}';
                   var data = {
                           'action': 'rm_user_exists',
                           'rm_slug': 'rm_user_exists',
                           'email': jQuery(this).val(),
                           'attr': 'data-rm-valid-email',
                           'form_id':"{$form_id}"
                   };
                   
                   rm_user_exists(this,rm_ajax_url,data,"{$validation_msg}");
                  
                 });
           
JS;
            
        
        }
}
