/* eslint-disable camelcase */

import { __ } from '@wordpress/i18n';
import axios from 'axios';
import { toast } from 'react-toastify';
import { useLocation } from 'react-router-dom';

let config = Object.assign({}, window.PqfwGlobal);

export const {
	nonce,
	pqfw_nonce,
	rest_url,
	ajaxurl,
	namespace,
	plugin_root_url,
	plugin_root_path,
	site_url,
	route_path,
	menu,
	admin_url,
	dashboard,
	logout_url,
	woocommerce_is_active,
	woocommerce_notice,
	current_user_id,
	is_admin,
	is_rtl,
	addons,
	current_user_can,
	customize_url,
	woo_store,
	editor_settings,
	login_url,
	current_permalink,
	toplevel_menu_icon_url,
	toplevel_menu_title,
	plugin_logo,
	logo_url,
	version,
} = config;

export const useQuery = () => {
	return new URLSearchParams(useLocation().search);
};

export function getAjaxUrl() {
	return config.ajaxurl;
}

export function getNonce() {
	return config.pqfw_nonce;
}

export function getSavedSettings() {
	return config.settings;
}

export function getPages() {
	return config.pages;
}

export function getCart(field = 'url') {
	return config.cart?.url;
}

export const getAllAddons = () => {
	if (typeof addons != 'string') {
		return addons;
	}

	return JSON.parse(addons);
};

export const getAddonActiveStatus = (name, isPro = false) => {
	const allAddons = getAllAddons();

	return allAddons?.[name] ?? false;
};

export const getAddonInfo = (name) => {
	return [
		{
			label: __('Contact Form 7', 'quotify'),
			name: 'contact-form-7',
			is_pro: false,
			required_plugin: true,
			details: __(
				'Use contact form 7 as quotation submission form.',
				'quotify'
			),
			icon: 'https://ps.w.org/contact-form-7/assets/icon.svg',
			url: `${admin_url}admin.php?page=forms`,
			docsUrl: `https://wpindiedev.xyz/docs/contact-form-7/`,
		},
		{
			label: __('WPForms', 'quotify'),
			name: 'wpforms',
			is_pro: false,
			required_plugin: false,
			details: __('Use WPForms as quotation submission form.', 'quotify'),
			icon: 'https://ps.w.org/contact-form-7/assets/icon.svg',
			url: `${admin_url}admin.php?page=forms`,
			docsUrl: `https://wpindiedev.xyz/docs/wpforms/`,
		},
	].find((item) => name === item.name);
}

export const API = axios.create({
	baseURL: rest_url,
	headers: {
		'content-type': 'application/json',
		'X-WP-Nonce': nonce,
		'Cache-Control': 'no-cache', // Prevent caching
	},
});

export const makeRequest = async (payload = {}, isRaw = false) => {
	let form_data = new FormData(); // eslint-disable-line
	form_data.append('security', getNonce());
	Object.entries(payload).forEach(([key, value]) => {
		if (!isRaw && typeof value === 'object' && value !== null) {
			form_data.append(key, JSON.stringify(value));
		} else {
			form_data.append(key, value);
		}
	});

	return await axios.post(ajaxurl, form_data).then(
		(response) => {
			return response;
		},
		(error) => {
			fireNotify(error?.message, 'error');
			console.log(error); // eslint-disable-line
		}
	);
};

export const fireNotify = (message, type = '', position = 'top-right') => {
	switch (type) {
		case 'error':
			return toast.error(
				<div className="quotify-toasts">
					<div className="quotify-toasts__icon">
						<span className="quotify-icon quotify-icon--information quotify-icon--information-error"></span>
					</div>
					<p className="quotify-toasts-message">{message}</p>
				</div>,
				{
					position,
					hideProgressBar: true,
				}
			);
		case 'info':
			return toast.info(
				<div className="quotify-toasts">
					<div className="quotify-toasts__icon">
						<span className="quotify-icon quotify-icon--information"></span>
					</div>
					<p className="quotify-toasts-message">{message}</p>
				</div>,
				{
					position,
					hideProgressBar: true,
				}
			);
		case 'warning':
			return toast.warning(
				<div className="quotify-toasts">
					<div className="quotify-toasts__icon">
						<span className="quotify-icon quotify-icon--notification"></span>
					</div>
					<p className="quotify-toasts-message">{message}</p>
				</div>,
				{
					position,
					hideProgressBar: true,
				}
			);
		default:
			return toast.success(
				<div className="quotify-toasts">
					<div className="quotify-toasts__icon">
						<span className="quotify-icon quotify-icon--check"></span>
					</div>
					<p className="quotify-toasts-message">{message}</p>
				</div>,
				{
					position,
					hideProgressBar: true,
				}
			);
	}
};

export const renderError = (e) => {
	fireNotify(
		e?.response?.data?.message ? e?.response?.data?.message : e?.message,
		'error'
	);
};

export const variationAlert = () => {
	if (
		(jQuery('.variation_id').length > 0 &&
			jQuery('.variation_id').val() == '') ||
		jQuery('.variation_id').val() == 0
	) {
		alert('Variation not selected');
		return false;
	}
	return true;
};

export const viewQuotationCart = (button) => {
	const $ = jQuery;
	const url = PqfwGlobal?.cart?.url;
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

export const getVariationDetails = () => {
	var variation = jQuery(
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

export const getVariationID = () => {
	const variation = jQuery(
		"form.variations_form input[name='variation_id']"
	).val();
	return typeof variation != 'undefined' && variation != 0
		? parseInt(variation)
		: 0;
};

export const getQuantity = () => {
	var quantity = jQuery('form.cart input[name="quantity"]').val();
	return typeof quantity != 'undefined' ? quantity : 1;
};

export const sliceString = (text, length = 20, more = '...') => {
	if (!text || text.length < length) {
		return text;
	}
	return text.slice(0, length).replace(/(^[\s]+|[\s]+$)/g, '') + more;
};

export const getPageTitle = (page) => {
}

export const modalFullWidthStyles = {
	overlay: {
		background: 'rgba(35, 40, 45, 0.62)',
		zIndex: 9999,
	},
	content: {
		top: '50%',
		left: '50%',
		right: 'auto',
		bottom: 'auto',
		width: '50%',
		marginRight: '-50%',
		padding: 0,
		transform: 'translate(-50%, -50%)',
	},
};