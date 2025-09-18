<?php
/**
 * Responsible for handling Ajax requests.
 *
 * @since 1.2.0
 * @package PQFW
 */

namespace PQFW\Ajax;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Registers Ajax requests.
 *
 * @since 2.5.0
 * @package Quotify
 */
class Settings {

	/**
	 * Class constructor.
	 *
	 * @since 2.5.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_quotify/settings/save', [ $this, 'save' ] );
		add_action( 'wp_ajax_quotify/settings/get_all', [ $this, 'get_all' ] );
		add_action( 'wp_ajax_quotify/cart/get_permalink', [ $this, 'getCartPermalink' ] );
	}

	/**
	 * Get cart permalink
	 *
	 * @since 2.0.1
	 */
	public function getCartPermalink() {
		check_ajax_referer( 'pqfw_nonce', 'security' );

		$pageID = isset( $_POST['pageID'] ) ? absint( $_POST['pageID'] ) : false;

		if ( ! $pageID ) {
			wp_send_json_error([
				'message' => esc_html__( 'Invalid Page ID.', 'quotify' ),
			], 400 );
		}

		wp_send_json_success([
			'url' => get_permalink( $pageID ),
		], 200 );
	}

	/**
	 * Get all plugin settings.
	 *
	 * @since 2.5.0
	 */
	public function get_all() {
		check_ajax_referer( 'pqfw_nonce', 'security' );

		return wp_send_json_success( pqfw()->settings->getAll() );
	}

	/**
	 * Save plugin settings.
	 *
	 * @since 2.5.0
	 */
	public function save() {
		pqfw()->settings->save();
	}
}
