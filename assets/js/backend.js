;
( function ( $ ) {
    'use strict';
    // here code for product filter
    $( '.wca-search-by' ).on( 'change', function () {
        if ( 'keyword' === $( this ).val() ) {
            $( '.wca-keyword' ).show();
            $( '.wca-asin' ).hide();
        } else {
            $( '.wca-keyword' ).hide();
            $( '.wca-asin' ).show();
        }
    } );

    var wca_item_page = 1;
    var wca_loading = false;
    var form_data = '';

    //here code is ajax request for get product list
    $( '.wca-product-search' ).on( 'submit', function ( event ) {
        event.preventDefault();
        wca_loading = true;
        wca_item_page = 1;
        form_data = $( this ).serialize();
        $( '.wca-loading-icon' ).show();
        $.ajax( {
            type: 'POST',
            url: amsbackend.ajax_url,
            data: form_data + '&item_page=' + wca_item_page,
            success: function ( html ) {
                $( '.wca-loading-icon' ).hide();
                $( '.wca-amazon-product' ).html( html );
                wca_product_import();
                wca_item_page = wca_item_page + 1;
                wca_loading = false;
            }
        } );

        //These codes are written to automatically load the product when scrolling to the bottom of the product page.
        $( window ).scroll( function () {
            if ( ! wca_loading ) {
                if ( ($( document ).height() - $( window ).height()) - $( window ).scrollTop() <= 100 ) {
                    wca_loading = true;
                    $( '.wca-loading-icon' ).show();
                    $.ajax( {
                        type: 'POST',
                        url: amsbackend.ajax_url,
                        data: form_data + '&item_page=' + wca_item_page,
                        success: function ( html ) {
                            $( '.wca-loading-icon' ).hide();
                            $( '.wca-amazon-product' ).append( html );
                            wca_product_import();
                            wca_item_page = wca_item_page + 1;
                            wca_loading = false;
                        }
                    } );
                }
            }
        } );

    } );

    //These codes are written for product import
    function wca_product_import() {
        $( '.wca-add-to-import' ).on( 'click', function () {
            var wca_button = this;
            var data_asin = $( this ).attr( 'data-asin' );
            $( wca_button ).html( '<span class="dashicons dashicons-update wca-spin"></span> Importing' );
            $.ajax( {
                type: 'POST',
                url: amsbackend.ajax_url,
                data: {
                    'nonce': amsbackend.check_nonce,
                    'asin': data_asin,
                    'action': 'ams_product_import',
                },
                success: function ( html ) {
                    $( '.wca-loading-icon' ).hide();
                    $( wca_button ).html( html );
                }
            } );
        } );
    }

    //These codes are written to bring dashboard information
    if ( true == amsbackend.ams_dashboard ) {

        setInterval( function(){
            dashboard_info();
        }, 5000 );

        function dashboard_info() {
            $.ajax( {
                type: 'POST',
                url: amsbackend.ajax_url,
                data: {
                    'nonce_ams_dashboard_info': amsbackend.nonce_ams_dashboard_info,
                    'action': 'ams_dashboard_info',
                },
                success: function ( json_data ) {
                    var data = JSON.parse( json_data );
                    $( '#wca-products-count' ).html( amsbackend.products_count );
                    $( '#wca-total-view-count' ).html( amsbackend.total_view_count );
                    $( '#wca-total-product-added-to-cart' ).html( amsbackend.total_product_added_to_cart );
                    $( '#wca-total-product-direct-redirected' ).html( amsbackend.total_product_direct_redirected );
                    $( '#wca-total-product-search' ).html( amsbackend.products_search_count );
                }
            } );
        }
    }

    // These codes written for license activation
    $( '.wca-activation-btn' ).on( 'click', function () {
        event.preventDefault();
        $( '.wca-purchase-massage' ).html( "<p class='wca-success'> Loading... </p>" );
        var wca_purchase_code = $( '.wca-purchase-code-input' ).val();
        $.ajax( {
            type: 'POST',
            url: amsbackend.ajax_url,
            data: {
                'nonce': amsbackend.check_nonce,
                'action': 'ams_license_activation',
                'purchase_code': wca_purchase_code,
            },
            success: function ( data ) {
                $( '#wca_license_activation' ).html( data.license_status );
                $( '.wca-purchase-massage' ).html( data.massage );
            }
        } );
    } );

    //Test amazon api
    $( '.ams-test-api-btn' ).on( 'click', function () {
        event.preventDefault();
        $( '.ams-api-massage' ).html( "<p class='wca-success'> Loading... </p>" );
        $.ajax( {
            type: 'POST',
            url: amsbackend.ajax_url,
            data: {
                'nonce': amsbackend.ams_test_api,
                'action': 'ams_test_api',
            },
            success: function ( data ) {
                $( '.ams-api-massage' ).html( data );
            }
        } );
    } );

    //These codes written for plugin  accordion
    var wca_acc = document.getElementsByClassName( 'wca-accordion' );
    var i;
    for ( i = 0; i < wca_acc.length; i++ ) {
        wca_acc[ i ].addEventListener( 'click', function () {
            /* Toggle between adding and removing the 'wca-active' class,
            to highlight the button that controls the panel */
            this.classList.toggle( 'wca-active' );

            /* Toggle between hiding and showing the wca-active panel */
            var panel = this.nextElementSibling;
            if ( panel.style.display === 'block' ) {
                panel.style.display = 'none';
            } else {
                panel.style.display = 'block';
            }
        } );
    }

} )( jQuery );

