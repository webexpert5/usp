<div id="rm_otp_login">

    <div class="dbfl rm-white-box rm-rounded-corners">

        <!--Block to display if email is not entered -->
        <div id="rm_otp_enter_email">
            <div class="rm-login-panel-user-image dbfl">
                <img class="dbfl rm-placeholder-user-image" src="<?php echo RM_IMG_URL; ?>placeholder-pic.png">
            </div>
            <div class="dbfl rm-login-panel-fields">
                <input type="text" placeholder="<?php _e('Email or User Name', 'custom-registration-form-builder-with-submission-manager'); ?>" value="" id="rm_otp_econtact" name="<?php echo wp_generate_password(5, false, false); ?>"
                       onkeypress="return rm_call_otp(event, 'rm-floating-page-login')" maxlength="50" class="difl rm-rounded-corners rm-grey-box"/>
                <input type="hidden" id="rm_username" value="">
                <button class="difl rm-rounded-corners rm-accent-bg rm-button" id="rm-panel-login" onclick="rm_call_otp(event, 'rm-floating-page-login', 'submit')"><?php echo RM_UI_Strings::get('LABEL_NEXT'); ?></button>
            </div>
        </div>
        
        <!-- Block to enter OTP Code-->
        <div id="rm_otp_enter_otp" style="display:none" class="rm_otp_after_email">
            <div class="rm-login-goback_img dbfl">
                <img onclick="rm_otp_go_back()" class="" src="<?php echo RM_IMG_URL; ?>left-arrow.png">
            </div>
            <div class="rm-login-panel-user-image dbfl">
                <img class="dbfl rm-placeholder-user-image" src="<?php echo RM_IMG_URL; ?>user-icon-blue.jpg">
            </div>
            <div class="dbfl rm-login-panel-fields">

                <input type="text" value="" placeholder="<?php _e('OTP', 'custom-registration-form-builder-with-submission-manager'); ?>" maxlength="50" name="<?php echo wp_generate_password(5, false, false); ?>" id="rm_otp_kcontact" class="difl rm-rounded-corners rm-grey-box" onkeypress="return rm_call_otp(event, 'rm-floating-page-login')"/>

                <button class="difl rm-rounded-corners rm-accent-bg rm-button" id="rm-panel-login" onclick="rm_call_otp(event, 'rm-floating-page-login', 'submit')"><?php echo RM_UI_Strings::get('LABEL_LOGIN'); ?></button>
            </div>
        </div>
        
        <!-- Block to enter User Password -->
        <div id="rm_otp_enter_password" style="display:none" class="rm_otp_after_email">
            <div class="rm-login-goback_img dbfl">
                <img onclick="rm_otp_go_back()" class="" src="<?php echo RM_IMG_URL; ?>left-arrow.png">
            </div>
            <div class="rm-login-panel-user-image dbfl">
                <img class="dbfl rm-placeholder-user-image" src="<?php echo RM_IMG_URL; ?>user-icon-blue.jpg">
            </div>
            <div class="dbfl rm-login-panel-fields">

                <input type="password" value="" placeholder="<?php _e('Password', 'custom-registration-form-builder-with-submission-manager'); ?>" maxlength="50" name="<?php echo wp_generate_password(5, false, false); ?>" id="rm_otp_kcontact" class="difl rm-rounded-corners rm-grey-box" onkeypress="return rm_call_otp(event, 'rm-floating-page-login')"/>
                            
                <button class="difl rm-rounded-corners rm-accent-bg rm-button" id="rm-panel-login" onclick="rm_call_otp(event, 'rm-floating-page-login', 'submit')"><?php echo RM_UI_Strings::get('LABEL_LOGIN'); ?></button>
                
                <div id="rm_rememberme_cb" class="dbfl"><div class="difl rm_cb"><input style="width:auto" type="checkbox" id="rm_rememberme" value="yes"><?php echo RM_UI_Strings::get('LABEL_REMEMBER'); ?></div><div class="difl rm_link"><a href="<?php echo wp_lostpassword_url(); ?>" target="blank"><?php echo RM_UI_Strings::get('MSG_LOST_PASS'); ?></a></div></div>    
            </div>
        </div>
    </div>
    
    <input type="hidden" value="<?php echo wp_generate_password(8, false); ?>" name="security_key"/>
    <div class="rm_f_notifications">
        <span class="rm_f_error"></span>
        <span class="rm_f_success"></span> 
    </div>
</div>
<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
    function rm_otp_go_back(){
        jQuery("." + "rm-floating-page-login" + " #rm_otp_login " + "#rm_otp_enter_email").show('slide',{direction: 'right'},100);
        jQuery("." + "rm-floating-page-login" + " #rm_otp_login " + ".rm_otp_after_email").hide('slide',{direction: 'right'},1000);
    }
</script></pre>

