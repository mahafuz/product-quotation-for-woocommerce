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
			'button_text'                    => __( 'Add to Quote', 'pqfw' ),
			'hide_add_to_cart_button'        => false,
			'hide_product_prices'            => false,
			'button_position'                => 'woocommerce_after_shop_loop_item',
			'button_position_single_product' => 'woocommerce_after_add_to_cart_quantity',
			'privacy_policy'                 => false,
			'privacy_policy_label'           => __( 'I have read and agree to the website terms and conditions.', 'pqfw' ),
			'privacy_policy_content'         => __( 'Your personal data will be used to process your request, support your experience throughout this website, and for other purposes described in our  [privacy_policy].', 'pqfw' ),
			'quotation_cart_page'            => pqfw()->helpers->get_cart()
		];

		$this->saved = get_option( 'pqfw_settings', $this->default );
		$this->all   = wp_parse_args( $this->saved, $this->default );

		return $this->all;
	}

	/**
	 * Get settings by key or get all settings without any key.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Setting key.
	 * @return mixed      Saved settings.
	 */
	public function get( $key = null, $default = null ) {
		if ( empty( $key ) ) {
			return $this->getAll();
		}

		$settings = $this->getAll();

		if ( isset( $settings[ $key ] ) ) {
			return $settings[ $key ];
		}

		if ( $default ) {
			return $default;
		}

		return false;
	}
}