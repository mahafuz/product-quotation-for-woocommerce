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
		add_action( 'wp_ajax_save_quotation', [ $this, 'submit_entry' ] );
	}

	/**
	 * Responsible for submitting each entry.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function submit_entry() {
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'pqfw_form_nonce_action' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid Request', 'PQFW' ) ] );
		}

		$fullname = $email = $comments = $phone = $product_title = $product_sku = '';
		$quantity = $product_id = $product_quantity = 0;

		$fullname = sanitize_user( $_REQUEST['pqfw_customer_name'] );
		$email    = sanitize_email( $_REQUEST['pqfw_customer_email'] );
		$phone    = pqfw()->utils->sanitize_phone_number( $_REQUEST['pqfw_customer_phone'] );
		$comments = sanitize_text_field( $_REQUEST['pqfw_customer_comments'] );
		$products = ( $_REQUEST['fragments']['products'] );

		$validate = pqfw()->utils->validate( $quantity, $fullname, $email, $product_id );

		if ( count( $validate->errors ) > 0 ) {
			wp_send_json_error( $validate->errors );
		}

		// TODO: Save the data in a new way because there is collection of products.

		// $data = array (
		// 	'product_id'    => $product_id,
		// 	'product_title' => $product_title,
		// 	'quantity'      => $quantity,
		// 	'fullname'      => $fullname,
		// 	'email'         => $email,
		// 	'phone'         => $phone,
		// 	'comments'      => $comments,
		// 	'status'        => 'publish',
		// );

		// $formats = array (
		// 	'%d',
		// 	'%s',
		// 	'%d',
		// 	'%s',
		// 	'%s',
		// 	'%d',
		// 	'%s',
		// 	'%s',
		// );

		// $inserteID = pqfw()->utils->insert( $data, $formats );

		// if ( $inserteID ) {
		// 	// sending mail
		// 	if ( Settings::get_setting( 'pqfw_form_send_mail' ) ) {
		// 		$mailer = new Mailer( $data );
		// 		$mailer->send();
		// 	}
		// 	wp_send_json_success( __( 'Your entry was successfully submitted.', 'pqfw' ) );
		// } else {
		// 	wp_send_json_error( __( 'Something went wrong', 'pqfw' ) );
		// }

		die();
	}
}