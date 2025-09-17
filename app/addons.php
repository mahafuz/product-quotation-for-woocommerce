<?php
/**
 * Quotify addons manager.
 *
 * @since 2.4.0
 * @package Quotify
 */

namespace PQFW;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Responsible for managing plugin addons.
 *
 * @since 2.4.0
 * @package Quotify
 */
class Addons {

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->addons_loader();

		add_action( 'wp_ajax_quotify/addons/get_all', [ $this, 'get_all' ] );
		add_action( 'wp_ajax_quotify/addons/save', [ $this, 'save' ] );
	}

	/**
	 * Loads all addons for the plugin.
	 *
	 * @since 2.5.0
	 */
	private function addons_loader() {
		$Autoload = Autoload::get_instance();

		$addons = apply_filters(
			'quotify/addons/loader_args', //phpcs:ignore
			[
				'contact-form-7' => 'Contact_Form_7',
			]
		);

		foreach ( $addons as $addon_name => $addon_class_name ) {
			$addon_root_path = PQFW_PLUGIN_ROOT_DIR_PATH . 'addons' . DIRECTORY_SEPARATOR . $addon_name . '/';

			// Register the addon's root namespace and path.
			$addon_namespace = 'Quotify' . $addon_class_name;

			$Autoload->add_namespace_directory( $addon_namespace, $addon_root_path );

			// Initialize the addon's main class.
			$class = $addon_namespace . '\\' . $addon_class_name;

			$class::init();
		}
	}

	/**
	 * Get addons collection
	 *
	 * @return void
	 */
	public function get_all() {
		check_ajax_referer( 'pqfw_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		$addons = json_decode( get_option( PQFW_ADDONS_SETTINGS_KEY, '{}' ) );
		wp_send_json_success( $addons );
	}

	/**
	 * Get saved addons.
	 *
	 * @return json
	 */
	public static function get_saved() {
		$addons = json_decode( get_option( PQFW_ADDONS_SETTINGS_KEY, '{}' ) );

		return wp_json_encode( $addons );
	}

	/**
	 * Save addons settings.
	 *
	 * @return void
	 */
	public function save() {
		check_ajax_referer( 'pqfw_nonce', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		$addon = ( isset( $_POST['addon'] ) ? sanitize_text_field( $_POST['addon'] ) : '' );
		$status = ( isset( $_POST['status'] ) ? pqfw()->helpers->sanitize_checkbox_field( $_POST['status'] ) : false );

		if ( empty( $addon ) ) {
			wp_send_json_error( __( 'Addon Name missing', 'quotify' ) );
		}

		$saved_addons = (array) json_decode( get_option( PQFW_ADDONS_SETTINGS_KEY ), true );
		$saved_addons[ $addon ] = $status;

		update_option( PQFW_ADDONS_SETTINGS_KEY, wp_json_encode( $saved_addons ) );

		if ( $status ) {
			do_action( "quotify/addons/activated_{$addon}", $status );//phpcs:ignore
		} else {
			do_action( "pqfw/addons/deactivated_{$addon}", $status );//phpcs:ignore
		}

		wp_send_json_success( $saved_addons );
	}
}
