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
    let form_data = new FormData(); // eslint-disable-line
    form_data.append('security', getNonce('ajax'));

    Object.entries(payload).forEach(([key, value]) => {
        if (!isRaw && typeof value === 'object' && value !== null) {
            form_data.append(key, JSON.stringify(value));
        } else {
            form_data.append(key, value);
        }
    });

    return await axios.post(config?.ajaxurl, form_data).then(
        (response) => {
            return response;
        },
        (error) => {
            console.log(error); // eslint-disable-line
        }
    );
};

export const API = axios.create({
    baseURL: getRestUrl(),
    headers: {
        'content-type': 'application/json',
        'X-WP-Nonce': getNonce(),
        'Cache-Control': 'no-cache', // Prevent caching
    },
});