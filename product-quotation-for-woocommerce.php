<?php
/**
 * Plugin Name: Product Quotation For WooCommerce
 * Plugin URI: https://github.com/mahafuz/product-quotation-for-woocommerce
 * Description: Removes the 'Add to cart' button from WooCommerce and adds a simple 'Request for quotation' form on all product pages instead of it.
 * Version: 1.0.0
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
 * @since 1.0.0
 */
define( 'PQFW_PLUGIN_FILE', __FILE__ );
define( 'PQFW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'PQFW_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'PQFW_PLUGIN_VIEWS', trailingslashit( plugin_dir_path( __FILE__ ) . 'includes/Views' ) );
define( 'PQFW_PLUGIN_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
define( 'PQFW_PLUGIN_VERSION', '1.0.0' );

/**
 * Initializing the plugin migration.
 *
 * @since 1.0.0
 */
register_activation_hook(__FILE__, function () {
	$migration = new \PQFW\Classes\Migration();
	$migration->run();
	$migration->createCartPage();
});

require PQFW_PLUGIN_PATH . 'includes/PQFW.php';

add_action( 'plugins_loaded', function () {
	if ( ! function_exists( 'pqfw' ) ) {

		/**
		 * Run the plugin after all other plugins.
		 *
		 * @since 1.0.0
		 */
		function pqfw() {
			return \PQFW\PQFW::instance();
		}

		pqfw();
	}
});