<?php

/**
 * View template file of the plugin
 *
 * @internal Add form page view.
 */

$form = new RM_PFBC_Form("rm_login_form");
$form->configure(array(
    "prevent" => array("bootstrap", "jQuery"),
    "action" => ""
));


$form->addElement(new Element_Hidden("rm_slug", "rm_login_form"));
$form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_USERNAME') . ":</b>", "username", array("required" => "1")));
$form->addElement(new Element_Password("<b>" . RM_UI_Strings::get('LABEL_PASSWORD') . ":</b>", "password",array("required"=>1)));
$form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_REMEMBER'), "remember", array(1 => ""),array("value"=> 1)));
/*
 * Checking if recpatcha is enabled
 */
if(get_option('rm_option_enable_captcha')=="yes")
    $form->addElement(new Element_Captcha());
$form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_LOGIN'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn rm_login_btn", "name" => "submit")));
$form->addElement(new Element_HTML('<div class="rm_forgot_pass"><a href="'.  wp_lostpassword_url() .'" target="blank">'.RM_UI_Strings::get('MSG_LOST_PASS').'</a></div>'));

/*
 * Render the form if user is not logged in
 */
?>
<div class='rmagic'>
	<div class='rmcontent'>
<?php
if(!is_user_logged_in()){
    $form->render();
    echo $data->facebook_html;

}
else{
    echo '<div class="rm_notice">'.RM_UI_Strings::get('LOGGED_STATUS').' <a href="'.wp_logout_url( get_permalink() ).'">'.RM_UI_Strings::get("LABEL_LOGOUT").'</a></div>';
}

?>
	</div>
</div>


