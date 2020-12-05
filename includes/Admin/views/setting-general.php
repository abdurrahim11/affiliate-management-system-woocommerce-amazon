<div class="wrap">
    <h2><?php esc_html_e( 'Affiliate Management System - WooCommerce Amazon', 'ams-wc-amazon' ); ?></h2>
    <br />
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url( 'admin.php?page=wc-product-setting-page' ); ?>" class="nav-tab nav-tab-active"><?php esc_html_e( 'General', 'ams-wc-amazon' ); ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=wc-product-setting-page&action=affiliates' )?>" class="nav-tab"><?php esc_html_e( 'Amazon Settings', 'ams-wc-amazon' ); ?></a>
    </h2>
    <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" enctype="multipart/form-data">
        <h2><?php esc_html_e( 'General', 'ams-wc-amazon'); ?></h2>
        <p><?php esc_html_e( 'All General setting for Affiliate Management System - WooCommerce Amazon.', 'ams-wc-amazon'); ?></p>
        <hr />
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label><?php esc_html_e( 'Product Per Page', 'ams-wc-amazon' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="product_per_page"  value="<?php echo  $this->get_option( 'ams_product_per_page' ) ? esc_attr( $this->get_option( 'ams_product_per_page' ) ) : '10'; ?>" />
                        <p><?php esc_html_e( 'To display products per request to Amazon store by restful api.Example -5,6,8,10 etc and max value 10', 'ams-wc-amazon' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php esc_html_e( 'Buy Now Label', 'ams-wc-amazon' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="buy_now_label"  value="<?php echo   $this->get_option( 'ams_buy_now_label' ) ? esc_attr( $this->get_option( 'ams_buy_now_label' ) ) : 'Buy Now'; ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php esc_html_e( 'Buy Now Button Action', 'ams-wc-amazon' ); ?></label>
                    </th>
                    <td>
                        <div class="ams-wc-a-radio">
                            <input <?php echo $this->get_option( 'ams_buy_action_btn' ) ===  'redirect' ? 'checked' : ''; ?>  type="radio"  name="buy_action_btn" value="redirect" /><span><?php esc_html_e( 'Direct Amazon Details Page (for Affiliate 24 hour cookie)', 'ams-wc-amazon' ); ?></span>
                        </div>
                        <div class="ams-wc-a-radio">
                            <input <?php echo $this->get_option( 'ams_buy_action_btn' ) ===  'cart_page' ? 'checked' : ''; ?>  type="radio"  name="buy_action_btn" value="cart_page" /><span><?php esc_html_e( 'Direct Amazon Cart Page (for Affiliate 90 day cookie)', 'ams-wc-amazon' ); ?></span>
                        </div>
                        <div class="ams-wc-a-radio">
                            <input <?php echo $this->get_option( 'ams_buy_action_btn' ) ===  'multi_cart' ? 'checked' : ''; ?> type="radio"  name="buy_action_btn" value="multi_cart" /><span> <?php esc_html_e( 'Product will be added to site cart whent click checkout it will be redirected to Amazon Cart Page (for Affiliate 90 day cookie)', 'ams-wc-amazon' ); ?></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php esc_html_e( 'Enable No Follow to Link', 'ams-wc-amazon' ); ?></label>
                    </th>
                    <td>
                        <div class="ams-wc-a-radio">
                            <input <?php echo $this->get_option( 'ams_enable_no_follow_link' ) === 'nofollow' ? 'checked' : ''; ?>  type="radio"  name="enable_no_follow_link" value="follow" /><span><?php esc_html_e( 'Yes', 'ams-wc-amazon' ); ?></span>
                        </div>
                        <div class="ams-wc-a-radio">
                            <input <?php echo $this->get_option( 'ams_enable_no_follow_link' ) === 'follow' ? 'checked' : ''; ?>  type="radio"  name="enable_no_follow_link" value="nofollow" /><span><?php esc_html_e( 'No', 'ams-wc-amazon' ); ?></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Product Import Default Category', 'ams-wc-amazon' ); ?> </th>
                    <td>
                        <select name="ams_default_category" >
                            <?php
                            foreach ( $this->get_wc_terms() as $value ) {
                                ?>
                                <option <?php echo $this->selected_check( 'ams_default_category', $value['name'] ); ?> value="<?php echo esc_attr( $value['name'] ); ?>"><?php echo $value['name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php esc_html_e( 'Checkout Message', 'ams-wc-amazon' ); ?></label>
                    </th>
                    <td>
                        <textarea name="checkout_mass_redirected" cols="120"><?php echo get_option( 'ams_checkout_mass_redirected' ); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php esc_html_e( 'Checkout redirect in', 'ams-wc-amazon' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="checkout_redirected_seconds"  value="<?php echo get_option( 'ams_checkout_redirected_seconds' ); ?>" />
                        <p><?php esc_html_e( 'How many seconds to wait before redirect to Amazon!', 'ams-wc-amazon' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Remote amazon images', 'ams-wc-amazon' ); ?></th>
                    <td>
                        <select name="remote_amazon_images" >
                            <option <?php echo $this->selected_check( 'ams_remote_amazon_images', 'Yes' ); ?> value="Yes"><?php esc_html_e( 'Yes', 'ams-wc-amazon' ); ?></option>
                            <option <?php echo $this->selected_check( 'ams_remote_amazon_images', 'No' ); ?> value="No"><?php esc_html_e( 'No', 'ams-wc-amazon' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Select remote images sizes', 'ams-wc-amazon' ); ?> </th>
                    <td>
                        <select name="product_thumbnail_size" >
                            <option <?php echo $this->selected_check( 'ams_product_thumbnail_size', 'Large' ); ?> value="Large"><?php esc_html_e( 'Large (500 X 500)', 'ams-wc-amazon' ); ?></option>
                            <option <?php echo $this->selected_check( 'ams_product_thumbnail_size', 'Medium' ); ?> value="Medium"><?php esc_html_e( 'Medium (160 X 160)', 'ams-wc-amazon' ); ?></option>
                            <option <?php echo $this->selected_check( 'ams_product_thumbnail_size', 'Small' ); ?>  value="Small"><?php esc_html_e( 'Small (75 X 75)', 'ams-wc-amazon' ); ?></option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <input type="hidden" name="action" value="ams-wc-general-setting">
        <?php wp_nonce_field("general_setting_nonce")?>
        <?php submit_button(esc_html__("Save Settings","ams-wc-amazon"),"primary","general-setting-submit");?>
    </form>
</div>