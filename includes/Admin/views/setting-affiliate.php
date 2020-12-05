<div class="wrap">
    <h2><?php esc_html_e( 'Affiliate Management System - WooCommerce Amazon', 'ams-wc-amazon' ); ?></h2>
    <br />
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url( 'admin.php?page=wc-product-setting-page' ); ?>" class="nav-tab"><?php esc_html_e( 'General', 'ams-wc-amazon' ); ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=wc-product-setting-page&action=affiliates' )?>" class="nav-tab nav-tab-active"><?php esc_html_e( 'Amazon Settings', 'ams-wc-amazon' ); ?></a>
    </h2>
    <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="ams_wc_amazon_general_setting">
        <?php wp_nonce_field( 'general_amazon_setting_nonce' ); ?>
        <h2><?php esc_html_e( 'Credentials', 'ams-wc-amazon' ); ?></h2>
        <hr />
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Access Key ID', 'ams-wc-amazon' ); ?></label>
                </th>
                <td>
                    <input  type="text" name="access_key_id" placeholder="" value="<?php echo esc_attr( $this->get_option( 'ams_access_key_id' ) ); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Secret Access Key','ams-wc-amazon' ); ?></label>
                </th>
                <td>
                    <input  type="text" name="secret_access_key" placeholder="" value="<?php echo esc_attr( $this->get_option( 'ams_secret_access_key' ) ); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Amazon Affiliate Associate Tag', 'ams-wc-amazon' ); ?></label>
                </th>
                <td>
                    <input  type="text" name="ams_associate_tag" placeholder="Associate ID" value="<?php echo esc_attr( $this->get_option( 'ams_associate_tag' ) ); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e( 'Select Country', 'ams-wc-amazon' ); ?> </th>
                <td>
                    <select name="ams_amazon_country" id="amazon_country">
                        <?php
                        $regions = ams_get_amazon_regions();

                        foreach ( $regions as $key => $value ) {
                            ?>
                            <option <?php echo $this->selected_check( 'ams_amazon_country', $key ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo $value['RegionName']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?php submit_button(esc_html__( 'Save Settings', 'ams-wc-amazon' ), 'primary', 'general-setting-submit' ); ?>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <div class="ams-test-api">
        <h2><?php esc_html_e( 'Amazon api test', 'ams-wc-amazon' ); ?></h2>
        <p><?php esc_html_e( 'Test your API settings to make sure everything is setup correctly. Save your settings before testing.', 'ams-wc-amazon' ); ?></p>
        <hr />
        <a href="#" class="button button-primary ams-test-api-btn">Test API Settings</a>
        <div class="ams-api-massage"></div>
    </div>
</div>


