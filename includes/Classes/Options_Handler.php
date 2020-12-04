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

	protected $default_settings;

	protected $saved_settings;

	public function __construct() {
		add_action( 'admin_menu', array ( $this, 'add_settings_page' ) );
		add_action( 'wp_ajax_pqrf_save_settings', array ( $this, 'save_settings' ) );
		add_action( 'admin_enqueue_scripts', array ( $this, 'enqueue_scripts' ) );
	}

	/**
	 *
	 */
	public function add_settings_page() {
		add_submenu_page(
			'pqfw-entries-page',
			__( 'Settings', 'pqfw' ),
			__( 'Settings', 'pqfw' ),
			'manage_options',
			'pqfw-settings',
			array ( $this, 'display_pqfw_settings_page' ),
			null
		);
	}

	public function display_pqfw_settings_page() {
		$settings = $this->get_saved_settings();
		include PQFW_PLUGIN_VIEWS . 'partials/settings.php';
	}

	protected function get_saved_settings() {

		$this->default_settings = array (
			'pqfw_form_default_design',
			'pqfw_floating_form'
		);

		$this->default_settings = array_fill_keys( $this->default_settings, true );
		$this->saved_settings   = get_option( 'pqfw_settings', $this->default_settings );

		return wp_parse_args( $this->saved_settings, $this->default_settings );

	}

	public function enqueue_scripts() {
		$screen = get_current_screen();

		if ( $screen->id === "product-quotation_page_pqfw-settings" ) {
			wp_enqueue_script(
				'pqfw-options-handler', PQFW_PLUGIN_URL . 'assets/js/pqfw-options.js',
				array ( 'jquery' ), PQFW_PLUGIN_VERSION, true
			);

			wp_localize_script(
				'pqfw-options-handler',
				'PQFW_OBJECT',
				array (
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'actions' => array (
						'save_settings' => 'pqrf_save_settings'
					)
				)
			);
		}
	}

	public function save_settings() {

		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'pqfw_settings_form_action' ) ) {
			die( __( 'Unauthorized Action', 'pqfw' ) );
		}

		$name   = sanitize_text_field( $_REQUEST['name'] );
		$status = filter_var( $_REQUEST['status'], FILTER_VALIDATE_BOOLEAN );

		$settings = $this->get_saved_settings();

		if ( array_key_exists( $name, $settings ) ) {
			$settings[ $name ] = $status;
			update_option( 'pqfw_settings', $settings );

			wp_send_json_success( __( 'Settings Saved', 'pqfw' ) );
		}

		wp_send_json_error( __( 'Error while saving settings', 'pqfw' ) );

		die();
	}


}