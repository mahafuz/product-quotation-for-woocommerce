const config = {
    ...window?.QUOTIFY_CONFIG
};

export function ajaxNonce() {
    return config?.nonce?.ajax;
}

export function adminMenu() {
    return config?.menu;
}

export function getMenuTitle() {
    return config?.toplevel_menu_title;
}

export function getMenuIconUrl() {
    return config?.toplevel_menu_icon_url;
}

export function getWCNotice() {
    return config?.woocommerce_notice;
}

export function getSavedSettings() {
    return config?.settings;
}

export function getLogoUrl() {
    return config?.logo_url;
}

export function getPluginLogo() {
    return config?.plugin_logo;
}

export default config;