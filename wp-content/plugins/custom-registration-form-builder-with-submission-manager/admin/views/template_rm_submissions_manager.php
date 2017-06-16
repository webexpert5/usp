
<div class="rmagic">
    <?php
    ?>
    <!-----Operations bar Starts-->

    <div class="operationsbar">
        <div class="rmtitle"><?php echo RM_UI_Strings::get("TITLE_SUBMISSION_MANAGER"); ?></div>
        <div class="icons">
            <a href="?page=rm_options_manage"><img alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/global-settings.png'; ?>"></a>

        </div>
        <div class="nav">
            <ul>
                <li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_BACK"); ?></a></li>
                
                <li><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_EXPORT_ALL"); ?></a></li>

                <li onclick="jQuery.rm_do_action('rm_submission_manager_form', 'rm_submission_remove')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_DELETE"); ?></a></li>

                <li class="rm-form-toggle"><?php
                    if (count($data->forms) !== 0)
                    {
                        echo RM_UI_Strings::get('LABEL_DISPLAYING_FOR');
                        ?>
                        <select id="rm_form_dropdown" name="form_id" onchange = "rm_load_page(this, 'submission_manage')">
                            <?php
                            foreach ($data->forms as $form_id => $form)
                                if ($data->filter->form_id == $form_id)
                                    echo "<option value=$form_id selected>$form</option>";
                                else
                                    echo "<option value=$form_id>$form</option>";
                            ?>
                        </select>
                        <?php
                    }
                    ?>
                </li>
            </ul>
        </div>

    </div>
    <!--  Operations bar Ends----->


    <!-------Content area Starts----->

    <?php
    if (count($data->forms) === 0)
    {
        ?><div class="rmnotice-container">
            <div class="rmnotice">
        <?php echo RM_UI_Strings::get('MSG_NO_FORM_SUB_MAN'); ?>
            </div>
        </div><?php
    } elseif ($data->submissions || $data->filter->filters['rm_interval'] != 'all' || $data->filter->searched)
    {
        ?>
        <div class="rmagic-table">
            <div class="sidebar">
                <div class="sb-filter">
    <?php echo RM_UI_Strings::get("LABEL_TIME"); ?>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' name="filter_between" value="all"   <?php if ($data->filter->filters['rm_interval'] == "all") echo "checked"; ?>><?php echo RM_UI_Strings::get("LABEL_ALL"); ?> </div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' name="filter_between" value="today" <?php if ($data->filter->filters['rm_interval'] == "today") echo "checked"; ?>><?php echo RM_UI_Strings::get("LABEL_TODAY"); ?> </div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' name="filter_between" value="week"  <?php if ($data->filter->filters['rm_interval'] == "week") echo "checked"; ?>><?php echo RM_UI_Strings::get("LABEL_THIS_WEEK"); ?></div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' name="filter_between" value="month" <?php if ($data->filter->filters['rm_interval'] == "month") echo "checked"; ?>><?php echo RM_UI_Strings::get("LABEL_THIS_MONTH"); ?></div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' name="filter_between" value="year"  <?php if ($data->filter->filters['rm_interval'] == "year") echo "checked"; ?>><?php echo RM_UI_Strings::get("LABEL_THIS_YEAR"); ?></div>

                </div>

                <div class="sb-filter">
    <?php echo RM_UI_Strings::get("LABEL_MATCH_FIELD"); ?>
                    <form action="" method="post">
                        <div class="filter-row">
                            <select name="rm_field_to_search">
                                <?php
                                foreach ($data->fields as $f)
                                {
                                    if ($f->field_type !== 'File' && $f->field_type !== 'HTMLH' && $f->field_type !== 'HTMLP' && $f->field_type !== 'Divider' && $f->field_type !== 'Spacing')
                                    {
                                        ?>
                                        <option value="<?php echo $f->field_id; ?>" <?php if ($data->filter->filters['rm_field_to_search'] === $f->field_id) echo "selected"; ?>><?php echo $f->field_label; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="filter-row"><input type="text" name="rm_value_to_serach" class="sb-search" value="<?php echo $data->filter->filters['rm_value_to_serach']; ?>"></div>
                        <div class="filter-row"><input type="submit" name="submit" value="Search"></div>
                    </form>
                </div>


            </div>

            <!--*******Side Bar Ends*********-->

            <form method="post" action="" name="rm_submission_manage" id="rm_submission_manager_form">
                <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
                <input type="hidden" name="rm_form_id" value="<?php echo $data->filter->form_id; ?>" id="rm_form_id_input_field" />
                <input type="hidden" name="rm_interval" value="<?php echo $data->filter->filters['rm_interval']; ?>">
                <table>
                    <?php if ($data->submissions)
                    {
                        ?>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <?php
                            //echo "<pre>";var_dump($data->submissions);die();


                            $field_names = array();
                            $i = $j = 0;

                            for ($i = 0; $j < 4; $i++):
                                if ((isset($data->fields[$i]->field_type) && $data->fields[$i]->field_type != 'File' && $data->fields[$i]->field_type != 'Spacing' && $data->fields[$i]->field_type != 'Divider' && $data->fields[$i]->field_type !== 'HTMLH' && $data->fields[$i]->field_type !== 'HTMLP' && $data->fields[$i]->field_type !== 'Address') || !isset($data->fields[$i]->field_type))
                                {

                                    $label = isset($data->fields[$i]->field_label) ? $data->fields[$i]->field_label : null;
                                    ?><th><?php echo $label; ?></th>

                                    <?php
                                    $field_names[$j] = isset($data->fields[$i]->field_id) ? $data->fields[$i]->field_id : null;
                                    $j++;
                                }

                            endfor;
                            ?>

                            <th><?php echo RM_UI_Strings::get("ACTION"); ?></th></tr>

                        <?php
                        if (is_array($data->submissions) || is_object($data->submissions))
                            foreach ($data->submissions as $submission):

                                $submission->data_us = RM_Utilities::strip_slash_array(maybe_unserialize($submission->data));
                                ?>
                                <tr>
                                    <td><input class="rm_checkbox_group" type="checkbox" value="<?php echo $submission->submission_id; ?>" name="rm_selected[]"></td>
                                    <td> <?php
                                        $submission_model=new RM_Submissions;
                                        $submission_model->load_from_db($submission->submission_id);
                                        $have_attchment=$submission_model-> is_have_attcahment();
                                        $payment_status=$submission_model->get_payment_status();
                                       if($payment_status == 'Pending')
                                       {
                                          ?>
                                         <img  class="rm_submission_icon" alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/pending_payment.png'; ?>">
                                       <?php  
                                       }
                                       if($payment_status == 'Completed')
                                       {
                                          ?>
                                         <img  class="rm_submission_icon" alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/payment_completed.png'; ?>">
                                       <?php  
                                       }
                                        if($have_attchment)
                                       {
                                         ?>
                                         <img  class="rm_submission_icon" alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/attchment.png'; ?>">
                                       <?php  
                                       }
                                        ?>
                                      </td>
                                      
                                    <?php
                                    for ($i = 0; $i < 4; $i++):

                                        $value = null;
                                            $type=null;
                                              
                                        if (is_array($submission->data_us) || is_object($submission->data_us))
                                            foreach ($submission->data_us as $key => $sub_data)
                                                 
                                                if ($key == $field_names[$i])
                                                {
                                                    
                                                    $type=  isset($sub_data->type)?$sub_data->type:'';
                                                    if($type=='Checkbox' || $type == 'Select' || $type == 'Radio')
                                                       $value = RM_Utilities::get_lable_for_option($key, $sub_data->value);
                                                    else
                                                       $value = $sub_data->value;

                                                }
                                        ?>

                                        <td><?php
                                            if (is_array($value))
                                                $value = implode(', ', $value);

                                            echo mb_strimwidth($value, 0, 70, "...");
                                            ?></td>

                                        <?php
                                    endfor;
                                    ?>
                                    <td><a href="?page=rm_submission_view&rm_submission_id=<?php echo $submission->submission_id; ?>"><?php echo RM_UI_Strings::get("VIEW"); ?></a></td>
                                </tr>

                                <?php
                            endforeach;
                        ?>
                    <?php
                    }elseif ($data->filter->searched)
                    {
                        ?>
                        <tr><td>
                        <?php echo RM_UI_Strings::get('MSG_NO_SUBMISSION_MATCHED'); ?>
                            </td></tr>
                            <?php } else
                            {
                                ?>
                        <tr><td>
        <?php echo RM_UI_Strings::get('MSG_NO_SUBMISSION_SUB_MAN_INTERVAL'); ?>
                            </td></tr>
        <?php }
        ?>
                </table>
                
            </form>
    <?php include RM_ADMIN_DIR.'views/template_rm_submission_legends.php'; ?>
        </div>
        <?php
        echo $data->filter->render_pagination();
    }else
    {
        ?><div class="rmnotice-container">
            <div class="rmnotice">
        <?php echo RM_UI_Strings::get('MSG_NO_SUBMISSION_SUB_MAN'); ?>
            </div>
        </div>
    <?php
}
?>

    <?php     
    $rm_promo_banner_title = "Unlock export submissions and more by upgrading";
    include RM_ADMIN_DIR.'views/template_rm_promo_banner_bottom.php';
    ?>
</div>







