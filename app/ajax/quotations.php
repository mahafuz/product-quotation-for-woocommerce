<?php
/**
 * PQFW class
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.2.0
 */

namespace Quotify\Ajax;

use WP_Query;

/**
 * PQFW class
 *
 * @author      Mahafuz
 * @package     PQFW
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

		$status   = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'publish';
		$search   = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
		$page     = isset( $_GET['page'] ) ? absint( wp_unslash( $_GET['page'] ) ) : 1;
		$per_page = isset( $_GET['per_page'] ) ? absint( wp_unslash( $_GET['per_page'] ) ) : 10;

		$args = [
			'post_status'    => $status,
			'posts_per_page' => $per_page,
			'paged'          => $page,
			's'              => $search,
		];

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
			'date'          => sanitize_text_field( get_the_date( '', $post ) ),
			'modified_date' => sanitize_text_field( get_the_modified_date( '', $post ) ),
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
						'span' => ['class' => true],
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

		$quotation['author_name'] = sanitize_text_field( quotify()->quotations()->get_author( $post, $meta ) );
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
		$id    = json_decode( wp_unslash( $_POST['id'] ), true );
		$id    = ! empty( $id['ID'] ) ? absint( $id['ID'] ) : 0;

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
}
