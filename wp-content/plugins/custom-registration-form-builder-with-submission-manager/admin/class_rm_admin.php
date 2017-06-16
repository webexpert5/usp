<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://registration_magic.com
 * @since      1.0.0
 *
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/admin
 * @author     CMSHelplive
 */
class RM_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $registraion_magic    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The controller of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $controller    The main controller of this plugin.
     */
    private $controller;
    private static $editor_counter = 1;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name   The name of this plugin.
     * @param      string    $version       The version of this plugin.
     */
    public function __construct($plugin_name, $version, $controller) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->controller = $controller;
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_version() {
        return $this->version;
    }

    public function get_controller() {
        return $this->controller;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/style_rm_admin.css', array(), $this->version, 'all');
        wp_enqueue_style('rm_google_font', 'https://fonts.googleapis.com/css?family=Titillium+Web:400,600', array(), $this->version, 'all');
         wp_enqueue_style('rm_rating_style', plugin_dir_url(__FILE__) . 'js/rating3/rateit.css', array(), $this->version, 'all');
        //wp_enqueue_style('rm-jquery-ui','http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css',false,$this->version,'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script('rm-color', plugin_dir_url(__FILE__) . 'js/jscolor.min.js', array(), $this->version, false);
        wp_enqueue_script('rm-utilities', plugin_dir_url(__FILE__) . 'js/script_rm_utilities.js', array(), $this->version, false);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/script_rm_admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-tabs', 'jquery-ui-datepicker'), $this->version, false);
        wp_enqueue_script('google_charts', 'https://www.gstatic.com/charts/loader.js');
         wp_enqueue_script('rm-rating', plugin_dir_url(__FILE__) . 'js/rating3/jquery.rateit.js', array(), $this->version, false);
        
    }

    /**
     * Registers menu pages and submenu pages at the admin area.
     *
     * @since    1.0.0
     */
    public function add_menu() {
        if (current_user_can('manage_options'))
        {
            global  $rm_env_requirements;
            
            if(!RM_Utilities::fatal_errors())
            {
                global $submenu;

                add_menu_page(RM_UI_Strings::get('ADMIN_MENU_REG'), RM_UI_Strings::get('ADMIN_MENU_REG'), "manage_options", "rm_form_manage", array($this->get_controller(), 'run'), plugins_url('../images/profile-icon2.png', __FILE__), 26);
                //add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), RM_UI_Strings::get('ADMIN_MENU_NEWFORM'), "manage_options", "rm_form_add", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), "manage_options", "rm_form_sett_general", array($this->get_controller(), 'run'));

                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_SUBS'), RM_UI_Strings::get('ADMIN_MENU_SUBS'), "manage_options", "rm_submission_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_MNG_FIELDS_PT'), RM_UI_Strings::get('ADMIN_MENU_MNG_FIELDS_PT'), "manage_options", "rm_field_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), "manage_options", "rm_field_add", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_FORM_STATS'), RM_UI_Strings::get('ADMIN_MENU_FORM_STATS'), "manage_options", "rm_analytics_show_form", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_FIELD_STATS'), RM_UI_Strings::get('ADMIN_MENU_FIELD_STATS'), "manage_options", "rm_analytics_show_field", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_PRICE'), RM_UI_Strings::get('ADMIN_MENU_PRICE'), "manage_options", "rm_paypal_field_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_ADD_PP_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_PP_FIELD_PT'), "manage_options", "rm_paypal_field_add", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_PP_PROC_PT'), "", "manage_options", "rm_paypal_proc", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_ATTS'), RM_UI_Strings::get('ADMIN_MENU_ATTS'), "manage_options", "rm_attachment_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_ATT_DL_PT'), RM_UI_Strings::get('ADMIN_MENU_ATT_DL_PT'), "manage_options", "rm_attachment_download", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_PT'), RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_PT'), "manage_options", "rm_submission_view", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_RELATED'), RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_RELATED'), "manage_options", "rm_submission_related", array($this->get_controller(), 'run'));

                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_INV'), RM_UI_Strings::get('ADMIN_MENU_INV'), "manage_options", "rm_invitations_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), "manage_options", "rm_sent_emails_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), "manage_options", "rm_sent_emails_view", array($this->get_controller(), 'run'));

                //Sub menu for User role section 8th March 2016
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_USERS'), RM_UI_Strings::get('ADMIN_MENU_USERS'), "manage_options", "rm_user_manage", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_ROLES'), RM_UI_Strings::get('ADMIN_MENU_ROLES'), "manage_options", "rm_user_role_manage", array($this->get_controller(), 'run'));
                
                /* Option menues */
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_SETTINGS'), RM_UI_Strings::get('ADMIN_MENU_SETTINGS'), "manage_options", "rm_options_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_general", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_FAB_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_FAB_PT'), "manage_options", "rm_options_fab", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_AS_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_security", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_UA_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_UA_PT'), "manage_options", "rm_options_user", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_AR_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), "manage_options", "rm_options_autoresponder", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), "manage_options", "rm_options_thirdparty", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), "manage_options", "rm_options_payment", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_SAVE_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_SAVE_PT'), "manage_options", "rm_options_save", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_ADD_NOTE_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_NOTE_PT'), "manage_options", "rm_note_add", array($this->get_controller(), 'run'));

                /* End of settings */
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_IMPORT'), RM_UI_Strings::get('ADMIN_MENU_FS_IMPORT'), "manage_options", "rm_form_import", array($this->get_controller(), 'run'));
                
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_FRONTEND'), RM_UI_Strings::get('ADMIN_MENU_FRONTEND'), "manage_options", "rm_support_frontend", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_SUPPORT'), RM_UI_Strings::get('ADMIN_MENU_SUPPORT'), "manage_options", "rm_support_forum", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_USER_ROLE_DEL_PT'), RM_UI_Strings::get('ADMIN_MENU_USER_ROLE_DEL_PT'), "manage_options", "rm_user_role_delete", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_REG_PT'), RM_UI_Strings::get('ADMIN_MENU_REG_PT'), "manage_options", "rm_user_view", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_CC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_CC_PT'), "manage_options", "rm_form_sett_ccontact", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_AW_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_AW_PT'), "manage_options", "rm_form_sett_aweber", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_OV_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_OV_PT'), "manage_options", "rm_form_sett_override", array($this->get_controller(), 'run'));
                
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_AR_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_AR_PT'), "manage_options", "rm_form_sett_autoresponder", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_LIM_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_LIM_PT'), "manage_options", "rm_form_sett_limits", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_PST_SUB_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_PST_SUB_PT'), "manage_options", "rm_form_sett_post_sub", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_ACC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ACC_PT'), "manage_options", "rm_form_sett_accounts", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_VIEW_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_VIEW_PT'), "manage_options", "rm_form_sett_view", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_MC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_MC_PT'), "manage_options", "rm_form_sett_mailchimp", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_PT'), "manage_options", "rm_form_sett_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_ACTRL_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ACTRL_PT'), "manage_options", "rm_form_sett_access_control", array($this->get_controller(), 'run'));
                
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_PREMIUM'), "<div style='color:#ff6c6c;'>".RM_UI_Strings::get('ADMIN_MENU_PREMIUM')."</div>", "manage_options", "rm_support_premium_page", array($this->get_controller(), 'run'));
                $submenu['rm_form_manage'][0][0] = RM_UI_Strings::get('ADMIN_SUBMENU_REG');
            }
            else
            {
                add_menu_page(RM_UI_Strings::get('ADMIN_MENU_REG'), RM_UI_Strings::get('ADMIN_MENU_REG'), "manage_options", "rm_form_manage", array($this, 'fatal_error_message_display'), plugins_url('../images/profile-icon2.png', __FILE__), 26);
            }
        }
    }
    
    //To disaply errors on menu page. Such as SimplXML extension not available or PHP version.
    public function fatal_error_message_display()
    {        
        include_once RM_ADMIN_DIR.'views/template_rm_cant_continue.php';
    }

    public function add_dashboard_widget()
    {
        //Dashboard widget is for admin users only.
        if (current_user_can('manage_options'))
        {
            wp_add_dashboard_widget(
                    'rm_dashboard_widget_display', // Widget slug.
                    RM_UI_Strings::get('TITLE_DASHBOARD_WIDGET'), // Title.
                    array($this, 'dashboard_widget_display_function')
            );
        }
    }

    public function dashboard_widget_display_function() {
        $xml_loader = RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');

        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_dashboard_widget_display', true);

        $params = array('request' => $request, 'xml_loader' => $xml_loader);
        $this->controller = new RM_Main_Controller($params);
        $this->controller->run();
    }

    public function user_edit_page_widget($user) {
        $xml_loader = RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');

        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_user_widget', true);

        $params = array('request' => $request, 'xml_loader' => $xml_loader, 'user' => $user);
        $this->controller = new RM_Main_Controller($params);
        $this->controller->run();
    }

    function add_new_form_editor_button() {
        if (is_admin()) {
            $screen = get_current_screen();
            if ($screen->base == 'post') {
                $xml_loader = RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');

                $request = new RM_Request($xml_loader);
                $request->setReqSlug('rm_editor_actions_add_form', true);

                $params = array('request' => $request, 'xml_loader' => $xml_loader);
                $this->controller = new RM_Main_Controller($params);
                $this->controller->run();
            }
        }
    }

    function add_field_autoresponder() {
        if (is_admin()) {
            $screen = get_current_screen();
            if ($screen->base == 'admin_page_rm_form_sett_autoresponder') {
                if (self::$editor_counter == 1) {
                    $xml_loader = RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');

                    $request = new RM_Request($xml_loader);
                    $request->setReqSlug('rm_editor_actions_add_email', true);

                    $params = array('request' => $request, 'xml_loader' => $xml_loader);
                    $this->controller = new RM_Main_Controller($params);
                    $this->controller->run();
                }

                self::$editor_counter = self::$editor_counter + 1;
            } elseif ($screen->base == 'registrationmagic_page_rm_invitations_manage') {
                $xml_loader = RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');

                $request = new RM_Request($xml_loader);
                $request->setReqSlug('rm_editor_actions_add_fields_dropdown_invites', true);

                $params = array('request' => $request, 'xml_loader' => $xml_loader);
                $this->controller = new RM_Main_Controller($params);
                $this->controller->run();
            }
        }
    }
    
    public function add_global_setting_notice()
    {
        if(is_admin())
        {
            $screen = get_current_screen();//var_dump($screen->id);die;
            if($screen->id == 'registrationmagic_page_rm_options_manage')
            {
                ?>
                <div style="text-align:center;background-color:#ffffce;color:orange" class= "notice notice-info">
                  <p style="font-size:14px;">
                  <?php
                    echo __('Form specific settings can be found on form dashboard.','custom-registration-form-builder-with-submission-manager');
                   ?>
                  </p>
                </div>
                <?php 
            }
        }
    }
    
    public function remove_queue()
    {
        $inv_service = new RM_Invitations_Service;
        $form_id= $_POST['form_id'];
        
        $inv_service->remove_queue($form_id);
        
        wp_die();
    }
}
