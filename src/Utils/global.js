import config from '@Utils/config';

export function getNonce() {
    return config.pqfw_nonce;
}

export function getAjaxUrl() {
    return config.ajaxurl;
}

export function getRestUrl() {
    return config.rest_url;
}

export function getPages() {
    return config.pages;
}

export const sliceString = (text, length = 20, more = '...') => {
    if (!text || text.length < length) {
        return text;
    }
    return text.slice(0, length).replace(/(^[\s]+|[\s]+$)/g, '') + more;
};