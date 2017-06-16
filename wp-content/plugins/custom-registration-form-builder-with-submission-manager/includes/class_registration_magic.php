<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://registration_magic.com
 *
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/includes
 * @author     CMSHelplive
 */
class Registration_Magic
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @access   protected
     * @var      RM_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @access   protected
     * @var      string    $registraion_magic    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * The controller of this plugin.
     *
     * @access   private
     * @var      string    $controller    The main controller of this plugin.
     */
    protected $controller;

    /**
     * The xml_loader of this plugin.
     *
     * @access   private
     * @var      string    $xml_loader    The xml loader of this plugin.
     */
    protected $xml_loader;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     */
    public function __construct()
    {
        $this->plugin_name = 'RegistrationMagic';
        $this->version = RM_PLUGIN_VERSION;
        $this->loader = new RM_Loader();
        $this->set_locale();
        $this->define_global_hooks();

        $this->xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');

        $request = new RM_Request($this->xml_loader);
        $params = array('request' => $request, 'xml_loader' => $this->xml_loader);
        $this->controller = new RM_Main_Controller($params);
        $this->define_public_hooks();
        $this->define_admin_hooks();
        $this->add_ob_start($request->req['rm_slug']);
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @access   private
     */
    private function set_locale()
    {

        $rm_i18n = new RM_i18n();

        $this->loader->add_action('plugins_loaded', $rm_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @access   private
     */
    private function define_admin_hooks()
    {
        $rm_admin = new RM_Admin($this->get_plugin_name(), $this->get_version(), $this->get_controller());

        $this->loader->add_action('admin_enqueue_scripts', $rm_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $rm_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $rm_admin, 'add_menu');
        $this->loader->add_action('wp_ajax_rm_sort_form_fields', $this->controller, 'run');
        $this->loader->add_action('wp_ajax_rm_get_stats', $this->controller, 'run');
        $this->loader->add_action('wp_dashboard_setup', $rm_admin, 'add_dashboard_widget');
        $this->loader->add_action('edit_user_profile', $rm_admin, 'user_edit_page_widget');
        $this->loader->add_action('show_user_profile', $rm_admin, 'user_edit_page_widget');
        $this->loader->add_action('wp_ajax_rm_test_smtp_config', 'RM_Utilities', 'check_smtp');
        $this->loader->add_action('wp_ajax_rm_get_fields', 'RM_Map_MailChimp_Controller', 'get_mc_list_field');
        $this->loader->add_action('wp_ajax_rm_save_form_view_sett', 'RM_Form_Settings_Controller', 'view');
        $this->loader->add_action('wp_ajax_review_banner_handler', 'RM_Utilities', 'handle_rating_operations');
        $this->loader->add_action('wp_ajax_newsletter_sub_handler', 'RM_Utilities', 'disable_newsletter_banner');
        $this->loader->add_action('wp_ajax_set_default_form', 'RM_Utilities', 'set_default_form');
        $this->loader->add_action('wp_ajax_rm_activate_user', 'RM_Utilities', 'link_activate_user');
        $this->loader->add_action('wp_ajax_nopriv_rm_activate_user', 'RM_Utilities', 'link_activate_user');
        $this->loader->add_action('wp_ajax_import_first', 'RM_Services', 'import_form_first_ajax');
        $this->loader->add_filter('plugin_action_links', $this, 'add_plugin_link', 10, 5);
        $this->loader->add_action('media_buttons', $rm_admin, 'add_new_form_editor_button');
        $this->loader->add_action('media_buttons', $rm_admin, 'add_field_autoresponder');
        $this->loader->add_action('plugins_loaded', 'RM_Utilities', 'safe_login', 10);
        $this->loader->add_action('wp_ajax_rm_save_fab_settings', $this->controller, 'run');
        $this->loader->add_action('wp_ajax_import_data', 'RM_Services', 'import_form');
        $this->loader->add_action('wp_ajax_rm_admin_js_data', 'RM_Utilities', 'load_admin_js_data');
        $this->loader->add_action('wp_ajax_rm_add_default_form', 'RM_User_Services', 'add_default_form');
        $this->loader->add_action('wp_ajax_send_email_user_view', 'RM_User_Services', 'send_email_ajax');
        $this->loader->add_filter('admin_notices', $rm_admin, 'add_global_setting_notice', 10, 5); 
        $this->loader->add_action('wp_ajax_joyride_tour_update', 'RM_Utilities', 'update_tour_state_ajax');
        $this->loader->add_action('wp_ajax_remove_queue', $rm_admin, 'remove_queue');
   }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @access   private
     */
    private function define_public_hooks()
    {
        $rm_public = new RM_Public($this->get_plugin_name(), $this->get_version(), $this->get_controller());

        $this->loader->add_action('init', $rm_public, 'cron');
        $this->loader->add_action('wp_enqueue_scripts', $rm_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $rm_public, 'enqueue_scripts');

        //$this->loader->add_action('media_buttons', $rm_public, 'add_field_invites');
        $this->loader->add_shortcode('RM_Login', $rm_public, 'rm_login');
        $this->loader->add_shortcode('RM_Form', $rm_public, 'rm_user_form_render');
       // $this->loader->add_shortcode('RM_Users', $rm_public, 'rm_user_list');
        $this->loader->add_shortcode('RM_Front_Submissions', $rm_public, 'rm_front_submissions');
//        $this->loader->add_action('widgets_init', $rm_public, 'register_otp_widget');
        $this->loader->add_action('wp_ajax_nopriv_rm_set_otp', $this->controller, 'run');
        $this->loader->add_action('wp_ajax_registrationmagic_embedform', $rm_public, 'render_embed');
        $this->loader->add_action('wp_ajax_nopriv_registrationmagic_embedform', $rm_public, 'render_embed');
//for shortcodes in widgets.
        $this->loader->add_filter('widget_text', $rm_public, 'do_shortcode');
        //For legacy version
        $this->loader->add_shortcode('CRF_Login', $rm_public, 'rm_login');
        $this->loader->add_shortcode('CRF_Form', $rm_public, 'rm_user_form_render');
        $this->loader->add_shortcode('CRF_Submissions', $rm_public, 'rm_front_submissions');
        $this->loader->add_action('wp_footer', $rm_public, 'floating_action');
        $this->loader->add_action('wp_ajax_rm_toggle_form_option', $this->controller, 'run');
        //Ajax calls for Username checking
        $this->loader->add_action('wp_ajax_nopriv_rm_user_exists', $this->controller, 'run');
        $this->loader->add_action('wp_ajax_rm_js_data', 'RM_Utilities', 'load_js_data');
        $this->loader->add_action('wp_ajax_nopriv_rm_js_data', 'RM_Utilities', 'load_js_data');
        $this->loader->add_action('wp_ajax_rm_save_submit_label', 'RM_Utilities', 'save_submit_label');
        $this->loader->add_action('wp_ajax_nopriv_rm_load_front_users', $rm_public, 'rm_user_list');
        }

    /**
     * Register all the hooks common with both public and admin facing
     * functionality of the plugin
     *
     * @access   private
     */
    public function define_global_hooks()
    {
        $this->loader->add_filter('login_redirect', $this, 'after_login_redirect', 12, 3);
        $this->loader->add_filter('register_url', $this, 'rm_register_redirect', 12);
        $this->loader->add_action('wp_login', $this, 'prevent_deactivated_logins');
        $this->loader->add_filter('login_message', $this, 'login_notice');
        $this->loader->add_action('wpmu_new_blog', 'RM_Table_Tech', 'on_create_blog',10,6);
        $this->loader->add_filter('wpmu_drop_tables', 'RM_Table_Tech', 'on_delete_blog');
        $this->loader->add_filter('plugins_loaded', $this, 'run_onload_tasks');
        $this->loader->add_filter('wp_logout', $this, 'after_logout_redirect');

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    RM_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    public function get_controller()
    {
        return $this->controller;
    }

    public function start_session()
    {
        if (!session_id())
        {            
            $drake = new stdClass;
            $drake->status = 'OKAY';
            $drake->payload_data = '';
            
            $drake = apply_filters('rm_session_path_hook', $drake);
            
            if($drake->status == 'OKAY')
                session_start();
            elseif($drake->status == 'USE_CUSTOM')
            {
                session_save_path($drake->payload_data);
                session_start();
            }
            elseif($drake->status == 'ERROR')
            {
                global $regmagic_errors;
                $err_msg = sprintf(RM_UI_Strings::get('ERR_SESSION_DIR_NOT_WRITABLE'), session_save_path());
                $regmagic_errors[RM_ERR_ID_SESSION_PATH] = (object) array('msg' => $err_msg, 'should_cont' => false);
                return;
            }
            
        }
    }

    /**
     * Prevents the deactivated user form login
     *
     * @param   string      $user_login     login name of the user
     * @param   object      $user           WP_user object
     * @return boolean
     */
    public function prevent_deactivated_logins($user_login, $user = null)
    {
        if (!$user)
        {
            $user = get_user_by('login', $user_login);
        }
        if (!$user)
        {
            return false;
        }

        $is_disabled = (int) get_user_meta($user->ID, 'rm_user_status', true);

        if ($is_disabled == 1)
        {
            wp_clear_auth_cookie();

            $goto = site_url('wp-login.php', 'login');

            $goto = add_query_arg('is_disabled', '1', $goto);

            wp_redirect($goto);

            exit;
        }
    }

    /**
     * returns the message when deactivated user tries to login
     *
     * @param string $notice
     * @return string
     */
    public function login_notice($notice)
    {
        if (isset($_GET['is_disabled']) && $_GET['is_disabled'] === '1')
            $notice = '<div id="login_error"><strong>'.RM_UI_Strings::get('LABEL_ERROR').':</strong> ' . apply_filters('rm_login_notice', RM_UI_Strings::get ('ACCOUNT_NOT_ACTIVE_YET')) . '</div>';
        elseif(isset($_GET['is_reset']) && $_GET['is_reset'] === '1')
            $notice = '<p id="rm_login_error" class="message">' . apply_filters('rm_login_notice', RM_UI_Strings::get('LOGIN_AGAIN_AFTER_RESET')) . '</p>';
        return $notice;
    }

    public function after_login_redirect($redirect_to, $user)
    {
        global $user;
        $post_id = get_option('rm_option_post_submission_redirection_url');

        return RM_Utilities::after_login_redirect($user);
    }
    
    public function after_logout_redirect()
    {
        $post_id = get_option('rm_option_post_logout_redirection_page_id');
        if($post_id)
        {
            $url = get_permalink($post_id);
            if($url)
            {
                wp_redirect($url);
                exit;
            }
        }
    }

    public function rm_register_redirect($registration_redirect)
    {
        $post_id = get_option('rm_option_default_registration_url');
        if ($post_id != 0)
        {
            $url = home_url("?p=" . $post_id);
            return $url;
        }
        return $registration_redirect;
    }

    public function add_ob_start($slug)
    {
        $pass = array(
            'rm_login_form',
            'rm_attachment_download_all',
            'rm_submission_print_pdf',
            'rm_attachment_download',
            'rm_attachment_download_selected',
            'rm_submission_export',
            'rm_front_log_off',
            'rm_form_export'
        );

        if (in_array($slug, $pass))
            ob_start();

        // Incase facebook
        if (isset($_REQUEST['rm_target']) && $_REQUEST['rm_target'] == 'fbcb')
        {
            ob_start();
        }
    }
    
    //Add custom links on wp plugin listing page.
    public function add_plugin_link($actions, $plugin_file)
    {        
        if (RM_PLUGIN_BASENAME == $plugin_file)
        {
            $extra_menus = array('upgrade' => '<a class="rm-upgrade-menu-link" target="_blank" href="https://registrationmagic.com/comparison/">Upgrade</a>',
                           'settings' => '<a href="' . get_admin_url() . 'admin.php?page=rm_options_manage">Settings</a>',
                           'support' => '<a href="' . get_admin_url() . 'admin.php?page=rm_support_forum">Support</a>');
            
            $actions = $extra_menus + $actions;
        }

        return $actions;
    }
    
    public function on_upgrade_migrate() {
        global $rmbasic;
        
        if (!function_exists('is_plugin_active_for_network')) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }

        if (is_plugin_active_for_network($rmbasic)) 
            RM_Activator::migrate(true);
        else
            RM_Activator::migrate(false);

    }
     public function patch_dbversion_miss() {
        global $wpdb;
        
        $existing_rm_db_version = get_site_option('rm_option_db_version', false);
        $existing_plugin_version = get_site_option('rm_option_rm_version', false);
        $db_name = DB_NAME;
        $sub_table_name = $wpdb->prefix."rm_submissions";
        $field_table_name = $wpdb->prefix."rm_fields";
        
        if (($existing_rm_db_version == false && $existing_plugin_version) || floatval($existing_rm_db_version) < 4.4 )
        {      
            $test_query = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$field_table_name' AND COLUMN_NAME = 'page_no'";
            $result = $wpdb->get_results($test_query);
            if($result == NULL || count($result) == 0)
                update_site_option('rm_option_db_version', '4.0');
            else
            {
                $test_query = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$field_table_name' AND COLUMN_NAME = 'field_is_editable'";
                $result = $wpdb->get_results($test_query);
                if($result == NULL || count($result) == 0)
                    update_site_option('rm_option_db_version', '4.1');
                else
                {
                    update_site_option('rm_option_db_version', RM_DB_VERSION);
                }
            }
        }
    }
    public function run_onload_tasks(){
         $this->patch_dbversion_miss();
         $this->on_upgrade_migrate();
     }

}
