<?php


namespace Amazon\Affiliate\Admin;


/**
 * Admin setting page and setting handler
 *
 * @package Amazon\Affiliate\Admin
 */
class Setting {

    /**
     * Setting page load
     */
    public function setting_page() {
        $action = '';

        if ( isset( $_GET['action'] ) ) {
            $action = $_GET['action'];
        }

        switch ( $action ) {
            case 'affiliates':
                $template = __DIR__ . '/views/setting-affiliate.php';

                if ( file_exists( $template ) ) {
                    require_once $template;
                }
                break;
            default:
                $template = __DIR__ . '/views/setting-general.php';
                if ( file_exists( $template ) ) {
                    require_once $template;
                }
        }
    }

    /**
     * Amazon setting form handler
     */
    public function general_amazon_setting_handler() {
        check_admin_referer( 'general_amazon_setting_nonce' );

        $access_key_id     = sanitize_text_field( $_POST['access_key_id'] );
        $secret_access_key = sanitize_text_field( $_POST['secret_access_key'] );
        $associate_tag     = sanitize_text_field( $_POST['ams_associate_tag'] );
        $country           = sanitize_text_field( $_POST['ams_amazon_country'] );

        update_option( 'ams_access_key_id', $access_key_id );
        update_option( 'ams_secret_access_key', $secret_access_key );
        update_option( 'ams_associate_tag', $associate_tag );
        update_option( 'ams_amazon_country', $country );

        wp_redirect( 'admin.php?page=wc-product-setting-page&action=affiliates' );
    }

    /**
     * General setting form handler
     */
    public function general_setting() {
        check_admin_referer( 'general_setting_nonce' );

        $product_per_page            = sanitize_text_field( $_POST['product_per_page'] );
        $buy_now_label               = sanitize_text_field ($_POST['buy_now_label'] );
        $buy_action_btn              = sanitize_text_field( $_POST['buy_action_btn'] );
        $enable_no_follow_link       = sanitize_text_field( $_POST['enable_no_follow_link'] );
        $ams_default_category        = sanitize_text_field( $_POST['ams_default_category'] );
        $checkout_mass_redirected    = sanitize_text_field( $_POST['checkout_mass_redirected'] );
        $checkout_redirected_seconds = sanitize_text_field( $_POST['checkout_redirected_seconds'] );
        $remote_amazon_images        = sanitize_text_field( $_POST['remote_amazon_images'] );
        $product_thumbnail_size      = sanitize_text_field( $_POST['product_thumbnail_size'] );

        update_option( 'ams_product_per_page', $product_per_page );
        update_option( 'ams_buy_now_label', $buy_now_label );
        update_option( 'ams_buy_action_btn', $buy_action_btn );
        update_option( 'ams_enable_no_follow_link', $enable_no_follow_link );
        update_option( 'ams_default_category', $ams_default_category );
        update_option( 'ams_checkout_mass_redirected', $checkout_mass_redirected );
        update_option( 'ams_checkout_redirected_seconds', $checkout_redirected_seconds );
        update_option( 'ams_remote_amazon_images', $remote_amazon_images );
        update_option( 'ams_product_thumbnail_size', $product_thumbnail_size );

        wp_redirect( 'admin.php?page=wc-product-setting-page' );

    }

    /**
     * Get option
     *
     * @param $name
     * @return string|void
     */
    public function get_option( $name ) {
        $option =  get_option( $name );

        return  $option;
    }

    /**
     * Get woocommerce categories list
     *
     * @return array
     */
    function get_wc_terms() {
        $categories = get_terms( array(
            'hide_empty' => false,
        ) );

        $cat = array();

        foreach ( $categories as $row ) {
            if ( 'product_cat' === $row->taxonomy ) {
                $cat[] = array(
                    'term_id'  => $row->term_id,
                    'name'  => $row->name,
                );
            }
        }

        return array_reverse( $cat );
    }

    /**
     * Select value check if select or not select
     *
     * @param $key
     * @param $value
     * @return string
     */
    public function selected_check( $key, $value ) {
        $option_value  = get_option( $key );
        if ( $option_value === $value ) {
            return 'selected';
        } else {
            return '';
        }
    }

    public function test_api() {
        $keyword = array( 'puppy poster','kitten poster','disney movies','Game of Thrones','kids' );
        $keyword_index = rand( 0, 5 );
        $keyword_key = $keyword[ $keyword_index ];
        $ams_product_per_page   = get_option( 'ams_product_per_page' );
        $locale      = get_option( 'ams_amazon_country' );
        $regions     = ams_get_amazon_regions();
        $marketplace = 'www.amazon.'. get_option( 'ams_amazon_country' );
        $serviceName = 'ProductAdvertisingAPI';
        $region      = $regions[ $locale ]['RegionCode'];
        $accessKey   = get_option( 'ams_access_key_id' );
        $secretKey   = get_option( 'ams_secret_access_key' );

        $payloadArr = array();
        $payloadArr['Keywords']    = $keyword_key;
        $payloadArr['Resources']   = array( 'CustomerReviews.Count', 'CustomerReviews.StarRating', 'Images.Primary.Small', 'Images.Primary.Medium', 'Images.Primary.Large', 'Images.Variants.Small', 'Images.Variants.Medium', 'Images.Variants.Large', 'ItemInfo.ByLineInfo', 'ItemInfo.ContentInfo', 'ItemInfo.ContentRating', 'ItemInfo.Classifications', 'ItemInfo.ExternalIds', 'ItemInfo.Features', 'ItemInfo.ManufactureInfo', 'ItemInfo.ProductInfo', 'ItemInfo.TechnicalInfo', 'ItemInfo.Title', 'ItemInfo.TradeInInfo', 'Offers.Listings.Availability.MaxOrderQuantity', 'Offers.Listings.Availability.Message', 'Offers.Listings.Availability.MinOrderQuantity', 'Offers.Listings.Availability.Type', 'Offers.Listings.Condition', 'Offers.Listings.Condition.SubCondition', 'Offers.Listings.DeliveryInfo.IsAmazonFulfilled', 'Offers.Listings.DeliveryInfo.IsFreeShippingEligible', 'Offers.Listings.DeliveryInfo.IsPrimeEligible', 'Offers.Listings.DeliveryInfo.ShippingCharges', 'Offers.Listings.IsBuyBoxWinner', 'Offers.Listings.LoyaltyPoints.Points', 'Offers.Listings.MerchantInfo', 'Offers.Listings.Price', 'Offers.Listings.ProgramEligibility.IsPrimeExclusive', 'Offers.Listings.ProgramEligibility.IsPrimePantry', 'Offers.Listings.Promotions', 'Offers.Listings.SavingBasis', 'Offers.Summaries.HighestPrice', 'Offers.Summaries.LowestPrice', 'Offers.Summaries.OfferCount', 'ParentASIN','SearchRefinements' );
        $payloadArr["ItemCount"]   = (int) $ams_product_per_page;
        $payloadArr["ItemPage"]    = 1;
        $payloadArr["SortBy"]      = 'Relevance';
        $payloadArr["SearchIndex"] = 'All';
        $payloadArr['PartnerTag']  = get_option( 'ams_associate_tag' );
        $payloadArr['PartnerType'] = 'Associates';
        $payloadArr['Marketplace'] = $marketplace;
        $payload                   = json_encode( $payloadArr );
        $host                      = $regions[ $locale ]['Host'];
        $uri_path                   = "/paapi5/searchitems";

        $api      = new  \Amazon\Affiliate\Api\Amazon_Product_Api( $accessKey, $secretKey, $region, $serviceName, $uri_path, $payload, $host, 'SearchItems' );
        $response = $api->do_request();

        if ( isset( $response->Errors ) ) {
            $massage = sprintf( '<div class="wca-warning">%s</div>', wp_json_encode( $response ) );
        } elseif ( isset( $response->SearchResult ) ) {
            $massage = sprintf( '<div class="wca-success">%s</div>', esc_html__( 'Your api connect successfully', 'ams-wc-amazon' ) );
        } else {
            $massage = sprintf( '<div class="wca-success">%s</div>', esc_html__( 'Api Connect problem', 'ams-wc-amazon' ) );
        }
        echo $massage;
        exit();
        return true;
    }
}