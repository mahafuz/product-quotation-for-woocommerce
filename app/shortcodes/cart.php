<?php
/**
 * Responsible for registering shortcode.
 *
 * @since 1.2.0
 * @package Quotify
 */

namespace Quotify\Shortcodes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Registers shortcode for cart.
 *
 * @since 1.0.0
 */
class Cart {

	/**
	 * Class instance.
	 *
	 * @var \Quotify\Shortcodes\Cart
	 */
	private static $instance = null;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return \Quotify\Shortcodes\Cart
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		add_shortcode( 'pqfw_quotations_cart', [ $this, 'render' ] );

		add_filter( 'body_class', [ $this, 'addBodyClass' ] );
	}

	/**
	 * Add body class to the page.
	 *
	 * @since 1.2.0
	 * @param array $classes Array of classes.
	 * @return array          Array of classes.
	 */
	public function addBodyClass( $classes ) {
		$classes[] = 'pqfw-quotations-cart';
		$classes[] = 'woocommerce-cart';
		$classes[] = 'woocommerce-page';
		return $classes;
	}

	/**
	 * Render the shortcode.
	 *
	 * @param array $products The products.
	 *
	 * @since 1.0.0
	 * @return mixed      Rendered shortcode output.
	 */
	public function render( array $products = [] ) {
		if ( empty( $products ) || ! is_array( $products ) ) {
			$products = quotify()->cart()->get_products();
		}

		ob_start();
				include QUOTIFY_PLUGIN_VIEWS . 'pqfw-cart-shortcode.php';
			$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
}
