<?php
//echo "<pre>", var_dump($data);
/**
 * Plugin Template File[For Front End Submission Page]
 */
?>

<!-- setup initial tab -->
<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#rm_tabbing_container_front_sub").tabs({active: <?php echo $data->active_tab_index; ?>});
    });

    function get_tab_and_redirect(reqpagestr) {
        //alert(reqpage);
        var tab_index = jQuery("#rm_tabbing_container_front_sub").tabs("option", "active");
        var curr_url = window.location.href;
        var sign = '&';
        if (curr_url.indexOf('?') === -1)
            sign = '?';
        window.location.href = curr_url + sign + reqpagestr + '&rm_tab=' + tab_index;
    }
</script></pre>

<?php
if (!$data->payments && !$data->submissions && $data->is_user !== true)
{
    ?>

    <div class="rmnotice-container"><div class="rmnotice"><?php echo RM_UI_Strings::get('MSG_NO_DATA_FOR_EMAIL'); ?></div></div>
    <?php
}
?>
<div class="rmagic rm_tabbing_container" id="rm_tabbing_container_front_sub"> 

    <!-----Operationsbar Starts-->

    <div class="operationsbar">
        <!--        <div class="rmtitle">Submissions</div>-->
        <div class="nav">
            <ul>
                <?php
                if ($data->is_user === true)
                {
                    ?>
                    <li><a href="#rm_my_details_tab"><?php echo RM_UI_Strings::get('LABEL_MY_DETAILS'); ?></a></li>
                    <?php
                }
                ?>
                <li><a href="#rm_my_sub_tab"><?php echo RM_UI_Strings::get('LABEL_MY_SUBS'); ?></a></li>
                <?php
                if ($data->payments)
                {
                    ?>
                    <li><a href="#rm_my_pay_tab"><?php echo RM_UI_Strings::get('LABEL_PAY_HISTORY'); ?></a></li>
                    <?php
                }
                if (!is_user_logged_in())
                {
                    ?>
                    <li class="rm-form-toggle" onclick="document.getElementById('rm_front_submissions_nav_form').submit()"><?php echo RM_UI_Strings::get('LABEL_LOG_OFF'); ?></li>
                    <?php
                }else
                    {
                    ?>
                    <li class="rm-form-toggle" onclick="document.getElementById('rm_front_submissions_respas_form').submit()"><?php echo RM_UI_Strings::get('LABEL_RESET_PASS'); ?></li>
                    <?php
                    }
                ?>
            </ul>
            <form method="post" id="rm_front_submissions_nav_form">
                <input type="hidden" name="rm_slug" value="rm_front_log_off">
            </form>
            <form method="post" id="rm_front_submissions_respas_form">
                <input type="hidden" name="rm_slug" value="rm_front_reset_pass_page">
                <input type="hidden" name="RM_CLEAR_ERROR" value="true">
            </form>

        </div>


    </div>
    <!--------Operationsbar Ends----->

    <!-------Contentarea Starts----->

    <!----Table Wrapper---->

    <?php
    if ($data->is_user)
    {
        $editable_forms = array();
        ?>
        <div class="rm-submission" id="rm_my_details_tab">
            <div class="rm-user-details-card">
                <div class="rm-user-image-container">
                    <div class="rm-user-name"><?php echo $data->user->display_name; ?></div>
                    <div class="rm-user-image">
                        <?php
                        echo get_avatar($data->user->ID, 512, '', '', array('class' => 'rm-user'));
                        ?>
                    </div>
                </div>
                <div class="rm-user-fields-container">
                    <?php
                    if ($data->user->first_name)
                    {
                        ?>
                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_FNAME'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->first_name; ?></div>
                        </div>
                        <?php
                    }
                    if ($data->user->last_name)
                    {
                        ?>

                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_LNAME'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->last_name; ?></div>
                        </div>
                        <?php
                    }
                    if ($data->user->description)
                    {
                        ?>

                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('LABEL_BIO'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->description; ?></div>
                        </div>
                        <?php
                    }
                    if ($data->user->user_email)
                    {
                        ?>

                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('LABEL_EMAIL'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->user_email; ?></div>
                        </div>
                        <?php
                    }
                    if ($data->user->sec_email)
                    {
                        ?>

                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('LABEL_SECEMAIL'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->sec_email; ?></div>
                        </div>
                        <?php
                    }
                    if ($data->user->nickname)
                    {
                        ?>

                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_NICKNAME'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->nickname; ?></div>
                        </div>
                        <?php
                    }
                    if ($data->user->user_url)
                    {
                        ?>

                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_WEBSITE'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->user_url; ?></div>
                        </div>
                        <?php
                    }
                    if (is_array($data->custom_fields) || is_object($data->custom_fields))
                        foreach ($data->custom_fields as $field_id => $sub)
                        {
                            $key = $sub->label;
                            $meta = $sub->value;
                            if(!isset($sub->type)){
                                $sub->type = '';
                            }
                            
                            $sub_original = $sub;
                            
                            $meta = RM_Utilities::strip_slash_array(maybe_unserialize($meta));
                            ?>
                            <div class="rm-user-field-row">

                                <div class="rm-user-field-label"><?php echo $key; ?></div>
                                <div class="rm-user-field-value">
                                    <?php
                                    if (is_array($meta) || is_object($meta))
                                    {
                                        if (isset($meta['rm_field_type']) && $meta['rm_field_type'] == 'File')
                                        {
                                            unset($meta['rm_field_type']);

                                            foreach ($meta as $sub)
                                            {

                                                $att_path = get_attached_file($sub);
                                                $att_url = wp_get_attachment_url($sub);
                                                ?>
                                                <div class="rm-user-attachment">
                                                    <?php echo wp_get_attachment_link($sub, 'thumbnail', false, true, false); ?>
                                                    <div class="rm-user-attachment-field"><?php echo basename($att_path); ?></div>
                                                    <div class="rm-user-attachment-field"><a href="<?php echo $att_url; ?>"><?php echo RM_UI_Strings::get('LABEL_DOWNLOAD'); ?></a></div>
                                                </div>

                                                <?php
                                            }
                                        }elseif ($sub->type == 'Time') {                                  
                                    echo $meta['time'].", Timezone: ".$meta['timezone'];
                                } elseif ($sub->type == 'Checkbox') {   
                                    echo implode(', ',RM_Utilities::get_lable_for_option($field_id, $meta));
                                } else {
                                            $sub = implode(', ', $meta);
                                            echo $sub;
                                        }
                                    } else {
                                        if($sub->type=='Rating')
                                        {
                                           echo RM_Utilities::enqueue_external_scripts('script_rm_rating', RM_BASE_URL . 'public/js/rating3/jquery.rateit.js');
                                           echo '<div class="rateit" id="rateit5" data-rateit-min="0" data-rateit-max="5" data-rateit-value="'.$meta.'" data-rateit-ispreset="true" data-rateit-readonly="true"></div>';
                                 
                                        }
                                        elseif ($sub->type == 'Radio' || $sub->type == 'Select')
                                        {   
                                            echo RM_Utilities::get_lable_for_option($field_id, $meta);
                                        }
                                        else
                                            echo $meta;
                                    }
                                    ?>
                                </div>
                            </div>
                                    <?php
                                    //check if any field is editable
                                    if ($sub_original->is_editable == 1 && !in_array($sub_original->form_id, $editable_forms)) {
                                        $editable_forms[] = $sub_original->form_id;
                                    }
                                }
                            ?>
                </div>
            </div>
            <?php if(!empty($editable_forms)) { ?>
            <div id="rm_edit_sub_link">
                <form method="post" name="rm_form" action="" id="rmeditsubmissions">
                    <input type="hidden" name="rm_edit_user_details" value="true">
                    <input type="hidden" name="form_ids" value='<?php echo json_encode($editable_forms); ?>'>
                </form>
                <a href="javascript:void(0)" onclick="document.getElementById('rmeditsubmissions').submit();"><?php echo RM_UI_Strings::get('MSG_EDIT_YOUR_SUBMISSIONS'); ?></a>
            </div> 
            <?php } ?>
        </div>
        <?php
    }
    ?>

    <div class="rmagic-table" id="rm_my_sub_tab">

        <?php
        if ($data->submission_exists === true)
        {
            ?>
            <table class="rm-table">
                <tr>
                    <th><?php echo RM_UI_Strings::get('LABEL_SR'); ?></th>
                    <th><?php echo RM_UI_Strings::get('LABEL_FORM'); ?></th>
                    <th><?php echo RM_UI_Strings::get('LABEL_DATE'); ?></th>
                    <th></th>
                </tr>
                <?php
                $i = 0;
                if ($data->submissions):
                    foreach ($data->submissions as $data_single):
                        ?>  
                        <tr>
                            <td id="<?php echo $data_single->submission_id; ?>"><?php echo ++$i; ?></td>
                            <td><a href="<?php echo add_query_arg( 'submission_id',$data_single->submission_id); ?>"><?php echo $data_single->form_name; ?></a></td>
                            <td><?php echo RM_Utilities::localize_time($data_single->submitted_on, $data->date_format); ?></td>
                            <td></td>
                        <form id="rmsubmissionfrontform<?php echo $data_single->submission_id; ?>" method="post">
                            <input type="hidden" value="<?php echo $data_single->submission_id; ?>" name="rm_submission_id">
                            
                        </form>    
                        </tr>
                        <?php
                    endforeach;
                else:

                endif;
                ?>
            </table>
            <?php
            /*             * ********** Pagination Logic ************** */
            $max_pages_without_abb = 10;
            $max_visible_pages_near_current_page = 3; //This many pages will be shown on both sides of current page number.

            if ($data->total_pages_sub > 1):
                ?>
                <ul class="rmpagination">
                    <?php
                    if ($data->curr_page_sub > 1):
                        ?>
                        <li onclick="get_tab_and_redirect('rm_reqpage_sub=1')"><a><?php echo RM_UI_Strings::get('LABEL_FIRST'); ?></a></li>
                        <li onclick="get_tab_and_redirect('rm_reqpage_sub=<?php echo $data->curr_page_sub - 1; ?>')"><a><?php echo RM_UI_Strings::get('LABEL_PREVIOUS'); ?></a></li>
                        <?php
                    endif;
                    if ($data->total_pages_sub > $max_pages_without_abb):
                        if ($data->curr_page_sub > $max_visible_pages_near_current_page + 1):
                            ?>
                            <li><a> ... </a></li>
                            <?php
                            $first_visible_page = $data->curr_page_sub - $max_visible_pages_near_current_page;
                        else:
                            $first_visible_page = 1;
                        endif;

                        if ($data->curr_page_sub < $data->total_pages_sub - $max_visible_pages_near_current_page):
                            $last_visible_page = $data->curr_page_sub + $max_visible_pages_near_current_page;
                        else:
                            $last_visible_page = $data->total_pages_sub;
                        endif;
                    else:
                        $first_visible_page = 1;
                        $last_visible_page = $data->total_pages_sub;
                    endif;
                    for ($i = $first_visible_page; $i <= $last_visible_page; $i++):
                        if ($i != $data->curr_page_sub):
                            ?>
                            <li onclick="get_tab_and_redirect('rm_reqpage_sub=<?php echo $i; ?>')"><a><?php echo $i; ?></a></li>
                        <?php else:
                            ?>
                            <li onclick="get_tab_and_redirect('rm_reqpage_sub=<?php echo $i; ?>')"><a class="active"?><?php echo $i; ?></a></li>
                        <?php
                        endif;
                    endfor;
                    if ($data->total_pages_sub > $max_pages_without_abb):
                        if ($data->curr_page_sub < $data->total_pages_sub - $max_visible_pages_near_current_page):
                            ?>
                            <li><a> ... </a></li>
                            <?php
                        endif;
                    endif;
                    ?>
                    <?php
                    if ($data->curr_page_sub < $data->total_pages_sub):
                        ?>
                        <li onclick="get_tab_and_redirect('rm_reqpage_sub=<?php echo $data->curr_page_sub + 1; ?>')"><a><?php echo RM_UI_Strings::get('LABEL_NEXT'); ?></a></li>
                        <li onclick="get_tab_and_redirect('rm_reqpage_sub=<?php echo $data->total_pages_sub; ?>')"><a><?php echo RM_UI_Strings::get('LABEL_LAST'); ?></a></li>
                        <?php
                    endif;
                    ?>
                </ul>
            <?php
            endif;
        } else
            echo RM_UI_Strings::get('MSG_NO_SUBMISSION_FRONT');
        ?>
    </div>
    <?php
    if ($data->payments):
        ?>
        <div class="rmagic-table" id="rm_my_pay_tab">


            <table class="rm-table">
                <tr>
                    <th><?php echo RM_UI_Strings::get('LABEL_DATE'); ?></th>
                    <th><?php echo RM_UI_Strings::get('LABEL_FORM'); ?></th>
                    <th><?php echo RM_UI_Strings::get('LABEL_AMOUNT'); ?></th>
                    <th><?php echo RM_UI_Strings::get('LABEL_TXN_ID'); ?></th>
                    <th><?php echo RM_UI_Strings::get('LABEL_STATUS'); ?></th>
                </tr>
                <?php
                for ($i = $data->offset_pay; $i < $data->end_offset_this_page; $i++):
                    ?>
                    <tr>
                        <td><?php echo RM_Utilities::localize_time($data->payments[$i]->posted_date, $data->date_format); ?></td>
                        <td><a href="<?php echo add_query_arg( 'submission_id',$data->payments[$i]->submission_id); ?>"><?php echo $data->form_names[$data->payments[$i]->submission_id]; ?></a></td>
                        <td><?php echo $data->payments[$i]->total_amount; ?></td>
                        <td><?php echo $data->payments[$i]->txn_id; ?></td>
                        <td><?php echo $data->payments[$i]->status; ?></td>
                    </tr>
                    <?php
                endfor;
                ?>
            </table>

            <?php
            /*             * ********** Pagination Logic ************** */
            $max_pages_without_abb = 10;
            $max_visible_pages_near_current_page = 3; //This many pages will be shown on both sides of current page number.

            if ($data->total_pages_pay > 1):
                ?>
                <ul class="rmpagination">
                    <?php
                    if ($data->curr_page_pay > 1):
                        ?>
                        <li onclick="get_tab_and_redirect('rm_reqpage_pay=1')"><a><?php echo RM_UI_Strings::get('LABEL_FIRST'); ?></a></li>
                        <li onclick="get_tab_and_redirect('rm_reqpage_pay=<?php echo $data->curr_page_pay - 1; ?>')"><a><?php echo RM_UI_Strings::get('LABEL_PREVIOUS'); ?></a></li>
                        <?php
                    endif;
                    if ($data->total_pages_pay > $max_pages_without_abb):
                        if ($data->curr_page_pay > $max_visible_pages_near_current_page + 1):
                            ?>
                            <li><a> ... </a></li>
                            <?php
                            $first_visible_page = $data->curr_page_pay - $max_visible_pages_near_current_page;
                        else:
                            $first_visible_page = 1;
                        endif;

                        if ($data->curr_page_pay < $data->total_pages_pay - $max_visible_pages_near_current_page):
                            $last_visible_page = $data->curr_page_pay + $max_visible_pages_near_current_page;
                        else:
                            $last_visible_page = $data->total_pages_pay;
                        endif;
                    else:
                        $first_visible_page = 1;
                        $last_visible_page = $data->total_pages_pay;
                    endif;
                    for ($i = $first_visible_page; $i <= $last_visible_page; $i++):
                        if ($i != $data->curr_page_pay):
                            ?>
                            <li onclick="get_tab_and_redirect('rm_reqpage_pay=<?php echo $i; ?>')"><a><?php echo $i; ?></a></li>
                        <?php else:
                            ?>
                            <li onclick="get_tab_and_redirect('rm_reqpage_pay=<?php echo $i; ?>')"><a class="active"><?php echo $i; ?></a></li>
                            <?php
                            endif;
                        endfor;
                        if ($data->total_pages_pay > $max_pages_without_abb):
                            if ($data->curr_page_pay < $data->total_pages_pay - $max_visible_pages_near_current_page):
                                ?>
                            <li><a> ... </a></li>
                            <?php
                        endif;
                    endif;
                    ?>
                    <?php
                    if ($data->curr_page_pay < $data->total_pages_pay):
                        ?>
                        <li onclick="get_tab_and_redirect('rm_reqpage_pay=<?php echo $data->curr_page_pay + 1; ?>')"><a><?php echo RM_UI_Strings::get('LABEL_NEXT'); ?></a></li>
                        <li onclick="get_tab_and_redirect('rm_reqpage_pay=<?php echo $data->total_pages_pay; ?>')"><a><?php echo RM_UI_Strings::get('LABEL_LAST'); ?></a></li>
                        <?php
                    endif;
                    ?>
                </ul>
    <?php endif; ?>

            <!-- 
                    <ul class="rmpagination">
                        <li><a href="#">«</a></li>
                        <li><a href="#">1</a></li>
                        <li><a class="active" href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li><a href="#">6</a></li>
                        <li><a href="#">7</a></li>
                        <li><a href="#">»</a></li>
                    </ul>
            -->
            <!-- Pagination Ends    -->


        </div>   

        <?php
    endif;
    ?>

</div>
