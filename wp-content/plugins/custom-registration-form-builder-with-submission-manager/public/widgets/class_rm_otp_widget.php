<?php
/**
 * Adds OTP widget.
 */
class RM_OTP_Widget extends WP_Widget
{
    /**
     * Register widget with WordPress.
     */
    
    function __construct()
    {
        parent::__construct(
            'rm_otp_widget', // Base ID
            __('RegistrationMagic OTP Login', ''), // Name
            array('description' => __('One Time Password login system for RegistrationMagic form submissions', ''),) // Args
        );
    }
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        
        $rm_public = new RM_front_service(null);
        // Show if user is not logged in
        if(!$rm_public->is_authorized() && !is_user_logged_in()){
            
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        ?>
       <form method="post" action="" onsubmit="return false" class="rm_otp_widget_form">
           <div id="rm_otp_login">
               <input type="text" placeholder="<?php _e('Email:',''); ?>" value="" id="rm_otp_econtact" name="<?php echo wp_generate_password(5, false,false); ?>"
                      onkeypress="return rm_call_otp(event,'rm_otp_widget_form')" maxlength="50"/>
               <input type="text" value="" placeholder="<?php _e('OTP:',''); ?>" maxlength="50" name="<?php echo wp_generate_password(5, false,false); ?>" id="rm_otp_kcontact" class="rm_otp_key" style="display:none" onkeypress="return rm_call_otp(event,'rm_otp_widget_form')"/>
               <input type="hidden" value="<?php echo wp_generate_password(8, false); ?>" name="security_key"/>
               <div class="rm_f_notifications">
                   <span class="rm_f_error"></span>
                   <span class="rm_f_success"></span>
               </div>
           </div>
            
       </form>
       
            <img id="rm_f_loading" style="display:none" src="<?php echo plugin_dir_url(dirname(dirname( __FILE__)) ) .'images/rm_f_ajax_loader_wide.gif'; ?>" alt="Loading" >
    
        <pre class='rm-pre-wrapper-for-script-tags'><script>var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";</script></pre>
        <?php }else{ 
		
		if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
		?>
            <div id="rm_f_sub_page">
                <?php
                    RM_Utilities::create_submission_page();
                ?>
                <a href="<?php echo get_permalink(get_option('rm_option_front_sub_page_id'));?>"><?php _e('View Submissions', '');?></a>
            </div>
        <?php }
        echo $args['after_widget'];
    }
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('RegistrationMagic OTP Login', '');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:',''); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
} // class Foo_Widget