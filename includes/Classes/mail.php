<?php
/**
 * Responsible for managing the plugin base scripts.
 *
 * @since   1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Responsible for managing the plugin base scripts.
 *
 * @since   1.0.0
 * @package PQFW
 */
class Mail {

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
		$recipient_setting = pqfw()->settings->get( 'recipient' );
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
}
