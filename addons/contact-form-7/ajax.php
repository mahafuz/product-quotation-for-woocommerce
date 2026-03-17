<?php
/**
 * Elementor widget that inserts an embed-able content into the page, from any given URL.
 *
 * @since 2.0.3
 * @package Quotify
 */

namespace QuotifyContact_Form_7;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Contact form 7 support for the plugin.
 *
 * @since 2.5.0
 */
class Ajax {

	/**
	 * Initializer for the addon.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_quotify/ajax/addons/contact_form_7/get_all_forms', [ $this, 'get_all_forms' ] );
		add_action( 'wp_ajax_quotify/ajax/addons/contact_form_7/save_settings', [ $this, 'save_settings' ] );
		add_action( 'wp_ajax_quotify/ajax/addons/contact_form_7/get_settings', [ $this, 'get_settings' ] );
	}

	/**
	 * Fetch all contact form 7.
	 *
	 * @return void
	 */
	public function get_all_forms() {
		check_ajax_referer( 'quotify_ajax', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		$default = [
			'value' => 0,
			'label' => __( '--Select--', 'quotify' ),
		];

		$forms = \QuotifyContact_Form_7\Hook::find();
		$forms = [
			$default,
			...$forms,
		];

		wp_send_json( $forms );
	}

	/**
	 * Save settings for cf7 addon.
	 *
	 * @return void
	 */
	public function save_settings() {
		check_ajax_referer( 'quotify_ajax', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid operation', 'quotify' ),
			]);
		}

		$settings = ! empty( $_POST['settings'] ) ? sanitize_text_field( $_POST['settings'] ) : '{}';
		\QuotifyContact_Form_7\Database::save_settings( $settings );
		$settings = json_decode( wp_unslash( \QuotifyContact_Form_7\Database::get_setting( 'all', [], true ) ), true );

		wp_send_json_success([
			'message'  => __( 'Settings updated.', 'quotify' ),
			'settings' => $settings,
		]);
	}

	/**
	 * Get settings for cf7 addon.
	 *
	 * @return void
	 */
	public function get_settings() {
		check_ajax_referer( 'quotify_ajax', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid operation', 'quotify' ),
			]);
		}

		$settings = json_decode( wp_unslash( \QuotifyContact_Form_7\Database::get_setting( 'all', [], true ) ), true );

		wp_send_json_success([
			'message'  => __( 'Settings fetched.', 'quotify' ),
			'settings' => $settings,
		]);
	}
}
