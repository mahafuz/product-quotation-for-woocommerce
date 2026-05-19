import {makeRequest} from '@Utils/global';

(function ($) {
	'use strict';

	/**
	 * This file is responsible for handling the product quotation cart page.
	 *
	 * @since 1.2.0
	 */
	var QuotifyCart = {
		debounceTimer: null,

		init: function () {
			this.initialize();

			$(document).on('click', '.pqfw-remove-product', function (e) {
				e.preventDefault();

				const $hash = $(this).data('id');
				const $productElement = $(this).closest('.woocommerce-cart-form__cart-item');
				const $productName = $productElement.find('.product-name a').text().trim();

				// Show confirmation dialog.
				const confirmed = confirm(
					'Are you sure you want to remove "' + $productName + '" from your quotation cart?'
				);

				if (confirmed) {
					QuotifyCart.removeProduct($hash);
				}
			});

			$(document).on('change', '.pqfw-quantity', function () {
				const $input = $(this);
				const $new_quantity = $input.val();
				const hash = $input.data('hash');

				// Validate quantity
				if (!QuotifyCart.validateQuantity($new_quantity)) {
					alert('Please enter a valid quantity (minimum 1).');
					$input.val(window.pqfwProducts[hash]['quantity']);
					return;
				}

				// Clear previous debounce timer
				clearTimeout(QuotifyCart.debounceTimer);

				// Add loading state
				$input.addClass('updating');

				// Debounce the update
				QuotifyCart.debounceTimer = setTimeout(() => {
					// Update local state (server will recalculate price)
					window.pqfwProducts[hash]['quantity'] = parseInt($new_quantity);

					const products = window.pqfwProducts;
					QuotifyCart.updateProduct(products);
				}, 300);
			});

			$(document).on('change', '.pqfw-message > textarea', function () {
				const new_message = $(this).val();
				const hash = $(this).data('hash');

				window.pqfwProducts[hash]['message'] = new_message;
				const products = window.pqfwProducts;
				QuotifyCart.updateProduct(products);
			});
		},
		initialize: function () {
			makeRequest({
				action: 'quotify/ajax/cart/load'
			}).then((response) => {
				this.dataLoaded(response);
			});
		},
		sendData: function (button) {
			if (this.variationAlert()) {
				this.setLoading(button);

				makeRequest({
					action: 'quotify/ajax/cart/add_product',
					productID: $(button).data('id'),
					variationID: this.getVariationID(),
					variationDetails: this.getVariationDetails(),
					quantity: this.getQuantity(),
				}).then((response) => {
					if (response.data?.success) {
						this.addToQuotationCart(button);
						this.setLoading(button, false);
					} else {
						alert(response?.data?.data?.message, 'error');
						this.setLoading(button, false);
					}
				});
			}
		},
		addToQuotationCart: function (button) {
			$(button).removeClass('loading');
			$(button).addClass('added');
			this.viewQuotationCart(button);
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
		setLoading: function (button, status = true) {
			if ( status ) {
				$(button).addClass('loading');
			} else {
				$(button).removeClass('loading');
			}
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
			makeRequest({
				action: 'pqfw_remove_product',
				hash: $hash,
			}).then((response) => {
				if (response.data?.success) {
					this.dataLoaded(response);
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
				// Remove updating state from all quantity inputs
				$('.pqfw-quantity').removeClass('updating');

				if (response.data?.success) {
					this.dataLoaded(response);
				} else {
					this.showError(response?.data?.data || 'Failed to update cart. Please try again.');
				}
			}).catch((error) => {
				// Remove updating state on error
				$('.pqfw-quantity').removeClass('updating');
				this.showError('Network error. Please check your connection and try again.');
			});
		},

		validateQuantity: function(quantity) {
			const qty = parseInt(quantity);
			return !isNaN(qty) && qty > 0;
		},

		showError: function(message) {
			// Show error message as alert for now
			// TODO: Implement toast notifications
			alert(typeof message === 'string' ? message : 'An error occurred.');
		},
	};

	QuotifyCart.init();
	window.QuotifyCart = QuotifyCart;
})(jQuery);
