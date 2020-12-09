<?php
/**
 * Plugin Name: Product Quotation For WooCommerce
 * Plugin URI: https://github.com/mahafuz/product-quotation-for-woocommerce
 * Description: Removes the 'Add to cart' button from WooCommerce and adds a simple 'Request for quotation' form on all product pages instead of it.
 * Version: 1.0
 * Author: Mahafuz <m.mahfuz.me@gmail.com>
 * Author URI: https://github.com/mahafuz/
 * Text Domain: pqfw
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

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
 * Including spl autoloader globally.
 *
 * @since 1.0.0
 */
require PQFW_PLUGIN_PATH . 'autoload.php';

/**
 * Run the plugin after all other plugins.
 *
 * @since 1.0.0
 */
add_action( 'plugins_loaded', function () {
	\PQFW\Bootstrap::instance();
} );

/**
 * Initializing the plugin migration.
 *
 * @since 1.0.0
 */
register_activation_hook( __FILE__, function () {
	$migration = new \PQFW\Database\Migration();
	$migration->run();
} );