<?php


namespace Amazon\Affiliate\Admin;

/**
 * Dashboard handler class
 *
 * @package Amazon\Affiliate\Admin
 */
class Dashboard {

    /**
     * Dashboard page load
     */
    public function dashboard_page() {
        $products_info = ams_get_all_products_info();
        $template = __DIR__ . '/views/dashboard.php';

        if ( file_exists( $template ) ) {
            require_once $template;
        }
    }

    /**
     * In This function who is sending the dashboard information
     */
    public function dashboard_info() {
        // Check for nonce security.
        $nonce = $_POST['nonce_ams_dashboard_info'];

        if ( ! wp_verify_nonce( $nonce, 'ams_dashboard_info' ) ) {
            die( 'Busted!' );
        }

        $products_info = ams_get_all_products_info();

        $info = array(
            'products_count'                  => $products_info['products_count'],
            'total_view_count'                => $products_info['total_view_count'],
            'total_product_added_to_cart'     => $products_info['total_product_added_to_cart'],
            'total_product_direct_redirected' => $products_info['total_product_direct_redirected'],
            'products_search_count'           => $products_info['products_search_count'],
        );

        $json_info = wp_json_encode( $info );
        echo $json_info;
        wp_die();
    }
}
