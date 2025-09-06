<?php
/**
 * Hooks provider for the Quotify plugin.
 *
 * Handles WordPress action hooks and manages email notifications for quotation submissions.
 *
 * @since 2.0.4
 * @package Quotify
 */

namespace PQFW\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Hooks
 *
 * Manages plugin hooks and email notifications for quotation system.
 *
 * @since 2.0.4
 */
class Hooks {

	/**
	 * Initialize hooks and actions.
	 *
	 * Static method to set up action listeners for the plugin.
	 *
	 * @since 2.0.4
	 * @static
	 * @access public
	 *
	 * @return void
	 */
	public static function init() {
		$self = new self();
		add_action( 'quotify/quotations/after_insert', [ $self, 'quotation_submit' ] );
	}

	/**
	 * Handle quotation submission and send email notifications.
	 *
	 * Processes new quotation submissions, sanitizes data, and sends emails
	 * to both customer and admin based on plugin settings.
	 *
	 * @since 2.0.4
	 * @access public
	 *
	 * @param int $id The post ID of the submitted quotation.
	 * @return void
	 */
	public function quotation_submit( $id ) {
		$quote    = get_post( $id );
		$meta     = pqfw()->helpers->get_post_meta_by_id( $id );
		$author   = sanitize_user( get_the_author_meta( 'first_name', $quote->post_author ) );
		$title    = get_the_title( $id );
		$handle   = ! empty( $author ) ? esc_attr( $author ) : esc_attr( $title );
		$email    = sanitize_email( $meta['pqfw_customer_email'] );
		$phone    = pqfw()->helpers->sanitizePhoneNumber( $meta['pqfw_customer_phone'] );
		$subject  = sanitize_text_field( $meta['pqfw_customer_subject'] );
		$comments = sanitize_text_field( $meta['pqfw_customer_comments'] );
		$products = $meta['pqfw_products_info'];
		$headers  = [ 'Content-Type: text/html; charset=UTF-8' ];
		$response = [];

		ob_start();
			$collection = [
				'fullname'    => $handle,
				'email'       => $email,
				'subject'     => $subject,
				'phone'       => $phone,
				'comments'    => $comments,
				'products'    => $products,
				'email_title' => get_bloginfo( 'name' ),
				'site_url'    => get_bloginfo( 'url' ),
			];
			require PQFW_PLUGIN_VIEWS . 'email/new-quote.php';
			$body = ob_get_clean();

			if ( pqfw()->settings->get( 'pqfw_send_mail_to_customer' ) ) {
				pqfw()->mail->send( $email, $subject, $body, $headers );
			}

			if ( pqfw()->settings->get( 'pqfw_form_send_mail' ) ) {
				$recipient = sanitize_email( pqfw()->settings->get( 'recipient' ) );
				pqfw()->mail->send( $recipient, $subject, $body, $headers );
			}
	}
}
