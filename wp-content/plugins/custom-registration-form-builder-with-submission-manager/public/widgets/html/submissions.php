<?php
foreach ($submissions as $index => $submission) {
    $submission_id= $submission->submission_id; 
    ?>
    <div class="rm-submission-card rm-white-box rm-rounded-corners">
                        <div class="rm-submission-card-title dbfl rm-accent-bg"><a href="<?php echo add_query_arg( 'submission_id',$submission_id, get_permalink(get_option('rm_option_front_sub_page_id'))); ?>"><?php echo $submission->form_name; ?> </a></div>
                        <div class="rm-submission-card-content dbfl">
                            <div class="rm-submission-icon difl">
                                <img src="<?php echo RM_IMG_URL; ?>submission-clock.png">
                            </div>
                            <div class="rm-submission-details difl"><b><?php echo RM_UI_Strings::get('LABEL_SUBMITTED_ON'); ?></b><br/><?php echo $submission->submitted_on; ?></div>
                        </div>
                    </div>
<?php } ?>
