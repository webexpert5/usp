<?php

/**
 * @internal Template File [Form Manager]
 *
 * This file renders the form manager page of the plugin which shows all the forms
 * to manage delete edit or manage
 */

global $rm_env_requirements;
global $regmagic_errors;
?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
 <?php if (($rm_env_requirements & RM_REQ_EXT_CURL) && $data->newsletter_sub_link){ ?>
 <div class="rm-newsletter-banner" id="rm_newsletter_sub"><?php echo $data->newsletter_sub_link;?><img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/close-rm.png'; ?>" onclick="jQuery('#rm_newsletter_sub').hide()"></div>
 <?php } ?>
 
 <?php
 //Check errors
 RM_Utilities::fatal_errors();
 foreach($regmagic_errors as $err)
 {
    //Display only non - fatal errors
    if($err->should_cont)
        echo '<div class="shortcode_notification ext_na_error_notice"><p class="rm-notice-para">'.$err->msg.'</p></div>';
 }
 ?>
<div class="rmagic rmbasic">
    
    <!-- Joyride Magic begins -->
    <ol id="rm-form-man-joytips" style="display:none">
        <li>
            <h2>
                Welcome to RegistrationMagic
            </h2>
            <p>RegistrationMagic is a powerful plugin that allows you to build custom registration system on your WordPress site. This is the main landing page - Forms Manager. Click <b>Next</b> to start a quick tour of this page. To stop at anytime, click the close icon on top right.</p>
        </li>
    <li data-id="rmbar" data-options="tipLocation:bottom">You will see this flat white box on top of different sections inside RegistrationMagic. We call it operations bar. It contains...</li>
         <li data-id="rm-tour-title" data-options="tipLocation:bottom">The heading of the section you are presently in...</li>
        <li data-id="rm-ob-icons" data-options="nubPosition:bottom-right;tipAdjustmentX:-330">Quick access icons relevant to the section...</li>
        <li data-id="rm-ob-sort" data-options="nubPosition:bottom-right;tipAdjustmentX:-320">A filter and sort drop down menu. In this section, it allows you to sort your forms.</li>
        <li data-id="rm-ob-nav">And a navigation menu with most important functions laid horizontally. Let's look at the Form Manager functions one by one.</li>
        <li data-id="rm-ob-new">This allows you to create new forms.</li>
        <li data-id="rm-ob-duplicate">This allows you to duplicate one or multiple forms. Form's settings and fields are also duplicated.</li>
        <li data-id="rm-ob-delete">This allows you to delete one or multiple forms. All associated form data is deleted.</li>
        <li data-id="rm-ob-export">This allows you to export all your forms and associated data in a single XML file. Handy if you are reinstalling your site, moving forms to another site or simply backing up your hard work.</li>
        <li data-id="rm-ob-import">Import button allows you to import the XML file saved on your computer.</li>
        <li><h3>
            Forms As Cards
            </h3>
        <p>RegistrationMagic displays all forms as rectangular cards. This is a novel new approach. You will later see that a form card is much more than a symbolic representation of a form. It can show you form related data and stats at a glance. </p>
        </li>
        <li data-id="rm-card-area">All form cards are displayed as grid, starting from here. You may not need to create more than one registrations form, but it's totally up to you. RegistrationMagic gives a playground to experiment and play to find the best combination for your site. First two card slots are reserved for <b>Add New Form</b> and <b>Login Form</b></li>
        <li data-class="rm-card-tour">This is a form card. We automatically created it for you to give you a head start.</li>
        <li data-class="rm-title-tour">This shows title of the form. When you create a new form, you can define its title. You can always change title of this form later, by going into its <b>General Settings</b></li>
        <li data-class="rm-checkbox-tour" data-options="tipAdjustmentX:-28;tipAdjustmentY:-5">The checkbox on left side of the title allows you to select multiple forms and perform batch operations. For example, deleting multiple forms. Of course there's nothing stopping you from deleting or duplicating a single form.</li>
        <li data-class="unread-box" data-options="tipAdjustmentX:-22;tipAdjustmentY:-5">On top right side of each card is a red number badge. This is the count of total times this form has been filled and submitted on your site by visitors.</li>
        <li data-class="rm-last-submission">This area displays 3 latest submissions for this form. On new forms it will be empty in the start. Each submission will also show user's Gravatar and time stamp.</li>
        <li data-class="rm-shortcode-tour" data-options="tipAdjustmentX:50">Now this is important. RegistrationMagic works through shortcodes. That means, to display a form on the site, you must paste its shortcode inside a page, post or a widget (where you want this form to appear). Form shortcodes are always in this format - <b>[RM_Form id='x']</b></li>
        <li data-class="material-icons" data-options="tipAdjustmentX:-24;tipAdjustmentY:-5">This little star allows you to mark a form as your default registration form.</li>
        <li data-class="rm-form-settings">Each form has its own dashboard or operations area, that is accessible by clicking the <b>Settings</b> button on the respective form card.</li>
        <li data-class="rm-form-fields" data-options="tipAdjustmentX:-12">Any form once created is empty. Form fields need to be added manually. This is where <b>Custom Fields Manager</b> comes in. Clicking it will take you to a separate section, where you can add all sorts of fields and pages to your form.</li>
        <li>This ends our tour of Forms Manager. Feel free to explore other sections of RegistrationMagic. We would recommend visiting the form Dashboard first. If anything does not works as expected, please write to us <a href="https://registrationmagic.com/help-support"><u>here</u></a> and we will help you sort it out asap.</li>
        <li><h2>But one more thing!</h2></li>
        <li data-id="rm-new-form" data-button="Done">You can quickly create a new form by typing its title below and clicking on <b>CREATE NEW FORM</b> button. That's about it folks! You can restart the tour anytime by clicking <b>Tour</b> on operations bar.</li>

   </ol>
  <!-- Joyride Magic ends -->

    <!--  Operations bar Starts  -->
    <form name="rm_form_manager" id="rm_form_manager_operartionbar" class="rm_static_forms" method="post" action="">
        <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
        <div class="operationsbar" id="rmbar">
            <div class="rmtitle" id="rm-tour-title"><?php echo RM_UI_Strings::get('TITLE_FORM_MANAGER');?></div>
            <div class="icons" id="rm-ob-icons">
                <a href="?page=rm_options_general"><img alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/general-settings.png'; ?>"></a>
            </div>
            <div class="nav" id="rm-ob-nav">
                <ul>
                    <li id="rm-ob-new"><a href="admin.php?page=rm_form_sett_general"><?php echo RM_UI_Strings::get('LABEL_ADD_NEW');?></a></li>
                    <li id="rm-ob-duplicate" onclick="jQuery.rm_do_action('rm_form_manager_operartionbar','rm_form_duplicate')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get('LABEL_DUPLICATE'); ?></a></li>
                    <li id="rm-ob-delete" onclick="jQuery.rm_do_action_with_alert('<?php echo RM_UI_Strings::get('ALERT_DELETE_FORM'); ?>','rm_form_manager_operartionbar','rm_form_remove')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get('LABEL_REMOVE'); ?></a></li>
                    <li id="rm-ob-export" onclick="jQuery.rm_do_action('rm_form_manager_operartionbar','rm_form_export')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get('LABEL_EXPORT_ALL'); ?></a></li>
                    <li id="rm-ob-import"><a href="admin.php?page=rm_form_import"><?php echo RM_UI_Strings::get('LABEL_IMPORT'); ?></a></li>
                    <li><a href="javascript:void(0)" onclick="rm_start_joyride()"><?php echo RM_UI_Strings::get('LABEL_TOUR'); ?></a></li>
                    
                    <li class="rm-form-toggle" id="rm-ob-sort">Sort by<select onchange="rm_sort_forms(this,'<?php echo $data->curr_page;?>')">
                            <option value=null><?php echo RM_UI_Strings::get('LABEL_SELECT'); ?></option>
                            <option value="form_name"><?php echo RM_UI_Strings::get('LABEL_NAME'); ?></option>
                            <option value="form_id"><?php echo RM_UI_Strings::get('FIELD_TYPE_DATE'); ?></option>
                            <option value="form_submissions"><?php echo RM_UI_Strings::get('LABEL_SUBMISSIONS'); ?></option>
                        </select></li>
                </ul>
            </div>
        </div>
        <input type="hidden" name="rm_selected" value="">
        <input type="hidden" name="req_source" value="form_manager">
    </form>

    <!--  *****Operations bar Ends****  -->

    <!--  ****Content area Starts****  -->

    <div class="rmagic-cards" id="rm-card-area">

        <div class="rmcard" id="rm-new-form">
            <?php
            $form = new RM_PFBC_Form("rm_form_quick_add");
            $form->configure(array(
                "prevent" => array("bootstrap", "jQuery"),
                "action" => ""
            ));
            $form->addElement(new Element_HTML('<div class="rm-new-form">'));
            $form->addElement(new Element_Hidden("rm_slug",'rm_form_quick_add'));
            $form->addElement(new Element_Textbox('', "form_name", array("id" => "rm_form_name", "required" => 1)));
            $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_CREATE_FORM'), "submit", array("id" => "rm_submit_btn", "onClick" => "jQuery.prevent_quick_add_form(event)", "class" => "rm_btn", "name" => "submit")));
            $form->addElement(new Element_HTML('</div>'));
            $form->render();
            ?></div>
        <div id="login_form" class="rmcard">

                    <div class="cardtitle">
                        <input class="rm_checkbox" type="checkbox" disabled="disabled"><?php echo 'Login Form' ?></div>                       
                    <div class="rm-form-shortcode"><b>[RM_Login]</b></div>

                </div>
        <?php
        if (is_array($data->data) || is_object($data->data))
            foreach ($data->data as $entry)
            {
                if($entry->expiry_details->state == 'not_expired' && $entry->expiry_details->criteria != 'date')
                   $subcount_display = $entry->expiry_details->remaining_subs;// $subcount_display = $entry->count.'/'.$entry->expiry_details->sub_limit;
                else
                    $subcount_display = null;//$entry->count;
                
                //Check if form is one of the sample forms.
                $ex_form_card_class = '';
                $sample_data = get_site_option('rm_option_inserted_sample_data', null);
                if(isset($sample_data->forms) && is_array($sample_data->forms)):
                    foreach($sample_data->forms as $sample_form):
                        if($entry->form_id == $sample_form->form_id):
                            $ex_form_card_class = ($sample_form->form_type == RM_REG_FORM)? 'rm-sample-reg-form-card' : 'rm-sample-contact-form-card';                            
                        endif;
                    endforeach;
                endif;                
                    
                ?>

                <div id="<?php echo $entry->form_id; ?>" class="rmcard  rm-card-tour <?php echo $ex_form_card_class; ?>">
<div class='unread-box'><a href="?page=rm_submission_manage&rm_form_id=<?php echo $entry->form_id; ?>&rm_interval=<?php echo $data->submission_type; ?>"><?php echo $entry->count; ?></a></div>
                    <div class="cardtitle rm-title-tour">
                        <input class="rm_checkbox rm-checkbox-tour" type="checkbox" name="rm_selected_forms[]" value="<?php echo $entry->form_id; ?>"><span class="rm_form_name" ><?php echo $entry->form_name; ?></span></div>
                    <div class="rm-last-submission">
                          <b><?php if($subcount_display)
                              printf(RM_UI_Strings::get('RM_SUB_LEFT_CAPTION'),$subcount_display);
                              ?></b></div>
                            
                    <?php
                    if ($entry->count > 0)
                    {
                        foreach ($entry->submissions as $submission)
                        {
                            ?>
                            <div class="rm-last-submission">

                                <?php
                                echo $submission->gravatar . ' ' . RM_Utilities::localize_time($submission->submitted_on);
                                ?>
                            </div>
                            <?php
                        }
                    } else
                        echo '<div class="rm-last-submission"></div>';
                    ?>
                    <?php
                    if($entry->expiry_details->state == 'expired')
                        echo "<div class='rm-form-expiry-info'>".RM_UI_Strings::get('LABEL_FORM_EXPIRED')."</div>";
                    else if($entry->expiry_details->state == 'not_expired' && $entry->expiry_details->criteria != 'subs')
                    {
                        if($entry->expiry_details->remaining_days < 26)
                           echo "<div class='rm-form-expiry-info'>".sprintf(RM_UI_Strings::get('LABEL_FORM_EXPIRES_IN'),$entry->expiry_details->remaining_days)."</div>";
                        else
                        {
                           $exp_date = date('d M Y', strtotime($entry->expiry_details->date_limit));
                           echo "<div class='rm-form-expiry-info'>".RM_UI_Strings::get('LABEL_FORM_EXPIRES_ON')." {$exp_date}</div>";
                        }
                    }
                     
                    ?><div class="rm-form-shortcode">
                        <?php if($data->def_form_id == $entry->form_id) { ?>
                    <i class="material-icons rm_def_form_star" onclick="make_me_a_star(this)" id="rm-star_<?php echo $entry->form_id; ?>">&#xe838</i>
                        <?php } else { ?>
                    <i class="material-icons rm_not_def_form_star" onclick="make_me_a_star(this)" id="rm-star_<?php echo $entry->form_id; ?>">&#xe838</i>
                        <?php } ?>
                    <b class="rm-shortcode-tour">[RM_Form id='<?php echo $entry->form_id; ?>']</b></div>
                    <div class="rm-form-embedcode"  onclick="rm_open_dial(<?php echo $entry->form_id; ?>)"><?php echo RM_UI_Strings::get('MSG_GET_EMBED'); ?></div>
                    <div class="rm-form-links">
                        <div class="rm-form-row"><a class="rm-form-settings" href="admin.php?page=rm_form_sett_manage&rm_form_id=<?php echo $entry->form_id; ?>"><?php echo RM_UI_Strings::get('SETTINGS'); ?></a></div>
                        <div class="rm-form-row"><a class="rm-form-fields" href="admin.php?page=rm_field_manage&rm_form_id=<?php echo $entry->form_id; ?>"><?php echo RM_UI_Strings::get('LABEL_FIELDS'); ?></a></div>
                    </div>
                    <div style="display:none" class="rm_form_card_settings_dialog" id="rm_settings_dailog_<?php echo $entry->form_id; ?>"><ul class="rm_form_settings_list"><a href="?page=rm_form_sett_general&rm_form_id=<?php echo $entry->form_id; ?>"><li><?php echo RM_UI_Strings::get('LABEL_F_GEN_SETT'); ?></li></a><a href="?page=rm_form_sett_view&rm_form_id=<?php echo $entry->form_id; ?>"><li><?php echo RM_UI_Strings::get('LABEL_F_VIEW_SETT'); ?></li></a><a href="?page=rm_form_sett_accounts&rm_form_id=<?php echo $entry->form_id; ?>"><li><?php echo RM_UI_Strings::get('LABEL_F_ACC_SETT'); ?></li></a><a href="?page=rm_form_sett_post_sub&rm_form_id=<?php echo $entry->form_id; ?>"><li><?php echo RM_UI_Strings::get('LABEL_F_PST_SUB_SETT'); ?></li></a><a href="?page=rm_form_sett_autoresponder&rm_form_id=<?php echo $entry->form_id; ?>"><li><?php echo RM_UI_Strings::get('LABEL_F_AUTO_RESP_SETT'); ?></li></a><a href="?page=rm_form_sett_limits&rm_form_id=<?php echo $entry->form_id; ?>"><li><?php echo RM_UI_Strings::get('LABEL_F_LIM_SETT'); ?></li></a><a href="?page=rm_form_sett_mailchimp&rm_form_id=<?php echo $entry->form_id; ?>"><li><?php echo RM_UI_Strings::get('LABEL_F_MC_SETT'); ?></li></a><a href="?page=rm_form_sett_access_control&rm_form_id=<?php echo $entry->form_id; ?>"><li><?php echo RM_UI_Strings::get('LABEL_F_ACTRL_SETT'); ?></li></a><a href="?page=rm_form_sett_aweber&rm_form_id=<?php echo $entry->form_id; ?>"><li><?php echo RM_UI_Strings::get('LABEL_AWEBER_OPTION'); ?></li></a><a href="?page=rm_form_sett_ccontact&rm_form_id=<?php echo $entry->form_id; ?>"><li><?php echo RM_UI_Strings::get('LABEL_CONSTANT_CONTACT_OPTION'); ?></li></a><a href="?page=rm_field_manage&rm_form_id=<?php echo $entry->form_id; ?>"><li><?php echo RM_UI_Strings::get('LABEL_F_FIELDS'); ?></li></a></ul></div>
                </div>
                <?php
            } else
            echo "<h4>" . RM_UI_Strings::get('LABEL_NO_FORMS') . "</h4>";
        ?>
    </div>
    <?php if ($data->total_pages > 1): ?>
        <ul class="rmpagination">
            <?php if ($data->curr_page > 1): ?>
                <li><a href="?page=<?php echo $data->rm_slug ?>&rm_reqpage=<?php echo $data->curr_page - 1;
        if ($data->sort_by) echo'&rm_sortby=' . $data->sort_by;if (!$data->descending) echo'&rm_descending=' . $data->descending; ?>">«</a></li>
                <?php
            endif;
            for ($i = 1; $i <= $data->total_pages; $i++):
                if ($i != $data->curr_page):
                    ?>
                    <li><a href="?page=<?php echo $data->rm_slug ?>&rm_reqpage=<?php echo $i;
            if ($data->sort_by) echo'&rm_sortby=' . $data->sort_by;if (!$data->descending) echo'&rm_descending=' . $data->descending; ?>"><?php echo $i; ?></a></li>
                <?php else:
                    ?>
                    <li><a class="active" href="?page=<?php echo $data->rm_slug ?>&rm_reqpage=<?php echo $i;
            if ($data->sort_by) echo'&rm_sortby=' . $data->sort_by;if (!$data->descending) echo'&rm_descending=' . $data->descending; ?>"><?php echo $i; ?></a></li> <?php
                endif;
            endfor;
            ?>
            <?php if ($data->curr_page < $data->total_pages): ?>
                <li><a href="?page=<?php echo $data->rm_slug ?>&rm_reqpage=<?php echo $data->curr_page + 1;
        if ($data->sort_by) echo'&rm_sortby=' . $data->sort_by;if (!$data->descending) echo'&rm_descending=' . $data->descending; ?>">»</a></li>
            <?php endif;
        ?>
        </ul>
   
<?php endif;
   $current_user = wp_get_current_user();
?>
        <div id="rm_embed_code_dialog" style="display:none"><textarea readonly="readonly" id="rm_embed_code" onclick="jQuery(this).focus().select()"></textarea><img class="rm-close" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/close-rm.png'; ?>" onclick="jQuery('#rm_embed_code_dialog').fadeOut()"></div>
        
</div>
 <!--   <div class="rm-side-banner">

            <div class="rm-sidebaner-section-title dbfl"><img src="<?php// echo RM_IMG_URL; ?>icon.png"><span>Want More?</span></div>

            <div class="rm-sidebanner-image">
                <img src="<?php //echo RM_IMG_URL; ?>Layer 1.png" />
            </div>

            <div class="sidebanner-content-wrapper">
                <div class="sidebanner-text-content">
                    <p>While Standard Edition is pretty powerful system in its own right, there's a lot more waiting for you! RegistrationMagic's Silver, Gold and Platinum upgrades are crammed to the top with features, great new options and comes with top class support. It takes less than <b>5 minutes</b> to upgrade and all your stuff is <b>transferred automatically</b></p>			
                </div>
                <div class="rm-sidebanner-buttons">
                    <div class="rm-sidebanner-button silver">
                        <a href="https://registrationmagic.com/?download_id=317&edd_action=add_to_cart">GET SILVER</a>				
                    </div>

                    <div class="rm-sidebanner-button gold">
                        <a href="https://registrationmagic.com/?download_id=23029&edd_action=add_to_cart">GET GOLD</a>
                    </div>
                    <div class="rm-sidebanner-button platinum">
                        <a href="https://registrationmagic.com/?download_id=22865&edd_action=add_to_cart">GET PLATINUM</a>
                    </div>
                </div>

                <div class="rm-sidebanner-compare">

                    <div class="rm-sidebanner-compare-l">
                        <a href="https://registrationmagic.com/comparison/">Compare</a>
                    </div>

                    <div class="rm-sidebanner-compare-r">
                        <a href="https://registrationmagic.com/help-support/">Questions?</a>				
                    </div>			

                </div>

                <div class="rm-sidebanner-icons">
                    <img src="<?php //echo RM_IMG_URL; ?>icon-pack.png">
                </div>

            </div> <!-- sidebanner-content-wrapper 
        </div>-->


  <pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
   
    jQuery(document).ready(function(){
       //Configure joyride
       //If autostart is false, call again "jQuery("#rm-form-man-joytips").joyride()" to start the tour.
       <?php if($data->autostart_tour): ?>
       jQuery("#rm-form-man-joytips").joyride({tipLocation: 'top',
                                               autoStart: true,
                                               postRideCallback: rm_joyride_tour_taken});
        <?php else: ?>
            jQuery("#rm-form-man-joytips").joyride({tipLocation: 'top',
                                               autoStart: false,
                                               postRideCallback: rm_joyride_tour_taken});
        <?php endif; ?>
    });
   
   function rm_start_joyride(){
       jQuery("#rm-form-man-joytips").joyride();
    }
    
    function rm_joyride_tour_taken(){
        var data = {
			'action': 'joyride_tour_update',
			'tour_id': 'form_manager_tour',
                        'state': 'taken'
		};

        jQuery.post(ajaxurl, data, function(response) {});
    }
    
    function rm_open_dial(form_id){
        jQuery('textarea#rm_embed_code').html('<?php echo RM_UI_Strings::get('MSG_BUY_PRO_GOLD_EMBED'); ?>');
        jQuery('#rm_embed_code_dialog').fadeIn(100);
    }
    jQuery(document).mouseup(function (e) {
        var container = jQuery("#rm_embed_code_dialog,.rm_form_card_settings_dialog");
        if (!container.is(e.target) // if the target of the click isn't the container... 
                && container.has(e.target).length === 0) // ... nor a descendant of the container 
        {
            container.hide();
        }
    });
    
    function make_me_a_star(e){
        var form_id = jQuery(e).attr('id').slice(8);
        if(typeof form_id != 'undefined' && !jQuery(e).hasClass('rm_def_form_star')){
        var data = {
			'action': 'set_default_form',
			'rm_def_form_id': form_id
		};

        jQuery.post(ajaxurl, data, function(response) {
                        var old_form = jQuery('.rm_def_form_star');
			old_form.removeClass('rm_def_form_star');
                        old_form.addClass('rm_not_def_form_star');
                        
                        var curr_form = jQuery('#rm-star_'+form_id);
                        curr_form.removeClass('rm_not_def_form_star');
                        curr_form.addClass('rm_def_form_star');
		});
            }
    }
    
    function rm_show_form_sett_dialog(form_id){
        jQuery("#rm_settings_dailog_"+form_id).show();
    }
      
jQuery("#rm_rateit_banner").bind('rated', function (event, value) { 
        if(value<=3)
        {
            
             jQuery("#rm-rate-popup-wrap").fadeOut();  
             jQuery("#wordpress_review").fadeOut(100);  
             jQuery("#feedback_message").fadeIn(100);  
             jQuery('#feedback_message').removeClass('rm-blur');
             jQuery('#feedback_message').addClass('rm-hop');
             handle_review_banner_click('rating',value);
        }
        else
        {
             jQuery("#rm-rate-popup-wrap").fadeOut();  
             jQuery("#feedback_message").fadeOut();  
             jQuery("#wordpress_review").fadeIn(100);
             jQuery('#wordpress_review').removeClass('rm-blur');
             jQuery('#wordpress_review').addClass('rm-hop');
             handle_review_banner_click('rating',value);
        }
    
    
    });
  </script></pre>





