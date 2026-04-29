import { __ } from '@wordpress/i18n';
import { makeRequest } from '@Utils/global';
import config from '@Utils/config';

import {
	getCartUrl,
	getQuantity,
	getVariationDetails,
	getVariationID,
	variationAlert,
} from '@Utils/cart';


const $ = jQuery || window?.jQuery;

document.addEventListener('DOMContentLoaded', () => {
	const viewQuotationCart = (button, label) => {
		const url = getCartUrl();

		if (url) {
			$('.pqfw-view-quotation-cart').remove();
			const link = document.createElement('a');
			link.className = 'pqfw-view-quotation-cart';
			link.href = url;
			link.textContent = label;
			$(button).after(link);
		}
	};

	$(document).on(
			'click',
			'.quotify-quote-btn-wrap > .pqfw-add-to-quotation-single',
			function (event) {
				event.preventDefault();

				if (variationAlert()) {
					const button = $(this);
					const productId = button.data('id');
					const loader = button.children('.loading-spinner');
					loader.addClass('loading');

					makeRequest({
						action: 'quotify/ajax/cart/add_product',
						data: {
							productID: parseInt(productId, 10),
							variationID: getVariationID(),
							variationDetails: getVariationDetails(),
							quantity: getQuantity(),
						},
					}).then((response) => {
						if (response.data?.success) {
							const settings = config?.settings || {};
							const btnLabel = settings?.cart_button_text || __('View Quotation Cart', 'quotify');
							viewQuotationCart(button, btnLabel);
							loader.removeClass('loading');
						} else {
							alert(response?.data?.message, 'error');
						}
					});
				}
			}
		);
});
