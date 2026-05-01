import axios from 'axios';
import config from '@Utils/config';

export function getNonce(action_type) {
    if ( 'ajax' === action_type ) {
        return config?.nonce?.ajax;
    }

    if ( 'cart' === action_type ) {
        return config?.nonce?.cart;
    }

    if ( 'rest' === action_type ) {
        return config?.nonce?.rest;
    }

    return config?.nonce?.action_type;
}

export function getAjaxUrl() {
    return config?.ajaxurl;
}

export function getRestUrl() {
    return config.rest_url;
}

export function getPages() {
    return config.pages;
}

export function getRoutePath() {
    return config.route_path;
}

export function isAdmin() {
    return config?.is_admin;
}

export function getAdminUrl() {
    return config?.admin_url;
}

export function isPro() {
    return config?.is_pro;
}

export function currentUserCan() {
    return config?.current_user_can;
}

export function getCurrentUserId() {
    return config?.current_user_id;
}

export const sliceString = (text, length = 20, more = '...') => {
    if (!text || text.length < length) {
        return text;
    }
    return text.slice(0, length).replace(/(^[\s]+|[\s]+$)/g, '') + more;
};

export const makeRequest = async (payload = {}, isRaw = false) => {
	try {
		// Validate config
		if (!config || !config.ajaxurl) {
			console.error('Quotify: Configuration not loaded. Please refresh the page.');
			return {
				data: {
					success: false,
					data: 'Configuration not loaded. Please refresh the page.'
				}
			};
		}

		// Validate payload
		if (!payload || typeof payload !== 'object') {
			console.error('Quotify: Invalid payload provided');
			return {
				data: {
					success: false,
					data: 'Invalid request data.'
				}
			};
		}

		// Validate nonce
		const nonce = getNonce('ajax');
		if (!nonce) {
			console.error('Quotify: Security nonce not available');
			return {
				data: {
					success: false,
					data: 'Security check failed. Please refresh the page.'
				}
			};
		}

		let form_data = new FormData(); // eslint-disable-line
		form_data.append('security', nonce);

		Object.entries(payload).forEach(([key, value]) => {
			if (!isRaw && typeof value === 'object' && value !== null) {
				form_data.append(key, JSON.stringify(value));
			} else {
				form_data.append(key, value);
			}
		});

		const response = await axios.post(config.ajaxurl, form_data);

		// Validate response structure
		if (!response || !response.data) {
			console.error('Quotify: Invalid response structure', response);
			return {
				data: {
					success: false,
					data: 'Invalid server response. Please try again.'
				}
			};
		}

		return response;
	} catch (error) {
		// Handle different error types with specific messages
		if (error.response) {
			// Server responded with error status
			const errorMessage = error.response.data?.data || error.response.data?.message || 'Server error occurred';
			console.error('Quotify: Server error', errorMessage);
			return {
				data: {
					success: false,
					data: typeof errorMessage === 'string' ? errorMessage : 'Server error occurred'
				}
			};
		} else if (error.request) {
			// Request was made but no response received
			console.error('Quotify: Network error - no response received');
			return {
				data: {
					success: false,
					data: 'Network error. Please check your internet connection.'
				}
			};
		} else {
			// Other error (e.g., request setup error)
			console.error('Quotify: Request error', error.message);
			return {
				data: {
					success: false,
					data: error.message || 'An unexpected error occurred. Please try again.'
				}
			};
		}
	}
};

export const API = axios.create({
    baseURL: getRestUrl(),
    headers: {
        'content-type': 'application/json',
        'X-WP-Nonce': getNonce(),
        'Cache-Control': 'no-cache', // Prevent caching
    },
});