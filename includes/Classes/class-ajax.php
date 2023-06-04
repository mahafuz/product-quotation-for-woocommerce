<?php

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

class Ajax {
	/**
	 * Constructor of the class
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		error_log( 'hello world' );
		add_action( 'wp_ajax_pqfw_save_settings', [ $this, 'save_settings' ] );
		add_action( 'wp_ajax_pqfw_cart_get_permalink', [ $this, 'get_cart_permalink' ] );
	}

	/**
	 * Get cart permalink
	 *
	 * @since 2.0.1
	 */
	public function get_cart_permalink() {
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'pqfw-app-ui' ) ) {
			wp_send_json_error([
				'message' => esc_html__( 'Unauthorized Action', 'pqfw' )
			], 400 );
		}

		$pageID = isset( $_POST['pageID'] ) ? absint( $_POST['pageID'] ) : false;

		if ( ! $pageID ) {
			wp_send_json_error([
				'message' => esc_html__( 'Invalid Page ID.', 'pqfw' )
			], 400 );
		}

		wp_send_json_success([
			'url' => get_permalink( $pageID )
		], 200 );
	}

	/**
	 * Saving settings.
	 *
	 * @access  public
	 * @return  void
	 */
	public function save_settings() {
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'pqfw-app-ui' ) ) {
			wp_send_json_error([
				'message' => esc_html__( 'Unauthorized Action', 'pqfw' )
			], 400 );
		}

		$settings = isset( $_POST['settings'] ) ? (array) json_decode( wp_unslash( $_POST['settings'] ) ) : false;

		if ( ! is_array( $settings ) ) {
			wp_send_json_error([
				'message' => esc_html__( 'Invalid Settings.', 'pqfw' )
			], 400 );
		}

		$allowed   = pqfw()->settings->getAll();
		$sanitized = array_filter( $settings, function( $key ) use ( $allowed ) {
			return array_key_exists( $key, $allowed );
		}, ARRAY_FILTER_USE_KEY );

		if ( isset( $sanitized['quotation_cart_page'] ) && absint( get_option( 'pqfw_quotations_cart' ) ) !== absint( $sanitized['quotation_cart_page'] ) ) {
			update_option( 'pqfw_quotations_cart', absint( $sanitized['quotation_cart_page'] ) );
		}

		update_option( 'pqfw_settings', $sanitized );

		wp_send_json_success([
			'message' => esc_html__( 'Settings has been updated.', 'pqfw' )
		], 200 );
	}
}