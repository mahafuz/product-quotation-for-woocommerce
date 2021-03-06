<?php

namespace PQFW\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use \PQFW\Database\Utils;

/**
 * Form Handler class.
 *
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */
class Form_Handler {

	/**
	 * Single instance of the class
	 *
	 * @var \Form_Handler
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Returns single instance of the class
	 *
	 * @return \Form_Handler
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			return self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor of the class
	 *
	 * @return \Form_Handler
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'wp_ajax_handle_insert_entry', array ( $this, 'submit_entry' ) );

	}

	/**
	 * Responsible for submitting each entry.
	 *
	 * @access  private
	 * @return  void
	 * @since   1.0.0
	 */
	public function submit_entry() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'pqfw_form_nonce_action' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid Request', 'PQFW' ) ] );
		}

		$fullname = $email = $comments = $phone = $product_title = $product_sku = '';
		$quantity = $product_id = $product_quantity = 0;

		$quantity = absint( $_REQUEST['pqfw_product_quantity'] );
		$fullname = sanitize_user( $_REQUEST['pqfw_customer_name'] );
		$email    = sanitize_email( $_REQUEST['pqfw_customer_email'] );
		$phone    = Utils::sanitize_phone_number( $_REQUEST['pqfw_customer_phone'] );
		$comments = sanitize_text_field( $_REQUEST['pqfw_customer_comments'] );


		$product_id       = absint( $_REQUEST['fragments']['product_id'] );
		$product_title    = sanitize_text_field( $_REQUEST['fragments']['product_title'] );
		$product_quantity = absint( $_REQUEST['fragments']['product_quantity'] );
		$product_sku      = sanitize_text_field( $_REQUEST['fragments']['product_sku'] );

		$validate = Utils::validate( $quantity, $fullname, $email, $product_id );

		if ( count( $validate->errors ) > 0 ) {
			wp_send_json_error( $validate->errors );
		}

		$data = array (
			'product_id'    => $product_id,
			'product_title' => $product_title,
			'quantity'      => $quantity,
			'fullname'      => $fullname,
			'email'         => $email,
			'phone'         => $phone,
			'comments'      => $comments,
			'status'        => 'publish',
		);

		$formats = array (
			'%d',
			'%s',
			'%d',
			'%s',
			'%s',
			'%d',
			'%s',
			'%s',
		);

		$inserteID = Utils::insert( $data, $formats );

		if ( $inserteID ) {
			// sending mail
			if ( Settings::get_setting( 'pqfw_form_send_mail' ) ) {
				$mailer = new Mailer( $data );
				$mailer->send();
			}
			wp_send_json_success( __( 'Your entry was successfully submitted.', 'pqfw' ) );
		} else {
			wp_send_json_error( __( 'Something went wrong', 'pqfw' ) );
		}

		die();

	}

}

new Form_Handler();