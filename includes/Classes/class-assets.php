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
		if ( defined( 'ELEMENTOR_PATH' ) ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_editor_style' ] );
		}
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
	}

	/**
	 * Print styles for the elementor editor.
	 *
	 * @since 2.0.3
	 */
	public function elementor_editor_style() {
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

	/**
	 * Loading admin css.
	 *
	 * @since 1.0.0
	 */
	public function assets() {
		$screen        = get_current_screen();
		$valid_screens = [
			'pqfw_quotations_page_pqfw-settings',
			'pqfw_quotations_page_pqfw-entries-page',
			'pqfw_quotations_page_pqfw-help',
			'pqfw_quotations'
		];

		if ( 'pqfw_quotations' === $screen->post_type ) {
			wp_enqueue_style(
				'pqfw-admin-quotations',
				PQFW_PLUGIN_URL . 'assets/css/pqfw-quotations.css',
				[], '1.0.0', 'all'
			);
		}

		if ( in_array( $screen->id, $valid_screens, true ) ) {
			wp_enqueue_style(
				'pqfw-admin',
				PQFW_PLUGIN_URL . 'assets/css/pqfw-admin.css',
				[], '1.0.0', 'all'
			);
		}
	}
}