<?php


namespace Amazon\Affiliate\Admin;

/**
 * Class LicenseActivation
 *
 * @package Amazon\Affiliate\Admin
 */
class LicenseActivation {

    /**
     * License activation handler
     */
    public function license_activation() {
        $url             = $_SERVER['HTTP_HOST'];
        $purchase_code   = sanitize_text_field( $_POST['purchase_code'] );
        $response        = wp_remote_get( "https://api.affiliateproamazon.com/envato?product_code={$purchase_code}&url={$url}" );
        $response_body   = wp_remote_retrieve_body( $response );
        $response_decode = json_decode( $response_body );

        $info = array();

        if ( strtolower( $response_decode->status ) === strtolower( 'success' ) ) {

            update_option( 'ams_activated_status', 'success' );
            update_option( 'ams_activated_license', $purchase_code );

            $info['license_status'] = '<span class="wca-success">Activated</span>';
            $info['massage']        = "<p class='wca-success'> $response_decode->massage </p>";
        } else {
            update_option( 'ams_activated_status', 'failed' );

            $info['license_status'] = '<span class="wca-warning">Not Activated</span>';
            $info['massage']        = "<p class='wca-warning'> $response_decode->massage </p>";
        }

        wp_send_json( $info );
        wp_die();
    }
}