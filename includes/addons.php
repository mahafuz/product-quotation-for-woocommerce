<?php
namespace PQFW;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Addons {
	public function __construct() {
		// Load all addons
		$cf7 = \PQFW\Addons\Contactform\Contactform::init();

		// Addons
		add_action( 'wp_ajax_quotify/addons/get_all', array( $this, 'get_all' ) );
		add_action( 'wp_ajax_quotify/addons/save', array( $this, 'save' ) );
	}

	public function get_all() {
		check_ajax_referer( 'pqfw_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		$addons = json_decode( get_option( PQFW_ADDONS_SETTINGS_KEY, '{}' ) );
		wp_send_json_success( $addons );
	}

	public static function get_saved() {
		$addons = json_decode( get_option( PQFW_ADDONS_SETTINGS_KEY, '{}' ) );

		return wp_json_encode( $addons );
	}

	public function save() {
		check_ajax_referer( 'pqfw_nonce', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		$addon = ( isset( $_POST['addon'] ) ? sanitize_text_field( $_POST['addon'] ) : '' );
		$status = ( isset( $_POST['status'] ) ? pqfw()->helpers->sanitize_checkbox_field( $_POST['status'] ) : false );

		// echo '<pre>', var_dump( $addon ), '</pre>';	
		// echo '<pre>', var_dump( $status ), '</pre>';	


		if ( empty( $addon ) ) {
			wp_send_json_error( __( 'Addon Name missing', 'pqfw' ) );
		}

		// Saved Data
		$saved_addons = (array) json_decode( get_option( PQFW_ADDONS_SETTINGS_KEY ), true );
		$saved_addons[ $addon ] = $status;

		// echo '<pre>', var_dump( $saved_addons ), '</pre>';	


		update_option( PQFW_ADDONS_SETTINGS_KEY, wp_json_encode( $saved_addons ) );

		// Fire Addon Action
		if ( $status ) {
			do_action( "pqfw/addons/activated_{$addon}", $status );
		} else {
			do_action( "pqfw/addons/deactivated_{$addon}", $status );
		}

		// response
		wp_send_json_success( $saved_addons );
	}
}
