<?php
/**
 * Hooks provider for the Quotify plugin.
 *
 * Handles WordPress action hooks and manages email notifications for quotation submissions.
 *
 * @since 2.0.4
 * @package Quotify
 */

namespace Quotify\Internals;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

use Quotify\Library\Helper;

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
		add_action( 'quotify/quotations/before_submit', [ $self, 'before_quotation_submit' ] );
		add_action( 'quotify/quotations/after_insert', [ $self, 'quotation_submit' ] );
	}

	/**
	 * Handle actions before quotation submission.
	 *
	 * This method implements rate limiting to prevent form spam and abuse.
	 * Uses atomic operations to prevent race conditions.
	 *
	 * @since 2.0.4
	 * @access public
	 *
	 * @return void
	 */
	public function before_quotation_submit() {
		// Rate limiter prevents the same IP from submitting the form too frequently.
		$settings = quotify()->settings()->get();

		if ( empty( $settings['pqfw_rate_limit_enabled'] ) ) {
			return; // nothing to do if limiter is disabled.
		}

		$max    = absint( $settings['pqfw_rate_limit_count'] );
		$period = absint( $settings['pqfw_rate_limit_period'] ); // stored in minutes.

		if ( $max < 1 || $period < 1 ) {
			return; // invalid configuration, allow submission.
		}

		$ip = Helper::get_client_ip();
		if ( ! $ip ) {
			return; // unable to determine IP, skip limiter.
		}

		// Build transient key with plugin-specific prefix to avoid collisions.
		$transient_key = 'quotify_rate_limit_' . md5( $ip );
		$now           = time();
		$expires       = $period * MINUTE_IN_SECONDS;

		// Use WordPress object cache for atomic increment to prevent race conditions.
		// This is thread-safe and prevents concurrent requests from bypassing the limit.
		$count = wp_cache_get( $transient_key );

		if ( false === $count ) {
			// First request in the window.
			wp_cache_set( $transient_key, 1, '', $expires );
			// Also store in transient for persistence across cache clears.
			set_transient(
				$transient_key . '_data',
				[
					'count' => 1,
					'start' => $now,
				],
				$expires
			);
			return;
		}

		// Check if the window has expired.
		$data = get_transient( $transient_key . '_data' );
		if ( false === $data || ( $now - $data['start'] ) > $expires ) {
			// Window expired, reset counter.
			wp_cache_set( $transient_key, 1, '', $expires );
			set_transient(
				$transient_key . '_data',
				[
					'count' => 1,
					'start' => $now,
				],
				$expires
			);
			return;
		}

		// Atomically increment counter (thread-safe).
		$new_count = wp_cache_incr( $transient_key );

		// Update persistent data.
		$data['count'] = $new_count;
		set_transient( $transient_key . '_data', $data, $expires );

		if ( $new_count > $max ) {
			// Calculate retry-after time in seconds.
			$retry_after = $expires - ( $now - $data['start'] );
			$retry_minutes = ceil( $retry_after / MINUTE_IN_SECONDS );

			// Log this rate limit hit for admin visibility.
			$this->log_rate_limit_hit( $ip, $new_count, $retry_after );

			// Send Retry-After header for proper HTTP semantics.
			header( sprintf( 'Retry-After: %d', $retry_after ) );

			// Send error with helpful message.
			$message = sprintf(
				/* translators: %s: number of minutes to wait */
				__( 'Too many quotation requests. Please try again in %s minutes.', 'quotify' ),
				$retry_minutes
			);

			wp_send_json_error( $message, 429 );
		}
	}

	/**
	 * Log rate limit violations for admin visibility.
	 *
	 * Helps identify potential attacks or abuse patterns.
	 *
	 * @since 2.5.1
	 * @access private
	 *
	 * @param string $ip          The IP address being limited.
	 * @param int    $count       Current count.
	 * @param int    $retry_after Seconds until retry is allowed.
	 * @return void
	 */
	private function log_rate_limit_hit( $ip, $count, $retry_after ) {
		// Get existing log or create new one.
		$log_key = 'quotify_rate_limit_log';
		$log = get_option( $log_key, [] );

		// Add entry with timestamp.
		$log[] = [
			'ip' => $ip,
			'count' => $count,
			'time' => current_time( 'mysql' ),
			'retry' => $retry_after,
		];

		// Keep only last 100 entries to prevent bloat.
		if ( count( $log ) > 100 ) {
			$log = array_slice( $log, -100 );
		}

		update_option( $log_key, $log, false );
	}

	/**
	 * Handle quotation submission and send email notifications.
	 *
	 * Processes new quotation submissions, sanitizes data, and sends emails
	 * to both customer and admin based on plugin settings using the template system.
	 *
	 * @since 2.0.4
	 * @since 2.6.0 - Updated to use Template_Manager for separate admin/customer templates.
	 * @access public
	 *
	 * @param int $id The post ID of the submitted quotation.
	 * @return void
	 */
	public function quotation_submit( $id ) {
		// Get template data using Template Manager.
		$template_data = quotify()->templates()->prepare_quotation_data( $id );

		// Generate email subjects using Mail class.
		$admin_subject = quotify()->mail()->get_admin_subject( $template_data );
		$customer_subject = quotify()->mail()->get_customer_subject( $template_data );

		// Send email to customer if enabled.
		if ( quotify()->settings()->get( 'pqfw_send_mail_to_customer' ) ) {
			$customer_email = $template_data['email'];
			$customer_body = quotify()->templates()->render( 'new-quotation', 'customer', $template_data );

			if ( ! empty( $customer_body ) ) {
				// Get headers with Reply-To set to admin email for customer responses.
				$admin_recipient = quotify()->settings()->get( 'recipient' );
				$headers = quotify()->mail()->get_headers( [
					'reply_to' => $admin_recipient,
				] );

				quotify()->mail()->send( $customer_email, $customer_subject, $customer_body, $headers );
			}
		}

		// Send email to admin if enabled.
		if ( quotify()->settings()->get( 'pqfw_form_send_mail' ) ) {
			$recipient = sanitize_email( quotify()->settings()->get( 'recipient' ) );
			$admin_body = quotify()->templates()->render( 'new-quotation', 'admin', $template_data );

			if ( ! empty( $admin_body ) ) {
				// Get headers with Reply-To set to customer email for admin responses.
				$customer_email = $template_data['email'];
				$headers = quotify()->mail()->get_headers( [
					'reply_to' => $customer_email,
				] );

				quotify()->mail()->send( $recipient, $admin_subject, $admin_body, $headers );
			}
		}
	}
}
