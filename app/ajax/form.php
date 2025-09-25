<?php
/**
 * Responsible for handling submission of the frontend form.
 *
 * @since 1.0.0
 * @package PQFW
 */

namespace Quotify\Ajax;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Form Handler class.
 *
 * @since   1.0.0
 * @package PQFW
 */
class Form {

	/**
	 * Constructor of the class
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_quotify/ajax/quotation/submit', [ $this, 'submit' ] );
		add_action( 'wp_ajax_nopriv_quotify/ajax/quotation/submit', [ $this, 'submit' ] );
	}

	/**
	 * Responsible for submitting each entry.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function submit() {
		check_ajax_referer( 'pqfw_nonce', 'security' );

		$entry = ! empty( $_POST['data'] ) ? json_decode( wp_unslash( $_POST['data'] ), true ) : false;

		if ( ! $entry ) {
			wp_send_json_error( __( 'Something wen\'t wrong', 'quotify' ) );
		}

		$fullname = sanitize_user( $entry['pqfw_customer_name'] );
		$email    = sanitize_email( $entry['pqfw_customer_email'] );
		$phone    = \Quotify\Library\Helper::sanitizePhoneNumber( $entry['pqfw_customer_phone'] );
		$subject  = sanitize_text_field( $entry['pqfw_customer_subject'] );
		$comments = sanitize_textarea_field( $entry['pqfw_customer_comments'] );

		$validate = \Quotify\Library\Helper::validate([
			'fullname' => $fullname,
			'email'    => $email,
			'subject'  => $subject,
			'phone'    => $phone,
			'comments' => $comments,
		]);

		if ( $validate->has_errors() ) {
			wp_send_json_error( $validate->errors );
		}

		$form_entries = [
			'pqfw_customer_name'     => $fullname,
			'pqfw_customer_email'    => $email,
			'pqfw_customer_subject'  => $subject,
			'pqfw_customer_phone'    => $phone,
			'pqfw_customer_comments' => $comments,
		];

		do_action( 'quotify/quotations/before_insert', $form_entries );

		$insertID = quotify()->quotations()->save( $form_entries );

		if ( $insertID ) {
			do_action( 'quotify/quotations/after_insert', $insertID );

			// Reset the current cart.
			quotify()->cart()->purge();
			wp_send_json_success( __( 'Your quotation is successfully submitted.', 'quotify' ) );
		} else {
			wp_send_json_error( __( 'Something went wrong', 'quotify' ) );
		}
	}
}
