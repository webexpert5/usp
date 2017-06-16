<?php
foreach ($payments as $payment) {
    ?><div class="rm-submission-card rm-white-box">
        <div class="rm-transaction-title rm-pad-10 rm-grey-box dbfl"><?php echo $payment->form_name; ?></div>
        <div class="rm-transaction-card-content rm-pad-10 dbfl">
            <!----<div class="rm-submission-icon difl">
             <img src="<?php echo RM_IMG_URL; ?>submission-clock.png">
             </div>---->
            <div class="rm-transaction-details rm-pad-10 dbfl"><b>Amount</b><br/><?php echo $payment->total_amount; ?><span class="rm_txn_status rm-transaction-<?php echo $payment->status; ?> difr rm-rounded-corners"><?php echo $payment->status; ?></span></div>
            <div class="rm-transaction-details rm-pad-10 dbfl"><b><?php echo RM_UI_Strings::get('LABEL_DATE_OF_PAYMENT'); ?></b><br/><?php echo $payment->posted_date; ?></div>
            <div class="rm-transaction-details rm-pad-10 dbfl"><b><?php echo RM_UI_Strings::get('LABEL_INVOICE'); ?></b><br/><?php echo $payment->invoice; ?></div>
            <div class="rm-transaction-details rm-pad-10 dbfl"><b><?php echo RM_UI_Strings::get('LABEL_TAXATION_ID'); ?></b><br/><?php echo $payment->txn_id; ?></div>
        </div>
    </div>
<?php } ?>
