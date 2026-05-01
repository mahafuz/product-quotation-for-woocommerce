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

// Track request state to prevent duplicate submissions
let isRequestInProgress = false;

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

				// Prevent duplicate requests
				if (isRequestInProgress) {
					alert(__('Please wait while we process your request.', 'quotify'), 'warning');
					return;
				}

				if (variationAlert()) {
					const button = $(this);
					const productId = button.data('id');
					const loader = button.children('.loading-spinner');

					// Store original button state
					const originalButtonDisabled = button.prop('disabled');

					// Set loading state
					isRequestInProgress = true;
					loader.addClass('loading');
					button.prop('disabled', true);

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
						} else {
							// Display error message from server
							const errorMessage = response?.data?.data || response?.data?.message || __('Failed to add product to quotation.', 'quotify');
							alert(errorMessage, 'error');
						}
					}).catch((error) => {
						// Handle any unexpected errors
						console.error('Quotify: Add to quotation error', error);
						alert(__('Failed to add product. Please try again.', 'quotify'), 'error');
					}).finally(() => {
						// Reset loading state
						isRequestInProgress = false;
						loader.removeClass('loading');
						button.prop('disabled', originalButtonDisabled);
					});
				}
			}
		);
});
