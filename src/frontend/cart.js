import {makeRequest} from '@Utils/http';

(function ($) {
	'use strict';

	/**
	 * This file is responsible for handling the product quotation cart page.
	 *
	 * @since 1.2.0
	 */
	var pqfwCart = {
		init: function () {
			this.initialize();

			$(document).on('click', '.pqfw-remove-product', function () {
				const $hash = $(this).data('id');
				pqfwCart.removeProduct($hash);
			});

			$(document).on('change', '.pqfw-quantity', function () {
				const $new_quantity = $(this).val();

				const hash   = $(this).data('hash');
				const single = $(this).data('single');

				window.pqfwProducts[hash]['quantity'] = $new_quantity;

				window.pqfwProducts[hash]['price'] = Math.floor(
					single * $new_quantity
				);


				const products = window.pqfwProducts;
				pqfwCart.updateProduct(products);
			});

			$(document).on('change', '.pqfw-message > textarea', function () {
				const new_message = $(this).val();
				const hash = $(this).data('hash');

				window.pqfwProducts[hash]['message'] = new_message;
				const products = window.pqfwProducts;
				pqfwCart.updateProduct(products);
			});
		},
		initialize: function () {
			makeRequest({
				action: 'quotify/ajax/cart/load'
			}).then((response) => {
				pqfwCart.dataLoaded(response);
			});
		},
		sendData: function (button) {
			if (pqfwCart.variationAlert()) {
				pqfwCart.setLoading(button);

				makeRequest({
					action: 'quotify/ajax/cart/add_product',
					productID: $(button).data('id'),
					variationID: pqfwCart.getVariationID(),
					variationDetails: pqfwCart.getVariationDetails(),
					quantity: pqfwCart.getQuantity(),
				}).then((response) => {
					if (response.data?.success) {
						pqfwCart.addToQuotationCart(button);
					} else {
						alert(response?.data?.data?.message, 'error');
					}
				});
			}
		},
		addToQuotationCart: function (button) {
			$(button).removeClass('loading');
			$(button).addClass('added');
			pqfwCart.viewQuotationCart(button);
		},
		viewQuotationCart: function (button) {
			var url = PQFW_OBJECT.cartPageUrl;
			if (url != false) {
				$('.pqfw-view-quotation-cart').remove();
				$(button).after(
					'<a class="pqfw-view-quotation-cart"  href="' +
						url +
						'">' +
						PQFW_OBJECT.ViewCartLabel +
						'</a>'
				);
			}
		},
		setLoading: function (button) {
			$(button).addClass('loading');
		},
		getVariationID: function () {
			var variation = $(
				"form.variations_form input[name='variation_id']"
			).val();
			return typeof variation != 'undefined' && variation != 0
				? parseInt(variation)
				: 0;
		},
		getVariationDetails: function () {
			var variation = $(
					"form.variations_form input[name='variation_id']"
				).val(),
				details = {};

			if (typeof variation != 'undefined' && variation != 0) {
				jQuery('select[name^=attribute_]').each(function (ind, obj) {
					details[jQuery(this).attr('name')] = jQuery(this).val();
				});
			}

			if ($.isEmptyObject(details)) {
				return 0;
			}

			return details;
		},
		getQuantity: function (id) {
			var quantity = $('form.cart input[name="quantity"]').val();
			return typeof quantity != 'undefined' ? quantity : 1;
		},
		variationAlert: function () {
			if (
				(jQuery('.variation_id').length > 0 &&
					jQuery('.variation_id').val() == '') ||
				jQuery('.variation_id').val() == 0
			) {
				alert('Variation not selected');
				return false;
			}
			return true;
		},
		dataLoaded: function (response) {
			const $cart_preview  = response?.data?.data?.html;
			const $cart_products = response?.data?.data?.products;
			$('#pqfw-quotations-list-row').html( $cart_preview );
			window.pqfwProducts = $cart_products;
			this.visibleForm( $cart_products );
		},
		removeProduct: function ($hash) {
			// pqfwCart.showLoader();

			makeRequest({
				action: 'pqfw_remove_product',
				hash: $hash,
			}).then((response) => {
				if (response.data?.success) {
					pqfwCart.dataLoaded(response);
				} else {
					// alert(response?.data?.data?.message, 'error');
				}
			});
		},
		visibleForm: function (products) {
			if ('{}' == products || products == null || products.length == 0) {
				$('#pqfw-frontend-form-wrap').css('display', 'none');
			} else {
				$('#pqfw-frontend-form-wrap').css('display', 'block');
			}
		},
		updateProduct: function (products) {
			makeRequest({
				action: 'quotify/ajax/cart/update',
				products: products,
			}).then((response) => {
				if (response.data?.success) {
					pqfwCart.dataLoaded(response);
				} else {
					// alert(response?.data?.data?.message, 'error');
				}
			});
		},
	};

	pqfwCart.init();
	window.pqfwCart = pqfwCart;
})(jQuery);
