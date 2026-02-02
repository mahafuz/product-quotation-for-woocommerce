const $ = jQuery || window?.jQuery;
import config from '@Utils/config';

export function getCartUrl() {
    return config.cart.url;
}

export function variationAlert() {
    if (
        (jQuery('.variation_id').length > 0 &&
            jQuery('.variation_id').val() == '') ||
        jQuery('.variation_id').val() === 0
    ) {
        alert('Variation not selected');
        return false;
    }
    return true;
};

export const viewQuotationCart = (button) => {
    const url = getCartUrl();
    const btnLabel = 'View Quotation Cart';

    if (url != false) {
        $('.pqfw-view-quotation-cart').remove();
        $(button).after(
            '<a class="pqfw-view-quotation-cart"  href="' +
            url +
            '">' +
            btnLabel +
            '</a>'
        );
    }
};

export function getVariationDetails() {
    const variation = $(
            "form.variations_form input[name='variation_id']"
        ).val(),
        details = {};

    if (typeof variation != 'undefined' && variation != 0) {
        jQuery('select[name^=attribute_]').each(function (ind, obj) {
            details[jQuery(this).attr('name')] = jQuery(this).val();
        });
    }

    if (jQuery.isEmptyObject(details)) {
        return 0;
    }

    return details;
};

export function getVariationID() {
    const variation = jQuery(
        "form.variations_form input[name='variation_id']"
    ).val();
    return typeof variation != 'undefined' && variation != 0
        ? parseInt(variation)
        : 0;
};

export function getQuantity() {
    const quantity = jQuery('form.cart input[name="quantity"]').val();
    return typeof quantity != 'undefined' ? quantity : 1;
};