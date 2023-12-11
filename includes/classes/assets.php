<?php

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @since   2.0.3
 * @package PQFW
 */
class Assets {

	/**
	 * Constructor of the class
	 *
	 * @since  2.0.3
	 * @return void
	 */
	public function __construct() {

	}

	/**
	 * Print styles for the elementor editor.
	 *
	 * @since 2.0.3
	 */
	public function elmentorEditorStyle() {
		?>
		<style>
			body #elementor-panel-elements-wrapper .icon .pqfw-quote-cart-icon {
				background: url('https://ps.w.org/product-quotation-for-woocommerce/assets/icon-128x128.png?rev=2445332') no-repeat center center;
				background-size: contain;
				height: 29px;
				display: block;
			}
		</style>
		<?php
	}

}