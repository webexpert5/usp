<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$form = new RM_PFBC_Form("add-field");

$form->configure(array(
    "prevent" => array("bootstrap", "jQuery"),
    "action" => ""
));

$form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("TITLE_EDIT_FIELD_PAGE") . '</div>'));

$form->addElement(new Element_Hidden("field_id", $data->model->field_id));

$form->addElement(new Element_Hidden("form_id", $data->form_id));
$form->addElement(new Element_Hidden("field_is_required", 1));

$form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_SELECT_TYPE') . ":</b>", "primary_field_type", array("id" => "rm_field_type_select_dropdown", "disabled" => "1" , "value" => 'Email', "class" => "rm_static_field rm_required", /*"required" => "1",*/ "longDesc"=>RM_UI_Strings::get('HELP_ADD_PRIMARY_FIELD_EMAIL'))));
$form->addElement(new Element_Hidden('field_type','Email'));
$form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_LABEL') . ":</b>", "field_label", array("class" => "rm_static_field rm_required", "required" => "1", "value" => $data->model->field_label, "longDesc"=>RM_UI_Strings::get('HELP_ADD_FIELD_LABEL'))));
$form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_PLACEHOLDER_TEXT') . ":</b>", "field_placeholder", array("id" => "rm_field_placeholder", "class" => "rm_static_field rm_text_type_field rm_input_type", "value" => $data->model->field_options->field_placeholder, "longDesc"=>RM_UI_Strings::get('HELP_ADD_FIELD_PLACEHOLDER'))));

$form->addElement(new Element_HTML('<div id="rm_icon_setting_container">'));
$form->addElement(new Element_HTML('<div class="rmrow" id="rm_jqnotice_row_date_type"><div class="rmfield" for="rm_field_value_options_textarea"><label>'.RM_UI_Strings::get('LABEL_FIELD_ICON').'</label></div><div class="rminput" id="rm_field_icon_chosen"><i class="material-icons"'.$icon_style.' id="id_show_selected_icon">'.$f_icon->codepoint.'</i><div class="rm-icon-action"><div onclick="show_icon_reservoir()"><a href="javascript:void(0)">'.RM_UI_Strings::get('LABEL_FIELD_ICON_CHANGE').'</a></div> <div onclick="rm_remove_icon()"><a href="javascript:void(0)">'.RM_UI_Strings::get('LABEL_REMOVE').'</a></div></div></div><div class="rmnote"><div class="rmprenote"></div><div class="rmnotecontent">'.RM_UI_Strings::get('HELP_FIELD_ICON').'</div></div></div>'));
$form->addElement(new Element_Hidden('input_selected_icon_codepoint', $f_icon->codepoint, array('id'=>'id_input_selected_icon')));
$form->addElement(new Element_Color(RM_UI_Strings::get('LABEL_FIELD_ICON_FG_COLOR'), "icon_fg_color", array("id" => "rm_", "value" => $f_icon->fg_color, "onchange" => "change_icon_fg_color(this)", "longDesc" => RM_UI_Strings::get('HELP_FIELD_ICON_FG_COLOR'))));

$form->addElement(new Element_Color(RM_UI_Strings::get('LABEL_FIELD_ICON_BG_COLOR'), "icon_bg_color", array("id" => "rm_", "value" => $f_icon->bg_color, "onchange" => "change_icon_bg_color(this)", "longDesc" => RM_UI_Strings::get('HELP_FIELD_ICON_BG_COLOR'))));

$form->addElement(new Element_Range(RM_UI_Strings::get('LABEL_FIELD_ICON_BG_ALPHA'), "icon_bg_alpha", array("id" => "rm_", "value" => $f_icon->bg_alpha, "step" => 0.1, "min" => 0, "max" => 1, "oninput" => "finechange_icon_bg_color()", "onchange" => "finechange_icon_bg_color()", "longDesc" => RM_UI_Strings::get('HELP_FIELD_ICON_BG_ALPHA'))));

$form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_FIELD_ICON_SHAPE'), "icon_shape", $icon_shapes, array("id" => "rm_", "value" => $f_icon->shape, "onchange" => "change_icon_shape(this)", "longDesc" => RM_UI_Strings::get('HELP_FIELD_ICON_SHAPE'))));
$form->addElement(new Element_HTML('</div>'));

$form->addElement(new Element_HTML('<div style="display:none">'));
$form->addElement(new Element_jQueryUIDate("", '', array()));
$form->addElement(new Element_HTML('</div>'));

$form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_CSS_CLASS') . ":</b>", "field_css_class", array("id" => "rm_field_class", "class" => "rm_static_field rm_required", "value" => $data->model->field_options->field_css_class, "longDesc"=>RM_UI_Strings::get('HELP_ADD_FIELD_CSS_CLASS'))));
$form->addElement(new Element_HTML('<div id="rm_field_helptext_container">'));
$form->addElement(new Element_Textarea("<b>" . RM_UI_Strings::get('LABEL_HELP_TEXT') . ":</b>", "help_text", array("id" => "rm_field_helptext", "class" => "", "value" => $data->model->field_options->help_text, "longDesc" => RM_UI_Strings::get('HELP_ADD_FIELD_HELP_TEXT'))));
$form->addElement(new Element_HTML('</div>'));
//Button Area
$form->addElement(new Element_HTMLL('&#8592; &nbsp; Cancel', '?page=rm_field_manage&rm_form_id='.$data->form_id, array('class' => 'cancel')));
$form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit")));

?>

<?php 

$form->render();
