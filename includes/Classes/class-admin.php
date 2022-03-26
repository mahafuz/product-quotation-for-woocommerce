<?php

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @since   1.0.0
 * @package PQFW
 */
class Admin {

	/**
	 * Constructor of the class
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'menus' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
	}

	/**
	 * Adding page on the database.
	 *
	 * @since   1.0.0
	 */
	public function menus() {
		add_menu_page(
			__( 'Entries', 'PQFW' ),
			__( 'Product Quotation', 'PQFW' ),
			'manage_options',
			'pqfw-entries-page',
			[ $this, 'display' ],
			PQFW_PLUGIN_URL . 'assets/images/pqfw-dashboard-icon.png'
		);
	}

	/**
	 * Loading admin css.
	 *
	 * @since 1.0.0
	 */
	public function assets() {
		$screen = get_current_screen();

		if ( 'toplevel_page_pqfw-entries-page' === $screen->id || 'product-quotation_page_pqfw-settings' === $screen->id ) {
			wp_enqueue_style(
				'pqfw-admin',
				PQFW_PLUGIN_URL . 'assets/css/pqfw-admin.css',
				[], '1.0.0', 'all'
			);
		}
	}

	/**
	 * Loading layout page tamplate.
	 *
	 * @since 1.0.0
	 */
	public function display() {
		include PQFW_PLUGIN_VIEWS . 'layout.php';
	}
}