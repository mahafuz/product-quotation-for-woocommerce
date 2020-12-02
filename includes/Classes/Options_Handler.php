<?php

/**
 * Manages the options form dashboard.
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */

namespace PQFW\Classes;


class Options_Handler {

	/**
	 * Single instance of the class
	 *
	 * @var \Form_Handler
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Returns single instance of the class
	 *
	 * @return \Form_Handler
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			return self::$instance = new self();
		}

		return self::$instance;
	}

	protected $default_options;

	protected $saved_options;

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array ( $this, 'enqueue_scripts' ) );
	}

	protected function get_saved_modules() {

		$this->default_options = array (
			'pqfw_form_default_design',
			'pqfw_floating_form'
		);

		$this->default_options = array_fill_keys( $this->default_options, true );
		$this->saved_options   = get_option( 'pqfw_options', $this->default_options );

		return wp_parse_args( $this->default_options, $this->saved_options );

	}

	public function enqueue_scripts() {
		$screen = get_current_screen();

		if ( $screen->id === "product-quotation_page_pqfw-settings" ) {
			wp_enqueue_script(
				'pqfw-options-handler', PQFW_PLUGIN_URL . 'assets/js/pqfw-options.js',
				array( 'jquery' ), PQFW_PLUGIN_VERSION, true
			);
		}
	}




}