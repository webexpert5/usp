<?php
/**
 * Plugin Name: WooCommerce Weight Based Shipping
 * Plugin URI: http://wordpress.org/plugins/weight-based-shipping-for-woocommerce/
 * Description: Simple yet flexible shipping method for WooCommerce.
 * Version: 4.2.3
 * Author: dangoodman
 * Author URI: http://tablerateshipping.com
 */

require_once(dirname(__FILE__).'/WBS_Loader.php');
WBS_Loader::loadWbs(__FILE__);