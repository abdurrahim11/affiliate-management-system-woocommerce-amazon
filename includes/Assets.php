<?php


namespace Amazon\Affiliate;

/**
 * Plugin Assets handler class
 *
 * @package Amazon\Affiliate
 */
class Assets {

    /**
     * Assets constructor.
     */
    function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'backend_register_assets' ) );
    }

    /**
     * Call backend js and css.
     */
    public function backend_register_assets( $screen ) {
        wp_enqueue_style( 'ams-amazon-backend', AMS_PLUGIN_URL . 'assets/css/backend.css', false, AMS_VERSION );
        wp_enqueue_script( 'ams-amazon-backend', AMS_PLUGIN_URL . 'assets/js/backend.js', array( 'jquery' ), AMS_VERSION,true );

        if ( strtolower( 'toplevel_page_wc-amazon-affiliate' ) == strtolower( $screen ) ) {
            $ams_dashboard = true;
        } else {
            $ams_dashboard = false;
        }

        wp_localize_script( 'ams-amazon-backend', 'amsbackend', array(
            'ajax_url'    => admin_url( 'admin-ajax.php' ),
            'check_nonce' => wp_create_nonce( 'ams_product_import' ),
            'ams_test_api' => wp_create_nonce( 'ams_test_api' ),
            'nonce_ams_dashboard_info' => wp_create_nonce( 'ams_dashboard_info' ),
            'ams_dashboard' => $ams_dashboard,
            'ams_assets' => AMS_PLUGIN_URL . 'assets/',
        ) );
    }

}