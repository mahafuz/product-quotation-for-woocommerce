<?php
/**
 * Quotify addons manager.
 *
 * @since 2.4.0
 * @package Quotify
 */

namespace Quotify\Ajax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Quotify\Library\Helper;

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
		add_action( 'wp_ajax_quotify/ajax/addons/get_all', [ $this, 'get_all' ] );
		add_action( 'wp_ajax_quotify/ajax/addons/save', [ $this, 'save' ] );
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

		wp_send_json_success(
			quotify()->addons()->get()
		);
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
		$status = ( isset( $_POST['status'] ) ? Helper::sanitize_checkbox_field( $_POST['status'] ) : false );

		if ( empty( $addon ) ) {
			wp_send_json_error( __( 'Addon Name missing', 'quotify' ) );
		}

		$updated = quotify()->addons()->save( $addon, $status );

		if ( $updated ) {
			do_action( "quotify/addons/activated_{$addon}", $status );//phpcs:ignore
		} else {
			do_action( "pqfw/addons/deactivated_{$addon}", $status );//phpcs:ignore
		}

		wp_send_json_success([
			'message'  => __( 'Addons has been updated.', 'quotify' ),
			'settings' => quotify()->addons()->get()
		]);
	}
}
