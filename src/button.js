import {
	getQuantity,
	getVariationDetails,
	getVariationID,
	variationAlert,
	viewQuotationCart,
	makeRequest,
	fireNotify
} from '@Utils/helper';

document.addEventListener('DOMContentLoaded', () => {
	const $ = jQuery;

	$(document).on(
		'click',
		'.quotify-quote-btn-wrap > .pqfw-add-to-quotation-single',
		function (event) {
			event.preventDefault();

			if (variationAlert()) {
				const button = $(this);
				const $product_id = button.data('id');
				const loader = button.children('.loading-spinner');

				wp.ajax.send('quotify/ajax/cart/add_product', {
					data: {
						productId: parseInt($product_id),
						variationID: parseInt(getVariationID()),
						variationDetails: getVariationDetails(),
						quantity: parseInt(getQuantity()),
						security: PqfwGlobal.pqfw_nonce,
					},
					beforeSend: function () {
						loader.addClass('loading');
					},
					success: function (response) {
						loader.removeClass('loading');
						viewQuotationCart(button);
					},
					error: function (error) {
						console.log(error);
					},
				});
			}
		}
	);
});
