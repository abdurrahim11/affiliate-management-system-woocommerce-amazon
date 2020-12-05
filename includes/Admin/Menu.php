<?php


namespace Amazon\Affiliate\Admin;

/**
 * Plugin admin menu handler Class
 *
 * @package Amazon\Affiliate\Admin
 */
class Menu {

    private  $setting;
    private  $Productsearch;

    /**
     * Menu constructor.
     *
     * @param $setting
     * @param $Productsearch
     */
    function __construct( $setting, $Productsearch, $dashboard ) {
        $this->setting       = $setting;
        $this->dashboard     = $dashboard;
        $this->Productsearch = $Productsearch;

        add_action( 'admin_menu', array( $this,'admin_menu' ) );
        add_filter( 'plugin_action_links_affiliate-management-system-woocommerce-amazon/affiliate-management-system-woocommerce-amazon.php', array( $this, 'plugin_setting_link' ) );
    }

    /**
     * Admin Menu register
     */
    public function admin_menu(){
        $capability         = 'manage_options';
        $title              = esc_html__( 'Ams Amazon', 'ams-wc-amazon' );
        $setting_page_title = esc_html__( 'Setting', 'ams-wc-amazon' );
        $parent_slug        = 'wc-amazon-affiliate';

        add_menu_page( $title , $title, $capability, $parent_slug, array( $this->dashboard, 'dashboard_page' ),'dashicons-amazon' );
        add_submenu_page( $parent_slug, esc_html__( 'Dashboard','ams-wc-amazon' ), esc_html__('Dashboard','ams-wc-amazon' ), $capability, $parent_slug, array( $this->dashboard, 'dashboard_page' ) );
        add_submenu_page( $parent_slug, esc_html__( 'Product Search','ams-wc-amazon' ), esc_html__('Product Search','ams-wc-amazon' ), $capability, 'wc-product-search', array( $this->Productsearch, 'product_page' ) );
        add_submenu_page( $parent_slug, $setting_page_title, $setting_page_title, $capability, 'wc-product-setting-page', array( $this->setting, 'setting_page' ) );
    }

    /**
     * Plugin setting page link
     *
     * @param $link
     * @return mixed
     */
    public function plugin_setting_link( $link ) {
        $new_link = sprintf("<a href='%s'>%s</a>","admin.php?page=wc-product-setting-page",esc_html__("Setting","woo-address-auto-complete"));
        $link[]   = $new_link;
        return $link;
    }
}