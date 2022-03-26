(function($) {
    'use strict';

    /**
     * This file is responsible for handling the product quotation cart page.
     * 
     * @since 1.2.0
     */

    // todo: enqueue this file.

    var pqfwCart = {

        init: function() {
            this.initialize();

            $(document).on('click', '.pqfw-remove-product', function () {
                var $hash = $(this).data( 'id' );

                pqfwCart.removeProduct( $hash );
            });

            $(document).on('change', '.pqfw-quantity', function () {
                var new_quantity = $(this).val();
                var hash = $(this).data('hash');

                window.pqfwProducts[hash]['quantity'] = new_quantity;
                var products = window.pqfwProducts;
                pqfwCart.updateProduct(products);
            });

            $(document).on('change', '.pqfw-message', function () {
                var new_message = $(this).val();
                var hash = $(this).data('hash');
                window.pqfwProducts[hash]['message'] = new_message;
                var products = window.pqfwProducts;
                pqfwCart.updateProduct(products);
            });
        },
        initialize: function() {
            pqfwCart.showLoader();

            $.ajax({
                url     : PQFW_OBJECT.ajaxurl,
                data    : { action: 'pqfw_load_cart_data' },
                success : function( response ) {
                    pqfwCart.dataLoaded(response);
                },
                error   : function( response ) {
                    console.error(response);
                }
            });
        },
        dataLoaded: function(response) {
            var $parsed = JSON.parse( response );
            $('#pqfw-enquiry-list-row').html( $parsed.html );
            window.pqfwProducts = $parsed.products;
            this.hideLoader();
            this.visibleForm($parsed.products);
        },
        hideLoader: function () {
            $('#pqfw-enquiry-list-row').unblock();
        },
        showLoader: function() {
            $('#pqfw-enquiry-list-row').block({
                message: '<img src="' + PQFW_OBJECT.loader + '" />',
                css: {
                    width: '40px',
                    height: '40px',
                    top: '50%',
                    left: '50%',
                    border: '0px',
                    backgroundColor: "transparent"
                },
                overlayCSS: {
                    background: "#fff",
                    opacity: .7
                }
            });
        },
        removeProduct: function($hash) {
            pqfwCart.showLoader();

            $.ajax({
                url     : PQFW_OBJECT.ajaxurl,
                type    : 'POST',
                data    : { action: 'pqfw_remove_product', hash: $hash },
                success : function( response ) {
                    pqfwCart.dataLoaded(response);
                },
                error   : function( response ) {
                    console.error(response);
                }
            });
        },
        visibleForm:function( products ) {
            if (products == null || products.length == 0) {
                $("#pqfw-frontend-form-wrap").css( "display", "none" );
            } else {
                $("#pqfw-frontend-form-wrap").css( "display", "block" );
            }
        },
        updateProduct: function (products) {
            pqfwCart.showLoader();

            $.ajax({
                url     : PQFW_OBJECT.ajaxurl,
                type    : 'POST',
                data    : { action: 'pqfw_update_products', products: products },
                success : function( response ) {
                    pqfwCart.dataLoaded(response);
                },
                error   : function( response ) {
                    console.error(response);
                }
            });
        },
    };


    pqfwCart.init();


})(jQuery);