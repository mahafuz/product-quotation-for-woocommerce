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

	public function get_from_name( $name ) {
		return sanitize_text_field( get_the_author_meta( 'display_name', get_current_user_id() ) );
	}

	public function get_from_address( $email ) {
		$email = pqfw()->settings->get( 'recipient' );
		if ( ! empty( $recipient ) && is_email( $recipient ) ) {
			return sanitize_text_field( $email );
		} else {
			return sanitize_email( get_the_author_meta( 'email', get_current_user_id() ) );
		}
		return $email;
	}
}
