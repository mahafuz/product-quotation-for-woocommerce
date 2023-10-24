<?php
/**
 * Plugin Name: Products Quotation For WooCommerce
 * Plugin URI: https://github.com/mahafuz/product-quotation-for-woocommerce
 * Description: Removes the 'Add to cart' button from WooCommerce and adds a simple 'Request for quotation' form on all product pages instead of it.
 * Version: 2.0.5
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
 * Defining plugin constans
 *
 * @since 1.2.0
 */
define( 'PQFW_PLUGIN_FILE', __FILE__ );
define( 'PQFW_PLUGIN_NAME', __( 'Products Quotation For WooCommerce', 'pqfw' ) );
define( 'PQFW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'PQFW_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'PQFW_PLUGIN_LANGUAGES_PATH', plugin_dir_path( __FILE__ ) . 'languages/' );
define( 'PQFW_PLUGIN_VIEWS', trailingslashit( plugin_dir_path( __FILE__ ) . 'includes/Views' ) );
define( 'PQFW_PLUGIN_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
define( 'PQFW_PLUGIN_VERSION', '2.0.5' );
define( 'QUOTE_PACKER_PLUGIN_SLUG', 'quote-packer' );

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

add_action( 'plugins_loaded', function() {
	pqfw();
});

/**
 * Initialize the plugin tracker
 *
 * @since 1.2.0
 * @return void
 */
function appsero_init_tracker_product_quotation_for_woocommerce() {
	if ( ! class_exists( 'Appsero\Client' ) ) {
		require PQFW_PLUGIN_PATH . 'appsero/client/src/Client.php';
	}

	$client = new Appsero\Client(
		'e806fe7d-f314-425d-8be4-9f62fdaf71cf',
		'Product Quotation - Product Quotation For WooCommerce',
		__FILE__
	);

	// Active insights.
	$client->insights()->init();
}

appsero_init_tracker_product_quotation_for_woocommerce();
