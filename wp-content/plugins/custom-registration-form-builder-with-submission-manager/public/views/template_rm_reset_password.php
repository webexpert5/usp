<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$form = new RM_PFBC_Form("rm_reset_pass_form");
$form->configure(array(
    "prevent" => array("bootstrap", "jQuery", "focus"),
    "action" => ""
));


$form->addElement(new Element_Hidden("rm_slug", "rm_front_reset_pass_page"));
$form->addElement(new Element_Password("<b>" . RM_UI_Strings::get('LABEL_OLD_PASS') . ":</b>", "old_pass", array('required' => true, 'id' => 'rm_old_pass_field')));
$form->addElement(new Element_HTML('<label class="rm-form-field-invalid-msg" id="old_pass_error" style="display:none"></label>'));
$form->addElement(new Element_Password("<b>" . RM_UI_Strings::get('LABEL_NEW_PASS') . ":</b>", "new_pass", array('required' => true, 'id' => 'rm_new_pass_field')));
$form->addElement(new Element_HTML('<label class="rm-form-field-invalid-msg" id="new_pass_error" style="display:none"></label>'));
$form->addElement(new Element_Password("<b>" . RM_UI_Strings::get('LABEL_NEW_PASS_AGAIN') . ":</b>", "new_pass_repeat", array('required' => true, 'id' => 'rm_repeat_pass_field')));
$form->addElement(new Element_HTML('<label class="rm-form-field-invalid-msg" id="repeat_pass_error" style="display:none"></label>'));
/*
 * Checking if recpatcha is enabled
 */
if(get_option('rm_option_enable_captcha')=="yes")
    $form->addElement(new Element_Captcha());

$form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_RESET_PASS'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn rm_login_btn", "name" => "submit", "onclick" => "rm_validate(event)")));

/*
 * Render the form if user is not logged in
 */
?>
<div class='rmagic'>
	<div class='rmcontent'>
<?php

    $form->render();
?>
            <pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
                function rm_validate(e){
                    var old_pass = jQuery('#rm_old_pass_field').val().toString().trim();
                    var new_pass = jQuery('#rm_new_pass_field').val().toString().trim();
                    var repeat_pass = jQuery('#rm_repeat_pass_field').val().toString().trim();
                    
                    if(old_pass === "" || !old_pass){
                        jQuery('#old_pass_error').html('This field is required.');
                        jQuery('#old_pass_error').show();
                    }
                    if(new_pass === "" || !new_pass){
                        jQuery('#new_pass_error').html('This field is required.');
                        jQuery('#new_pass_error').show();
                    }
                    if(repeat_pass === "" || !repeat_pass){
                        jQuery('#repeat_pass_error').html('This field is required.');
                        jQuery('#repeat_pass_error').show();
                    }
                    if(jQuery('#rm_new_pass_field').val() !== jQuery('#rm_repeat_pass_field').val()){
                        jQuery('#new_pass_error, #repeat_pass_error').html('password does not match.');
                        jQuery('#new_pass_error, #repeat_pass_error').show();
                        e.preventDefault();
                    }
                }
            </script></pre>
	</div>
</div>