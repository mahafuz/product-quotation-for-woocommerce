<?php
namespace Quotify\Ajax;

use WP_Query;

/**
 * Quotify quotations ajax class
 *
 * @author      Mahafuz
 * @package     Quotify
 * @since       1.2.0
 */
class Quotations {
	/**
	 * Initialize ajax actions.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_quotify/ajax/quotations/load', [ $this, 'load' ] );
		add_action( 'wp_ajax_quotify/ajax/quotations/get', [ $this, 'get_item' ] );
		add_action( 'wp_ajax_quotify/ajax/quotations/delete', [ $this, 'delete_item' ] );
		add_action( 'wp_ajax_quotify/ajax/quotations/restore', [ $this, 'restore_item' ] );
		add_action( 'wp_ajax_quotify/ajax/quotations/update_status', [ $this, 'update_status' ] );
		add_action( 'wp_ajax_quotify/ajax/quotations/email', [ $this, 'send_email' ] );
		add_action( 'wp_ajax_quotify/ajax/quotations/stats', [ $this, 'get_stats' ] );
		add_action( 'wp_ajax_quotify/ajax/quotations/export', [ $this, 'export_csv' ] );
	}

	/**
	 * Get quotations.
	 *
	 * @since 1.2.0
	 */
	public function load() {
		check_ajax_referer( 'quotify_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permission to view quotations.', 'quotify' ) );
			wp_die();
		}

		$status      = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'publish';
		$search      = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
		$page        = isset( $_GET['page'] ) ? absint( wp_unslash( $_GET['page'] ) ) : 1;
		$per_page    = isset( $_GET['per_page'] ) ? absint( wp_unslash( $_GET['per_page'] ) ) : 10;
		$date_filter = isset( $_GET['date_filter'] ) ? sanitize_text_field( $_GET['date_filter'] ) : 'all';

		// Calculate date query based on filter
		$date_query = $this->get_date_query( $date_filter );

		$args = [
			'post_status'    => $status,
			'posts_per_page' => $per_page,
			'paged'          => $page,
			's'              => $search,
		];

		// Add date query if not 'all'
		if ( $date_query ) {
			$args['date_query'] = $date_query;
		}

		$response = quotify()->quotations()->query( $args )->get();

		wp_send_json_success( $response );
	}

	/**
	 * Get single quotation.
	 *
	 * @return void
	 */
	public function get_item() {
		check_ajax_referer( 'quotify_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( __( 'You do not have permission to view quotations.', 'quotify' ) );
			wp_die();
		}

		$id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;

		if ( ! $id ) {
			wp_send_json_error([
				'message' => __( 'Quotation not found.', 'quotify' ),
				'not_found' => true,
			]);
		}

		$post = get_post( $id, OBJECT, 'display' );

		if ( empty( $post ) || is_wp_error( $post ) ) {
			wp_send_json_error([
				'message' => __( 'Quotation not found.', 'quotify' ),
				'not_found' => true,
			]);
		}

		// Verify post type is correct
		if ( 'pqfw_quotations' !== $post->post_type ) {
			wp_send_json_error([
				'message' => __( 'Invalid quotation.', 'quotify' ),
				'not_found' => true,
			]);
		}

		$quotation = [
			'ID'            => absint( $post->ID ),
			'title'         => sanitize_text_field( get_the_title( $post ) ),
			'content'       => wp_kses_post( apply_filters( 'the_content', $post->post_content ) ),
			'excerpt'       => wp_kses_post( get_the_excerpt( $post ) ),
			'date'          => sanitize_text_field( get_the_date( get_option( 'date_format' ), $post ) ),
			'modified_date' => sanitize_text_field( get_the_modified_date( get_option( 'date_format' ), $post ) ),
			'slug'          => sanitize_title( $post->post_name ),
			'status'        => sanitize_key( $post->post_status ),
			'type'          => sanitize_key( $post->post_type ),
			'permalink'     => esc_url( get_permalink( $post ) ),
		];

		$meta = quotify()->quotations()->format_meta( $id );

		// Sanitize price HTML in products info
		if ( isset( $meta['pqfw_products_info'] ) && is_array( $meta['pqfw_products_info'] ) ) {
			foreach ( $meta['pqfw_products_info'] as &$product ) {
				if ( isset( $product['price'] ) ) {
					// Allow only safe HTML for price formatting (currency symbols, etc)
					$product['price'] = wp_kses( $product['price'], [
						'span' => [ 'class' => true ],
						'del' => true,
						'ins' => true,
						'b' => true,
						'strong' => true,
						'em' => true,
					] );
				}
				if ( isset( $product['name'] ) ) {
					$product['name'] = sanitize_text_field( $product['name'] );
				}
				if ( isset( $product['link'] ) ) {
					$product['link'] = esc_url( $product['link'] );
				}
				if ( isset( $product['img'] ) ) {
					$product['img'] = esc_url( $product['img'] );
				}
				if ( isset( $product['message'] ) ) {
					$product['message'] = sanitize_textarea_field( $product['message'] );
				}
				if ( isset( $product['quantity'] ) ) {
					$product['quantity'] = absint( $product['quantity'] );
				}
			}
			unset( $product );
		}

		$quotation['author_name'] = sanitize_text_field( quotify()->quotations()->get_author( $post ) );
		$quotation['meta']   = $meta;

		wp_send_json_success([
			'message'   => __( 'Quotation fetched successfully.', 'quotify' ),
			'quotation' => $quotation,
		]);
	}

	/**
	 * Delete operation for a quotation.
	 *
	 * @return void
	 */
	public function delete_item() {
		check_ajax_referer( 'quotify_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( __( 'You do not have permission to delete quotation.', 'quotify' ) );
			wp_die();
		}

		$force = isset( $_POST['force'] ) ? wp_validate_boolean( $_POST['force'] ) : false;

		// Handle both plain ID and JSON-encoded ID formats for backward compatibility
		$id    = isset( $_POST['id'] ) ? wp_unslash( $_POST['id'] ) : '';
		$decoded = json_decode( $id, true );
		if ( is_array( $decoded ) && isset( $decoded['ID'] ) ) {
			$id = absint( $decoded['ID'] );
		} else {
			$id = absint( $id );
		}

		if ( ! $id ) {
			wp_send_json_error( __( 'Quotation not found.', 'quotify' ) );
		}

		if ( $force ) {
			$deleted_post = wp_delete_post( $id, true );

			wp_send_json_success([
				'message'   => __( 'Quotation permanently deleted!', 'quotify' ),
				'quotation' => $deleted_post,
			]);
		} else {
			$trashed_post = wp_trash_post( $id );

			wp_send_json_success([
				'message'   => __( 'Quotation Moved to Trash!', 'quotify' ),
				'quotation' => $trashed_post,
			]);
		}
	}

	/**
	 * Restore operation for a trashed post.
	 *
	 * @return void
	 */
	public function restore_item() {
		check_ajax_referer( 'quotify_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( __( 'You do not have permission to delete quotation.', 'quotify' ) );
		}

		$id = ! empty( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

		if ( ! $id ) {
			wp_send_json_error( __( 'Quotation not found.', 'quotify' ) );
		}

		$post = wp_untrash_post( $id );

		$result = wp_update_post( [
			'ID'          => $id,
			'post_status' => 'pending',
		], true );

		wp_send_json_success([
			'message'   => __( 'Quotation Moved to Trash!', 'quotify' ),
			'quotation' => $post,
		]);
	}

	/**
	 * Update quotation status.
	 *
	 * @return void
	 */
	public function update_status() {
		check_ajax_referer( 'quotify_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( __( 'You do not have permission to update quotation status.', 'quotify' ) );
			wp_die();
		}

		$id     = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		$status = isset( $_POST['status'] ) ? sanitize_key( $_POST['status'] ) : '';

		if ( ! $id ) {
			wp_send_json_error( __( 'Quotation not found.', 'quotify' ) );
		}

		// Validate status
		$valid_statuses = [ 'pending', 'publish', 'draft', 'trash' ];
		if ( ! in_array( $status, $valid_statuses, true ) ) {
			wp_send_json_error( __( 'Invalid status.', 'quotify' ) );
		}

		$post = get_post( $id );

		if ( ! $post || is_wp_error( $post ) ) {
			wp_send_json_error( __( 'Quotation not found.', 'quotify' ) );
		}

		if ( 'pqfw_quotations' !== $post->post_type ) {
			wp_send_json_error( __( 'Invalid quotation.', 'quotify' ) );
		}

		// Update post status
		$result = wp_update_post( [
			'ID'          => $id,
			'post_status' => $status,
		], true );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( __( 'Failed to update status.', 'quotify' ) );
		}

		// Get updated quotation data
		$updated_post = get_post( $id, OBJECT, 'display' );
		$quotation = [
			'ID'     => absint( $updated_post->ID ),
			'title'  => sanitize_text_field( get_the_title( $updated_post ) ),
			'status' => sanitize_key( $updated_post->post_status ),
		];

		wp_send_json_success([
			'message'   => __( 'Status updated successfully.', 'quotify' ),
			'quotation' => $quotation,
		]);
	}

	/**
	 * Send quotation email to customer.
	 *
	 * @return void
	 */
	public function send_email() {
		check_ajax_referer( 'quotify_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( __( 'You do not have permission to send emails.', 'quotify' ) );
			wp_die();
		}

		$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

		if ( ! $id ) {
			wp_send_json_error( __( 'Quotation not found.', 'quotify' ) );
		}

		$post = get_post( $id );

		if ( ! $post || is_wp_error( $post ) ) {
			wp_send_json_error( __( 'Quotation not found.', 'quotify' ) );
		}

		if ( 'pqfw_quotations' !== $post->post_type ) {
			wp_send_json_error( __( 'Invalid quotation.', 'quotify' ) );
		}

		// Get customer email
		$customer_email = get_post_meta( $id, 'pqfw_customer_email', true );

		if ( ! is_email( $customer_email ) ) {
			wp_send_json_error( __( 'Customer email not found.', 'quotify' ) );
		}

		// Prepare email data
		$meta     = quotify()->quotations()->format_meta( $id );
		$quote    = $post;
		$author   = sanitize_user( get_the_author_meta( 'first_name', $quote->post_author ) );
		$title    = get_the_title( $id );
		$handle   = ! empty( $author ) ? esc_attr( $author ) : esc_attr( $title );
		$subject  = sanitize_text_field( $meta['pqfw_customer_subject'] ?? __( 'Your Quotation', 'quotify' ) );
		$products = $meta['pqfw_products_info'] ?? [];
		$headers  = [ 'Content-Type: text/html; charset=UTF-8' ];

		// Build email content
		ob_start();
		$collection = [
			'fullname'    => $handle,
			'email'       => $customer_email,
			'subject'     => $subject,
			'phone'       => $meta['pqfw_customer_phone'] ?? '',
			'comments'    => $meta['pqfw_customer_comments'] ?? '',
			'products'    => $products,
			'email_title' => get_bloginfo( 'name' ),
			'site_url'    => get_bloginfo( 'url' ),
			'quotation_id' => $id,
			'is_admin_email' => false,
		];
		require QUOTIFY_PLUGIN_VIEWS . 'email/new-quote.php';
		$body = ob_get_clean();

		// Send email
		$sent = quotify()->mail()->send( $customer_email, $subject, $body, $headers );

		if ( $sent ) {
			wp_send_json_success([
				'message' => __( 'Quotation sent to customer successfully.', 'quotify' ),
			]);
		} else {
			wp_send_json_error( __( 'Failed to send email.', 'quotify' ) );
		}
	}

	/**
	 * Get quotation statistics for dashboard.
	 *
	 * @return void
	 */
	public function get_stats() {
		check_ajax_referer( 'quotify_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( __( 'You do not have permission to view statistics.', 'quotify' ) );
			wp_die();
		}

		$date_filter = isset( $_GET['date_filter'] ) ? sanitize_text_field( $_GET['date_filter'] ) : 'all';

		// Calculate date range based on filter
		$date_query = $this->get_date_query( $date_filter );

		// Get total count
		$total_args = [
			'post_type'      => 'pqfw_quotations',
			'post_status'    => 'any',
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'date_query'     => $date_query,
		];

		$query = new WP_Query( $total_args );
		$total = $query->found_posts;

		// Get pending count
		$pending_args = $total_args;
		$pending_args['post_status'] = 'pending';
		$query = new WP_Query( $pending_args );
		$pending = $query->found_posts;

		// Get approved (publish) count
		$approved_args = $total_args;
		$approved_args['post_status'] = 'publish';
		$query = new WP_Query( $approved_args );
		$approved = $query->found_posts;

		// Get trash count
		$trash_args = $total_args;
		$trash_args['post_status'] = 'trash';
		$query = new WP_Query( $trash_args );
		$trash = $query->found_posts;

		// Calculate total value (sum of products with prices)
		$value = $this->calculate_total_value( $date_query );

		wp_send_json_success([
			'message' => __( 'Statistics fetched successfully.', 'quotify' ),
			'stats' => [
				'total'    => $total,
				'pending'  => $pending,
				'approved'  => $approved,
				'trash'    => $trash,
				'value'    => $value,
			],
		]);
	}

	/**
	 * Calculate date query array based on date filter.
	 *
	 * @param string $filter The date filter key.
	 * @return array|false Date query array or false.
	 */
	private function get_date_query( $filter ) {
		if ( 'all' === $filter ) {
			return false;
		}

		// Use WordPress current_time for local time
		$now = current_time( 'timestamp' );
		$year = intval( date_i18n( 'Y', $now ) );
		$month = intval( date_i18n( 'm', $now ) );
		$day = intval( date_i18n( 'd', $now ) );

		switch ( $filter ) {
			case 'today':
				$today = date_i18n( 'Y-m-d', $now );
				return [
					[
						'after'     => $today,
						'before'    => $today,
						'inclusive' => true,
					],
				];

			case 'week':
				// Calculate week start and end using WordPress time
				$week_start = date_i18n( 'Y-m-d', strtotime( 'this week', $now ) );
				$week_end = date_i18n( 'Y-m-d', strtotime( 'this week +6 days', $now ) );
				return [
					[
						'after'     => $week_start,
						'before'    => $week_end,
						'inclusive' => true,
					],
				];

			case 'month':
				// Get first day of month
				$month_start_timestamp = mktime( 0, 0, 0, $month, 1, $year );
				$month_start = date_i18n( 'Y-m-d', $month_start_timestamp );

				// Get last day of month using WordPress date
				$days_in_month = intval( date_i18n( 't', $month_start_timestamp ) );
				$month_end_timestamp = mktime( 23, 59, 59, $month, $days_in_month, $year );
				$month_end = date_i18n( 'Y-m-d', $month_end_timestamp );

				return [
					[
						'after'     => $month_start,
						'before'    => $month_end,
						'inclusive' => true,
					],
				];

			case 'quarter':
				// Calculate quarter start and end
				$quarter = intval( ceil( $month / 3 ) );
				$quarter_start_month = ( $quarter - 1 ) * 3 + 1;

				// Quarter start
				$quarter_start_timestamp = mktime( 0, 0, 0, $quarter_start_month, 1, $year );
				$quarter_start = date_i18n( 'Y-m-d', $quarter_start_timestamp );

				// Quarter end
				$quarter_end_month = $quarter_start_month + 2;
				$days_in_quarter_end_month = intval( date_i18n( 't', mktime( 0, 0, 0, $quarter_end_month, 1, $year ) ) );
				$quarter_end_timestamp = mktime( 23, 59, 59, $quarter_end_month, $days_in_quarter_end_month, $year );
				$quarter_end = date_i18n( 'Y-m-d', $quarter_end_timestamp );

				return [
					[
						'after'     => $quarter_start,
						'before'    => $quarter_end,
						'inclusive' => true,
					],
				];

			case 'year':
				// Year start
				$year_start_timestamp = mktime( 0, 0, 0, 1, 1, $year );
				$year_start = date_i18n( 'Y-m-d', $year_start_timestamp );

				// Year end
				$year_end_timestamp = mktime( 23, 59, 59, 12, 31, $year );
				$year_end = date_i18n( 'Y-m-d', $year_end_timestamp );

				return [
					[
						'after'     => $year_start,
						'before'    => $year_end,
						'inclusive' => true,
					],
				];

			default:
				return false;
		}
	}

	/**
	 * Calculate total value of all products in quotations.
	 *
	 * @param array|false $date_query Date query array.
	 * @return float Total value.
	 */
	private function calculate_total_value( $date_query = false ) {
		$args = [
			'post_type'      => 'pqfw_quotations',
			'post_status'    => [ 'pending', 'publish' ],
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'date_query'     => $date_query,
		];

		$query = new WP_Query( $args );
		$total_value = 0;

		foreach ( $query->posts as $post_id ) {
			$products = get_post_meta( $post_id, 'pqfw_products_info', true );
			if ( is_array( $products ) ) {
				foreach ( $products as $product ) {
					$numeric_value = 0;
					$quantity = isset( $product['quantity'] ) ? absint( $product['quantity'] ) : 1;

					// Try to get price from different fields
					if ( isset( $product['price_html'] ) ) {
						// Extract numeric value from price HTML
						$price = html_entity_decode( $product['price_html'] );
						$price = preg_replace( '/[^0-9.,]/', '', $price );
						$numeric_value = (float) $price;
					} elseif ( isset( $product['price'] ) ) {
						// Price field might be HTML or numeric
						if ( is_numeric( $product['price'] ) ) {
							$numeric_value = (float) $product['price'];
						} else {
							// Extract numeric value from price HTML string
							$price = html_entity_decode( $product['price'] );
							$price = preg_replace( '/[^0-9.,]/', '', $price );
							$numeric_value = (float) $price;
						}
					}

					$total_value += $numeric_value * $quantity;
				}
			}
		}

		return $total_value;
	}

	/**
	 * Export quotations to CSV.
	 *
	 * @since 2.5.0
	 * @return void
	 */
	public function export_csv() {
		check_ajax_referer( 'quotify_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( __( 'You do not have permission to export quotations.', 'quotify' ) );
			wp_die();
		}

		$status   = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'all';
		$date_filter = isset( $_GET['date_filter'] ) ? sanitize_text_field( $_GET['date_filter'] ) : 'all';

		// Calculate date query if needed
		$date_query = 'all' !== $date_filter ? $this->get_date_query( $date_filter ) : false;

		// Build query args
		$args = [
			'post_type'      => 'pqfw_quotations',
			'post_status'    => 'all' === $status ? [ 'pending', 'publish' ] : $status,
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'date_query'     => $date_query,
		];

		$query = new WP_Query( $args );
		$quotation_ids = $query->posts;

		if ( empty( $quotation_ids ) ) {
			wp_send_json_error( __( 'No quotations found to export.', 'quotify' ) );
		}

		// Set headers for CSV download
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename=quotations-' . date_i18n( 'Y-m-d' ) . '.csv' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		// Open output stream
		$output = fopen( 'php://output', 'w' );

		// Add BOM for UTF-8
		fprintf( $output, "\xEF\xBB\xBF" );

		// CSV headers
		$headers = [
			'ID',
			'Title',
			'Author',
			'Email',
			'Phone',
			'Status',
			'Date',
			'Products',
			'Comments',
		];

		fputcsv( $output, $headers );

		// Write quotation data
		foreach ( $quotation_ids as $quotation_id ) {
			$post = get_post( $quotation_id );
			$meta = quotify()->quotations()->format_meta( $quotation_id );
			$author_name = quotify()->quotations()->get_author( $post, $meta );

			// Format products as string
			$products_info = $meta['pqfw_products_info'] ?? [];
			$products = [];
			foreach ( $products_info as $product ) {
				$product_name = $product['name'] ?? '';
				$quantity = $product['quantity'] ?? 1;
				$price = $product['price'] ?? '';
				$products[] = $product_name . ' (x' . $quantity . ') - ' . $price;
			}
			$products_string = implode( ' | ', $products );

			$row = [
				$quotation_id,
				get_the_title( $quotation_id ),
				$author_name,
				$meta['pqfw_customer_email'] ?? '',
				$meta['pqfw_customer_phone'] ?? '',
				get_post_status( $quotation_id ),
				get_the_date( 'Y-m-d H:i:s', $quotation_id ),
				$products_string,
				$meta['pqfw_customer_comments'] ?? '',
			];

			fputcsv( $output, $row );
		}

		fclose( $output );
		exit;
	}
}
