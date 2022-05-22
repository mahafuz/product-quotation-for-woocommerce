(function($) {
    'use strict';

    /**
     * This file is responsible for handling the product quotation cart page.
     * 
     * @since 1.2.0
     */
    var pqfwCart = {

        init: function() {
            this.initialize();

            $(document)
				.on('click', '.pqfw-add-to-quotation-single', function (ev) {
				ev.preventDefault();
				pqfwCart.sendData(this);
			});

            $(document).on('click', '.pqfw-remove-product', function () {
                var $hash = $(this).data( 'id' );
                pqfwCart.removeProduct( $hash );
            });

            $(document).on('change', '.pqfw-quantity', function () {
                var new_quantity = $(this).val();
                var hash = $(this).data('hash');
                var single = $(this).data('single');

                window.pqfwProducts[hash]['quantity'] = new_quantity;
                window.pqfwProducts[hash]['price'] = Math.floor( single * new_quantity );
                var products = window.pqfwProducts;
                pqfwCart.updateProduct(products);
            });

            $(document).on('change', '.pqfw-message > textarea', function () {
                var new_message = $(this).val();

                var hash = $(this).data('hash');
                window.pqfwProducts[hash]['message'] = new_message;
                var products = window.pqfwProducts;
                pqfwCart.updateProduct(products);
            });
        },
        initialize: function() {
            pqfwCart.showLoader();

            wp.ajax.send(
                'pqfw_load_cart_data',
                {
                    data : {
                        nonce : PQFW_OBJECT.nonce
                    },
                    success : function( response ) {
                        pqfwCart.dataLoaded(response);
                    },
                    error   : function( response ) {
                        console.error(response);
                    }
                }
            )
        },
        sendData : function(button) {
			if( pqfwCart.variationAlert() ) {
				pqfwCart.setLoading(button);

                wp.ajax.send(
                    'pqfw_add_product',
                    {
                        data   : {
                            productID        : $(button).data('id'),
                            variationID      : pqfwCart.getVariationID(),
                            variationDetails : pqfwCart.getVariationDetails(),
                            quantity         : pqfwCart.getQuantity(),
                            nonce            : PQFW_OBJECT.nonce
                        },
                        success : function( response ) {
                            pqfwCart.addToQuotationCart(button);
                        },
                        error   : function( error ) {
                            console.log( error )
                        }
                    }
                )
			}
		},
		addToQuotationCart : function (button) {
			$(button).removeClass('loading');
			$(button).addClass('added');
			pqfwCart.viewQuotationCart(button);
		},
		viewQuotationCart : function (button) {
			var url = PQFW_OBJECT.cartPageUrl;
			if (url != false) {
				$(".pqfw-view-quotation-cart").remove();
				$(button).after('<a class="pqfw-view-quotation-cart"  href="' + url + '">' + PQFW_OBJECT.ViewCartLabel + '</a>');
			}
		},
		setLoading : function (button) {
			$(button).addClass('loading');
		},
		getVariationID : function () {
			var variation = $("form.variations_form input[name='variation_id']").val();
			return typeof variation != "undefined" && variation != 0 ? parseInt(variation) : 0;
		},
		getVariationDetails : function () {
			var variation  = $("form.variations_form input[name='variation_id']").val(),
				details    = {};

			if (typeof variation != "undefined" && variation != 0) {
				jQuery('select[name^=attribute_]').each(function (ind, obj) {
					details[jQuery(this).attr('name')] = jQuery(this).val();
				});
			}

			if ($.isEmptyObject(details)) {
				return 0;
			}

			return details;
		},
		getQuantity : function (id) {
			var quantity = $('form.cart input[name="quantity"]').val();
			return typeof quantity != "undefined" ? quantity : 1;
		},
		variationAlert : function () {
			if ( jQuery('.variation_id').length > 0 && jQuery('.variation_id').val() == '' || jQuery('.variation_id').val() == 0 ) {
				alert('Variation not selected');
				return false;
			}
			return true;
		},
        dataLoaded: function(response) {
            $('#pqfw-quotations-list-row').html( response.html );
            window.pqfwProducts = response.products;
            this.hideLoader();
            this.visibleForm(response.products);
        },
        hideLoader: function () {
            $('#pqfw-quotations-list-row').unblock();
        },
        showLoader: function() {
            $('#pqfw-quotations-list-row').block({
                message: ''
            });
            // $('#pqfw-quotations-list-row').block({
            //     message: '<img src="' + PQFW_OBJECT.loader + '" />',
            //     css: {
            //         width: '40px',
            //         height: '40px',
            //         top: '50%',
            //         left: '50%',
            //         border: '0px',
            //         backgroundColor: "transparent"
            //     },
            //     overlayCSS: {
            //         background: "#fff",
            //         opacity: .7
            //     }
            // });
        },
        removeProduct: function($hash) {
            pqfwCart.showLoader();

            wp.ajax.send(
                'pqfw_remove_product',
                {
                    data : {
                        hash  : $hash,
                        nonce : PQFW_OBJECT.nonce
                    },
                    success : function( response ) {
                        pqfwCart.dataLoaded(response);
                    },
                    error   : function( error ) {
                        console.error(error);
                    }
                }
            );
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

            wp.ajax.send(
                'pqfw_update_products',
                {
                    data    : {
                        products : products,
                        nonce    : PQFW_OBJECT.nonce
                    },
                    success : function( response ) {
                        pqfwCart.dataLoaded(response);
                    },
                    error   : function( error ) {
                        console.error(error);
                    }
                }
            )
        },
    };

    pqfwCart.init();
    window.pqfwCart = pqfwCart;

})(jQuery);