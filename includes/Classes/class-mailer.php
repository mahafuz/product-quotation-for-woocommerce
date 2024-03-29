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
		$this->args     = $args;
		$this->blogname = esc_attr( get_option( 'blogname' ) );
		$this->subject  = sprintf( '%s - %s', $this->args['fullname'], $this->args['subject'] );

		if ( pqfw()->settings->get( 'pqfw_form_send_mail' ) ) {
			$recipient     = sanitize_email( pqfw()->settings->get( 'recipient' ) );
			$this->email[] = $recipient ? $recipient : sanitize_email( get_option( 'admin_email' ) );
		}

		if ( pqfw()->settings->get( 'pqfw_send_mail_to_customer' ) ) {
			$this->email[] = sanitize_email( $this->args['email'] );
		}

		$this->prepare_message();
		$this->prepare_headers();
		return $this;
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
		$message .= '<p>' . __( 'Quotation Products Details:', 'pqfw' );

		$this->products = pqfw()->quotations->getProducts();
		foreach ( $this->products as $product ) {
			$img = wp_get_attachment_image_src( get_post_thumbnail_id( $product['id'] ), 'thumbnail' );
			$img = ! empty( $img[0] ) ? ( $img[0] ) : false;
			$message .= '<br><a href="' . get_permalink( $product['id'] ) . '">' . esc_attr( get_the_title( $product['id'] ) ) . '</a>';
			$message .= '<br>' . __( 'Quantity', 'pqfw' ) . ': ' . absint( $product['quantity'] ) . '</p>';
			$message .= '<br>' . __( 'Price', 'pqfw' ) . ': ' . wc_price( $product['price'] ) . '</p>';
			$message .= '<br>' . __( 'Note', 'pqfw' ) . ': ' . wp_kses_post( $product['message'] ) . '</p>';
			$message .= '<p><a href="' . rawurlencode( esc_url( get_permalink( $product['id'] ) ) ) . '">
			<img src="' . $img . '" alt="' . esc_attr( get_the_title( $product['id'] ) ) . '" title="' . esc_attr( get_the_title( $product['id'] ) ) . '" style="display: block" height="100" width="100" /></a></p>';
		}

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
		if ( ! is_array( $this->email ) || empty( $this->email ) ) {
			return;
		}

		wp_mail(
			$this->email,
			$this->subject,
			$this->message,
			$this->headers
		);
	}
}