<?php
//echo '<pre>';print_r($data);die;

?>

<div class="rmagic">

    <!-----Operationsbar Starts----->

    <div class="operationsbar">
        <div class="rmtitle"><?php echo $data->user->data->display_name; ?></div>
        <div class="icons">
        </div>
        <div class="nav">
            <ul>
                <li><a href="<?php echo get_admin_url() . 'user-edit.php?user_id=' . $data->user->ID; ?>"><?php echo RM_UI_Strings::get('LABEL_EDIT'); ?></a></li>
                <?php
                if ($data->curr_user != $data->user->ID)
                {
                    ?>
                    <li onclick="jQuery.rm_do_action('form_user_page_action', 'rm_user_delete')"><a href="#"><?php echo RM_UI_Strings::get('LABEL_DELETE'); ?></a></li>
                    <?php
                }
                ?>
                <?php
                if ($data->user->rm_user_status != 1)
                {
                    if ($data->curr_user != $data->user->ID)
                    {
                        ?>
                        <li onclick="jQuery.rm_do_action('form_user_page_action', 'rm_user_deactivate')"><a href="#"><?php echo RM_UI_Strings::get('DEACTIVATE'); ?></a></li>
                        <?php
                    }
                } else
                {
                    ?>
                    <li onclick="jQuery.rm_do_action('form_user_page_action', 'rm_user_activate')"><a href="#"><?php echo RM_UI_Strings::get('ACTIVATE'); ?></a></li>
                    <?php
                }
                ?>
                    <li onclick="rm_show_send_mail_popup()"><a href="#"><?php echo RM_UI_Strings::get('SEND_MAIL'); ?></a></li>
            </ul>
        </div>

    </div>
    <!--------Operationsbar Ends----->

    <!----User Area Starts---->

    <div class="rm-user-area">

        <div class="rm-user-info">
            <div class="rm-profile-image">
                <?php echo get_avatar($data->user->ID, 250); ?>
            </div>
            <div class="rm-profile-fields">

                <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_FNAME'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->first_name; ?></div>
                </div>

                <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_LNAME'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->last_name; ?></div>
                </div>

                <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('LABEL_EMAIL'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->user_email; ?></div>
                </div>

                <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('LABEL_ROLE'); ?></div>
                    <?php
                    foreach ($data->user->roles as $role)
                    {
                        $user_roles = RM_Utilities::user_role_dropdown();
                        ?>
                        <div class="rm-field-value"><?php echo $user_roles[$role]; ?></div>
                        <?php
                    }
                    ?>
                </div>

                <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('LABEL_BIO'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->description; ?></div>
                </div>
                <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_NICKNAME'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->nickname; ?></div>
                </div>
                 <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_WEBSITE'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->user_url; ?></div>
                </div>

            </div>

        </div>
        <div class="rm_tabbing_container">

            <ul class="rm-profile-nav">
                <li class="rm-profile-nav-item"><a href="#rmfirsttabcontent"><?php echo RM_UI_Strings::get('LABEL_CUSTOM_FIELD'); ?></a></li>
                <li class="rm-profile-nav-item"><a href="#rmsecondtabcontent"><?php echo RM_UI_Strings::get('LABEL_SUBMISSIONS'); ?></a></li>
                <li class="rm-profile-nav-item"><a href="#rmthirdtabcontent"><?php echo RM_UI_Strings::get('LABEL_PAYMENTS'); ?></a></li>
                <li class="rm-profile-nav-item"><a href="#rmfourthtabcontent"><?php echo RM_UI_Strings::get('LABEL_SENT_EMAILS'); ?></a></li>

            </ul>

            <div class="rm-user-content">
                <div class="rm-profile-fields" id="rmfirsttabcontent">

                    <?php
                    if (is_array($data->custom_fields) || is_object($data->custom_fields))
                        foreach ($data->custom_fields as $field_id => $sub) {
                            $key = $sub->label;
                            $meta = $sub->value;
                            if(!isset($sub->type)){
                                $sub->type = '';
                            }
                            $type = $sub->type;
                            $meta = RM_Utilities::strip_slash_array(maybe_unserialize($meta));
                            ?>
                            <div class="rm-profile-field-row">

                                <div class="rm-field-label"><?php echo $key; ?></div>
                                <div class="rm-field-value">
                                    <?php
                                    
                                    if (is_array($meta) || is_object($meta)) {
                                        if (isset($meta['rm_field_type']) && $meta['rm_field_type'] == 'File') {
                                            unset($meta['rm_field_type']);

                                            foreach ($meta as $sub) {

                                                $att_path = get_attached_file($sub);
                                                $att_url = wp_get_attachment_url($sub);
                                                ?>
                                                <div class="rm-submission-attachment">
                                                    <?php echo wp_get_attachment_link($sub, 'thumbnail', false, true, false); ?>
                                                    <div class="rm-submission-attachment-field"><?php echo basename($att_path); ?></div>
                                                    <div class="rm-submission-attachment-field"><a href="<?php echo $att_url; ?>"><?php echo RM_UI_Strings::get('LABEL_DOWNLOAD'); ?></a></div>
                                                </div>

                                                <?php
                                            }
                                        } elseif (isset($meta['rm_field_type']) && $meta['rm_field_type'] == 'Address') {
                                            $sub = $meta['original'] . '<br/>';
                                            if (count($meta) === 8) {
                                                $sub .= '<b>Street Address</b> : ' . $meta['st_number'] . ', ' . $meta['st_route'] . '<br/>';
                                                $sub .= '<b>City</b> : ' . $meta['city'] . '<br/>';
                                                $sub .= '<b>State</b> : ' . $meta['state'] . '<br/>';
                                                $sub .= '<b>Zip code</b> : ' . $meta['zip'] . '<br/>';
                                                $sub .= '<b>Country</b> : ' . $meta['country'];
                                            }
                                                echo $sub;
                                        } elseif ($sub->type == 'Time') {                                  
                                    echo $meta['time'].", Timezone: ".$meta['timezone'];
                                } elseif ($sub->type == 'Checkbox') {   
                                    echo implode(', ',RM_Utilities::get_lable_for_option($field_id, $meta));
                                } else {
                                            $sub = implode(', ', $meta);
                                            echo $sub;
                                        }
                                    } else {
                                        if($type=='Rating')
                                        {
                                           echo '<div class="rateit" id="rateit5" data-rateit-min="0" data-rateit-max="5" data-rateit-value="'.$meta.'" data-rateit-ispreset="true" data-rateit-readonly="true"></div>';
                                 
                                        }
                                        elseif ($sub->type == 'Radio' || $sub->type == 'Select') {   
                                            echo RM_Utilities::get_lable_for_option($field_id, $meta);
                                        } 
                                        else
                                            echo $meta;
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        } else
                        echo "<div class='rmnotice'>" . RM_UI_Strings::get('MSG_NO_CUSTOM_FIELDS') . "</div>";
                    ?>

                </div>

                <table class="user-content" id="rmsecondtabcontent">

                    <?php
                    if (count($data->submissions) !== 0)
                    {
                        ?>

                        <th>#</th> <th><?php echo RM_UI_Strings::get('LABEL_FORM'); ?></th> <th><?php echo RM_UI_Strings::get('LABEL_DATE'); ?></th> <th>&nbsp;</th> <th>&nbsp;</th>

                        <?php
                        $i = 0;
                        foreach ($data->submissions as $sub)
                        {
                            $form_name = ($sub->form_name) ? : 'FORM DELETED'
                            ?>
                            <tr> <td><?php echo $i++; ?></td><td><?php echo $form_name; ?></td><td><?php echo $sub->submitted_on; ?></td><td><a href="?page=rm_submission_view&rm_submission_id=<?php echo $sub->submission_id; ?>&rm_form_id=<?php echo $sub->form_id; ?>"><img class="icon" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/view_form.png'; ?>"></a></td><td><a href="javascript:void(0)" class="rm_deactivated"><img class="icon" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/form_download.png'; ?>"></a></td>
                            <form id="rmsubmissionuserform<?php echo $sub->submission_id; ?>" method="post">
                                <input type="hidden" value="<?php echo $sub->submission_id; ?>" name="rm_submission_id">
                                <input type="hidden" value="rm_submission_print_pdf" name="rm_slug">
                            </form>
                            </tr>
                            <?php
                        }
                    } else
                    {
                        echo "<tr> <td class='rmnotice'>" . RM_UI_Strings::get('MSG_NO_SUBMISSIONS_USER') . "</td></tr>";
                    }
                    ?>

                </table>

                <table class="user-content" id="rmthirdtabcontent">
                    <?php
                    if (count($data->payments) != 0)
                    {
                        ?>
                        <th>#</th> <th><?php echo RM_UI_Strings::get('LABEL_FORM'); ?></th> <th><?php echo RM_UI_Strings::get('LABEL_DATE'); ?></th> <th><?php echo RM_UI_Strings::get('LABEL_PAYMENT'); ?></th> <th>&nbsp;</th>
                        <?php
                        $i = 0;
                        foreach ($data->payments as $payment)
                        {
                            ?>
                            <tr> <td><?php echo $i++; ?></td><td><?php echo $payment['form_name']; ?></td><td><?php echo $payment['payment']->posted_date; ?></td><td><?php echo $payment['payment']->status; ?></td><td><a href="?page=rm_submission_view&rm_submission_id=<?php echo $payment['submission_id']; ?>&rm_form_id=<?php echo $payment['form_id']; ?>"><img class="icon" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/view_form.png'; ?>"></a></td></tr>
                            <?php
                        }
                    } else
                        echo "<tr> <td class='rmnotice'>" . RM_UI_Strings::get('MSG_NO_PAYMENTS_USER') . "</td></tr>";
                    ?>
                </table>
                
                <table class="user-content" id="rmfourthtabcontent">

                    <?php
                    if (count($data->sent_emails) !== 0) {
                        ?>

                        <th><?php echo RM_UI_Strings::get('LABEL_EMAIL_SUB'); ?></th> <th><?php echo RM_UI_Strings::get('LABEL_EMAIL_BODY'); ?></th><th><?php echo RM_UI_Strings::get('LABEL_EMAIL_SENT_ON'); ?></th> <th>&nbsp;</th>

                        <?php
                        $i = 1;
                        foreach ($data->sent_emails as $email) {
                            
                            ?>
                            <tr><td><?php echo strip_tags(htmlspecialchars_decode($email->sub));; ?></td><td><?php echo strip_tags(htmlspecialchars_decode($email->body)); ?></td><td><?php echo $email->sent_on; ?></td><td><a href="?page=rm_sent_emails_view&rm_sent_email_id=<?php echo $email->mail_id; ?>"><?php echo RM_UI_Strings::get("VIEW"); ?></a></td>
                            <form id="rmsubmissionuserform<?php echo $sub->submission_id; ?>" method="post">
                                <input type="hidden" value="<?php echo $sub->submission_id; ?>" name="rm_submission_id">
                                <input type="hidden" value="rm_submission_print_pdf" name="rm_slug">
                            </form>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr> <td class='rmnotice'>" . RM_UI_Strings::get('MSG_NO_SENT_EMAIL_USER') . "</td></tr>";
                    }
                    ?>

                </table>

            </div>
        </div>
        
        <!-- Pop-up div to send email -->        
        <div id="rm_popup_send_email" style="display:none">
            <div class="rm_popup_send_email_row">
                    <span class="rm_popup_send_email">Subject</span> <input type="text" id="rm_popup_send_email_sub">
            </div>
            <div class="rm_popup_send_email_row">
                    <span class="rm_popup_send_email">Message</span> <textarea id="rm_popup_send_email_body"></textarea>
            </div>
            <div class="rm_popup_send_email_row">
                    <button class="popup-submit" type="button" id="rm_popup_send_email_button" onclick="rm_send_email('admin@gmail.com') ">Send</button>
            <button class="popup-submit" type="button" id="rm_popup_cancel_email_button" onclick="jQuery('#rm_popup_send_email').hide()">Cancel</button>
            </div>
        </div>
        <!-- Pop-up end --> 
            
    </div>

    <form id="form_user_page_action" method="post">
        <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
        <input type="hidden" name="rm_users[]" value="<?php echo $data->user->ID; ?>">
    </form>
<?php     
    include RM_ADMIN_DIR.'views/template_rm_promo_banner_bottom.php';
    ?>
</div>

<pre class='rm-pre-wrapper-for-script-tags'><script>
function rm_show_send_mail_popup()
{
    //Enable send button if disabled previously
    jQuery("#rm_popup_send_email_button").prop('disabled', false);
    jQuery("#rm_popup_cancel_email_button").prop('disabled', false);
    jQuery("#rm_popup_send_email_button").html("Send");
    
    jQuery('#rm_popup_send_email').show();
}

function rm_send_email(email)
{
    if(!rm_validate_fields())
        return;
    //Disable send button to prevent multiple send requests.
    jQuery("#rm_popup_send_email_button").prop('disabled', true);
    jQuery("#rm_popup_cancel_email_button").prop('disabled', true);
    jQuery("#rm_popup_send_email_button").html("<i>Sending...</i>");
    
    var address = '<?php echo $data->user->user_email; ?> ';
    var subject = jQuery('#rm_popup_send_email_sub').val();
    var message = jQuery('#rm_popup_send_email_body').val();
    var data = {action: 'send_email_user_view', to: address, sub: subject, body: message};
    jQuery.post(ajaxurl, data, function(){jQuery('#rm_popup_send_email').hide();alert('sent');});
}

function rm_validate_fields()
{
    var jqel_subject = jQuery('#rm_popup_send_email_sub');
    var jqel_message = jQuery('#rm_popup_send_email_body');
    var is_valid = true;
    if(jqel_message.val().toString().trim() === '')
    {
        flash_element(jqel_message);
        is_valid = false;
    }
    
    if(jqel_subject.val().toString().trim() === '')
    {
        flash_element(jqel_subject);
        is_valid = false;
    }
    
    return is_valid;
    
}

function flash_element(x){
   x.each(function () {
                jQuery(this).css("border", "1px solid #FF6C6C");        
                jQuery(this).fadeIn(100).fadeOut(1000, function () {
                    jQuery(this).css("border", "");
                    jQuery(this).fadeIn(100);
                    jQuery(this).val('');
                });
            });
                        
}
</script></pre>

