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
	}

	/**
	 * Get quotations.
	 *
	 * @since 1.2.0
	 */
	public function load() {
		check_ajax_referer( 'pqfw_nonce', 'nonce' );

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
		check_ajax_referer( 'pqfw_nonce', 'nonce' );

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

		$quotation = [
			'ID'            => $post->ID,
			'title'         => get_the_title( $post ),
			'content'       => apply_filters( 'the_content', $post->post_content ),
			'excerpt'       => get_the_excerpt( $post ),
			'date'          => get_the_date( '', $post ),
			'modified_date' => get_the_modified_date( '', $post ),
			'slug'          => $post->post_name,
			'status'        => $post->post_status,
			'type'          => $post->post_type,
			'permalink'     => get_permalink( $post ),
		];

		$meta = quotify()->quotations()->format_meta( $id );

		$quotation['author_name'] = quotify()->quotations()->get_author( $post, $meta );
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
		check_ajax_referer( 'pqfw_nonce', 'nonce' );

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
		check_ajax_referer( 'pqfw_nonce', 'nonce' );

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
}
