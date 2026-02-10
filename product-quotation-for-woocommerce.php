<?php
/**
 * Plugin Name: Products Quotation For WooCommerce
 * Plugin URI: https://github.com/mahafuz/product-quotation-for-woocommerce
 * Description: Removes the 'Add to cart' button from WooCommerce and adds a simple 'Request for quotation' form on all product pages instead of it.
 * Version: 2.5.0
 * Requires Plugins: woocommerce
 * Author: Mahafuz <m.mahfuz.me@gmail.com>
 * Author URI: https://github.com/mahafuz/
 * Text Domain: quotify
 * Domain Path: /languages
 *
 * @package Quotify
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Defining plugin constants.
 *
 * @since 1.2.0
 */
if ( ! defined( 'QUOTIFY_PLUGIN_FILE' ) ) {
	define( 'QUOTIFY_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'QUOTIFY_PLUGIN_BASENAME' ) ) {
	define( 'QUOTIFY_PLUGIN_BASENAME', plugin_basename( QUOTIFY_PLUGIN_FILE ) );
}

if ( ! defined( 'QUOTIFY_PLUGIN_ROOT_PATH' ) ) {
	define( 'QUOTIFY_PLUGIN_ROOT_PATH', plugin_dir_path( __FILE__ ) );
}

// Load autoloader.
require __DIR__ . '/app/autoload.php';

if ( ! \Quotify\Autoload::init() ) {
	return;
}

// Include the main plugin class.
if ( ! class_exists( 'Quotify', false ) ) {
	include_once dirname( QUOTIFY_PLUGIN_FILE ) . '/app/quotify.php';
}


require __DIR__ . '/app/install.php';

if ( ! function_exists( 'quotify' ) ) {
	/**
	 * Returns the plugin main class.
	 *
	 * @return \Quotify
	 */
	function quotify() {
		return \Quotify::instance();
	}

	quotify();
}
