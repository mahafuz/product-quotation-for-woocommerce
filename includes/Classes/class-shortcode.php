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