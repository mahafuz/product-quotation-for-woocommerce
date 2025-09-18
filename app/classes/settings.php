<?php
/**
 * Responsible for handling the plugin settings.
 *
 * @since 1.2.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages the options form dashboard.
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */
class Settings {

	/**
	 * Contain default settings.
	 *
	 * @var   array Default settings.
	 * @since 1.0.0
	 */
	protected $default;

	/**
	 * Contain saved settings.
	 *
	 * @var   array Saved settings.
	 * @since 1.0.0
	 */
	protected $saved;

	/**
	 * Contain saved and unsaved settings.
	 *
	 * @var   array All combined settings.
	 * @since 1.0.0
	 */
	private $all;

	/**
	 * Process and return the saved(wp_options) settings.
	 *
	 * @access  protected
	 * @return  array $settings
	 */
	public function getAll() {
		$this->default = [
			'pqfw_form_default_design'       => true,
			'pqfw_floating_form'             => true,
			'pqfw_shop_page_button'          => true,
			'pqfw_product_page_button'       => true,
			'pqfw_form_send_mail'            => true,
			'pqfw_send_mail_to_customer'     => true,
			'recipient'                      => sanitize_email( get_option( 'admin_email' ) ),
			'button_hover_color'             => '',
			'button_hover_bg_color'          => '',
			'button_normal_color'            => '',
			'button_normal_bg_color'         => '',
			'button_font_size'               => '',
			'button_width'                   => '',
			'button_text'                    => __( 'Add to Quote', 'quotify' ),
			'hide_add_to_cart_button'        => false,
			'hide_product_prices'            => false,
			'button_position'                => 'woocommerce_after_shop_loop_item',
			'button_position_single_product' => 'woocommerce_after_add_to_cart_quantity',
			'privacy_policy'                 => false,
			'privacy_policy_label'           => __( 'I have read and agree to the website terms and conditions.', 'quotify' ),
			'privacy_policy_content'         => __(
				'Your personal data will be used to process your request, support your experience throughout this website, and for other purposes described in our  [privacy_policy].',
				'quotify'
			),
			'quotation_cart_page'            => pqfw()->helpers->getCart(),
		];

		$this->saved = get_option( 'pqfw_settings', $this->default );
		$this->all   = wp_parse_args( $this->saved, $this->default );

		return $this->all;
	}

	/**
	 * Enqueue scripts and stuffs for settings page.
	 *
	 * @access  public
	 * @return  void
	 */
	public function assets() {
		$screen = get_current_screen();

		if ( 'pqfw_quotations_page_pqfw-settings' === $screen->id ) {

			$dependencies = require_once PQFW_PLUGIN_PATH . 'build/index.asset.php';
			array_push( $dependencies['dependencies'], 'wp-util' );

			wp_enqueue_style(
				'pqfw-app',
				PQFW_PLUGIN_URL . 'build/index.css',
				[ 'wp-components' ],
				PQFW_PLUGIN_VERSION,
				'all'
			);

			wp_register_script(
				'pqfw-app',
				PQFW_PLUGIN_URL . 'build/index.js',
				$dependencies['dependencies'],
				PQFW_PLUGIN_VERSION,
				true
			);

			wp_enqueue_script( 'pqfw-app' );
			load_plugin_textdomain( 'quotify', false, PQFW_PLUGIN_LANGUAGES_PATH );
		}
	}

	/**
	 * Saving settings.
	 *
	 * @access  public
	 * @return  void
	 */
	public function save() {
		if ( ! isset( $_REQUEST['security'] ) || ! wp_verify_nonce( $_REQUEST['security'], 'pqfw_nonce' ) ) {
			wp_send_json_error([
				'message' => esc_html__( 'Unauthorized Action', 'quotify' ),
			], 400 );
		}

		$settings = isset( $_POST['settings'] ) ? (array) json_decode( wp_unslash( $_POST['settings'] ) ) : false;

		if ( ! is_array( $settings ) ) {
			wp_send_json_error([
				'message' => esc_html__( 'Invalid Settings.', 'quotify' ),
			], 400 );
		}

		$allowed   = $this->getAll();
		$sanitized = array_filter( $settings, function ( $key ) use ( $allowed ) {
			return array_key_exists( $key, $allowed );
		}, ARRAY_FILTER_USE_KEY );

		if ( isset( $sanitized['quotation_cart_page'] ) && absint( get_option( 'pqfw_quotations_cart' ) ) !== absint( $sanitized['quotation_cart_page'] ) ) {
			update_option( 'pqfw_quotations_cart', absint( $sanitized['quotation_cart_page'] ) );
		}

		update_option( 'pqfw_settings', $sanitized );

		wp_send_json_success([
			'message' => esc_html__( 'Settings has been updated.', 'quotify' ),
		], 200 );
	}

	/**
	 * Get settings by key or get all settings without any key.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Setting key.
	 * @return mixed      Saved settings.
	 */
	public function get( $key = null ) {
		if ( empty( $key ) ) {
			return $this->getAll();
		}

		$settings = $this->getAll();

		if ( isset( $settings[ $key ] ) ) {
			return $settings[ $key ];
		}

		return false;
	}
}
