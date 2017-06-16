<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<!--------WP Menu Bar

<div class="wpadminbar">Hi</div>

<div class="adminmenublock">
test</div>------->


<div class="rmagic">

    <!-----Operationsbar Starts----->
    <form method="post" id="rm_field_manager_form">
        <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
        <div class="operationsbar">
            <div class="rmtitle"><?php echo RM_UI_Strings::get("TITLE_FORM_FIELD_PAGE"); ?></div>
            <div class="icons">
                <a href="?page=rm_form_sett_manage&rm_form_id=<?php echo $data->form_id; ?>"><img alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/general-settings.png'; ?>"></a>
            </div>
            <div class="nav">
                <ul>
                    <li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_BACK"); ?></a></li>
              <li onclick='add_new_field_to_page()'><a href="javascript:void(0)"><?php echo RM_UI_Strings::get('LABEL_ADD_NEW_FIELD'); ?></a></li>
                    <li onclick="jQuery.rm_do_action('rm_field_manager_form', 'rm_field_duplicate')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get('LABEL_DUPLICATE'); ?></a></li>  
                    
                    <li onclick="jQuery.rm_do_action('rm_field_manager_form', 'rm_field_remove')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get('LABEL_REMOVE'); ?></a></li>
                    <li class="rm-form-toggle"><?php echo RM_UI_Strings::get('LABEL_FILTER_BY'); ?>
                        <select id="rm_form_dropdown" name="form_id" onchange = "rm_load_page(this, 'field_manage')">
                            <?php
                            foreach ($data->forms as $form_id => $form)
                                if ($data->form_id == $form_id)
                                    echo "<option value=$form_id selected>$form</option>";
                                else
                                    echo "<option value=$form_id>$form</option>";
                            ?>
                        </select></li>  
                </ul>
            </div>

        </div>
        <!--------Operationsbar Ends----->

        <!----Field Selector Starts---->

        <div class="rm-field-selector rm_tabbing_container">
            <div class="">
                <ul class="field-tabs">
                    <li class="field-tabs-row"><a href="#rm_common_fields_tab" class="rm_tab_links" id="rm_special_fields_tab_link"><?php echo RM_UI_Strings::get("LABEL_COMMON_FIELDS"); ?></a></li>  
                    <li class="field-tabs-row"><a href="#rm_special_fields_tab" class="rm_tab_links" id="rm_special_fields_tab_link"><?php echo RM_UI_Strings::get("LABEL_SPECIAL_FIELDS"); ?></a></li>
                    <li class="field-tabs-row"><a href="#rm_profile_fields_tab" class="rm_tab_links" id="rm_special_fields_tab_link"><?php echo RM_UI_Strings::get("LABEL_PROFILE_FIELDS"); ?></a></li>
                    <li class="field-tabs-row"><a href="#rm_social_fields_tab" class="rm_tab_links" id="rm_special_fields_tab_link"><?php echo RM_UI_Strings::get("LABEL_SOCIAL_FIELDS"); ?></a></li> </ul>
            </div>
            <div class="field-selector-pills">
                <div id="rm_common_fields_tab">
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Textbox"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Textbox')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_TEXT"); ?></a></div>  
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Select"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Select')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_DROPDOWN"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Radio"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Radio')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_RADIO"); ?></a></div>  
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Textarea"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Textarea')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_TEXTAREA"); ?></a></div>  
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Checkbox"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Checkbox')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_CHECKBOX"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_HTMLH"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('HTMLH')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_HEADING"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_HTMLP"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('HTMLP')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_PARAGRAPH"); ?></a></div>
                </div>
                <div id="rm_special_fields_tab">
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_jQueryUIDate"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('jQueryUIDate')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_DATE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Email"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Email')">       <a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_EMAIL"); ?></a></div>                    
                     <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Password"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Password')">    <a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_PASSWORD"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Number"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Number')">      <a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_NUMBER"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Country"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Country')">     <a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_COUNTRY"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Timezone"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Timezone')">    <a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_TIMEZONE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Terms"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Terms')">       <a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_T_AND_C"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_File"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_FILE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Price"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Price')">       <a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_PRICE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Repeatable"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_RAPEAT"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Map"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_MAP"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Address"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_ADDRESS"); ?></a></div>
                    
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Phone"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_PHONE"); ?></a></div>
                     <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Mobile"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_MOBILE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Language"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_LANGUAGE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Bdate"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_BDATE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Gender"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_GENDER"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Time"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_TIME"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Image"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_IMAGE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Shortcode"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_SHORTCODE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Divider"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_DIVIDER"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Spacing"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_SPACING"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Multi-Dropdown"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_MULTI_DROP_DOWN"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Custom"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_CUSTOM"); ?></a></div>
                   
                
                
                </div>
                <div id="rm_profile_fields_tab">
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Fname"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Fname')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_FNAME"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Lname"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Lname')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_LNAME"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_BInfo"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('BInfo')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_BINFO"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Nickname"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Nickname')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_NICKNAME"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Website"); ?>" class="rm_button_like_links" onclick="add_new_field_to_page('Website')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_WEBSITE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_SecEmail"); ?>" class="rm_button_like_links" ><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_SEMAIL"); ?></a></div>

                </div>
                 <div  id="rm_social_fields_tab">
                   <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Facebook"); ?>" class="rm_button_like_links" ><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_FACEBOOK"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Twitter"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_TWITTER"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Google"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_GOOGLE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Instagram"); ?>" class="rm_button_like_links" ><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_INSTAGRAM"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Linkedin"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_LINKED"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Youtube"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_YOUTUBE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_VKonatcte"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_VKONTACTE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Skype"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_SKYPE"); ?></a></div>
                    <div title="<?php echo RM_UI_Strings::get("FIELD_HELP_TEXT_Soundcloud"); ?>" class="rm_button_like_links"><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("FIELD_TYPE_SOUNDCLOUD"); ?></a></div>
                    
                </div>
            </div>

        </div>


        <?php
//////////////////////////////////////////
//////////////////////////////////////////
        if($data->total_page > 1)
            echo "<div class='rmnotice'>".RM_UI_Strings::get('MULTIPAGE_DEGRADE_WARNING')."</div>";
        ?>
        


        <div class="rm-field-creator">
            <div id="rm_form_page_tabs">
                <ul class="rm-page-tabs-sidebar" class="field-tabs">
                    <?php // foreach($data->form_pages as $k => $fpage)//for ($i = 1; $i <= $data->total_page; $i++)
                    { $i = 1;
                        ?>
                        <li class="rm-page-tab"><a href="#rm_form_page<?php echo '_' . $i; ?>" class="rm_page_tab rmZX56-param" id="rm_form_page_tab_link<?php echo '_' . $i; ?>"><?php echo 'Page 1'; ?></a></li>  
                        <?php
                        }
                        ?>
                       
                        <li class="rm-page-tab-add-new"><a class='rm_deactivated' href='javascript:void(0)'>New Page</a></li>
                        
                    </ul>

                    <div class="field-selector-pills">
    <?php //foreach($data->form_pages as $k => $fpage)//for ($i = 1; $i <= $data->total_page; $i++)
    {$i = 1;
        ?>
                            <div id="rm_form_page<?php echo '_' . $i; ?>">
                                <div class="rm-custom-fields-page">
                                    
                                    <div class="rmrow">
<!--                                        <a href="#">Current Page <?php echo $i; ?></a>-->
                                        
                                        <a class="rm_deactivated" href="javascript:void(0)">Rename Page</a>
                                        
                                        <a class="rm_deactivated" href="javascript:void(0)">Delete Page</a>
                                       
                                    </div>
<ul class="rm-field-container rm_sortable_form_fields">
                                        <?php
                                        if ($data->fields_data)
                                        {
                                            foreach ($data->fields_data as $field_data)
                                            {
                                                
                                                ?>
                                            

                                                <li id="<?php echo $field_data->field_id ?>">
                                                    <div class="rm-custom-field-page-slab">
                                                        <div class="rm-slab-drag-handle">
                                                            <span class="rm_sortable_handle">
                                                                <img alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/rm-drag.png'; ?>">
                                                            </span>
                                                        </div>
                                                        <div class="rm-slab-info">
                                                            <input type="checkbox" name="rm_selected[]" value="<?php echo $field_data->field_id; ?>" <?php if ($field_data->is_field_primary == 1) echo "disabled"; ?>>
                                                            <span><?php echo $field_data->field_label; ?>
                                                                <sup><?php echo $data->field_types[$field_data->field_type] ?></sup></span>

                                                        </div>
                                                        <div class="rm-slab-buttons">

                                                            <a onclick="edit_field_in_page('<?php echo $field_data->field_type;?>',<?php echo $field_data->field_id;?>)" href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_EDIT"); ?></a>

                                                            <?php
                                                            //var_dump($field_data->is_field_primary);die;
                                                            if ($field_data->is_field_primary == 1)
                                                            {
                                                                ?>
                                                                <a href="javascript:void(0)" class="rm_deactivated"><?php echo RM_UI_Strings::get("LABEL_DELETE"); ?></a>

                                                                <?php
                                                            } else
                                                            {
                                                                ?>

                                                                <a href="<?php echo '?page=rm_field_manage&rm_form_id=' . $data->form_id . '&rm_field_id=' . $field_data->field_id . '&rm_action=delete"'; ?>"><?php echo RM_UI_Strings::get("LABEL_DELETE"); ?></a>
                    <?php
                }
                ?>
                                                        </div>
                                                    </div>
                                                </li>

                                                <?php
                                            }
                                        } else
                                        {
                                            echo RM_UI_Strings::get('NO_FIELDS_MSG');
                                        }
                                        ?>    </ul>

                                    <div class="rmrow">
                                    <div class="rm_buy_pro_inline"><a href="https://registrationmagic.com/comparison/" target="blank"><?php echo RM_UI_Strings::get('MSG_BUY_PRO_GOLD_MULTIPAGE'); ?></a>
                                    </div>
                                    </div>

                                </div>

                            </div>
        <?php
        }
        ?>
                        </div>
                    </div>


                </div>


                <!----Slab View---->

               
            </form>
    <?php 
    $rm_promo_banner_title = "Unlock all custom field types by upgrading";
    include RM_ADMIN_DIR.'views/template_rm_promo_banner_bottom.php';
    ?>
    
    
        </div>

        <pre class='rm-pre-wrapper-for-script-tags'><script>
            jQuery(document).ready(function () {
                jQuery("#rm_form_page_tabs").tabs();
                jQuery("#rm_form_page_tabs").tabs("option", "active", 0);
                jQuery("#rm_form_page_tabs").tabs("disable", 1);
            })

            function get_current_form_page() {
                return (jQuery("#rm_form_page_tabs").tabs("option", "active")) + 1;
            }

            function add_new_field_to_page(field_type) {
                var curr_form_page = (jQuery("#rm_form_page_tabs").tabs("option", "active")) + 1;
                var loc = "?page=rm_field_add&rm_form_id=<?php echo $data->form_id; ?>&rm_form_page_no=" + curr_form_page + "&rm_field_type";
                if (field_type !== undefined)
                    loc += ('=' + field_type);
                window.location = loc;
            }
            
            function edit_field_in_page(field_type, field_id) {
                if (field_type == undefined || field_id == undefined)
                    return;
                var curr_form_page = (jQuery("#rm_form_page_tabs").tabs("option", "active")) + 1;
                var loc = "?page=rm_field_add&rm_form_id=<?php echo $data->form_id; ?>&rm_form_page_no=" + curr_form_page + "&rm_field_type";
                loc += ('=' + field_type);
                loc += "&rm_field_id="+field_id;
                window.location = loc;
            }

            function add_new_page_to_form() {
                var loc = "?page=rm_field_manage&rm_form_id=<?php echo $data->form_id; ?>&rm_action=add_page";
                window.location = loc;
            }

            function delete_page_from_page() {
                if (confirm('This will remove the page along with all the contained fields! Proceed?')) {
                var curr_form_page = (jQuery("#rm_form_page_tabs").tabs("option", "active")) + 1;
                var loc = "?page=rm_field_manage&rm_form_id=<?php echo $data->form_id; ?>&rm_form_page_no=" + curr_form_page + "&rm_action=delete_page";
                window.location = loc;
                }
            }

            function rename_form_page() {
                var new_name = prompt("Please enter new name", "New Page");
                if (new_name != null)
                {
                    var curr_form_page = (jQuery("#rm_form_page_tabs").tabs("option", "active")) + 1;
                    var loc = "?page=rm_field_manage&rm_form_id=<?php echo $data->form_id; ?>&rm_form_page_no=" + curr_form_page + "&rm_form_page_name=" + new_name + "&rm_action=rename_page";
                    window.location = loc;
                }
            }

        </script></pre> 
        <?php

        function get_current_form_page_no()
        {
            ?><pre class='rm-pre-wrapper-for-script-tags'><script>               
                return (jQuery("#rm_form_page_tabs").tabs("option", "active")) + 1;
             </script></pre><?php
        }
        ?>
 
