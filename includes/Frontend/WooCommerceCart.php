<?php


namespace Amazon\Affiliate\Frontend;

/**
 * Class WooCommerceCart
 *
 * @package Amazon\Affiliate\Frontend
 */
class WooCommerceCart {

    /**
     * WooCommerceCart constructor.
     */
    public function __construct() {
        add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'custom_add_cart' ) );
        add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'shop_add_to_cart_direct_details_page' ), 10, 2 );

        add_action( 'woocommerce_add_to_cart', array( $this, 'single_add_to_cart_direct_details_page' ), 10, 2 );
        add_action( 'woocommerce_before_single_product', array( $this, 'visitor_record' ) );
        add_action( 'woocommerce_checkout_init', array($this, 'woocommerce_external_checkout'), 10 );
        add_action( 'admin_post_cart_redirected_count', 'my_handle_form_submit' );
        add_action( 'admin_post_nopriv_cart_redirected_count', 'my_handle_form_submit' );
    }

    /**
     * WooCommerce Singe page Add Cart text Change
     *
     * @return bool|mixed|void
     */
    public function custom_add_cart() {
        $btn_text = get_option( 'ams_buy_now_label' );
        return $btn_text;
    }

    /**
     * Products visitor record.
     */
    public function visitor_record() {
        global $product;

        $post_id   = $product->get_id();
        $count_key = 'ams_product_views_count';
        $count     = get_post_meta( $post_id, $count_key, true );
        $count++;
        update_post_meta( $post_id, $count_key, $count );
    }

    /**
     * Shop page customize or set affiliate link
     * @param $product
     * @param $args
     * @return string
     */
    public function shop_add_to_cart_direct_details_page( $product, $args ) {
        global $product;

        $product_id         = $product->get_id();
        $btn_text           = esc_html( get_option( 'ams_buy_now_label' ) );
        $ams_access_key_id  = esc_attr( get_option( 'ams_access_key_id' ) );
        $ams_associate_tag  = esc_attr( get_option( 'ams_associate_tag' ) );
        $asin_id            = get_post_meta( $product_id, '_wca_amazon_affiliate_asin', true );
        $ams_amazon_country = esc_attr( get_option( 'ams_amazon_country' ) );
        $enable_no_follow   = esc_attr( get_option( 'ams_enable_no_follow_link' ) );
        $button_url         = admin_url( "admin-post.php?action=cart_redirected_count&id={$product_id}&url=" );

        if ( 'redirect' === get_option( 'ams_buy_action_btn' ) ) {
            $button_str = '<a rel="%s" href="%s//www.amazon.%s/dp/%s/?tag=%s" class="button ">%s</a>';
            $button = sprintf( $button_str, $enable_no_follow, $button_url, esc_attr( $ams_amazon_country ), esc_attr( $asin_id ), esc_attr( $ams_associate_tag ), $btn_text );
            return $button;
        } elseif ( 'cart_page' === get_option( 'ams_buy_action_btn' ) ) {
            $url = 'https://www.amazon.' . $ams_amazon_country . '/gp/aws/cart/add.html?';

            $arg = array(
                'AWSAccessKeyId' => $ams_access_key_id,
                'AssociateTag'   => $ams_associate_tag,
                'ASIN.1'         => $asin_id,
                'Quantity.1'     => 1,
            );

            $arg = http_build_query( $arg );

            $add_to_cart = $url . $arg;
            $button_str  = '<a rel="%s" href="%s%s" class="button ">%s</a>';
            $button      = sprintf( $button_str, $enable_no_follow, $button_url, esc_attr( urlencode( $add_to_cart ) ), esc_attr( $btn_text ) );
            return $button;
        } else {
            if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && !$product->is_sold_individually() ) {

                // Get the necessary classes.
                $class = implode( ' ', array_filter( array(
                    'button',
                    'product_type_' . $product->get_type(),
                    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                    $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
                ) ) );

                // Adding embeding <form> tag and the quantity field.
                $button_str = '<a rel="%s" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>';
                $button = sprintf( $button_str, $enable_no_follow, esc_url( $product->add_to_cart_url() ), esc_attr( isset( $quantity ) ? $quantity : 1 ), esc_attr( $product->get_id() ), esc_attr( $product->get_sku() ), esc_attr( isset( $class ) ? $class : 'button' ), esc_html( $product->add_to_cart_text() ) );
                return $button;
            }
        }
    }

    /**
     * Woocommerce singe page cart link customize
     * @param $cart_item_data
     * @param $product_id
     */
    public function single_add_to_cart_direct_details_page( $cart_item_data, $product_id ) {

        /**
         * Get all basic info
         */
        $ams_access_key_id  = esc_attr( get_option( 'ams_access_key_id' ) );
        $ams_associate_tag  = esc_attr( get_option( 'ams_associate_tag' ) );
        $ams_amazon_country = esc_attr( get_option( 'ams_amazon_country' ) );
        $asin_id            = get_post_meta( $product_id, '_wca_amazon_affiliate_asin', true );

        if ( 'redirect' === get_option( 'ams_buy_action_btn' ) ) {
            $link = sprintf( '//www.amazon.%s/dp/%s/?tag=%s', $ams_amazon_country, $asin_id, $ams_associate_tag );

            $count_key = 'ams_product_direct_redirected';
            $count = get_post_meta( $product_id, $count_key, true );
            $count++;
            update_post_meta( $product_id, $count_key, $count );

            wp_redirect( $link );

            die();

        } elseif ( 'cart_page' === get_option( 'ams_buy_action_btn' ) ) {
            $url = 'https://www.amazon.' . $ams_amazon_country . '/gp/aws/cart/add.html?';

            $arg = array(
                'AWSAccessKeyId' => $ams_access_key_id,
                'AssociateTag'   => $ams_associate_tag,
                'ASIN.1'         => $asin_id,
                'Quantity.1'     => 1,
            );

            $count_key = 'ams_product_added_to_cart';
            $count = get_post_meta( $product_id, $count_key, true );
            $count++;
            update_post_meta( $product_id, $count_key, $count );

            $arg = http_build_query( $arg );
            $add_to_cart = $url . $arg;
            wp_redirect( $add_to_cart );
            die();
        }
    }

    /**
     * Checkout page redirect amazon cart page
     */
    public function woocommerce_external_checkout() {
        $checkout_loading = sprintf( '<img src="%s" alt="">', AMS_PLUGIN_URL . 'assets/images/checkout_loading.gif');
        echo $checkout_loading;
        $ams_access_key_id = esc_attr( get_option( 'ams_access_key_id' ) );
        $ams_associate_tag = esc_attr( get_option( 'ams_associate_tag' ) );
        $ams_amazon_country = esc_attr( get_option( 'ams_amazon_country' ) );
        $url = 'https://www.amazon.' . $ams_amazon_country . '/gp/aws/cart/add.html';

        $html = '';
        $html .= sprintf( '<form id="ams-redirect" method="GET" action="%s">', $url );
        $html .= sprintf( '<input type="hidden" name="AWSAccessKeyId" value="%s" />', esc_attr( $ams_access_key_id ) );
        $html .= sprintf( '<input type="hidden" name="AssociateTag" value="%s" />', esc_attr( $ams_associate_tag ) );

        $count = 1;

        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $product_id = $cart_item['product_id'];
            $quantity   = $cart_item['quantity'];
            $asin_id    = get_post_meta( $product_id, '_wca_amazon_affiliate_asin', true );

            $html .= sprintf( '<input type="hidden" name="%s" value="%s" />', "ASIN.{$count}", $asin_id );
            $html .= sprintf( '<input type="hidden" name="%s" value="%s" />', "Quantity.{$count}", $quantity );

            $this->total_count_products_add_to_cart( $product_id );

            $this->woo_cart_delete_amazon_products( $product_id );

            $count++;
        }

        $redirected_seconds = get_option( 'ams_checkout_redirected_seconds' );
        $checkout_mass_redirected = get_option( 'ams_checkout_mass_redirected' );
        $html .= sprintf( '<p>%s</p>', $checkout_mass_redirected );
        $html .= '</form>';

        // The second dynamic cannot be sent via wp_localize_script that is why the JavaScript codes are written here.
        ob_start();
        ?>
        <script type="text/javascript">
            setTimeout( function() {
                document.getElementById("ams-redirect").submit();
            }, <?php echo $redirected_seconds * 1000; ?> );
        </script>
        <?php
        $html .= ob_get_contents();
        ob_clean();

        echo $html;
        exit();
        return true;
    }

    /**
     * Delete amazon products from cart
     */
    public function woo_cart_delete_amazon_products( $product_id ) {
        $product_cart_id = WC()->cart->generate_cart_id( $product_id );
        $cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );

        if ( $cart_item_key ) {
            WC()->cart->remove_cart_item( $cart_item_key );
        }
    }

    /**
     * Total count products add to cart
     */
    public function total_count_products_add_to_cart( $product_id ) {
        $count_key     = 'ams_product_added_to_cart';
        $product_count = get_post_meta( $product_id, $count_key, true );
        $product_count++;

        update_post_meta( $product_id, $count_key, $product_count );
    }
}