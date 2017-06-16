

<div class="rm-features-list">
    <div class="rm-101">RegistrationMagic 101</div>
    <div class="rm-feature-banner">
        A quick lowdown on RegistrationMagic front end features and how to display them.<span class="rm-pro"><a target="_blank" href="http://registrationmagic.com/registrationmagic-silver-edition/"></a></span>

    </div>


    <div class="rm-features-table">
        <div class="rm-features-row">
            <div class="rm-feature-cell">
                <img src="<?php echo RM_IMG_URL; ?>help.png">
            </div>
            <div class="rm-feature-cell">Three simple ways to register users on your site.</div>
            <div class="rm-feature-cell">
                <img src="<?php echo RM_IMG_URL; ?>next.png">
            </div>

        </div>
        <div class="rm-feature-content">
            <img src="<?php echo RM_IMG_URL; ?>content-arrow.png" class="content-arrow">
            <ol>
                <li><strong>Using shortcode</strong></li>
                <p>Paste the form shortcode on the page or post where you want to display the form. RegistrationMagic form shortcodes are in this format <span class="rm-code">[RM_Form id='1']</span> and are found on form cards. <a target="_blank" href="https://registrationmagic.com/display-registration-forms-wordpress-site/">Read this tutorial</a> for a step by step guide.</p>
                <li><strong>Using Front End sliding panel</strong></li>
                <p>Turn on the Magic button on your site by going to <a target="_blank" href="admin.php?page=rm_options_general">Global Settings / General Settings</a>. Look for the toggle checkbox with label <span class="rm-code">Show Magic Pop-up Button, Menu and Panels</span> and turn it on.</p>
                <p>Make sure you have selected a form as your default registration form by clicking on the grey star over the form card. This form will display automatically inside the sliding panel.</p>
                <img src="<?php echo RM_IMG_URL; ?>floating-menu.jpg" class="content-asset">
                <img src="<?php echo RM_IMG_URL; ?>floating-button.jpg" class="content-asset">
                <li><strong>Embedding form on an external site</strong></li>
                <p>This method to display registration forms on WordPress site is for advanced users and therefore only available in Gold Bundle of RegistrationMagic. It works best when we want to show a RegistrationMagic form where there’s no option to paste the shortcode. For example – a site outside our WordPress site.</p>
                <p>Embed codes are located just below the short code.</p>
                <img src="<?php echo RM_IMG_URL; ?>shortcode-location-1024x537.jpg" class="content-asset">
            </ol>
        </div>
        <div class="rm-features-row">
            <div class="rm-feature-cell">
                <img src="<?php echo RM_IMG_URL; ?>help.png">
            </div>
            <div class="rm-feature-cell">How to insert a form into a post, page or widget?</div>
            <div class="rm-feature-cell">
                <img src="<?php echo RM_IMG_URL; ?>next.png">
            </div>

        </div>
        <div class="rm-feature-content">
            <img src="<?php echo RM_IMG_URL; ?>content-arrow.png" class="content-arrow">
            The easiest way to insert a form into content is to paste its shortcode where you want to display the form. Form shortcode is found on the form card. RegistrationMagic form shortcodes are in this format <span class="rm-code">[RM_Form id='1']</span>
            <img src="<?php echo RM_IMG_URL; ?>form-card-description.png" class="content-asset">
        </div>

        <div class="rm-features-row">
            <div class="rm-feature-cell">
                <img src="<?php echo RM_IMG_URL; ?>help.png">
            </div>
            <div class="rm-feature-cell">How to display a login box?</div>
            <div class="rm-feature-cell">
                <img src="<?php echo RM_IMG_URL; ?>next.png">
            </div>

        </div>

        <div class="rm-feature-content">
            <img src="<?php echo RM_IMG_URL; ?>content-arrow.png" class="content-arrow">
            Login system is built into RegistrationMagic. To display a login box on any page, post or widget use this code <span class="rm-code">[RM_Login]</span>. There's also a dedicated widget for temporary logins. You will find it inside widgets section, with title "RegistrationMagic OTP login".
            <img class="content-asset" src="<?php echo RM_IMG_URL; ?>login-box.png">
        </div>

        <div class="rm-features-row">
            <div class="rm-feature-cell">
                <img src="<?php echo RM_IMG_URL; ?>help.png">
            </div>
            <div class="rm-feature-cell">How to allow users to check their submissions?</div>
            <div class="rm-feature-cell">
                <img src="<?php echo RM_IMG_URL; ?>next.png">
            </div>

        </div>

        <div class="rm-feature-content">
            <img src="<?php echo RM_IMG_URL; ?>content-arrow.png" class="content-arrow">
            RegistrationMagic allows your website users to check the forms they have submitted by logging into the front end area. Shortcode for front-end submission viewing is <span class="rm-code">[RM_Front_Submissions]</span>. When the plugin is first installed a new page is automatically created with this shortcode pasted inside it.      
        </div>

        <div class="rm-features-row">
            <div class="rm-feature-cell">
                <img src="<?php echo RM_IMG_URL; ?>help.png">
            </div>
            <div class="rm-feature-cell">How to let users check transaction details and status.</div>
            <div class="rm-feature-cell">
                <img src="<?php echo RM_IMG_URL; ?>next.png">
            </div>

        </div>

        <div class="rm-feature-content">Front-end submission area also contains tab for transaction details. If you have set up paid registration forms, then this area will display to users with their transaction details including payments status as complete or refunded.</div>
    
<!--        <div class="rm-features-row">
            <div class="rm-feature-cell">
                <img src="<?php //echo RM_IMG_URL; ?>help.png">
            </div>
            <div class="rm-feature-cell">Easily display list of registered users <span class="rm-new-primer-entry">New</span></div>
            <div class="rm-feature-cell">
                <img src="<?php //echo RM_IMG_URL; ?>next.png">
            </div>

        </div>
        <div class="rm-feature-content">
            <img src="<?php //echo RM_IMG_URL; ?>content-arrow.png" class="content-arrow">
            Now you can display registered users' list on front end of your website! The shortcode format is <span class="rm-code">[RM_Users]</span>. This will display all the users on a page or post where you paste the shortcode. If you want to display only specific users, you can pass two more parameters to the shortcode - filter the list by registration form and group the users. The longer format is <span class="rm-code">[RM_Users form_id="id" timerange="year"]</span>. Where id should be replaced by ID for the RegistrationMagic form and timerange can be year, month, week or today. Of course, you can use any of the two depending on your needs. IDs of the form can be found in their respective form shortcodes.
            <img src="<?php //echo RM_IMG_URL; ?>user-profiles.jpg" class="content-asset">
        </div>-->
    
    </div>



</div>
<pre class='rm-pre-wrapper-for-script-tags'><script>
    jQuery(document).ready(function () {
        jQuery(".rm-features-row").click(function () {
            jQuery(this).next().filter(".rm-feature-content").slideToggle("fast");
        });
    });

</script></pre>
