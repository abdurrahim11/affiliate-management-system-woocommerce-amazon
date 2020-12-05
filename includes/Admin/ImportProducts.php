<?php


namespace Amazon\Affiliate\Admin;

/**
 * Product import handler class
 *
 * @package Amazon\Affiliate\Admin
 */
class ImportProducts {

    /**
     * Attach product images (feature/ gallery)
     *
     * @param $post_id thumbnail insert post
     * @param $url image url
     * @param $flag single or multipole insert
     */
    public function attach_product_thumbnail( $post_id, $url, $flag ) {
        $image_url  = $url;
        $url_array  = explode( '/', $url );
        $image_name = $url_array[ count( $url_array ) - 1 ];

        $result     = wp_remote_get( $image_url );
        $image_data = wp_remote_retrieve_body( $result );

        $upload_dir       = wp_upload_dir(); // Set upload folder.
        $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name.
        $filename         = basename( $unique_file_name ); // Create image file name.

        // Check folder permission and define file location.
        if( wp_mkdir_p( $upload_dir['path'] ) ) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }

        // You have to load this file.
        require_once( ABSPATH . 'wp-admin/includes/file.php' );

        global $wp_filesystem;

        WP_Filesystem(); // Initial WP file system
        $wp_filesystem->put_contents( $file, $image_data, 0644 ); // Finally, store the file.

        // Check image file type
        $wp_filetype = wp_check_filetype( $filename, null );

        // Set attachment data
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        );

        // Create the attachment.
        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

        // Include image.php
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        // Define attachment metadata.
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

        // Assign metadata to attachment
        wp_update_attachment_metadata( $attach_id, $attach_data );

        // Asign to feature image.
        if( $flag === 0 ) {
            // And finally assign featured image to post.
            set_post_thumbnail( $post_id, $attach_id );
        }

        // Assign to the product gallery.
        if( $flag === 1 ) {
            // Add gallery image to product.
            $attach_id_array = get_post_meta( $post_id,'_product_image_gallery', true ) ;
            $attach_id_array .= ',' . $attach_id;
            update_post_meta( $post_id, '_product_image_gallery', $attach_id_array );
        }
    }

    /**
     * Woocommerce product import function
     */
    public function product_import() {
        // Check for nonce security.
        $nonce = $_POST['nonce'];

        if ( ! wp_verify_nonce( $nonce, 'ams_product_import' ) ) {
            die ( 'Busted!' );
        }

        if ( ams_plugin_license_status()  === false ) {
            wp_die();
        }

        $asin = sanitize_text_field( $_POST['asin'] );

        $locale       = get_option( 'ams_amazon_country' );
        $regions      = ams_get_amazon_regions();
        $marketplace  = 'www.amazon.'. get_option( 'ams_amazon_country' );
        $service_name = 'ProductAdvertisingAPI';
        $region       = $regions[ $locale ]['RegionCode'];
        $access_key   = get_option( 'ams_access_key_id' );
        $secret_key   = get_option( 'ams_secret_access_key' );

        $payload_arr = array();
        $payload_arr['ItemIds']     = array( $asin );
        $payload_arr['Resources']   = array( "Images.Primary.Small", "Images.Primary.Medium", "Images.Primary.Large", "Images.Variants.Small", "Images.Variants.Medium", "Images.Variants.Large", "ItemInfo.ByLineInfo", "ItemInfo.ContentInfo", "ItemInfo.ContentRating", "ItemInfo.Classifications", "ItemInfo.ExternalIds", "ItemInfo.Features", "ItemInfo.ManufactureInfo", "ItemInfo.ProductInfo", "ItemInfo.TechnicalInfo", "ItemInfo.Title", "ItemInfo.TradeInInfo", "Offers.Listings.Availability.MaxOrderQuantity", "Offers.Listings.Availability.Message", "Offers.Listings.Availability.MinOrderQuantity", "Offers.Listings.Availability.Type", "Offers.Listings.Condition", "Offers.Listings.Condition.ConditionNote", "Offers.Listings.Condition.SubCondition", "Offers.Listings.DeliveryInfo.IsAmazonFulfilled", "Offers.Listings.DeliveryInfo.IsFreeShippingEligible", "Offers.Listings.DeliveryInfo.IsPrimeEligible", "Offers.Listings.DeliveryInfo.ShippingCharges", "Offers.Listings.IsBuyBoxWinner", "Offers.Listings.LoyaltyPoints.Points", "Offers.Listings.MerchantInfo", "Offers.Listings.Price", "Offers.Listings.ProgramEligibility.IsPrimeExclusive", "Offers.Listings.ProgramEligibility.IsPrimePantry", "Offers.Listings.Promotions", "Offers.Listings.SavingBasis", "Offers.Summaries.HighestPrice", "Offers.Summaries.LowestPrice", "Offers.Summaries.OfferCount" );
        $payload_arr['PartnerTag']  = get_option( 'ams_associate_tag' );
        $payload_arr['PartnerType'] = 'Associates';
        $payload_arr['Marketplace'] = $marketplace;
        $payload_arr['Operation']   = 'GetItems';
        $payload                    = wp_json_encode( $payload_arr );
        $host                       = $regions[ $locale ]['Host'];
        $uri_path                   = "/paapi5/getitems";
        $api                        = new \Amazon\Affiliate\Api\Amazon_Product_Api ( $access_key, $secret_key, $region, $service_name, $uri_path, $payload, $host, 'GetItems' );

        $response = $api->do_request();
        $results  = $response->ItemsResult->Items;

        foreach ( $results as $row ) {
            $thumbnail_size  = get_option( 'ams_product_thumbnail_size' );
            $asin            = $row->ASIN;
            $detail_page_url = $row->DetailPageURL;
            $image           = $row->Images->Primary->{$thumbnail_size}->URL;
            $gallery         = $row->Images->Variants;
            $amount          = $row->Offers->Listings[0]->Price->Amount;
            $saving_amount   = $row->Offers->Listings[0]->SavingBasis->Amount;
            $product_status  = $row->Offers->Listings[0]->Availability->Message;
            $title           = $row->ItemInfo->Title->DisplayValue;
            $features        = $row->ItemInfo->Features->DisplayValues;

            $content = '';
            foreach ( $features as $row) {
                $content .= '<ul>
                                <li>' . $row .'</li>
                            </ul>';
            }
            if ( 'in stock.'  === strtolower( $product_status ) ) {
                $product_status = 'instock';
            } else {
                $product_status = 'outofstock';
            }

            $user_id = get_current_user();

            $post_id = wp_insert_post( array(
                'post_author'  => $user_id,
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => "product",
                'post_parent'  => '',
            ) );

            wp_set_object_terms( $post_id, 'simple', 'product_type' );

            $ams_default_category = get_option( 'ams_default_category' );

            if ( $ams_default_category ) {
                wp_set_object_terms( $post_id, $ams_default_category, 'product_cat' );
            }

            update_post_meta( $post_id, '_visibility', 'visible' );
            update_post_meta( $post_id, '_stock_status', $product_status );
            update_post_meta( $post_id, 'total_sales', '0' );
            update_post_meta( $post_id, '_downloadable', 'no' );
            update_post_meta( $post_id, '_virtual', 'yes' );
            update_post_meta( $post_id, '_regular_price', $saving_amount );
            update_post_meta( $post_id, '_sale_price', $amount );
            update_post_meta( $post_id, '_purchase_note', '' );
            update_post_meta( $post_id, '_featured', 'no' );
            update_post_meta( $post_id, '_weight', '' );
            update_post_meta( $post_id, '_length', '' );
            update_post_meta( $post_id, '_width', '' );
            update_post_meta( $post_id, '_height', '' );
            update_post_meta( $post_id, '_sku', $asin );
            update_post_meta( $post_id, '_product_attributes', array() );
            update_post_meta( $post_id, '_sale_price_dates_from', '' );
            update_post_meta( $post_id, '_sale_price_dates_to', '' );
            update_post_meta( $post_id, '_price', $amount );
            update_post_meta( $post_id, '_sold_individually', '' );
            update_post_meta( $post_id, '_manage_stock', 'no' );
            update_post_meta( $post_id, '_backorders', 'no' );
            update_post_meta( $post_id, '_stock', '' );
            update_post_meta( $post_id, '_wca_amazon_affiliate_asin', $asin );

            // Check remote amazon images.
            if ( 'Yes' === get_option( 'ams_remote_amazon_images' ) ) {
                // Set product feature image.
                $this->attach_product_thumbnail( $post_id, $image, 0 );

                foreach( $gallery as $image ) {
                    // Set gallery image.
                    $this->attach_product_thumbnail( $post_id, $image->{$thumbnail_size}->URL, 1 );
                }
            }

            echo '<span class="dashicons dashicons-saved"></span> Success';
        }

        wp_die();
    }

}
