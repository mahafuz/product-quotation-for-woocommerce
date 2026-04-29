<?php
/**
 * Responsible for managing the plugin base scripts.
 *
 * @since   1.0.0
 * @package Quotify
 */

namespace Quotify\Library;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for managing the plugin base scripts.
 *
 * @since   1.0.0
 * @package Quotify
 */
class Mail {

	/**
	 * Class instance.
	 *
	 * @var Quotify\Shortcodes
	 */
	private static $instance = null;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var Quotify\Shortcodes
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get email headers following WooCommerce and WordPress standards.
	 *
	 * Generates standard email headers including Content-Type, Reply-To,
	 * CC, and BCC. Returns headers as an array compatible with wp_mail().
	 *
	 * @since 2.6.0
	 * @access public
	 *
	 * @param array $args Optional. Arguments to customize headers.
	 * @return array Email headers.
	 */
	public function get_headers( $args = [] ) {
		$defaults = [
			'reply_to' => '',
			'cc'       => '',
			'bcc'      => '',
		];

		$args = wp_parse_args( $args, $defaults );

		$headers = [
			'Content-Type: text/html; charset=UTF-8',
		];

		// Add Reply-To if provided.
		if ( ! empty( $args['reply_to'] ) && is_email( $args['reply_to'] ) ) {
			$headers[] = 'Reply-To: ' . sanitize_email( $args['reply_to'] );
		}

		// Add CC if provided.
		if ( ! empty( $args['cc'] ) ) {
			$cc_emails = is_array( $args['cc'] ) ? $args['cc'] : explode( ',', $args['cc'] );
			$cc_emails = array_filter( array_map( 'sanitize_email', $cc_emails ) );
			if ( ! empty( $cc_emails ) ) {
				$headers[] = 'Cc: ' . implode( ', ', $cc_emails );
			}
		}

		// Add BCC if provided.
		if ( ! empty( $args['bcc'] ) ) {
			$bcc_emails = is_array( $args['bcc'] ) ? $args['bcc'] : explode( ',', $args['bcc'] );
			$bcc_emails = array_filter( array_map( 'sanitize_email', $bcc_emails ) );
			if ( ! empty( $bcc_emails ) ) {
				$headers[] = 'Bcc: ' . implode( ', ', $bcc_emails );
			}
		}

		/**
		 * Filter email headers.
		 *
		 * @since 2.6.0
		 *
		 * @param array $headers Email headers.
		 * @param array $args    Arguments passed to get_headers().
		 */
		return apply_filters( 'quotify_mail_headers', $headers, $args );
	}

	/**
	 * Send mail
	 *
	 * @param  mixed $to Email recipient.
	 * @param  mixed $subject Email subject.
	 * @param  mixed $body Email body.
	 * @param  mixed $headers Email headers.
	 * @return bool
	 */
	public function send( $to, $subject, $body, $headers ) {
		add_filter( 'wp_mail_from_name', [ $this, 'get_from_name' ] );
		add_filter( 'wp_mail_from', [ $this, 'get_from_address' ] );

		$is_send = wp_mail( $to, $subject, $body, $headers );

		remove_filter( 'wp_mail_from_name', [ $this, 'get_from_name' ] );
		remove_filter( 'wp_mail_from', [ $this, 'get_from_address' ] );

		return $is_send;
	}

	/**
	 * Filters the email address sent from WordPress emails.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $from_email Default email address.
	 * @return string Filtered email address.
	 */
	public function get_from_address( $from_email ) {
		// First priority: Saved recipient setting from plugin options.
		$recipient_setting = quotify()->settings()->get( 'recipient' );
		if ( ! empty( $recipient_setting ) && is_email( $recipient_setting ) ) {
			return sanitize_email( $recipient_setting );
		}

		// Second priority: Current logged-in user's email (for background processing).
		$user_id = get_current_user_id();
		if ( $user_id ) {
			$user_email = get_the_author_meta( 'user_email', $user_id );
			if ( ! empty( $user_email ) && is_email( $user_email ) ) {
				return sanitize_email( $user_email );
			}
		}

		// Fallback: Keep the original WordPress default.
		return $from_email;
	}

	/**
	 * Filters the name associated with the from email address.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $from_name Default from name.
	 * @return string Filtered from name.
	 */
	public function get_from_name( $from_name ) {
		// First priority: Try to get current user's display name.
		$user_id = get_current_user_id();

		if ( $user_id ) {
			$display_name = get_the_author_meta( 'display_name', $user_id );
			if ( ! empty( $display_name ) ) {
				return sanitize_text_field( $display_name );
			}
		}

		// Second priority: Site name.
		$site_name = get_bloginfo( 'name' );
		if ( ! empty( $site_name ) ) {
			return sanitize_text_field( $site_name );
		}

		// Fallback: Keep the original WordPress default.
		return $from_name;
	}

	/**
	 * Generate admin email subject based on settings and template data.
	 *
	 * Handles custom subject templates with placeholders, or falls back to
	 * customer's subject from form, or uses a default if subject is empty.
	 *
	 * @since 2.6.0
	 * @access public
	 *
	 * @param array $template_data Quotation data from template manager.
	 * @return string Generated email subject.
	 */
	public function get_admin_subject( $template_data ) {
		$custom_subjects_enabled = quotify()->settings()->get( 'pqfw_custom_email_subject_enabled' );

		if ( $custom_subjects_enabled ) {
			// Use custom subject template with placeholders.
			$admin_subject_setting = quotify()->settings()->get( 'pqfw_admin_email_subject' );
			$default_admin_subject = __( 'New Quotation Request from {customer_name} - {quotation_id}', 'quotify' );

			$admin_subject = ! empty( $admin_subject_setting )
				? $admin_subject_setting
				: $default_admin_subject;

			// Parse placeholders in subject.
			$admin_subject = \Quotify\Library\Helper::parse_email_subject( $admin_subject, $template_data );
		} else {
			// Use customer's subject from form, with fallback if empty.
			$admin_subject = ! empty( $template_data['subject'] )
				? $template_data['subject']
				: __( 'New Quotation Request', 'quotify' );
		}

		/**
		 * Filter admin email subject.
		 *
		 * @since 2.6.0
		 *
		 * @param string $admin_subject Generated admin email subject.
		 * @param array  $template_data  Quotation template data.
		 */
		return apply_filters( 'quotify_mail_admin_subject', $admin_subject, $template_data );
	}

	/**
	 * Generate customer email subject based on settings and template data.
	 *
	 * Handles custom subject templates with placeholders, or uses default.
	 *
	 * @since 2.6.0
	 * @access public
	 *
	 * @param array $template_data Quotation data from template manager.
	 * @return string Generated email subject.
	 */
	public function get_customer_subject( $template_data ) {
		$custom_subjects_enabled = quotify()->settings()->get( 'pqfw_custom_email_subject_enabled' );

		if ( $custom_subjects_enabled ) {
			// Use custom subject template with placeholders.
			$customer_subject_setting = quotify()->settings()->get( 'pqfw_customer_email_subject' );
			$default_customer_subject = __( 'Your Quotation Request Received - {quotation_id}', 'quotify' );

			$customer_subject = ! empty( $customer_subject_setting )
				? $customer_subject_setting
				: $default_customer_subject;

			// Parse placeholders in subject.
			$customer_subject = \Quotify\Library\Helper::parse_email_subject( $customer_subject, $template_data );
		} else {
			// Use default subject.
			$customer_subject = __( 'Your Quotation Request Received', 'quotify' );
		}

		/**
		 * Filter customer email subject.
		 *
		 * @since 2.6.0
		 *
		 * @param string $customer_subject Generated customer email subject.
		 * @param array  $template_data     Quotation template data.
		 */
		return apply_filters( 'quotify_mail_customer_subject', $customer_subject, $template_data );
	}
}
