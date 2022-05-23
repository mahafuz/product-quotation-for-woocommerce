const config = Object.assign({}, window.PQFW_OBJECT);

delete window.PQFW_OBJECT.ajaxurl;
delete window.PQFW_OBJECT.nonce;
delete window.PQFW_OBJECT.settings;

export function getAjaxUrl() {
    return config.ajaxurl;
}

export function getNonce() {
    return config.nonce;
}

export function getSavedSettings() {
    return config.settings;
}

export function getPages() {
    return config.pages;
}

export function getCart( field = 'url') {
    return config.cart?.url;
}

export function translate( key ) {
    return config.strings[key];
}