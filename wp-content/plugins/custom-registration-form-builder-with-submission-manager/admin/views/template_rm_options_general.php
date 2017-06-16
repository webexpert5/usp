<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$image_dir = plugin_dir_url(dirname(dirname(__FILE__))) . "images";

$layout_checked_state = array('label_left' => null, 'label_top' => null, 'two_columns' => null);

$two_col_class = '';

if ($data['theme'] == 'matchmytheme')
{
    if ($data['form_layout'] == 'label_top')
        $layout_checked_state['label_top'] = 'checked';
    else
        $layout_checked_state['label_left'] = 'checked';
    $two_col_class = 'class="rm_hidden_element"';
}
else
{
    if ($data['form_layout'] == 'two_columns')
        $layout_checked_state['two_columns'] = 'checked';
    else if ($data['form_layout'] == 'label_top')
        $layout_checked_state['label_top'] = 'checked';
    else
        $layout_checked_state['label_left'] = 'checked';
}

$layout_radio_button_html_string = '<div class="rmrow"><div class="rmfield" for="layout_radio"><label>' .
        RM_UI_Strings::get('LABEL_LAYOUT') .
        '</label></div><div class="rminput"><ul class="rmradio">' .
        '<li><div id="layout_left_container"><div class="rmlayoutimage"><img src="' . $image_dir . '/label-left.png" /></div><input id="layout_radio-1" type="radio" name="form_layout" value="label_left" ' . $layout_checked_state['label_left'] . '>' .
        RM_UI_Strings::get('LABEL_LAYOUT_LABEL_LEFT') .
        '</div></li><li><div id="layout_top_container"><div class="rmlayoutimage"><img src="' . $image_dir . '/label-top.png" /></div><input id="layout_radio-2" type="radio" name="form_layout" value="label_top" ' . $layout_checked_state['label_top'] . '>' .
        RM_UI_Strings::get('LABEL_LAYOUT_LABEL_TOP') .
        '</div></li><li><div id="layout_two_columns_container"' . $two_col_class . '><div class="rmlayoutimage"><img src="' . $image_dir . '/two-column.png" /></div><input id="layout_radio-3" type="radio" name="form_layout" value="two_columns" ' . $layout_checked_state['two_columns'] . '>' .
        RM_UI_Strings::get('LABEL_LAYOUT_TWO_COLUMNS') .
        '</div></li></ul></div><div class="rmnote"><div class="rmprenote"></div><div class="rmnotecontent">' .
        RM_UI_Strings::get('HELP_OPTIONS_GEN_LAYOUT') .
        '</div></div></div>';


//echo $layout_radio_button_html_string;
?>


<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
        $pages = get_pages();
        $wp_pages = RM_Utilities::wp_pages_dropdown();

        $form = new RM_PFBC_Form("options_general");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));

        $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get('GLOBAL_SETTINGS_GENERAL') . '</div>'));
        $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_FORM_STYLE'), "theme", array("classic" => "Classic", "matchmytheme" => "Match my theme"), array("value" => $data['theme'], "id" => "theme_dropdown", "onchange" => "rm_toggle_visiblity_layouts(this)", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_THEME'))));

        $form->addElement(new Element_HTML($layout_radio_button_html_string));

        //Disabled for now, IP capture is needed to limit submissions from a given IP address. Unnecessary setting.
        //$form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_CAPTURE_INFO'), "user_ip", array("yes" => ''), $data['user_ip'] == 'yes' ? array("value" => "yes") : array()));

        $form->addElement(new Element_Textarea(RM_UI_Strings::get('LABEL_ALLOWED_FILE_TYPES'), "buy_pro", array("value" => $data['allowed_file_types'], 'disabled' => 1, "longDesc" => RM_UI_Strings::get('ALLOWED_FILE_TYPES_HELP'), "validation" => new Validation_RegExp("/[a-zA-Z0-9| ]*/", RM_UI_Strings::get('MSG_INVALID_CHAR')), "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_FILETYPES') . '<br><br>' . RM_UI_Strings::get('MSG_BUY_PRO_INLINE'))));

        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_ALLOWED_MULTI_FILES'), "buy_pro_2", array("yes" => ''), array("value" => "no", 'disabled' => 1, "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_FILE_MULTIPLE') . "<br><br>" . RM_UI_Strings::get('MSG_BUY_PRO_INLINE'))));

        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SHOW_PROG_BAR'), "display_progress_bar", array("yes" => ''), $data['display_progress_bar'] == 'yes' ? array("value" => "yes", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_PROGRESS_BAR')) : array("longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_PROGRESS_BAR'))));

        $selected = ($data['default_registration_url'] !== null) ? $data['default_registration_url'] : 0;
        $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_DEFAULT_REGISTER_URL'), "default_registration_url", $wp_pages, array("value" => $selected, "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_REG_URL'))));

        $selected = ($data['post_submission_redirection_url'] !== null) ? $data['post_submission_redirection_url'] : 0;
        $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_AFTER_LOGIN_URL'), "post_submission_redirection_url", $wp_pages, array("value" => $selected, "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_POST_SUB_REDIR'))));
        
        $selected = ($data['post_logout_redirection_page_id'] !== null) ? $data['post_logout_redirection_page_id'] : 0;
        $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_AFTER_LOGOUT_URL'), "post_logout_redirection_page_id", $wp_pages, array("value" => $selected, "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_POST_LOGOUT_REDIR'))));
        
  $submission_type=array(
             "all"=>"All",
             "today"=>"Today",
             "week"=>"This week",
             "month"=>"This month",
             "year"=>"This year"
             
        );
         $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_SUBMISSION_ON_CARD'), "submission_on_card", $submission_type, array("value" => $data['submission_on_card'], "longDesc" => RM_UI_Strings::get('HELP_SUBMISSION_ON_CARD'))));
        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SHOW_ASTERIX'), "show_asterix", array("yes" => ''), $data['show_asterix'] == 'yes' ? array("value" => "yes", "longDesc" => RM_UI_Strings::get('HELP_SHOW_ASTERIX')) : array("longDesc" => RM_UI_Strings::get('HELP_SHOW_ASTERIX'))));
       
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; Cancel', '?page=rm_options_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE')));
        $form->render();
        ?> 

    </div>
    <?php 
    include RM_ADMIN_DIR.'views/template_rm_promo_banner_bottom.php';
    ?>
</div>
<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
    jQuery(document).ready(function(){
       jQuery('#rm_floating_btn_type_rd-0').click(function(){
           jQuery('#floating_btn_txt_tb').slideUp();
       });
       jQuery('#rm_floating_btn_type_rd-1, #rm_floating_btn_type_rd-2').click(function(){
                      jQuery('#floating_btn_txt_tb').slideDown();
       });
    });
</script></pre>

<?php   
