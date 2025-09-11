<?php
/**
 * Plugin Name: Products Quotation For WooCommerce
 * Plugin URI: https://github.com/mahafuz/product-quotation-for-woocommerce
 * Description: Removes the 'Add to cart' button from WooCommerce and adds a simple 'Request for quotation' form on all product pages instead of it.
 * Version: 2.0.4
 * Requires Plugins: woocommerce
 * Author: Mahafuz <m.mahfuz.me@gmail.com>
 * Author URI: https://github.com/mahafuz/
 * Text Domain: pqfw
 * Domain Path: /languages
 *
 * @package PQFW
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Defining plugin constants.
 *
 * @since 1.2.0
 */
define( 'PQFW_PLUGIN_FILE', __FILE__ );
define( 'PQFW_PLUGIN_NAME', __( 'Products Quotation For WooCommerce', 'quotify' ) );
define( 'PQFW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'PQFW_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'PQFW_PLUGIN_SLUG', 'pqfw-product-quotations' );

define( 'PQFW_PLUGIN_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
define( 'PQFW_PLUGIN_ROOT_URI', plugins_url( '/', __FILE__ ) );
define( 'PQFW_PLUGIN_ROOT_DIR_PATH', plugin_dir_path( __FILE__ ) );

define( 'PQFW_PLUGIN_ASSETS', trailingslashit( PQFW_PLUGIN_URL . 'assets' ) );
define( 'PQFW_PLUGIN_ASSETS_DIR', trailingslashit( PQFW_PLUGIN_PATH . 'assets' ) );
define( 'PQFW_ADDONS_DIR_PATH', trailingslashit( PQFW_PLUGIN_PATH . 'addons' ) );
define( 'PQFW_ADDONS_SETTINGS_KEY', 'pqfw_addons' );
define( 'PQFW_PLUGIN_LANGUAGES_PATH', plugin_dir_path( __FILE__ ) . 'languages/' );
define( 'PQFW_PLUGIN_VIEWS', trailingslashit( plugin_dir_path( __FILE__ ) . 'includes/views' ) );
define( 'PQFW_PLUGIN_VERSION', '2.0.4' );

/**
 * Initializing the plugin migration.
 *
 * @since 1.0.0
 */
register_activation_hook(__FILE__, function () {
	pqfw()->migration->run();
	add_option( '_pqfw_activation_redirect', true );
});

require PQFW_PLUGIN_PATH . 'includes/PQFW.php';

add_action( 'plugins_loaded', function () {
	pqfw();
});
