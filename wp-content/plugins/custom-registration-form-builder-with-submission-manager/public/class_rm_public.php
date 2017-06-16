<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://registration_magic.com
 * @since      1.0.0
 *
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/public
 * @author     CMSHelplive
 */
class RM_Public {

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

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    private static $editor_counter = 1;

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
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     * 
     */
    public function enqueue_styles() {
        $settings = new RM_Options;
        $theme = $settings->get_value_of('theme');
        $layout = $settings->get_value_of('form_layout');

        switch ($theme) {
            case 'classic' :
                if ($layout == 'label_top')
                    wp_enqueue_style('rm_theme_classic_label_top', plugin_dir_url(__FILE__) . 'css/theme_rm_classic_label_top.css', array(), $this->version, 'all');
                elseif ($layout == 'two_columns')
                    wp_enqueue_style('rm_theme_classic_two_columns', plugin_dir_url(__FILE__) . 'css/theme_rm_classic_two_columns.css', array(), $this->version, 'all');
                else
                    wp_enqueue_style('rm_theme_classic', plugin_dir_url(__FILE__) . 'css/theme_rm_classic.css', array(), $this->version, 'all');
                break;

            /* case 'blue' :
              if ($layout == 'label_top')
              wp_enqueue_style('rm_theme_blue_label_top', plugin_dir_url(__FILE__) . 'css/theme_rm_blue_label_top.css', array(), $this->version, 'all');
              elseif ($layout == 'two_columns')
              wp_enqueue_style('rm_theme_blue_two_columns', plugin_dir_url(__FILE__) . 'css/theme_rm_blue_two_columns.css', array(), $this->version, 'all');
              else
              wp_enqueue_style('rm_theme_blue', plugin_dir_url(__FILE__) . 'css/theme_rm_blue.css', array(), $this->version, 'all');
              break; */

            default :
                if ($layout == 'label_top')
                    wp_enqueue_style('rm_theme_matchmytheme_label_top', plugin_dir_url(__FILE__) . 'css/theme_rm_matchmytheme_label_top.css', array(), $this->version, 'all');
                else
                    wp_enqueue_style('rm_theme_matchmytheme', plugin_dir_url(__FILE__) . 'css/theme_rm_matchmytheme.css', array(), $this->version, 'all');
                break;
        }
        //wp_enqueue_style('rm-jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css', false, $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/style_rm_front_end.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/script_rm_front.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-tabs', 'jquery-ui-datepicker','jquery-effects-core','jquery-effects-slide'), $this->version, false);
        wp_localize_script( $this->plugin_name, 'rm_ajax', array("url"=>admin_url('admin-ajax.php')) );
    }

    public function run_controller($attributes = null, $content = null, $shortcode = null) {
        return $this->controller->run();
    }

    public function rm_front_submissions() {
        if (RM_Utilities::fatal_errors()) {
            ob_start();
            include_once RM_ADMIN_DIR . 'views/template_rm_cant_continue.php';
            $html = ob_get_clean();
            return $html;
        }

        $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');

        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_front_submissions', true);

        $params = array('request' => $request, 'xml_loader' => $xml_loader);
        $this->controller = new RM_Main_Controller($params);
        return $this->controller->run();
    }

    public function rm_login($name) {
        if (RM_Utilities::fatal_errors()) {
            ob_start();
            include_once RM_ADMIN_DIR . 'views/template_rm_cant_continue.php';
            $html = ob_get_clean();
            return $html;
        }

        $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');

        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_login_form', true);

        $params = array('request' => $request, 'xml_loader' => $xml_loader);
        $this->controller = new RM_Main_Controller($params);
        return $this->controller->run();
    }

    public function rm_user_form_render($attribute) {
        if (RM_Utilities::fatal_errors()) {
            ob_start();
            include_once RM_ADMIN_DIR . 'views/template_rm_cant_continue.php';
            $html = ob_get_clean();
            return $html;
        }
        $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');

        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_user_form_process', true);
        
        $params = array('request' => $request, 'xml_loader' => $xml_loader, 'form_id' => isset($attribute['id']) ? $attribute['id'] : null);
        
        if(isset($attribute['force_enable_multiform']))
            $params['force_enable_multiform'] = true;
        
/*        if(isset($attribute['prefill_form']))
            $request->setReqSlug('rm_user_form_edit_sub', true);*/
        
        $this->controller = new RM_Main_Controller($params);
        return $this->controller->run();
    }

    public function register_otp_widget() {
        register_widget('RM_OTP_Widget');
    }

    /* function add_field_invites()
      {
      $screen = get_current_screen();

      if($screen->base=='registrations_page_rm_form_add')
      {   if(self::$editor_counter==3) {
      $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');

      $request = new RM_Request($xml_loader);
      $request->setReqSlug('rm_editor_actions_add_email', true);

      $params = array('request' => $request, 'xml_loader' => $xml_loader);
      $this->controller = new RM_Main_Controller($params);
      $this->controller->run();
      }
      self::$editor_counter= self::$editor_counter +1;
      }

      } */

    function execute_login() {
        $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');

        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_login_form', true);

        $params = array('request' => $request, 'xml_loader' => $xml_loader);
        $this->controller = new RM_Main_Controller($params);
        return $this->controller->run();
    }

    public function cron() {
        RM_DBManager::delete_front_user(1, 'h');
    }


    public function do_shortcode($content, $ignore_html = false) {
        return do_shortcode($content, $ignore_html);
    }

    public function floating_action() {
        $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');

        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_front_fab', true);

        $params = array('request' => $request, 'xml_loader' => $xml_loader);
        $this->controller = new RM_Main_Controller($params);
        return $this->controller->run();
        
    }
    
    public function render_embed() {
        die;
    }


}
