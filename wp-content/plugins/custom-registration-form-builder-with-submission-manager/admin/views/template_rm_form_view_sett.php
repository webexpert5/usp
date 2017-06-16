<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
wp_enqueue_media();
$submit_btn_label = $data->model->get_form_options()->form_submit_btn_label ? : 'Submit';
wp_enqueue_script('rm-form_presentation', RM_BASE_URL. 'admin/js/script_rm_form_presentation.js', array(), null, false);
        echo '<style>';
        if($data->model->form_options->btn_hover_color)
            echo '.rm_btn_selector .rm_btn_focus:hover{ background-color:'.$data->model->form_options->btn_hover_color.' !important; }';
        if($data->model->form_options->field_bg_focus_color || $data->model->form_options->text_focus_color){
            echo '.rmagic .rmrow .rm_field_focus_bg:focus{';
            if($data->model->form_options->field_bg_focus_color)
                echo 'background-color:'.$data->model->form_options->field_bg_focus_color.' !important; } ';
            if($data->model->form_options->text_focus_color)
                echo '.rmagic .rmrow .rm_field_focus_text:focus { color:'.$data->model->form_options->text_focus_color.' !important; }';
            
        }
        echo '</style>';
?>
<pre class="rm-pre-wrapper-for-script-tags"><script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script></pre>

<div class="rmagic" ng-controller="formStyleCtrl"  ng-app="formStyleApp">
    <div class="operationsbar">
        <div class="rmtitle"><?php echo RM_UI_Strings::get('LABEL_FORM_PRESENTATION'); ?></div>
        <div class="nav">
            <ul><li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_BACK"); ?></a></li>
              
                <li><a href="javascript:void(0)" ng-click='resetAll()' id="rm-field-selection-popup"><?php echo RM_UI_Strings::get('LABEL_RESET'); ?></a></li>
            </ul>
        </div>
    </div>
    <!--Dialogue Box Starts-->
    <fieldset class="rm_form_presentation_fs">
        <legend style="display:none" id="rm_section_name" style="<?php echo $data->model->form_options->style_section; ?>">Section Name</legend>
        <div class="rm_form_container">
            <div class="rm_style_container" id="rm_style_container" style='<?php echo $data->model->get_form_options()->style_form; ?>'>
                <div class="rm_element_selector"> <input class="rm_selector" type="button"  id="rm_form_selector" value="Form Selector" ng-click="selectForm()"/></div>

                <div class="rmrow rm_edit_form_ui">
                    <div class="rmfield" id="rm_field_label">Field Label</div>
                    <div class="rminput">
                        <input class="rm_field_focus_bg rm_field_focus_text" type="text" style='<?php echo $data->model->get_form_options()->style_textfield; ?>' placeholder="Field" id="rm_textfield" />
                    </div>
                    <div class="rm_element_selector">
                        <input  type="button" class="rm_selector"  id="rm_text_field_selector" value="Text Field Selector" ng-click="selectTextField()"/>
                    </div>

                    <div class="rm_style_action"  ng-show="selectedElement == 'rm_textfield'" >
                        <style-action-box selected-element="rm_textfield" el-text="true"></style-action-box>
                    </div>
                </div>
                <div class="rmrow rm_edit_form_ui">
                    <div class="rmfield" id="rm_field_label">Field Label</div>
                    <div class="rminput">
                        <input class="rm_field_focus_bg rm_field_focus_text" style='<?php echo $data->model->get_form_options()->style_textfield; ?>' type="text" placeholder="Field" id="rm_textfield" />
                    </div>
                </div>
                <div class="rmrow rm_edit_form_ui">
                    <div class="rmfield" id="rm_field_label">Field Label</div>
                    <div class="rminput">
                        <input class="rm_field_focus_bg rm_field_focus_text" style='<?php echo $data->model->get_form_options()->style_textfield; ?>' type="text" placeholder="Field" id="rm_textfield" />
                    </div>
                </div>
                <div class="rmrow rm_edit_form_ui">
                    <div class="rmfield" id="rm_field_label">Field Label</div>
                    <div class="rminput">
                        <input class="rm_field_focus_bg rm_field_focus_text" style='<?php echo $data->model->get_form_options()->style_textfield; ?>' type="text" placeholder="Field" id="rm_textfield" />
                    </div>
                </div>
                <div class="rm_style_action"  ng-show="selectedElement == 'rm_style_container'" >
                    <style-action-box selected-element="rm_style_container" el-form="true"></style-action-box>
                </div>
                <div class="rm_btn_selector">
                    <input class="rm_btn_focus" type="button" style='<?php echo $data->model->get_form_options()->style_btnfield; ?>' value="<?php echo $submit_btn_label; ?>" id="rm_btnfield"/>
                    <input type="button" class="rm_selector"   id="rm_button_field_selector" value="" ng-click="selectButtonField()"/>
                    <div class="rm_style_action" ng-show="selectedElement == 'rm_btnfield'" >
                        <style-action-box selected-element="rm_btnfield" el-btn="true"></style-action-box>
                    </div>
                </div>
                <input type="hidden" value="<?php echo $data->model->get_form_id(); ?>" id="rm_form_id">
            </div>
        </div>
    </fieldset>
    
        <div class="rmnotice rm-invite-field-row" style="text-transform:none"><?php echo RM_UI_Strings::get('DISCLAIMER_FORM_VIEW_SETTING');?></div>

    <div class="buttonarea popup-button-group" style="">
        <div class="cancel">
            <a value="&amp;#8592; &amp;nbsp; Cancel" href="?page=rm_form_sett_manage&amp;rm_form_id=<?php echo $data->model->form_id; ?>" id="form_sett_post_sub-element-18">← &nbsp; Cancel</a>
        </div> 
        <input type="button" value="Save" name="submit" id="rm_submit_btn" class="rm_btn btn btn-primary popup-submit" ng-click="saveStyles()">
    </div>
    <div id="rm_styling_options" style="display:none">
        <div class="rm_pop_up_close" ng-click="close()">X</div>
        
        <div class="rm_pop_up_tab">
            <div id="rm_field_styling_options" ng-show="elText"> 
                <div class="rm_pop_up_row">
                    <label>Label Color </label>
                    <input type="text" id="rm_label_color" class="jscolor" ng-model="styles.label_color" ng-change="executeAction()" >
                </div>
                <div class="rm_pop_up_row">
                    <label>Text Color </label>
                    <input type="text" id="rm_text_color" class="jscolor" ng-model="styles.text_color" ng-change="executeAction()" >
                </div>
                <div class="rm_pop_up_row">
                    <label>Placeholder Color </label>
                    <input type="text" id="rm_placeholder_color" class="jscolor" ng-model="styles.placeholder_color" ng-change="executeAction()" >
                </div>
                
                <div class="rm_pop_up_row">
                    <label>Outline Color </label>
                    <input type="text" id="rm_outline_color" class="jscolor" ng-model="styles.text_outline_color" ng-change="executeAction()" >
                </div>
                
                <div class="rm_pop_up_row">
                    <label>Color on Focus </label>
                    <input type="text" id="rm_field_focus_color" class="jscolor" ng-model="styles.text_focus_color" ng-change="executeAction()" >
                </div>
                
                <div class="rm_pop_up_row">
                    <label>Background on Focus </label>
                    <input type="text" id="rm_field_bg_focus_color" class="jscolor" ng-model="styles.field_bg_focus_color" ng-change="executeAction()" >
                </div>
                
            </div>
            <div id="rm_form_styling_options" ng-show="elForm">
                <div class="rm_pop_up_row">
                    <label>Form Padding </label>
                    <input type="text" id="rm_padding" ng-model="styles.padding" value="0" ng-change="executeAction()" >
                </div>
            </div>
           
            <div id="rm_border_styling_options">
                <div class="rm_pop_up_row">
                    <label>Border Color </label>
                    <input type="text" id="rm_border_color" class="jscolor" ng-model="styles.border_color" ng-change="executeAction()" >
                </div>
                <div class="rm_pop_up_row">
                    <label>Border Width </label>
                    <input type="number" id="rm_border_width" ng-model="styles.border_width" ng-change="executeAction()">
                </div>
                <div class="rm_pop_up_row">
                    <label>Border Radius </label>
                    <input type="number" id="rm_border_radius" ng-model="styles.border_radius" ng-change="executeAction()" >
                </div>
                <div class="rm_pop_up_row">
                    <label>Border Style </label>
                    <select id="rm_border_style" ng-model="styles.border_style" ng-change="executeAction()" >
                        <option value="">Select Style</option>
                        <option>solid</option>
                        <option>dashed</option>
                        <option>dotted</option>
                        <option>double</option>
                        <option>groove</option>
                        <option>hidden</option>
                        <option>inherit</option>
                        <option>initial</option>
                        <option>inset</option>
                        <option>none</option>
                        <option>outset</option>
                        <option>ridge</option>
                    </select>    
                </div>
            </div>
            <div class="rm_pop_up_row">
                <label>Background Image</label>
                <input type="button" class="upload-btn" value="Upload" ng-click="mediaUploader()">
                <input type="button" class="rm_trash" ng-click="removeBackImage()" value="Remove">
            </div>
            <div class="rm_pop_up_row">
                <label>Image Repeat </label>
                <select id="rm_image_repeat" ng-model="styles.image_repeat" ng-change="executeAction()" >
                    <option selected value="">Select Repeat</option>
                    <option>repeat</option>
                    <option>inherit</option>
                    <option>initial</option>
                    <option>no-repeat</option>
                    <option>repeat-x</option>
                    <option>repeat-y</option>
                    <option>round</option>
                    <option>space</option>
                </select>    
            </div>
            <div id="rm_btn_styling_options" ng-show="elBtn">
                <div class="rm_pop_up_row">
                    <label>Button Label</label>
                    <input type="text" class="ng-pristine ng-untouched ng-valid" ng-change="executeAction()" ng-model="styles.btn_label">
                </div>
                <div class="rm_pop_up_row">
                    <label>Font Color</label>
                    <input type="text" class="jscolor" ng-change="executeAction()" ng-model="styles.btn_font_color"  >
                </div>
                
                <div class="rm_pop_up_tab">
                    <div class="rm_pop_up_row">
                        <label>Hover Color </label>
                        <input type="text" class="jscolor" id="rm_btn_hover_color" ng-model="styles.btn_hover_color" ng-change="executeAction()"  >
                    </div>   
                </div>
            </div>
            <div class="rm_pop_up_row">
                <label>Background Color </label>
                <input type="text" class="jscolor" id="rm_background_border" ng-model="styles.background_color" ng-change="executeAction()"  >
            </div>
            
            
            
        </div>
        
        <div id="rm_custom_style"><?php echo $data->model->form_options->placeholder_css; ?></div>
    </div>
</div>
