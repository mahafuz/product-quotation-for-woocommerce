const $ = jQuery || window?.jQuery;
import config from '@Utils/config';

export function getCartUrl() {
	return config?.cart?.url;
}

export function variationAlert() {
	const $variation = $('.variation_id');

	if ($variation.length && (!$variation.val() || $variation.val() === '0')) {
		alert('Variation not selected');
		return false;
	}
	return true;
}

export const viewQuotationCart = (button) => {
	const url = getCartUrl();

	if (url) {
		$('.pqfw-view-quotation-cart').remove();
		const link = document.createElement('a');
		link.className = 'pqfw-view-quotation-cart';
		link.href = url;
		link.textContent = 'View Quotation Cart';
		$(button).after(link);
	}
};

export function getVariationDetails() {
	const variation = $("form.variations_form input[name='variation_id']").val();
	const details = {};

	if (variation) {
		$('select[name^=attribute_]').each(function () {
			details[this.name] = this.value;
		});
	}

	return Object.keys(details).length ? details : 0;
}

export function getVariationID() {
	const variation = $("form.variations_form input[name='variation_id']").val();
	return variation ? parseInt(variation, 10) : 0;
}

export function getQuantity() {
	const quantity = $('form.cart input[name="quantity"]').val();
	return parseInt(quantity, 10) || 1;
}
