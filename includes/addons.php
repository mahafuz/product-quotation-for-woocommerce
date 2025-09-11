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
		// $cf7 = \PQFW\Addons\ContactForm\ContactForm::init();

		add_action( 'wp_ajax_quotify/addons/get_all', [ $this, 'get_all' ] );
		add_action( 'wp_ajax_quotify/addons/save', [ $this, 'save' ] );
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
		wp_die();

		//phpcs:disable
		// $saved_addons = (array) json_decode( get_option( PQFW_ADDONS_SETTINGS_KEY ), true );
		// $saved_addons[ $addon ] = $status;

		// update_option( PQFW_ADDONS_SETTINGS_KEY, wp_json_encode( $saved_addons ) );

		// if ( $status ) {
		// 	do_action( "pqfw/addons/activated_{$addon}", $status );
		// } else {
		// 	do_action( "pqfw/addons/deactivated_{$addon}", $status );
		// }

		// wp_send_json_success( $saved_addons );
		//phpcs:enable
	}
}
