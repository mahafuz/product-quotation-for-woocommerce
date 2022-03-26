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
	private $email;

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
	public function __construct( $args ) {
		$this->args     = $args;
		$this->blogname = esc_attr( get_option( 'blogname' ) );
		$this->subject  = sprintf( '%s - %s', __( 'Request for quotation', 'pqfw' ), $this->blogname );
		$this->email    = sanitize_email( get_option( 'admin_email' ) );

		$this->prepare_message();
		$this->prepare_headers();
	}

	/**
	 * Prepare the headers for sending mail.
	 *
	 * @return string $headers
	 * @since 1.0.0
	 */
	private function prepare_headers() {
		$headers = "Content-Type: text/html; charset=UTF-8\n";
		$headers .= 'From: ' . esc_attr( $this->args['email'] ) . "\n";
		$headers .= 'Reply-To: ' . esc_attr( $this->args['fullname'] ) . '<' . esc_attr( $this->args['email'] ) . ">\n";
		$this->headers = $headers;

		return $this->headers;
	}

	/**
	 * Prepare the mail body.
	 *
	 * @since 1.0.0
	 * @return string $message
	 */
	private function prepare_message() {
		$message = '<html><head><meta charset="utf-8" /></head><body>';
		$message .= '<p><strong>' . esc_attr( $this->subject ) . '</strong></p>';
		$message .= '<p>' . __( 'Name:', 'pqfw' ) . ' ' . esc_attr( $this->args['fullname'] );
		$message .= '<br>' . __( 'Email:', 'pqfw' ) . ' ' . esc_attr( $this->args['email'] );
		$message .= '<br>' . __( 'Phone:', 'pqfw' ) . ' ' . esc_attr( $this->args['phone'] );
		$message .= '<br>' . __( 'Questions or comments:', 'pqfw' ) . '<br>' . esc_textarea( $this->args['comments'] ) . '</p>';
		$message .= '<p>' . __( 'Request for quotation for:', 'pqfw' );
		$message .= '<br><a href="' . get_permalink( $this->args['product_id'] ) . '">' . esc_attr( $this->args['product_title'] ) . '</a>';
		$message .= '<br>' . __( 'Quantity:', 'pqfw' ) . ': ' . esc_attr( $this->args['quantity'] ) . '</p>';
		$message .= '<p><a href="' . esc_url( get_permalink( $this->args['product_id'] ) ) . '">' . get_the_post_thumbnail( $this->args['product_id'], 'thumbnail' ) . '</a></p>';
		$message .= '</body></html>';

		$this->message = $message;

		return $this->message;
	}

	/**
	 * Sending the mail.
	 *
	 * @since 1.0.0
	 * @return void|error
	 */
	public function send() {
		$result = \wp_mail(
			$this->email,
			$this->subject,
			$this->message,
			$this->headers
		);

		if ( ! $result ) {
			wp_send_json_error( __( 'There was an error while we were trying to send your email. Please try again later or contact us in other way!', 'pqfw' ) );
		}
	}
}