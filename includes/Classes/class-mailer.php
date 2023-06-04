<?php
/**
 * Responsible for sending mails to users.
 *
 * @since 1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Mailer class
 *
 * @since   1.0.0
 */
class Mailer {

	/**
	 * Retrives the blog name.
	 *
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $blogname;

	/**
	 * Mail subject.
	 *
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $subject;

	/**
	 * Admin email to receive.
	 *
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $email = [];

	/**
	 * Generating full message body.
	 *
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $message;

	/**
	 * Contain the headers for sending mail.
	 *
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $headers;

	/**
	 * Data to attach with the email.
	 *
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $args;

	/**
	 * Constructor of the class
	 *
	 * @since 1.0.0
	 * @param array $args Arguments for sending the mail.
	 */
	public function prepare( $args ) {
		$emails_to_send = [];
		

		if ( wp_validate_boolean( pqfw()->settings->get( 'pqfw_form_send_mail' ) ) ) {
			$subject   = sprintf( '%s - %s', esc_attr( $args['full_name'] ), esc_attr( $args['subject'] ) );
			$recipient = sanitize_email( pqfw()->settings->get( 'recipient' ) );
			$recipient = $recipient ? $recipient : sanitize_email( get_option( 'admin_email' ) );
			$name = ! empty( $args['full_name'] ) ? sanitize_text_field( $args['full_name'] ) : '';
			$emails_to_send[] = $this->prepare_email_payload( $name, $subject, $recipient, $args );
		}


		if ( wp_validate_boolean( pqfw()->settings->get( 'pqfw_send_mail_to_customer' ) ) ) {
			if ( empty( $args['email'] ) ) {
				wp_send_json_error([
					'message' => __( 'No customer email found to send the quotation copy', '' )
				]);
			}


			$subject          = sprintf( '%s - %s', esc_attr( get_bloginfo( 'name' ) ), esc_attr( $args['subject'] ) );
			$recipient        = sanitize_email( $args['email'] );
			$name             = esc_attr( get_the_author_meta( 'display_name', get_current_user_id() ) );
			$emails_to_send[] = $this->prepare_email_payload( $name, $subject, $recipient, $args );
		}


		if ( empty( $emails_to_send ) ) {
			return false;
		}

		$sent = true;

		foreach( $emails_to_send as $mail ) {
			$sent = wp_mail(
				$mail['address'],
				$mail['subject'],
				$mail['template'],
				$mail['headers']
			);
		}

		return $sent;
	}

	public function prepare_email_payload( $name, $subject, $recipient, $args ) {
		return [
			'address'  => $recipient,
			'subject'  => $subject,
			'headers'  => $this->prepare_headers( $name, $recipient, $name ),
			'template' => include PQFW_PLUGIN_VIEWS . 'email-template.php'
		];
	}

	/**
	 * Prepare the headers for sending mail.
	 *
	 * @return string $headers
	 * @since 1.0.0
	 */
	private function prepare_headers( $email, $name ) {
		$headers = "Content-Type: text/html; charset=UTF-8\n";
		$headers .= 'From: ' . esc_attr( $email ) . "\n";
		$headers .= 'Reply-To: ' . esc_attr( $name ) . '<' . esc_attr( $email ) . ">\n";
		return $headers;
	}
}