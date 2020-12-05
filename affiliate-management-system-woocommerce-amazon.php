<?php
/**
 * Plugin Name:       Affiliate Management System - WooCommerce Amazon
 * Plugin URI:        http://joydevs.com/
 * Description:       This Description
 * Version:           1.00
 * Author:            Abdur Rahim
 * Author URI:        https://joydevs.com/
 * License:           GPL v2 or later
 * Text Domain:       ams-wc-amazon
 * Domain Path:       /languages/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Here call vendor.
require_once __DIR__ . '/vendor/autoload.php';

// Check if WooCommerce is active.
if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    add_action( 'admin_notices', 'ams_woocommerce_missing' );
}

// If the license is not activated, it will show a notices to activate.
if ( ams_plugin_license_status()  === false ) {
    add_action( 'admin_notices', 'ams_plugin_license_active_massage' );
}

if ( ! class_exists( 'AmsWcAmazon' ) ) {

    /**
     * The main plugin class
     */
    final class AmsWcAmazon {

        /**
         * AmsWcAmazon constructor.
         */
        private function __construct() {
            $this->define_constants();

            register_activation_hook( __FILE__, array( $this, 'activate' ) );

            add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
            add_action( 'plugins_loaded', array( $this, 'plugins_loaded_text_domain' ) );
            add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
            add_action( 'admin_post_nopriv_cart_redirected_count', array( $this, 'cart_redirected_count' ) );
            add_action( 'admin_post_cart_redirected_count', array( $this, 'cart_redirected_count' ) );
        }

        /**
         * Initializes a single instance
         */
        public static function init() {
            static $instance = false;

            if ( ! $instance ) {
                $instance = new self();
            }

            return $instance;
        }

        /**
         * Plugin text domain loaded
         */
        public function plugins_loaded_text_domain() {
            load_plugin_textdomain( 'ams-wc-amazon', false, AMS_PLUGIN_PATH . 'languages/' );
        }

        /**
         * Define plugin path and url constants
         */
        public function define_constants() {
            define( 'AMS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
            define( 'AMS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
            define( 'AMS_VERSION', '1.00' );
        }

        /**
         *  Init plugin
         */
        public function init_plugin() {
            new \Amazon\Affiliate\Assets();

            if ( is_admin() ) {
                new \Amazon\Affiliate\Admin();
            } else {

                new \Amazon\Affiliate\Frontend();
            }
        }

        /**
         * It's count cart amazon redirect
         */
        public function cart_redirected_count() {
            if ( isset( $_GET['url'] ) ) {
                $post_id = sanitize_text_field( $_GET['id'] );
                $url = '';

                if ( 'redirect' === get_option( 'ams_buy_action_btn' ) ) {
                    $count_key = 'ams_product_direct_redirected';
                    $count = get_post_meta( $post_id, $count_key, true );
                    $count++;
                    update_post_meta( $post_id, $count_key, $count );
                    $url = sanitize_text_field( $_GET['url']  );
                } elseif ( 'cart_page' === get_option( 'ams_buy_action_btn' ) ) {
                    $count_key = 'ams_product_added_to_cart';
                    $count = get_post_meta( $post_id, $count_key, true );
                    $count++;
                    update_post_meta( $post_id, $count_key, $count );
                    $url = urldecode_deep( sanitize_text_field( $_GET['url'] ) );
                }

                wp_redirect( $url );
                exit();
            }
        }

        /**
         * Do Stuff Plugin activation
         */
        public function activate() {
            $installer = new \Amazon\Affiliate\Installer();
            $installer->run();
        }
    }

}

/**
 * Initializes the main plugin
 *
 * @return \AmsWcAmazon
 */
function ams_wc_amazon() {
    return AmsWcAmazon::init();
}

/**
 * Rick off the plugin
 */
ams_wc_amazon();
