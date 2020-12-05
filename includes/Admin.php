<?php


namespace Amazon\Affiliate;

/**
 * Backend handler Class Admin
 *
 * @package Amazon\Affiliate
 */
class Admin {

    /**
     * Admin  constructor.
     */
    public function __construct() {
        $setting            = new Admin\Setting();
        $Productsearch      = new Admin\Productsearch();
        $ImportProducts     = new Admin\ImportProducts();
        $dashboard          = new Admin\Dashboard();
        $license_activation = new Admin\LicenseActivation();

        new Admin\Menu( $setting, $Productsearch, $dashboard );

        $this->dispatch_actions( $setting, $Productsearch, $ImportProducts, $dashboard, $license_activation );
    }

    /**
     * From action handler
     *
     * @param $setting
     */
    public function dispatch_actions( $setting, $Productsearch, $ImportProducts, $dashboard, $license_activation ) {
        //From action handler.
        add_action( 'admin_post_ams_wc_amazon_general_setting', array( $setting, 'general_amazon_setting_handler' ) );
        add_action( 'admin_post_ams-wc-general-setting', array( $setting, 'general_setting' ) );

        //Ajax action handler
        add_action( 'wp_ajax_search_products', array( $Productsearch, 'search_products' ) );
        add_action( 'wp_ajax_ams_product_import', array( $ImportProducts, 'product_import' ) );
        add_action( 'wp_ajax_ams_dashboard_info', array( $dashboard, 'dashboard_info' ) );
        add_action( 'wp_ajax_ams_license_activation', array( $license_activation, 'license_activation' ) );
        add_action( 'wp_ajax_ams_test_api', array( $setting, 'test_api' ) );
    }
}