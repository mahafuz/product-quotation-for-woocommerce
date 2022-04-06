<?php
/**
 * Responsible for registering shortocde.
 *
 * @since 1.2.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Registers shortcode for cart.
 *
 * @since 1.0.0
 */
class Shortcode {
	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_shortcode( 'pqfw_quotations_cart', [ $this, 'render' ] );
		add_filter( 'body_class', [ $this, 'addBodyClass' ] );
	}

	/**
	 * Add body class to the page.
	 *
	 * @since 1.2.0
	 */
	public function addBodyClass( $classes ) {
		$classes[] = 'pqfw-quotations-cart';
		$classes[] = 'woocommerce-cart';
		$classes[] = 'woocommerce-page';
		return $classes;
	}

	/**
	 * Render the shortocode.
	 *
	 * @since 1.0.0
	 * @return mixed      Rendered shortcode output.
	 */
	public function render() {
		ob_start();
			$products = pqfw()->quotations->getProducts();
				include PQFW_PLUGIN_VIEWS . 'partials/pqfw-cart-shortcode.php';
			$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
}