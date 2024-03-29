<?php
/**
 * Responsible for handling submission of the frontend form.
 *
 * @since 1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Form Handler class.
 *
 * @since   1.0.0
 * @package PQFW
 */
class Form_Handler {

	/**
	 * Constructor of the class
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_pqfw_quotation_submission', [ $this, 'submitQuotation' ] );
	}

	/**
	 * Responsible for submitting each entry.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function submitQuotation() {
		if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( $_POST['nonce'], 'pqfw_form_nonce_action' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid Request', 'PQFW' ) ] );
		}

		$fullname = sanitize_user( $_POST['pqfw_customer_name'] );
		$email    = sanitize_email( $_POST['pqfw_customer_email'] );
		$phone    = pqfw()->helpers->sanitizePhoneNumber( $_POST['pqfw_customer_phone'] );
		$subject  = sanitize_text_field( $_POST['pqfw_customer_subject'] );
		$comments = sanitize_text_field( $_POST['pqfw_customer_comments'] );
		$mapedDataToSave = [
			'fullname' => $fullname,
			'email'    => $email,
			'subject'  => $subject,
			'phone'    => $phone,
			'comments' => $comments
		];

		$validate = pqfw()->helpers->validate( $mapedDataToSave );

		if ( $validate->has_errors() ) {
			wp_send_json_error( $validate->errors );
		}

		$insertID = pqfw()->product->save( $mapedDataToSave );

		if ( $insertID ) {

			pqfw()->mailer->prepare( $mapedDataToSave )->send();
			pqfw()->quotations->purge();

			wp_send_json_success( __( 'Your quotation is successfully submitted.', 'pqfw' ) );
		} else {
			wp_send_json_error( __( 'Something went wrong', 'pqfw' ) );
		}

		die();
	}
}