<?php


namespace Amazon\Affiliate;

/**
 * Plugin Installer handler
 *
 * @package Amazon\Affiliate
 */
class Installer {

    /**
     * Initializes class
     *
     * @return void
     */
    public function run() {
        $this->add_version();
        $this->add_general();
    }

    /**
     * Store plugin version
     *
     * @return void
     */
    public function add_version() {
        $installed = get_option( 'ams_amazon_installed' );

        if ( ! $installed ) {
            update_option( 'ams_amazon_installed', time() );
        }

        update_option( 'ams_wc_version', AMS_VERSION );
    }

    /**
     * Create general setting
     *
     * @return void
     */
    public function add_general() {
        $products_search_count = get_option( 'wca_products_search_count' );

        update_option( 'ams_product_per_page', 10 );
        update_option( 'ams_amazon_country', 'com' );
        update_option( 'ams_enable_no_follow_link', 'nofollow' );
        update_option( 'ams_buy_action_btn', 'redirect' );
        update_option( 'ams_product_thumbnail_size', 'Large' );
        update_option( 'ams_checkout_mass_redirected', 'You will be redirected to complete your checkout!' );
        update_option( 'ams_checkout_redirected_seconds', 3 );

        if ( ! $products_search_count ) {
            update_option( 'wca_products_search_count', 0 );
        }
    }
}