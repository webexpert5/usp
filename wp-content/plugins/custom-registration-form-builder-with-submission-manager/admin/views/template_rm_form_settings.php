<?php
/*
 * This page shows the form settings page
 * It consissts of different icons with the link to specific form settings.
 */
?>
<link rel="stylesheet" type="text/css" href="<?php echo RM_BASE_URL . 'admin/css/'; ?>style_rm_form_dashboard.css">
<pre class="rm-pre-wrapper-for-script-tags"><script src="<?php echo RM_BASE_URL . 'admin/js/'; ?>script_rm_form_dashboard.js"></script></pre>
<pre class='rm-pre-wrapper-for-script-tags'><script>
    //Takes value of various status variables (form_id, timeline_range) and reloads page with those parameteres updated.
    function rm_refresh_stats(){
    var form_id = jQuery('#rm_form_dropdown').val();
    var trange = jQuery('#rm_stat_timerange').val();
    if(typeof trange == 'undefined')
        trange = <?php echo $data->timerange; ?>;
    window.location = '?page=rm_form_sett_manage&rm_form_id=' + '<?php echo $data->form_id; ?>' + '&rm_tr='+trange;
}
</script></pre>

<!-- Joyride Magic begins -->
    <ol id="rm-form-sett-dashboard-joytips" style="display:none">
        
        <li><h5>Welcome to the Form Dashboard!</h5>
            <br/>
        <p>This is where the magic begins &#x1f600;. You will notice, this area is a little barren at the moment. But fret not! As your wonderfully named form, <b><?php echo $data->form->get_form_name(); ?> </b>starts performing and visitors fill it in, it will soon look busy enough.</p>
        </li>
        <li>Remember - each form will have its own dashboard; accessible by clicking SETTINGS on its form card. That's how you reached here, right? But once you're here, you do not need to go back to open another form's dashboard. You can just...</li>
        <li data-id="rm-form-pretoggle" data-options="tipLocation:bottom;nubPosition:top-right;tipAdjustmentX:-330;tipAdjustmentY:12">Click here, and a drop down will appear with list of your forms. And you just have to choose another form from the list. You can try that later. Let's move on...</li>
        <li data-class="rm-grid-title">This of course, is your currently selected form's name. And...</li>
        <li data-class="rm-grid-button" data-options="tipLocation:bottom;tipAdjustmentY:12">You can start adding a new form from right here.</li>
        <li data-class="rm-permalink-textbox">This is form's unique embed code. As the name suggests, you can use this code to embed the form where WordPress shortcode would not work. Like an external webpage. Otherwise, shortcode is always the best way to display the form.</li>
        <li data-class="rm-grid-section" data-options="tipAdjustmentY:-15">This line graph shows how your form is performing. It displays your form views/ visits vs the number of times the form was filled and submitted.</li>
        <li data-id="rm_stat_timerange" data-options="tipAdjustmentY:-15">By default it displays stats from last 30 days. But you can change that easily from this dropdown. You can find more stats in the Analytics section.</li>
        <li data-id="rm-section-icons" data-options="tipAdjustmentY:-10"> This area below contains all the sections within this form.</li>
        <li data-class="rm-grid-icon" data-options="tipAdjustmentX:30"> Like this very important form <b>Inbox</b>. This is where all user submission records for <b><?php echo $data->form->get_form_name(); ?></b> go.</li>
        <li data-class="rm-grid-icon-badge" data-options="tipAdjustmentX:-20;tipAdjustmentY:-20">See this badge? This tell you the number of time users have filled and submitted this form. Right now there are <b><?php echo $data->sub_count; ?></b> submissions associated with your form <b><?php echo $data->form->get_form_name(); ?></b></li>
        <li data-id="rm-analytics-icon" data-options="tipAdjustmentX:20"><b>Analytics</b>, as the name suggests, collects and displays all the stats related to this form as table, graphs and charts.</li>
        <li data-id="rm-field-analytics-icon" data-options="tipAdjustmentX:20"><b>Field Analytics</b>, collects and displays stats related to individual fields inside the form. It can only display meaningful stats from pre-set option field types like <i>dropdown, checkbox or radiobox</i></li>
        <li data-id="rm-attachment-icon" data-options="tipAdjustmentX:20">If your form has a file upload field(s), all the attachments go here. Remember, attachments are also visible inside individual submission records in <b>Inbox</b>. But think of this as a bucket where files are stored individually. If you want to download all of them as a zip, this is the place.</li>
        <li data-id="rm-design-icon">This is where you tweak individual elements of your form to make the overall look match with your website's theme or experiment with different combinations. </li>
        <li data-id="rm-customfields-icon">While the <b>Design</b> takes care of the look of your form, <b>Pages and Field</b> decide its content. A form is made up of fillable fields. For example, Name, Email, Address etc. You can create, edit and manage fields from this section.</li>
        <li data-id="rm-emailusers-icon">This section allows you to email all the users who have submitted this form. You'll find this useful for broadcasting announcements and sending bulk updates.</li>
        <li data-id="rm-general-icon">All the form specific tweaks and settings go here. Remember, these are separate from Global Settings which apply to all the forms.</li>
        <li data-id="rm-accounts-icon">Now this is important! This section decides properties of  WordPress User accounts created when a person submits this form.</li>
        <li data-id="rm-postsubmit-icon">Things do not end with submitting the form. You may want to show a success message or a token number or perhaps redirect the user to another page with relevant information. All that and more is configured through this section.</li>
        <li data-id="rm-autoresponder-icon">Automatically send users an email notification with customized content after they have submitted the form.</li>
        <li data-id="rm-limits-icon">Limits allow you to set, well, limits to your form. Once a limit is reached, the form goes to <i>expired</i> state. This will also be visible on the form card. Limits are useful if you have limited submission slots or if registration is only open before a specified date.</li>
        <li data-id="rm-access-icon">Access Control allows you to lock the form and visitor will only be allowed to see it if they meets certain parameters. For example, if you want to allow registrations for people only above 18 years, you can set Date of Birth as access control.</li>
        <li data-id="rm-overrides-icon"><b>Overrides</b> are basically Global Settings which can be over ridden specific to a form. This is useful when you have lots of forms and you want one or some of the forms to have separate configuration compared to others. Remember, not all settings can be over ridden. Only those which can, will appear here.</li>
        <li data-id="rm-thirdparty-mailchimp">Here you can configure Mailchimp integration with your form. Note that you need to configure API key beforehand in Global Settings &#xbb; External Integration.</li>
        <li><h5>We hope you liked what you have seen so far! Let's move on to the two Sidebars.</h5></li>
        <li data-class="rm-grid-sidebar-1" data-options="tipAdjustmentY:-18">First one is sort of live update feed for your form.</li>
        <li data-class="rm-grid-sidebar-2" data-options="tipLocation:left;tipAdjustmentX:-10">And the next one is very similar to what you find inside WordPress pages and posts. It provides surface view of the form.</li>
        <li data-class="rm-grid-sidebar-card" data-options="tipLocation:top;tipAdjustmentY:-20">The first block on this sidebar shows submissions feed as users submit the forms. Initially, when there are no submissions, it will simply show "0". It'll look a lot different when the submissions start.</li>
        <li data-class="rm-grid-sidebar-row-label" data-options="tipAdjustmentX:-10;tipAdjustmentY:-12">You can view all submissions by clicking on this button.</li>
<!--        <li data-id="rm-attachments-card" data-options="tipAdjustmentY:-24">The second block shows a list of files received through your form. If you do not have a file upload field inside your form, this area will stay empty and you can ignore it.</li>
        <li data-id="rm-view-attachments" data-options="tipAdjustmentX:-10;tipAdjustmentY:-10">You can view all attachments anytime by clicking on this button.</li>-->
        <li data-id="rm-sidebar-sc-icon" data-options="tipLocation:top;nubPosition:bottom-right;tipAdjustmentX:-300;tipAdjustmentY:-15">This is the shortcode for this form. As you already know, you need to paste the shortcode where you wish to show the form. You can simply copy the shortcode...</li>
        <li data-id="rm-copy-sc" data-options="tipLocation:top;nubPosition:bottom-right;tipAdjustmentX:-344;tipAdjustmentY:-15">By clicking <i>Copy</i></li>
<!--        <li data-id="rm-sidebar-visibility" data-options="tipLocation:bottom;nubPosition:top-right;tipAdjustmentX:-300;tipAdjustmentY:15">Visiblity of the form defines if the form is visible to all users who have access to form page or if the access control is on. We have already discussed how to open Access Control section. Clicking <i>Edit</i> will take you directly to it.</li>-->
        <li data-id="rm-sidebar-delete" data-options="tipLocation:left;tipAdjustmentX:-20;tipAdjustmentY:-30">If you wish to delete this form (with all its contents, stats and submissions), you can do that by clicking Delete. To delete multiple forms, use batch selection checkboxes in Forms Manager.</li>
<!--        <li data-id="rm-sidebar-pages" data-options="tipLocation:left;tipAdjustmentX:-45;tipAdjustmentY:-30">Your form can be spread over multiple pages. This number shows the count of pages in your form.</li>-->
        <li data-id="rm-sidebar-fields" data-options="tipLocation:left;tipAdjustmentX:-45;tipAdjustmentY:-30">This shows the total number of fields on your form.</li>
        <li data-id="rm-sidebar-add-field"  data-options="tipLocation:top;nubPosition:bottom-right;tipAdjustmentX:-344;tipAdjustmentY:-15">While fields are managed by <b>Pages and Fields</b> section, you can also quickly add a new field by clicking <i>Add</i> here.</li>
        <li data-id="rm-sidebar-add-submit" data-options="tipLocation:left;tipAdjustmentX:-45;tipAdjustmentY:-30">This is the label of the submit button of your form. It can be labelled Submit, Register, Send or anything you please. To change it, you can click on <i>Change</i> button on the right side. You can fully customise the look of the Submit button by visiting <b>Design</b> section.</li>
        <li data-id="rm-sidebar-duplicate" data-options="tipLocation:left;tipAdjustmentX:-15;tipAdjustmentY:-30">You can create a clone of this form by clicking <i>Duplicate</i>. It will have same content, design and field/ pages.</li>
        <li data-id="rm-sidebar-visitors" data-options="tipLocation:left;tipAdjustmentX:-45;tipAdjustmentY:-30">This number shows the total number of visitors (or views) of your form during last 30 days.</li>
        <li data-id="rm-sidebar-submissions" data-options="tipLocation:left;tipAdjustmentX:-45;tipAdjustmentY:-30">Submissions is the number of times the form has been filled and submitted. Do not confuse this with Users. A single user can submit the form multiple times. You can download all submission records as CSV file by clicking <i>Download Records</i> and open it as spreadsheet inside a desktop program such as MS Excel, Apple Numbers etc.</li>
        <li data-id="rm-sidebar-attachments" data-options="tipLocation:left;tipAdjustmentX:-45;tipAdjustmentY:-30">As the name suggests, this is total count of files received through this form. The <i>Download All</i> button on right will download all files as single zip.</li>
        <li data-id="rm-sidebar-conversion" data-options="tipLocation:left;tipAdjustmentX:-45;tipAdjustmentY:-30">Basically this is number of submissions received versus number of total form views in percentage. If you are using the form on a landing page, this can provide useful insights into performance of the form.</li>
        <li data-id="rm-sidebar-avgtime" data-options="tipLocation:left;tipAdjustmentX:-45;tipAdjustmentY:-30">This is the average time visitors take to fill out and submit your form.</li>
        <li data-id="rm-sidebar-reset" data-options="tipLocation:left;tipAdjustmentX:-15;tipAdjustmentY:-30">Clicking <i>Reset</i> will set all stat counters to their initial state. You may want to do this after you have successfully tested your form and are preparing to make it live. It will ensure integrity of the Analytics data.</li>
        <li data-id="rm-sidebar-quick-toggles" data-options="tipLocation:top;nubPosition:bottom-right;tipAdjustmentX:-15;tipAdjustmentY:-230">This block has some useful toggles which allow you to turn options on and off without going into specific sections. Remember, some toggles will not work unless you have set them up first. Like the Autoresponder - you cannot turn it on when you have not yet setup the Autoresponder content inside the <b>Autoresponder</b> section.</li>
        <li data-button="Done">This ends the quick tour of the <b>Form Dashboard</b> area. You can restart the tour anytime in future by clicking on <i>Tour</i> button. As always, if anything does not works as expected, feel free to write to us <a href="mailto:support@registrationmagic.com">here</a>. Good luck!</li>
        
   </ol>
  <!-- Joyride Magic ends -->

<div class="rm-form-configuration-wrapper">
    <div class="rm-grid-top dbfl">
        <div class="rm-grid-title difl"><?php echo $data->form->get_form_name(); ?></div>
        <span class="rm-grid-button difl"><a class="rm_fd_link" href="?page=rm_form_sett_general"><?php echo RM_UI_Strings::get('FD_LABEL_ADD_NEW'); ?></a></span>
        <span class="rm-grid-button difl" onclick="rm_start_joyride()"><a class="rm_fd_link" href="javascript:void(0)"><?php echo RM_UI_Strings::get('LABEL_TOUR'); ?></a></span>
        <span class="rm-grid-button difr" id="rm-form-pretoggle" onclick="jQuery(this).hide();jQuery('#rm_form_toggle').show()"><?php echo RM_UI_Strings::get('FD_LABEL_SWITCH_FORM'); ?></span>
        <!--    Forms toggle-->
        <span class="rm-grid-button difr" id="rm_form_toggle" style="display: none">
            <select onchange="rm_fd_switch_form(jQuery(this).val(), <?php echo $data->timerange; ?>)">
                <?php
                echo "<option value=''>" . RM_UI_Strings::get('FD_FORM_TOGGLE_PH') . "</option>";
                foreach ($data->all_forms as $form_id => $form_name):
                    echo "<option value='$form_id'>$form_name</option>";
                endforeach;
                ?>
            </select>
        </span>
        <div class="dbfl"><?php echo RM_UI_Strings::get('NO_EMBED_CODE'); ?> </div>
    </div>
    <div class="rm-grid difl"> 
        
                <!--  -->
            <div class="rm-grid-section dbfl" id="rm_tour_timewise_stats">
                <div class="rm-grid-section-title dbfl rm-box-title"><?php echo RM_UI_Strings::get('LABEL_SUBS_OVER_TIME'); ?></div>
                <div class="rm-timerange-toggle rm-timerange-dashboard">
                <?php echo RM_UI_Strings::get('LABEL_SELECT_TIMERANGE'); ?>
                    <select id="rm_stat_timerange" onchange="rm_refresh_stats()">
                    <?php $trs = array(7,30,60,90); 

                    foreach($trs as $tr)
                    {
                        echo "<option value=$tr";
                        if($data->timerange == $tr)
                            echo " selected";
                        printf(">".RM_UI_Strings::get("STAT_TIME_RANGES")."</option>",$tr);
                    }
                    ?>

                </select>
                </div>
                <div class="rm-box-graph" id="rm_subs_over_time_chart_div">
                </div>
            </div>
        
        <!-- dummy spacer -->
        <div class="rm-grid-spacer"> </div>
        <!--    -->
        
        <div class="rm-grid-section dbfl" id="rm-section-icons">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_BASIC_DASHBOARD'); ?> <span class="rm-query-ask">?</span>
                <div class="difr rm-grid-section-title-button"><span class="rm-grid-button"><a target="_blank" href="https://registrationmagic.com/comparison/" class="rm_fd_link">upgrade</a></span></div>
                <div style="display:none" class="rm-query-answer">Standard Edition dashboard offers you all the features to get starting with building registration system on your site. For more powerful options consider upgrading to <a href="http://registrationmagic.com/?download_id=22865&edd_action=add_to_cart">Platinum</a>, <a href="https://registrationmagic.com/?download_id=23029&edd_action=add_to_cart">Gold</a> or <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart">Silver Bundle</a></div>
            </div>
            <div class="rm-grid-icon difl">
                <a href="?page=rm_submission_manage&rm_form_id=<?php echo $data->form_id; ?>" class="rm_fd_link">   
                    <div class="rm-grid-icon-area dbfl">
                        <div class="rm-grid-icon-badge"><?php echo $data->sub_count; ?></div>
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-inbox.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('LABEL_INBOX'); ?></div>
                </a>
            </div> 

            <div class="rm-grid-icon difl" id="rm-analytics-icon">
                <a href="?page=rm_analytics_show_form&rm_form_id=<?php echo $data->form_id; ?>" class="rm_fd_link">   
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-analytics.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('TITLE_FORM_STAT_PAGE'); ?></div>
                </a>
            </div> 

            <div class="rm-grid-icon difl"> 
                <a href="?page=rm_form_sett_view&rm_form_id=<?php echo $data->form_id; ?>" class="rm_fd_link">   
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-view.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('FD_LABEL_DESIGN'); ?></div>
                </a>
            </div> 
            
            <div class="rm-grid-icon difl" id="rm-customfields-icon">
                <a href="?page=rm_field_manage&rm_form_id=<?php echo $data->form_id; ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <div class="rm-grid-icon-badge"><?php echo $data->field_count; ?></div>
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-custom-fields.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('FD_LABEL_FORM_FIELDS'); ?></div>
                </a>
            </div>
            
            <div class="rm-grid-icon difl" id="rm-emailusers-icon">
                <a href="?page=rm_invitations_manage&rm_form_id=<?php echo $data->form_id; ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>email-users.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('TITLE_INVITES'); ?></div>
                </a>
            </div> 
            
            <div class="rm-grid-icon difl" id="rm-general-icon">
                <a href="?page=rm_form_sett_general&rm_form_id=<?php echo $data->form_id; ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-settings.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('LABEL_F_GEN_SETT'); ?></div>
                </a>
            </div>
            
            <div class="rm-grid-icon difl" id="rm-accounts-icon">
                <a href="?page=rm_form_sett_accounts&rm_form_id=<?php echo $data->form_id; ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-accounts.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('LABEL_F_ACC_SETT'); ?></div>
                </a>
            </div> 
            
            <div class="rm-grid-icon difl" id="rm-postsubmit-icon">
                <a href="?page=rm_form_sett_post_sub&rm_form_id=<?php echo $data->form_id; ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>post-submission.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('LABEL_F_PST_SUB_SETT'); ?></div>
                </a>
            </div> 
            
             <div class="rm-grid-icon difl" id="rm-autoresponder-icon">
                <a href="?page=rm_form_sett_autoresponder&rm_form_id=<?php echo $data->form_id; ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>auto-responder.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('LABEL_F_EMAIL_TEMPLATES_SETT'); ?></div>
                </a>
            </div> 
            
            <div class="rm-grid-icon difl" id="rm-limits-icon">
                <a href="?page=rm_form_sett_limits&rm_form_id=<?php echo $data->form_id; ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-limits.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('LABEL_F_LIM_SETT'); ?></div>
                </a>
            </div>
            
            <div class="rm-grid-icon difl" id="rm-thirdparty-mailchimp">  
                <a href="?page=rm_form_sett_mailchimp&rm_form_id=<?php echo $data->form_id; ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>mailchimp.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('LABEL_F_MC_SETT'); ?></div>
                </a>
            </div> 
        </div>
        
    </div>
    <div class="rm-grid-sidebar-1 difl">
        <div class="rm-grid-section-cards dbfl">        
            <?php
            if($data->sub_count == 0):
                ?>
            <div class="rm-grid-sidebar-card dbfl">
                <div class='rmnotice-container'><div class="rmnotice-container"><div class="rm-counter-box">0</div><div class="rm-counter-label"><?php echo RM_UI_Strings::get('LABEL_REGISTRATIONS'); ?></div></div></div>  
</div>
                <?php
            endif;
            foreach ($data->latest_subs as $submission):
                ?>
                <div class="rm-grid-sidebar-card dbfl">
                    <a href="?page=rm_submission_view&rm_submission_id=<?php echo $submission->id; ?>" class="fd_sub_link">
                    <?php echo $submission->is_read? '' : "<div class='rm-grid-user-badge'>". RM_UI_Strings::get('FD_BADGE_NEW')."!</div>"; ?>
                    <div class="rm-grid-card-profile-image dbfl">
                        <img class="fd_img" src="<?php echo $submission->user_avatar; ?>">
                    </div>
                    <div class="rm-grid-card-content difl">
                        <div class="dbfl"><?php echo $submission->user_name; ?></div>
                        <div class="rm-grid-card-content-subtext dbfl"><?php echo $submission->submitted_on ?></div></div>
                    </a>
                </div>
                <?php
            endforeach;
            ?>
            <div class="rm-grid-quick-tasks dbfl">
                <div class="rm-grid-sidebar-row dbfl">
                    <div class="rm-grid-sidebar-row-label difl">
                        <a class="<?php echo $data->sub_count ? '' : 'rm_deactivated'?>" href="?page=rm_submission_manage&rm_form_id=<?php echo $data->form_id; ?>"><?php echo RM_UI_Strings::get('FD_LABEL_VIEW_ALL'); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="rm-grid-section-cards dbfl"> 

            <div class="rm-grid-sidebar-card dbfl" id="rm-attachments-card">
                <div class='rmnotice-container'><div class="rmnotice-container"><div class="rm-counter-box">0</div><div class="rm-counter-label"><?php echo RM_UI_Strings::get('TITLE_ATTACHMENT_PAGE'); ?></div></div></div>  
            </div>

            <div class="rm-grid-quick-tasks dbfl">
                <div class="rm-grid-sidebar-row dbfl">
                    <div class="rm-grid-sidebar-row-label difl">
                        <a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get('FD_LABEL_VIEW_ALL'); ?></a>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="rm-grid-sidebar-2 difl">
        <div class="rm-grid-section dbfl">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_LABEL_STATUS'); ?>
                <span class="rm-grid-section-toggle rm-collapsible"></span>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl" id="rm-sidebar-sc-icon">
                    <img src="<?php echo RM_IMG_URL; ?>shortcode.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl"><?php echo RM_UI_Strings::get('FD_LABEL_FORM_SHORTCODE'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><span id="rmformshortcode">[RM_Form id='<?php echo $data->form->get_form_id(); ?>']</span><a href="javascript:void(0)" onclick="rm_copy_to_clipboard(document.getElementById('rmformshortcode'))" id="rm-copy-sc"><?php echo RM_UI_Strings::get('FD_LABEL_COPY'); ?></a>
                    <div style="display:none" id="rm_msg_copied_to_clipboard">Copied to clipboard</div><div style="display:none" id="rm_msg_not_copied_to_clipboard">Could not be copied. Please try manually.</div></div>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>visiblity.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-visibility"><?php echo RM_UI_Strings::get('FD_LABEL_FORM_VISIBILITY'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo $data->form_access; ?><a href="?page=rm_form_sett_access_control&rm_form_id=<?php echo $data->form_id; ?>"><?php echo RM_UI_Strings::get('LABEL_EDIT'); ?></a></div>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>event.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl"><?php echo RM_UI_Strings::get('FD_LABEL_FORM_CREATED_ON'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo RM_Utilities::localize_time($data->form->get_created_on()); ?></div>
            </div>

            <div class="rm-grid-quick-tasks dbfl">
                <div class="rm-grid-sidebar-row dbfl">
                    <div class="rm-grid-sidebar-row-label difl">
                        <a href="javascript:void(0)" id="rm-sidebar-delete" onclick="jQuery.rm_do_action_with_alert('<?php echo RM_UI_Strings::get('ALERT_DELETE_FORM'); ?>', 'rm_fd_action_form', 'rm_form_remove')"><?php echo RM_UI_Strings::get('LABEL_DELETE'); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="rm-grid-section dbfl">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_LABEL_CONTENT'); ?>
                <span class="rm-grid-section-toggle rm-collapsible"></span>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>pages.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-pages"><?php echo RM_UI_Strings::get('FD_FORM_PAGES'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo 1; ?></div>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>field.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-fields"><?php echo RM_UI_Strings::get('FD_LABEL_F_FIELDS'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo $data->field_count; ?><a id="rm-sidebar-add-field" href="?page=rm_field_manage&rm_form_id=<?php echo $data->form->get_form_id(); ?>"><?php echo RM_UI_Strings::get('LABEL_ADD'); ?></a></div>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>submit.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-add-submit"><?php echo RM_UI_Strings::get('FD_FORM_SUBMIT_BTN_LABEL'); ?>:</div>
               <div class="rm-grid-sidebar-row-value difl"><div id="rm-submit-label"><?php echo $data->form_options->form_submit_btn_label ? : 'Submit'; ?></div><a href='javascript:;' onclick='edit_label()' ><?php echo RM_UI_Strings::get('LABEL_FIELD_ICON_CHANGE'); ?></a></div>
                <div id="rm-submit-label-textbox" style="display:none"><input type="text" id="submit_label_textbox"/><div><input type="button" value ="Save" onclick="save_submit_label()"><input type="button" value ="Cancel" onclick="cancel_edit_label()"></div></div> </div>
            <div class="rm-grid-quick-tasks dbfl">
                <div class="rm-grid-sidebar-row dbfl">
                    <div class="rm-grid-sidebar-row-label difl">
                        <a id="rm-sidebar-duplicate" href="javascript:void(0)" onclick="jQuery.rm_do_action('rm_fd_action_form', 'rm_form_duplicate')"><?php echo RM_UI_Strings::get('LABEL_DUPLICATE'); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="rm-grid-section dbfl">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_LABEL_STATS'); ?>
                <span class="rm-grid-section-toggle rm-collapsible"></span>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>visitors.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-visitors"><?php echo RM_UI_Strings::get('FD_LABEL_VISITORS'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo $data->visitors_count . ' in last 30 days'; ?></div>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>submissions.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-submissions"><?php echo RM_UI_Strings::get('LABEL_REGISTRATIONS'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo $data->sub_count; ?><a href="javascript:void(0)" class="rm_deactivated"><?php echo RM_UI_Strings::get('FD_DOWNLOAD_REGISTRATIONS'); ?></a></div>
            </div>

            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>conversion.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-conversion"><?php echo RM_UI_Strings::get('LABEL_CONVERSION'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo $data->conversion_rate; ?>%</div>
            </div>

            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>avgtime.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-avgtime"><?php echo RM_UI_Strings::get('FD_AVG_TIME'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo $data->avg_time; ?></div>
            </div>


            <div class="rm-grid-quick-tasks dbfl">
                <div class="rm-grid-sidebar-row dbfl">
                    <div class="rm-grid-sidebar-row-label difl">
                        <a id="rm-sidebar-reset" href="javascript:void(0)" onclick="jQuery.rm_do_action_with_alert('You are going to delete all stats for selected form. Do you want to proceed?', 'rm_fd_action_form', 'rm_analytics_reset')"><?php echo RM_UI_Strings::get('LABEL_RESET'); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="rm-grid-section dbfl">
            <div class="rm-grid-section-title dbfl" id="rm-sidebar-quick-toggles">
                <?php echo RM_UI_Strings::get('FD_LABEL_QCK_TOGGLE'); ?>
                <span class="rm-grid-section-toggle rm-collapsible"></span>
            </div>

             <?php if($data->form_options->form_email_subject && $data->form_options->form_email_content)
                  {
                        $deactivation_class = '';
                        $tooltip = '';
                  }else{
                        $deactivation_class = 'rm_transparent_deactivated';
                        $tooltip = 'title="'.sprintf(RM_UI_Strings::get('FD_TOGGLE_TOOLTIP'),admin_url('admin.php?page=rm_form_sett_autoresponder&rm_form_id='.$data->form_id)).'"';
                  }
                
             ?>
            <div   <?php echo $tooltip; ?> class="rm-grid-sidebar-row dbfl <?php echo $deactivation_class; ?>">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>auto-responder.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" ><?php echo RM_UI_Strings::get('FD_AUTORESPONDER'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl<?php echo ($data->form_options->form_email_subject && $data->form_options->form_email_content) ? '' : ' rm_deactivated' ?>"><div class="rm-grid-sidebar-row-value difl"><div class="switch">
                            <input id="rm-toggle-1"  class="rm-toggle rm-toggle-round-flat" onchange="rm_fd_quick_toggle(this, <?php echo $data->form_id; ?>)" name="form_should_send_email" type="checkbox"<?php echo $data->form->get_form_should_send_email() == 1 ? ' checked' : '' ?>>
                            <label for="rm-toggle-1"></label>
                        </div></div></div>
            </div>

            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>form-accounts.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl"><?php echo RM_UI_Strings::get('FD_WP_REG'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><div class="rm-grid-sidebar-row-value difl"><div class="switch">
                            <input id="rm-toggle-2" class="rm-toggle rm-toggle-round-flat" onchange="rm_fd_quick_toggle(this, <?php echo $data->form_id; ?>)" name="form_type" type="checkbox"<?php echo $data->form->get_form_type() == 1 ? ' checked' : '' ?>>
                            <label for="rm-toggle-2"></label>
                        </div></div></div>
            </div>

          <?php if($data->form_options->form_expired_by)
                  {
                        $deactivation_class = '';
                        $tooltip = '';
                  }else{
                        $deactivation_class = 'rm_transparent_deactivated';
                        $tooltip = 'title="'.sprintf(RM_UI_Strings::get('FD_TOGGLE_TOOLTIP'),admin_url('admin.php?page=rm_form_sett_limits&rm_form_id='.$data->form_id)).'"';
                  }
                
             ?>
            <div <?php echo $tooltip;?> class="rm-grid-sidebar-row dbfl <?php echo $deactivation_class; ?>">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>form-limits.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl"><?php echo RM_UI_Strings::get('LABEL_EXPIRY'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl<?php echo ($data->form_options->form_expired_by) ? '' : ' rm_deactivated' ?>"><div class="rm-grid-sidebar-row-value difl"><div class="switch">
                            <input id="rm-toggle-5" class="rm-toggle rm-toggle-round-flat" onchange="rm_fd_quick_toggle(this, <?php echo $data->form_id; ?>)" name="form_should_auto_expire" type="checkbox"<?php echo $data->form->get_form_should_auto_expire() == 1 ? ' checked' : '' ?>>
                            <label for="rm-toggle-5"></label>
                        </div></div></div>
            </div>

        </div>

    </div>

    <!-- action form to execute rm_slug_actions -->
    <form style="display:none" method="post" action="" id="rm_fd_action_form">
        <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
        <input type="hidden" name="req_source" value="form_dashboard">
        <input type="hidden" name="rm_interval" value="all">
        <input type="number" name="form_id" value="<?php echo $data->form_id; ?>">
        <input type="number" name="rm_selected" value="<?php echo $data->form_id; ?>">
    </form

    <!--    Forms toggle-->
    <div id="rm_form_toggle" style="display: none">
        <select onchange="rm_fd_switch_form()">
            <?php
            foreach ($data->all_forms as $form_id => $form_name):
                echo "<option value='$form_id'>$form_name</option>";
            endforeach;
            ?>
        </select>
    </div>
</div>
<?php
            wp_enqueue_script('jquery-ui-tooltip',array('jquery'));
?>
<pre class='rm-pre-wrapper-for-script-tags'><script>
    function edit_label(){
        jQuery('#rm-submit-label-textbox').show();
    }
    
    function cancel_edit_label(){
        jQuery('#submit_label_textbox').val('');
        jQuery('#rm-submit-label-textbox').hide();
    }
    
    function save_submit_label(){
        
       var label= jQuery('#submit_label_textbox').val();
        if(label == '')
       {
           jQuery('#submit_label_textbox').focus();
           return 0;
       }
        var data = {
			'action': 'rm_save_submit_label',
			'label': label,
			'form_id':<?php echo $data->form_id ;?>
		};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
                    console.log(response);
                       if(response== 'changed')
                       {
                           jQuery('#rm-submit-label').html(label);
                           jQuery('#rm-submit-label-textbox').hide();
                       }
                       else
                       {
                           alert('Could not Change.Please try again.');
                           location.reload(); 
                       }
                      
		});
    }
    jQuery(function () { 
    jQuery(document).tooltip({
        content: function () {
            return jQuery(this).prop('title');
        },
        show: null, 
        close: function (event, ui) {
            ui.tooltip.hover(

            function () {
                jQuery(this).stop(true).fadeTo(400, 1);
            },

            function () {
                jQuery(this).fadeOut("400", function () {
                   jQuery(this).remove();
                })
            });
        }
    });
});

</script></pre>

<?php
/* * ****************************************************************
 * *************     Chart drawing - Line Chart        **************
 * **************************************************************** */
$data_string = '';
foreach ($data->day_wise_stat as $date => $per_day) {
    
        $formatted_name = $date;
        $data_string .= ", ['$formatted_name', " . $per_day->visits . ", $per_day->submissions]";
    
}
$data_string = substr($data_string, 2);
?>

<pre class='rm-pre-wrapper-for-script-tags'><script>
    jQuery(document).ready(function(){
       //Configure joyride
       //If autostart is false, call again "jQuery("#rm-form-man-joytips").joyride()" to start the tour.
       <?php if($data->autostart_tour): ?>
       jQuery("#rm-form-sett-dashboard-joytips").joyride({tipLocation: 'top',
                                               autoStart: true,
                                               postRideCallback: rm_joyride_tour_taken});
        <?php else: ?>
            jQuery("#rm-form-sett-dashboard-joytips").joyride({tipLocation: 'top',
                                               autoStart: false,
                                               postRideCallback: rm_joyride_tour_taken});
        <?php endif; ?>
    });
   
   function rm_start_joyride(){
       //Expand any collapsed section before starting tour.
       jQuery('.rm-collapsed').each(function(){jQuery(this).click();});
       jQuery("#rm-form-sett-dashboard-joytips").joyride();
    }
    
    function rm_joyride_tour_taken(){
        var data = {
			'action': 'joyride_tour_update',
			'tour_id': 'form_setting_dashboard_tour',
                        'state': 'taken'
		};

        jQuery.post(ajaxurl, data, function(response) {});
    }
    
    function drawTimewiseStat()
    {
        var data = google.visualization.arrayToDataTable([
            ['<?php echo RM_UI_Strings::get('LABEL_DATE'); ?>',
             '<?php echo RM_UI_Strings::get('LABEL_VISITS'); ?>',
             '<?php echo RM_UI_Strings::get('LABEL_SUBMISSIONS'); ?>'],
<?php echo $data_string; ?>
        ]);

        var options = {
            chartArea: {width: '90%'},
            height: 500,
            fontName: 'Titillium Web',
            hAxis: {
                title: '',
                minValue: 0,
                slantedText: false,
                maxAlternation: 1,
                maxTextLines: 1
            },
            vAxis: {
                title: '',
                viewWindow: {min: 0},
                minValue: 4,
            },
            legend: {position: 'top', maxLines: 3},
            colors: ['#8eb2cc', '#e2a1c4'],
            
        };
        
        var chart = new google.visualization.LineChart(document.getElementById('rm_subs_over_time_chart_div'));
        chart.draw(data, options);
    }
</script></pre>
