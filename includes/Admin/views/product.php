<div class="wrap">
    <div class="wca-container">
        <div class="wca-top-filter-area">
            <form action="" class="wca-product-search"  method="POST">
                <div class="wca-row text-center mb-1">
                    <input  type="radio" name="wca_search_by" class="wca-search-by" value="keyword" checked="" /> <?php esc_html_e( 'Keyword Search', 'ams-wc-amazon' ); ?>
                    <input type="radio" name="wca_search_by" class="wca-search-by" value="asin" /> <?php esc_html_e( 'ASIN Numbers', 'ams-wc-amazon' ); ?>
                </div>
                <div class="wca-row text-center">
                    <div class="wca-keyword">
                        <div class="wca-filed">
                            <select name="ams_amazon_cat" class="wca-form-control">
                                <?php
                                foreach ( $this->get_amazon_cat() as $key => $value ) {
                                    ?>
                                    <option value="<?php echo esc_attr( $key ); ?>"><?php echo $value; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="wca-filed">
                            <input name="keyword"  class="wca-form-control" placeholder="Type search keyword" type="text"/>
                        </div>
                        <div class="wca-filed">
                            <select name="sort_by" class="wca-form-control">
                                <option value="Relevance"><?php esc_html_e( 'Relevance', 'ams-wc-amazon' ); ?></option>
                                <option value="AvgCustomerReviews"><?php esc_html_e( 'AvgCustomerReviews', 'ams-wc-amazon' ); ?></option>
                                <option value="Featured"><?php esc_html_e( 'Featured', 'ams-wc-amazon' ); ?></option>
                                <option value="NewestArrivals"><?php esc_html_e( 'NewestArrivals', 'ams-wc-amazon' ); ?></option>
                                <option value="Price:HighToLow"><?php esc_html_e( 'Price:HighToLow', 'ams-wc-amazon' ); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="wca-asin">
                        <div class="wca-filed">
                            <input name="asin_id" class="wca-form-control" placeholder="B0813RK , B08G8BT" type="text" />
                        </div>
                    </div>
                </div>
                <input type="hidden" name="action" value="search_products">
                <div class="wca-row text-center wca-btn-center" >
                    <?php
                        wp_nonce_field( 'wca_search_product' );
                        submit_button('Search for product', 'button button-primary wca-button');
                    ?>
                </div>
            </form>
        </div>

        <div class="wca-amazon-product">

        </div>
        <div class="wca-loading-icon">
            <img class="wca-loading-images" src="<?php echo AMS_PLUGIN_URL . '/assets/images/loading.gif'; ?>" alt="">
        </div>
    </div>
</div>