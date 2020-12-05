<div class="wca-content">
    <div class="wrap">
        <div class="wca-status-box-area">
            <div class="wca-dashboard-status-box">
                <h3 id="wca-products-count"><?php echo $products_info['products_count']; ?></h3>
                <p><?php esc_html_e( 'Total Number of products', 'ams-wc-amazon' ); ?></p>
                <div class="wca-dashboard-status-box-icon wca-products">
                    <span class="dashicons dashicons-wordpress"></span>
                </div>
            </div>
            <div class="wca-dashboard-status-box">
                <h3 id="wca-total-view-count"><?php echo $products_info['total_view_count']; ?></h3>
                <p><?php esc_html_e( 'Total products views', 'ams-wc-amazon' ); ?></p>
                <div class="wca-dashboard-status-box-icon wca-views">
                    <span class="dashicons dashicons-groups"></span>
                </div>
            </div>
            <div class="wca-dashboard-status-box">
                <h3 id="wca-total-product-added-to-cart"><?php echo $products_info['total_product_added_to_cart']; ?></h3>
                <p><?php esc_html_e( 'Products added to cart', 'ams-wc-amazon' ); ?></p>
                <div class="wca-dashboard-status-box-icon wca-cart">
                    <span class="dashicons dashicons-cart"></span>
                </div>
            </div>
            <div class="wca-dashboard-status-box">
                <h3 id="wca-total-product-direct-redirected"><?php echo $products_info['total_product_direct_redirected']; ?></h3>
                <p><?php esc_html_e( 'Total redirected to Amazon', 'ams-wc-amazon' ); ?></p>
                <div class="wca-dashboard-status-box-icon wca-redirected">
                    <span class="dashicons dashicons-amazon"></span>
                </div>
            </div>
            <div class="wca-dashboard-status-box">
                <h3 id="wca-total-product-direct-redirected"><?php esc_html_e( 'Unlimited', 'ams-wc-amazon' ); ?></h3>
                <p><?php esc_html_e( 'Products search limit', 'ams-wc-amazon' ); ?></p>
                <div class="wca-dashboard-status-box-icon wca-search-limit">
                    <span class="dashicons dashicons-search"></span>
                </div>
            </div>
            <div class="wca-dashboard-status-box">
                <h3 id="wca-total-product-search"><?php echo $products_info['products_search_count']; ?></h3>
                <p><?php esc_html_e( 'Total Products search', 'ams-wc-amazon' ); ?></p>
                <div class="wca-dashboard-status-box-icon wca-products-search">
                    <span class="dashicons dashicons-search"></span>
                </div>
            </div>
            <div class="wca-dashboard-status-box">
                <h3 id="wca-total-product-direct-redirected"><?php esc_html_e( 'Unlimited', 'ams-wc-amazon' ); ?></h3>
                <p><?php esc_html_e( 'Products search left limit', 'ams-wc-amazon' ); ?></p>
                <div class="wca-dashboard-status-box-icon wca-search-left-limit">
                    <span class="dashicons dashicons-search"></span>
                </div>
            </div>
            <div class="wca-dashboard-status-box">
                <h3 id="wca-total-product-direct-redirected"><?php esc_html_e( '8640', 'ams-wc-amazon' ); ?></h3>
                <p><?php esc_html_e( 'Api requests can be sent per day', 'ams-wc-amazon' ); ?></p>
                <div class="wca-dashboard-status-box-icon wca-requests-per-day">
                    <span class="dashicons dashicons-warning"></span>
                </div>
            </div>
        </div>
        <div class="wca-plugin-activation">
            <div class="wca-plugin-activation-header">
                <div class="wca-plugin-activation-header-left">
                    <h3><?php esc_html_e( 'Activation', 'ams-wc-amazon' ); ?></h3>
                </div>
                <div class="wca-plugin-activation-header-right">
                    <?php
                    $wca_license_class  = get_option( 'ams_activated_status' ) == 'success' ? 'wca-success' : 'wca-warning';
                    $wca_license_status = get_option( 'ams_activated_status' ) == 'success'? esc_html__( 'Activated', 'ams-wc-amazon' ) : esc_html__( 'Not Activated', 'ams-wc-amazon' );
                    $wca_license = sprintf( '<span class="%s">%s</span>', $wca_license_class, $wca_license_status );
                    ?>
                    <h3><?php esc_html_e( 'CodeCanyon License', 'ams-wc-amazon' ); ?>: <strong id="wca_license_activation"><?php echo $wca_license; ?></strong></h3>
                </div>
            </div>
            <hr>
            <div class="wca-plugin-activation-box">
                <form class="wca-admin-page-activation-form-container" method="POST">
                    <div class="wca-admin-page-activation-form-header">
                        <h4><?php esc_html_e( 'CodeCanyon License', 'ams-wc-amazon' ); ?></h4>
                    </div>
                    <div class="wca-admin-page-activation-form-field">
                        <label>
                            <span class="wca-admin-page-activation-form-field-label"><?php esc_html_e( 'Please, enter your plugin\'s CodeCanyon purchase code', 'ams-wc-amazon' ); ?></span>
                        </label>
                        <div class="wca-purchase-code-input-area">
                            <input class="wca-purchase-code-input" type="text" placeholder="Purchase code" name="purchase_code">
                        </div>
                        <div class="wca-purchase-massage">

                        </div>
                    </div>
                    <?php submit_button(esc_html__( 'Activate License', 'ams-wc-amazon' ), 'primary wca-activation-btn', 'wca-activate-license' ); ?>
                </form>
            </div>
            <div class="wca-plugin-activation-box">
                <button class="wca-accordion"><?php esc_html_e( 'What is CodeCanyon purchase code?', 'ams-wc-amazon' ); ?></button>
                <div class="wca-panel">
                    <p><?php esc_html_e( 'Purchase code is a license key, that you get after buying the plugin on CodeCanyon. It looks like this:', 'ams-wc-amazon' ); ?> 13fc2617-5d1d-4127-873a-feb85d27a012.</p>
                    <img src="<?php echo AMS_PLUGIN_URL . '/assets/images/purchase-code.jpg'; ?>" alt="">
                </div>
                <button class="wca-accordion"><?php esc_html_e( 'How do I get my purchase code?', 'ams-wc-amazon' ); ?></button>
                <div class="wca-panel">
                    <?php
                    $wca_accordion_dis_two = sprintf( '%s<a href="%s" target="_blank">%s</a>, %s',
                    esc_html__( 'After purchasing the item, go to', 'ams-wc-amazon' ),
                    'http://codecanyon.net/downloads',
                    'http://codecanyon.net/downloads',
                    esc_html__( 'click "Download" and select “License Certificate & Purchase Code”. You’ll find your purchase code in the downloaded file.', 'ams-wc-amazon' )
                    );
                    ?>
                    <p><?php echo  $wca_accordion_dis_two; ?></p>
                    <img src="<?php echo AMS_PLUGIN_URL . '/assets/images/how-to-get.jpg'; ?>" alt="">
                </div>
                <button class="wca-accordion"><?php esc_html_e( 'How do I activate a CodeCanyon license?', 'ams-wc-amazon' ); ?></button>
                <div class="wca-panel">
                    <p><?php esc_html_e( 'To activate your license in the plugin insert you purchase code into the CodeCanyon License form above and press “Activate License” button. After the successful activation', 'ams-wc-amazon' ); ?> </p>
                </div>
            </div>
        </div>
    </div>
</div>
