<?php
/**
 * Responsible for handling the plugin settings.
 *
 * @since 1.2.0
 * @package Quotify
 */

namespace Quotify\Library;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages the options form dashboard.
 *
 * @author      Mahafuz
 * @package     Quotify
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
	 * Quotify plugin settings group key.
	 *
	 * @var string
	 * @since 2.5.0
	 */
	const OPTION_GROUP_KEY = 'pqfw_settings';

	/**
	 * Class instance.
	 *
	 * @var \Quotify\Library\Settings
	 */
	private static $instance;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return \Quotify\Library\Settings
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

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
			'cart_button_text'               => __( 'View Quotation Cart', 'quotify' ),
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
			// rate limiter settings (count per time window, window in minutes).
			'pqfw_rate_limit_enabled'        => false,
			'pqfw_rate_limit_count'          => 5,
			'pqfw_rate_limit_period'         => 60, // minutes.
			'quotation_cart_page'            => \Quotify\Library\Helper::getCart(),
			'empty_cart_message'             => __( 'Your quotation cart is currently empty.', 'quotify' ),
			// Form submission messages.
			'quotation_success_message'     => __( 'Your quotation is successfully submitted.', 'quotify' ),
			'quotation_error_message'       => __( 'Something went wrong. Please try again.', 'quotify' ),
			'add_to_cart_success_message'   => __( 'Product successfully added to quotation.', 'quotify' ),
			// Form field customization.
			'pqfw_form_fields_customization_enabled' => false,
			'pqfw_field_name_label'          => __( 'Full Name', 'quotify' ),
			'pqfw_field_name_required'       => true,
			'pqfw_field_email_label'         => __( 'Email', 'quotify' ),
			'pqfw_field_email_required'      => true,
			'pqfw_field_subject_label'       => __( 'Subject', 'quotify' ),
			'pqfw_field_subject_required'    => true,
			'pqfw_field_phone_label'         => __( 'Phone', 'quotify' ),
			'pqfw_field_phone_required'      => false,
			'pqfw_field_comments_label'      => __( 'Comments', 'quotify' ),
			'pqfw_field_comments_required'   => false,
			'pqfw_field_name_enabled'        => true,
			'pqfw_field_email_enabled'       => true,
			'pqfw_field_subject_enabled'     => true,
			'pqfw_field_phone_enabled'       => true,
			'pqfw_field_comments_enabled'    => true,
			// email template settings.
			'pqfw_custom_email_subject_enabled' => false,
			'pqfw_admin_email_subject'       => '',
			'pqfw_customer_email_subject'    => '',
			// Email message customization.
			'pqfw_custom_email_messages_enabled' => false,
			// Customer email messages.
			'pqfw_customer_email_greeting'      => __( 'Thank You for Your Inquiry!', 'quotify' ),
			'pqfw_customer_email_intro'         => __( 'Thank you for your interest in our products. We have successfully received your quotation request and our team is reviewing it. You can expect to hear from us within 1-2 business days with a detailed quotation.', 'quotify' ), // phpcs:ignore Generic.Files.LineLength.MaxExceeded
			'pqfw_customer_email_what_next'     => __( "Our team reviews your product inquiry and requirements\nWe prepare a customized quotation with pricing details\nYou'll receive an email with your quotation and next steps\nIf you have questions, feel free to contact us anytime", 'quotify' ), // phpcs:ignore Generic.Files.LineLength.MaxExceeded
			'pqfw_customer_email_closing'       => __( 'We appreciate your business and look forward to serving you!', 'quotify' ),
			'pqfw_customer_email_signature'     => __( 'This email was sent by Quotify - Product Quotation for WooCommerce', 'quotify' ),
			// Admin email messages.
			'pqfw_admin_email_greeting'         => __( 'New Quotation Request Received', 'quotify' ),
			'pqfw_admin_email_intro'            => __( 'A new quotation request has been submitted on your website. Please review the details below and respond to the customer as soon as possible.', 'quotify' ), // phpcs:ignore Generic.Files.LineLength.MaxExceeded
			'pqfw_admin_email_closing'          => __( 'Please log in to your WordPress admin to view and manage this quotation.', 'quotify' ),
			'pqfw_admin_email_signature'        => __( 'This email was sent by Quotify - Product Quotation for WooCommerce', 'quotify' ),
		];

		$this->saved = get_option( self::OPTION_GROUP_KEY, $this->default );
		$this->all   = wp_parse_args( $this->saved, $this->default );

		return $this->all;
	}

	/**
	 * Saving settings.
	 *
	 * @param array $settings Settings to save.
	 * @return  void
	 */
	public function save( $settings ) {
		if ( ! is_array( $settings ) || empty( $settings ) ) {
			wp_send_json_error( __( 'Invalid settings data.', 'quotify' ), 400 );
		}

		$allowed   = $this->getAll();
		$sanitized = array_filter( $settings, function ( $key ) use ( $allowed ) {
			return array_key_exists( $key, $allowed );
		}, ARRAY_FILTER_USE_KEY );

		if ( isset( $sanitized['quotation_cart_page'] ) && absint( get_option( 'pqfw_quotations_cart' ) ) !== absint( $sanitized['quotation_cart_page'] ) ) {
			update_option( 'pqfw_quotations_cart', absint( $sanitized['quotation_cart_page'] ) );
		}

		// Ensure rate limit values are integers/bools.
		if ( isset( $sanitized['pqfw_rate_limit_enabled'] ) ) {
			$sanitized['pqfw_rate_limit_enabled'] = filter_var( $sanitized['pqfw_rate_limit_enabled'], FILTER_VALIDATE_BOOLEAN );
		}
		if ( isset( $sanitized['pqfw_rate_limit_count'] ) ) {
			$sanitized['pqfw_rate_limit_count'] = absint( $sanitized['pqfw_rate_limit_count'] );
		}
		if ( isset( $sanitized['pqfw_rate_limit_period'] ) ) {
			$sanitized['pqfw_rate_limit_period'] = absint( $sanitized['pqfw_rate_limit_period'] );
		}

		// Sanitize form field customization settings.
		if ( isset( $sanitized['pqfw_form_fields_customization_enabled'] ) ) {
			$sanitized['pqfw_form_fields_customization_enabled'] = filter_var( $sanitized['pqfw_form_fields_customization_enabled'], FILTER_VALIDATE_BOOLEAN );
		}
		// Field labels.
		if ( isset( $sanitized['pqfw_field_name_label'] ) ) {
			$sanitized['pqfw_field_name_label'] = sanitize_text_field( $sanitized['pqfw_field_name_label'] );
		}
		if ( isset( $sanitized['pqfw_field_email_label'] ) ) {
			$sanitized['pqfw_field_email_label'] = sanitize_text_field( $sanitized['pqfw_field_email_label'] );
		}
		if ( isset( $sanitized['pqfw_field_subject_label'] ) ) {
			$sanitized['pqfw_field_subject_label'] = sanitize_text_field( $sanitized['pqfw_field_subject_label'] );
		}
		if ( isset( $sanitized['pqfw_field_phone_label'] ) ) {
			$sanitized['pqfw_field_phone_label'] = sanitize_text_field( $sanitized['pqfw_field_phone_label'] );
		}
		if ( isset( $sanitized['pqfw_field_comments_label'] ) ) {
			$sanitized['pqfw_field_comments_label'] = sanitize_text_field( $sanitized['pqfw_field_comments_label'] );
		}
		// Field required status.
		if ( isset( $sanitized['pqfw_field_name_required'] ) ) {
			$sanitized['pqfw_field_name_required'] = filter_var( $sanitized['pqfw_field_name_required'], FILTER_VALIDATE_BOOLEAN );
		}
		if ( isset( $sanitized['pqfw_field_email_required'] ) ) {
			$sanitized['pqfw_field_email_required'] = filter_var( $sanitized['pqfw_field_email_required'], FILTER_VALIDATE_BOOLEAN );
		}
		if ( isset( $sanitized['pqfw_field_subject_required'] ) ) {
			$sanitized['pqfw_field_subject_required'] = filter_var( $sanitized['pqfw_field_subject_required'], FILTER_VALIDATE_BOOLEAN );
		}
		if ( isset( $sanitized['pqfw_field_phone_required'] ) ) {
			$sanitized['pqfw_field_phone_required'] = filter_var( $sanitized['pqfw_field_phone_required'], FILTER_VALIDATE_BOOLEAN );
		}
		if ( isset( $sanitized['pqfw_field_comments_required'] ) ) {
			$sanitized['pqfw_field_comments_required'] = filter_var( $sanitized['pqfw_field_comments_required'], FILTER_VALIDATE_BOOLEAN );
		}
		// Field enabled status.
		if ( isset( $sanitized['pqfw_field_name_enabled'] ) ) {
			$sanitized['pqfw_field_name_enabled'] = filter_var( $sanitized['pqfw_field_name_enabled'], FILTER_VALIDATE_BOOLEAN );
		}
		if ( isset( $sanitized['pqfw_field_email_enabled'] ) ) {
			$sanitized['pqfw_field_email_enabled'] = filter_var( $sanitized['pqfw_field_email_enabled'], FILTER_VALIDATE_BOOLEAN );
		}
		if ( isset( $sanitized['pqfw_field_subject_enabled'] ) ) {
			$sanitized['pqfw_field_subject_enabled'] = filter_var( $sanitized['pqfw_field_subject_enabled'], FILTER_VALIDATE_BOOLEAN );
		}
		if ( isset( $sanitized['pqfw_field_phone_enabled'] ) ) {
			$sanitized['pqfw_field_phone_enabled'] = filter_var( $sanitized['pqfw_field_phone_enabled'], FILTER_VALIDATE_BOOLEAN );
		}
		if ( isset( $sanitized['pqfw_field_comments_enabled'] ) ) {
			$sanitized['pqfw_field_comments_enabled'] = filter_var( $sanitized['pqfw_field_comments_enabled'], FILTER_VALIDATE_BOOLEAN );
		}

		// Sanitize cart button text.
		if ( isset( $sanitized['cart_button_text'] ) ) {
			$sanitized['cart_button_text'] = sanitize_text_field( $sanitized['cart_button_text'] );
		}

		// Sanitize hide_add_to_cart_button setting.
		if ( isset( $sanitized['hide_add_to_cart_button'] ) ) {
			$sanitized['hide_add_to_cart_button'] = filter_var( $sanitized['hide_add_to_cart_button'], FILTER_VALIDATE_BOOLEAN );
		}

		// Sanitize hide_product_prices setting.
		if ( isset( $sanitized['hide_product_prices'] ) ) {
			$sanitized['hide_product_prices'] = filter_var( $sanitized['hide_product_prices'], FILTER_VALIDATE_BOOLEAN );
		}

		// Sanitize empty cart message.
		if ( isset( $sanitized['empty_cart_message'] ) ) {
			$sanitized['empty_cart_message'] = sanitize_text_field( $sanitized['empty_cart_message'] );
		}

			// Sanitize form submission messages.
		if ( isset( $sanitized['quotation_success_message'] ) ) {
			$sanitized['quotation_success_message'] = sanitize_text_field( $sanitized['quotation_success_message'] );
		}
		if ( isset( $sanitized['quotation_error_message'] ) ) {
			$sanitized['quotation_error_message'] = sanitize_text_field( $sanitized['quotation_error_message'] );
		}
		if ( isset( $sanitized['add_to_cart_success_message'] ) ) {
			$sanitized['add_to_cart_success_message'] = sanitize_text_field( $sanitized['add_to_cart_success_message'] );
		}

		// Sanitize email template content.
		if ( isset( $sanitized['pqfw_custom_email_template_enabled'] ) ) {
			$sanitized['pqfw_custom_email_template_enabled'] = filter_var( $sanitized['pqfw_custom_email_template_enabled'], FILTER_VALIDATE_BOOLEAN );
		}
		if ( isset( $sanitized['pqfw_admin_email_template'] ) ) {
			$sanitized['pqfw_admin_email_template'] = wp_kses_post( $sanitized['pqfw_admin_email_template'] );
		}
		if ( isset( $sanitized['pqfw_customer_email_template'] ) ) {
			$sanitized['pqfw_customer_email_template'] = wp_kses_post( $sanitized['pqfw_customer_email_template'] );
		}

		// Sanitize email message customization settings.
		if ( isset( $sanitized['pqfw_custom_email_messages_enabled'] ) ) {
			$sanitized['pqfw_custom_email_messages_enabled'] = filter_var( $sanitized['pqfw_custom_email_messages_enabled'], FILTER_VALIDATE_BOOLEAN );
		}
		// Customer email messages.
		if ( isset( $sanitized['pqfw_customer_email_greeting'] ) ) {
			$sanitized['pqfw_customer_email_greeting'] = sanitize_text_field( $sanitized['pqfw_customer_email_greeting'] );
		}
		if ( isset( $sanitized['pqfw_customer_email_intro'] ) ) {
			$sanitized['pqfw_customer_email_intro'] = wp_kses_post( $sanitized['pqfw_customer_email_intro'] );
		}
		if ( isset( $sanitized['pqfw_customer_email_what_next'] ) ) {
			$sanitized['pqfw_customer_email_what_next'] = sanitize_textarea_field( $sanitized['pqfw_customer_email_what_next'] );
		}
		if ( isset( $sanitized['pqfw_customer_email_closing'] ) ) {
			$sanitized['pqfw_customer_email_closing'] = sanitize_text_field( $sanitized['pqfw_customer_email_closing'] );
		}
		if ( isset( $sanitized['pqfw_customer_email_signature'] ) ) {
			$sanitized['pqfw_customer_email_signature'] = sanitize_text_field( $sanitized['pqfw_customer_email_signature'] );
		}
		// Admin email messages.
		if ( isset( $sanitized['pqfw_admin_email_greeting'] ) ) {
			$sanitized['pqfw_admin_email_greeting'] = sanitize_text_field( $sanitized['pqfw_admin_email_greeting'] );
		}
		if ( isset( $sanitized['pqfw_admin_email_intro'] ) ) {
			$sanitized['pqfw_admin_email_intro'] = wp_kses_post( $sanitized['pqfw_admin_email_intro'] );
		}
		if ( isset( $sanitized['pqfw_admin_email_closing'] ) ) {
			$sanitized['pqfw_admin_email_closing'] = sanitize_text_field( $sanitized['pqfw_admin_email_closing'] );
		}
		if ( isset( $sanitized['pqfw_admin_email_signature'] ) ) {
			$sanitized['pqfw_admin_email_signature'] = sanitize_text_field( $sanitized['pqfw_admin_email_signature'] );
		}

		update_option( self::OPTION_GROUP_KEY, $sanitized );

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
