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