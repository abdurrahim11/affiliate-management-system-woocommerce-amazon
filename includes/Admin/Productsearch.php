<?php


namespace Amazon\Affiliate\Admin;


use Amazon\Affiliate\Admin;

/**
 * Class Productsearch
 *
 * @package Amazon\Affiliate\Admin
 */
class Productsearch {

    /**
     * Load product search page view
     */
    public function product_page() {
        $template = __DIR__ . '/views/product.php';

        if ( file_exists( $template ) ) {
            require_once $template;
        }
    }

    /**
     * Product search by key word and filter
     */
    public function search_products() {
        check_admin_referer( 'wca_search_product' );
        wca_add_products_search_count();

        if ( ams_plugin_license_status()  === false ) {
             echo sprintf( "<h4 class='wca-warning'>%s</h4>", esc_html__( 'Please activate the plugin license before searching the product.', 'ams-wc-amazon' ) );
             wp_die();
        }

        if ( 'keyword'  === $_POST['wca_search_by'] ) {
            $keyword     = sanitize_text_field( $_POST['keyword'] );
            $sort_by     = sanitize_text_field( $_POST['sort_by'] );
            $item_page   = sanitize_text_field( $_POST['item_page'] );
            $amazon_cat   = sanitize_text_field( $_POST['ams_amazon_cat'] );

            $results = $this->get_keyword_products( $keyword, $item_page, $sort_by, $amazon_cat );

            foreach ( $results as $row ) {
                $asin            = $row->ASIN;
                $image           = $row->Images;
                $amount          = $row->Offers->Listings[0]->Price->DisplayAmount;
                $saving_amount   = $row->Offers->Listings[0]->SavingBasis->DisplayAmount;
                $img             = $image->Primary->Medium->URL;
                $title           = $row->ItemInfo->Title->DisplayValue;
                $detail_page_url = $row->DetailPageURL;
                ?>
                <div class="wca-single-product">
                    <div class="wca-product-box-thumb">
                        <img src="<?php echo esc_attr( $img ); ?>" alt="<?php esc_attr( $title ); ?>">
                    </div>
                    <div class="wca-product-box-info">
                        <h3 title="<?php esc_attr( $title ); ?>"> <?php echo  wp_trim_words( $title, 6, '...' ); ?> </h3>
                        <h4> <?php echo $amount; ?> <sup class="wca-delete"> <?php echo $saving_amount; ?></sup></h4>
                    </div>
                    <div class="wca-product-box-action">
                        <a href="<?php echo esc_attr( $detail_page_url ); ?>" class="wca-view-product" target="_blank">View Product</a>
                        <?php
                            $ams_all_asin = ams_get_all_products_info();

                            if ( in_array( $asin,  $ams_all_asin['asin'] ) ) {
                                ?>
                                <button disabled type="button" class="wca-add-to-imported" >Already imported</button>
                                <?php
                            } else {
                                ?>
                                <button type="button" class="wca-add-to-import" data-asin="<?php echo esc_attr( $asin ); ?> " >Import Product</button>
                                <?php
                            }
                        ?>

                    </div>
                </div>
                <?php
            }

        } else {
            /**
             * Product search by asin id
             */
            $asin_id = sanitize_text_field( $_POST['asin_id'] );
            $results = $this->get_item_id_products( $asin_id );

            foreach ( $results  as $row ) {
                $asin            =  $row->ASIN;
                $detail_page_url =  $row->DetailPageURL;
                $image           =  $row->Images->Primary->Medium->URL;
                $amount          =  $row->Offers->Listings[0]->Price->DisplayAmount;
                $saving_amount   =  $row->Offers->Listings[0]->SavingBasis->DisplayAmount;
                $title           =  $row->ItemInfo->Title->DisplayValue;
                ?>
                <div class="wca-single-product">
                    <div class="wca-product-box-thumb">
                        <img src="<?php echo $image; ?>" alt="<?php esc_attr( $title ); ?>">
                    </div>
                    <div class="wca-product-box-info">
                        <h3 title="<?php esc_attr( $title ); ?>"> <?php echo  wp_trim_words( $title, 5, '...' ); ?> </h3>
                        <h4> <?php echo $amount; ?> <sup class="wca-delete"> <?php echo $saving_amount; ?></sup></h4>
                    </div>
                    <div class="wca-product-box-action">
                        <a href="<?php echo esc_attr( $detail_page_url ); ?>" class="wca-view-product" target="_blank">View Product</a>
                        <?php
                        $ams_all_asin = ams_get_all_products_info();

                        if ( in_array( $asin, $ams_all_asin['asin'] ) ) {
                            ?>
                            <button disabled type="button" class="wca-add-to-imported" > <?php esc_html_e( 'Already imported', 'ams-wc-amazon' ) ;?> </button>
                            <?php
                        } else {
                            ?>
                            <button type="button" class="wca-add-to-import" data-asin="<?php echo esc_attr( $asin ); ?> " > <?php esc_html_e( 'Import Product', 'ams-wc-amazon' ) ;?> </button>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }

        }

        wp_die();
    }

    /**
     * Amazon products search use keyword
     *
     * @param $keyword
     * @param $item_page
     * @param $sort_by
     * @return mixed
     */
    public function get_keyword_products( $keyword, $item_page, $sort_by, $amazon_cat ) {
        $ams_product_per_page   = get_option( 'ams_product_per_page' );
        $locale      = get_option( 'ams_amazon_country' );
        $regions     = ams_get_amazon_regions();
        $marketplace = 'www.amazon.'. get_option( 'ams_amazon_country' );
        $serviceName = 'ProductAdvertisingAPI';
        $region      = $regions[ $locale ]['RegionCode'];
        $accessKey   = get_option( 'ams_access_key_id' );
        $secretKey   = get_option( 'ams_secret_access_key' );

        $payloadArr = array();
        $payloadArr['Keywords']    = $keyword;
        $payloadArr['Resources']   = array( 'CustomerReviews.Count', 'CustomerReviews.StarRating', 'Images.Primary.Small', 'Images.Primary.Medium', 'Images.Primary.Large', 'Images.Variants.Small', 'Images.Variants.Medium', 'Images.Variants.Large', 'ItemInfo.ByLineInfo', 'ItemInfo.ContentInfo', 'ItemInfo.ContentRating', 'ItemInfo.Classifications', 'ItemInfo.ExternalIds', 'ItemInfo.Features', 'ItemInfo.ManufactureInfo', 'ItemInfo.ProductInfo', 'ItemInfo.TechnicalInfo', 'ItemInfo.Title', 'ItemInfo.TradeInInfo', 'Offers.Listings.Availability.MaxOrderQuantity', 'Offers.Listings.Availability.Message', 'Offers.Listings.Availability.MinOrderQuantity', 'Offers.Listings.Availability.Type', 'Offers.Listings.Condition', 'Offers.Listings.Condition.SubCondition', 'Offers.Listings.DeliveryInfo.IsAmazonFulfilled', 'Offers.Listings.DeliveryInfo.IsFreeShippingEligible', 'Offers.Listings.DeliveryInfo.IsPrimeEligible', 'Offers.Listings.DeliveryInfo.ShippingCharges', 'Offers.Listings.IsBuyBoxWinner', 'Offers.Listings.LoyaltyPoints.Points', 'Offers.Listings.MerchantInfo', 'Offers.Listings.Price', 'Offers.Listings.ProgramEligibility.IsPrimeExclusive', 'Offers.Listings.ProgramEligibility.IsPrimePantry', 'Offers.Listings.Promotions', 'Offers.Listings.SavingBasis', 'Offers.Summaries.HighestPrice', 'Offers.Summaries.LowestPrice', 'Offers.Summaries.OfferCount', 'ParentASIN','SearchRefinements' );
        $payloadArr["ItemCount"]   = (int) $ams_product_per_page;
        $payloadArr["ItemPage"]    = (int) $item_page;
        $payloadArr["SortBy"]      = $sort_by;
        $payloadArr["SearchIndex"] = $amazon_cat;
        $payloadArr['PartnerTag']  = get_option( 'ams_associate_tag' );
        $payloadArr['PartnerType'] = 'Associates';
        $payloadArr['Marketplace'] = $marketplace;
        $payload                   = json_encode( $payloadArr );
        $host                      = $regions[ $locale ]['Host'];
        $uri_path                   = "/paapi5/searchitems";

        $api  = new  \Amazon\Affiliate\Api\Amazon_Product_Api( $accessKey, $secretKey, $region, $serviceName, $uri_path, $payload, $host, 'SearchItems' );
        $response = $api->do_request();
        $results = $response->SearchResult->Items;

        return $results;
    }

    /**
     * Amazon products search use asin id
     *
     * @param $asin_id
     * @return mixed
     */
    public function get_item_id_products( $asin_id ) {
        $space_remove_asin_id = str_replace( ' ', '', $asin_id );
        $array_asin_id = explode( ',', $space_remove_asin_id );

        $locale      = get_option( 'ams_amazon_country' );
        $regions     = ams_get_amazon_regions();
        $marketplace = 'www.amazon.'. get_option( 'ams_amazon_country' );
        $serviceName = 'ProductAdvertisingAPI';
        $region      = $regions[ $locale ]['RegionCode'];
        $accessKey   = get_option( 'ams_access_key_id' );
        $secretKey   = get_option( 'ams_secret_access_key' );

        $payloadArr = array();
        $payloadArr['ItemIds']     = $array_asin_id;
        $payloadArr['Resources']   = array( "Images.Primary.Small", "Images.Primary.Medium", "Images.Primary.Large", "Images.Variants.Small", "Images.Variants.Medium", "Images.Variants.Large", "ItemInfo.ByLineInfo", "ItemInfo.ContentInfo", "ItemInfo.ContentRating", "ItemInfo.Classifications", "ItemInfo.ExternalIds", "ItemInfo.Features", "ItemInfo.ManufactureInfo", "ItemInfo.ProductInfo", "ItemInfo.TechnicalInfo", "ItemInfo.Title", "ItemInfo.TradeInInfo", "Offers.Listings.Availability.MaxOrderQuantity", "Offers.Listings.Availability.Message", "Offers.Listings.Availability.MinOrderQuantity", "Offers.Listings.Availability.Type", "Offers.Listings.Condition", "Offers.Listings.Condition.ConditionNote", "Offers.Listings.Condition.SubCondition", "Offers.Listings.DeliveryInfo.IsAmazonFulfilled", "Offers.Listings.DeliveryInfo.IsFreeShippingEligible", "Offers.Listings.DeliveryInfo.IsPrimeEligible", "Offers.Listings.DeliveryInfo.ShippingCharges", "Offers.Listings.IsBuyBoxWinner", "Offers.Listings.LoyaltyPoints.Points", "Offers.Listings.MerchantInfo", "Offers.Listings.Price", "Offers.Listings.ProgramEligibility.IsPrimeExclusive", "Offers.Listings.ProgramEligibility.IsPrimePantry", "Offers.Listings.Promotions", "Offers.Listings.SavingBasis", "Offers.Summaries.HighestPrice", "Offers.Summaries.LowestPrice", "Offers.Summaries.OfferCount" );
        $payloadArr['PartnerTag']  = get_option( 'ams_associate_tag' );
        $payloadArr['PartnerType'] = 'Associates';
        $payloadArr['Marketplace'] = $marketplace;
        $payloadArr['Operation']   = 'GetItems';
        $payload                   = json_encode( $payloadArr );
        $host                      = $regions[ $locale ]['Host'];
        $uri_path                   = "/paapi5/getitems";
        $api                       =  new  \Amazon\Affiliate\Api\Amazon_Product_Api ( $accessKey, $secretKey,$region, $serviceName, $uri_path, $payload, $host, 'GetItems' );

        $response = $api->do_request();
        $results = $response->ItemsResult->Items;

        return $results;
    }

    /**
     * Get all amazon category
     *
     * @return string[]
     */
    public function get_amazon_cat() {
        $all_country_cat = ams_amazon_departments();
        $country         = get_option( 'ams_amazon_country' );
        $cat             = $all_country_cat[ $country ];
        return $cat;
    }

}