<?php

namespace PQFW\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Admin class
 *
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */
class Admin {

	/**
	 * Single instance of the class
	 *
	 * @var \Admin
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Returns single instance of the class
	 *
	 * @return \Admin
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			return self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor of the class
	 *
	 * @return \Admin
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_css' ) );

	}

	/**
	 * Adding page on the database.
	 *
	 * @since   1.0.0
	 */
	public function add_menu_page() {

		add_menu_page(
			__( 'Entries', 'PQFW' ),
			__( 'Product Quotation', 'PQFW' ),
			'manage_options',
			'pqfw-entries-page',
			array( $this, 'display_product_quotation_page' ),
			null
		);

	}


	/**
	 * Loading admin css.
	 *
	 * @since 1.0.0
	 */
	public function admin_css() {

		$screen = get_current_screen();

		if ( $screen->id === 'toplevel_page_pqfw-entries-page' || $screen->id == 'product-quotation_page_pqfw-settings' ) {
			wp_enqueue_style( 'pqfw-admin', PQFW_PLUGIN_URL . 'assets/css/pqfw-admin.css' );
		}

	}

	/**
	 * Loading layout page tamplate.
	 *
	 * @since 1.0.0
	 */
	public function display_product_quotation_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			exit;
		}

		include PQFW_PLUGIN_VIEWS . 'layout.php';

	}


}