<?php
/**
 * Plugin Name: Woo File Dropzone
 * Description: It enhance woocommerce store to receive files.
 * Version: 1.1.6
 * Author: Murtaza Bhurgri
 * Author URI: http://gmbhurgri.com
 * Requires at least: 4.1
 * Tested up to: 4.8
 * Text Domain : woo-file-dropzone
 * @package WooFileDropzone
 * @category Core
 * @author Murtaza Bhurgri
 *
 */
defined('ABSPATH') or die('No script kiddies please!');
$uploads = wp_upload_dir();
define('WOO_FILE_DROPZONE_VERSION', '1.1.6');
define('WOO_FILE_DROPZONE_UPLOAD_CONTENT_DIR', $uploads['basedir'] . DIRECTORY_SEPARATOR);
define('WOO_FILE_DROPZONE_UPLOAD_FOLDER', 'woo-file-dropzone-uploads');
define('WOO_FILE_DROPZONE_UPLOAD_CONTENT_URL',
    $uploads['baseurl'] . DIRECTORY_SEPARATOR . WOO_FILE_DROPZONE_UPLOAD_FOLDER . DIRECTORY_SEPARATOR);
define('WOO_FILE_DROPZONE_TEXT_DOMAIN', 'woo-file-dropzone');
define('WOO_FILE_DROPZONE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WOO_FILE_DROPZONE_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * Check if classes contain WooFileDropzone
 * then check in directories For classes to load.
 */
spl_autoload_register(function ($className) {
    if (false !== strpos($className, 'WooFileDropzone')) {
        if (false !== strpos($className, 'Controller')) {
            $classesDir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR;
            require_once $classesDir . $className . '.php';

        } elseif (false !== strpos($className, 'Model')) {
            $classesDir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR;
            require_once $classesDir . $className . '.php';

        } elseif (false !== strpos($className, 'Helper')) {
            $classesDir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR;
            require_once $classesDir . $className . '.php';

        } elseif (false !== strpos($className, 'Lib')) {

            $classesDir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
            require_once $classesDir . $className . '.php';
        }
    }
});

/**
 * Check initialization first and then load admin scripts
 */
add_action('plugins_loaded', function () {
    if (current_user_can('manage_options')) {
        new Controller_AdminWooFileDropzone();
    }else{
        new Controller_UserWooFileDropzone();
    }
});


/**
 * Installation
 */
function wooFileDropzoneInstallPlugin()
{
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    global $wpdb;
    $wc_file_dropzone = $wpdb->prefix . 'woo_file_dropzone';
    if ($wpdb->get_var("show tables like '$wc_file_dropzone' ") != $wc_file_dropzone) {
        $sql = "CREATE TABLE $wc_file_dropzone (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `extra_fields` longtext,
                  `file_urls` longtext,
                  `product_id` int(11) unsigned DEFAULT NULL,
                  `order_id` int(11) unsigned DEFAULT NULL,
                  `order_status` varchar(50) DEFAULT NULL,
                  `session_id` varchar(300) DEFAULT NULL,
                  `session_expiry_time` varchar(300) DEFAULT NULL,
                  `variation_id` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `id_UNIQUE` (`id`)
                )ENGINE=MyISAM AUTO_INCREMENT=1  DEFAULT CHARSET=utf8";
        dbDelta($sql);
    }

    // Create directory for upload
    $uploads = wp_upload_dir();
    $directory = $uploads['basedir'] . DIRECTORY_SEPARATOR . 'woo-file-dropzone-uploads';
    if (!is_writable($directory)) {
        wp_mkdir_p($directory);
    }

    /**
     * Register Cron job for removing unnecessary files left on server
     */
    $timestamp = wp_next_scheduled('woo_file_dropzone_cron_for_garbage_collection');

    //if already not registered register it
    if ($timestamp == false) {
        wp_schedule_event(time(), 'daily', 'woo_file_dropzone_cron_for_garbage_collection');
    }
}

// hook for cron job
add_action('woo_file_dropzone_cron_for_garbage_collection', 'wooFileDropzoneCronJobForGarbageCollection');
function wooFileDropzoneCronJobForGarbageCollection()
{
    Helper_WooFileDropzone::cronGarbageCollection();
}

//Register activation hook
register_activation_hook(__FILE__, 'wooFileDropzoneInstallPlugin');


//Plugin deactivation hook
register_deactivation_hook(__FILE__, 'wooFileDropzoneUnInstallPlugin');
function wooFileDropzoneUnInstallPlugin()
{
    //Clear cron job
    wp_clear_scheduled_hook('woo_file_dropzone_cron_for_garbage_collection');
}
