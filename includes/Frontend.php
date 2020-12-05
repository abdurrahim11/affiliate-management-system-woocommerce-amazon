<?php


namespace Amazon\Affiliate;

/**
 * Frontend handler class
 *
 * @package Amazon\Affiliate
 */
class Frontend {

    /**
     * Frontend constructor.
     */
    public function __construct() {
        $WooCommerceCart = new Frontend\WooCommerceCart();
    }
}