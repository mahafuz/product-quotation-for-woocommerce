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
		add_action( 'wp_ajax_nopriv_pqfw_quotation_submission', [ $this, 'submitQuotation' ] );
	}

	/**
	 * Degenerate input type field name.
	 *
	 * @since 2.0.7
	 *
	 * @param  string $label Form field label.
	 * @return string         Generated field name.
	 */
	public function dGenerateFieldName( $label ) {
		return str_replace( '_', ' ', trim( strtolower( $label ) ) );
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

		$errors = new \WP_Error();
		$mapedDataToSave = [];
		$savedFields = pqfw()->formApi->getFormatted();
		$requiredFields = array_keys( array_filter($savedFields, function( $field ) {
			if ( $field['required'] ) {
				return $field;
			}
		}) );

		foreach ( $_POST as $name => $value ) {
			if ( isset( $savedFields[ $name ] ) ) {
				if ( 'full_name' === $name ) {
					$mapedDataToSave[ $name ] = sanitize_text_field( $value );
					if ( strlen( $mapedDataToSave[ $name ] ) < 4 ) {
						$errors->add( 'username_length', __( 'Username too short. At least 4 characters is required', 'pqfw' ) );
					}

					if ( ! validate_username( $mapedDataToSave[ $name ] ) ) {
						$errors->add( 'username_invalid', __( 'Sorry, the username you entered is not valid', 'pqfw' ) );
					}
				}

				if ( 'website_url' === $name ) {
					$mapedDataToSave[ $name ] = esc_url( sanitize_text_field( $value ) );
				}

				if ( 'subject' === $name ) {
					$mapedDataToSave[ $name ] = sanitize_text_field( $value );
				}

				if ( 'address' === $name ) {
					$mapedDataToSave[ $name ] = sanitize_text_field( $value );
				}

				if ( 'organization' === $name ) {
					$mapedDataToSave[ $name ] = sanitize_text_field( $value );
				}

				if ( 'comments' === $name ) {
					$mapedDataToSave[ $name ] = sanitize_text_field( $value );
				}

				if ( 'email' === $name ) {
					$mapedDataToSave[ $name ] = sanitize_email( $value );

					if ( ! is_email( $mapedDataToSave[ $name ] ) ) {
						$errors->add( 'email_invalid', __( 'Email is not valid', 'pqfw' ) );
					}
				}

				if ( 'phone/mobile' === $name ) {
					$mapedDataToSave[ $name ] = pqfw()->helpers->sanitize_phone_number( $value );
				}
			}
		}

		foreach ( $requiredFields as $required ) {
			if ( empty( $mapedDataToSave[ $required ] ) ) {
				$errors->add( 'field', sprintf( '%s %s', $this->dGenerateFieldName( $required ), __( 'is required.', 'pqfw' ) ) );
			}
		}

		if ( $errors->has_errors() ) {
			wp_send_json_error( $errors->errors );
		}

		$insertID = pqfw()->product->save( $mapedDataToSave );

		if ( $insertID ) {
			$response = pqfw()->mailer->prepare( $mapedDataToSave );

			if ( $response ) {
				pqfw()->quotations->purge();
				wp_send_json_success([
					'message' => pqfw()->settings->get( 'success_message', __( 'Your quotation is successfully submitted.', 'pqfw' ) )
				]);
			}

			wp_send_json_error([
				'message' => pqfw()->settings->get( 'error_message', __( 'Your quotation created successfully but error occurred while sending emails.', 'pqfw' ) )
			]);
		}

		wp_send_json_error([
			'message' => __( 'Something went wrong, while submitting quote.', 'pqfw' )
		]);
	}
}