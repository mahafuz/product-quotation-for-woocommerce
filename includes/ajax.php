<?php

/**
 * PQFW class
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.2.0
 */

namespace PQFW; 


class Ajax {
	

	/**
	 * Initialize ajax actions.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_pqfw_get_quotations', [ $this, 'getQuotations' ] );
	}

	/**
	 * Get quotations.
	 *
	 * @since 1.2.0
	 */
	public function getQuotations() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( __( 'You do not have permission to view quotations.', 'product-quotation-for-woocommerce' ) );
			wp_die();
		}

		$status = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : 'publish';
		$page   = isset( $_POST['page'] ) ? absint( wp_unslash( $_POST['page'] ) ) : 1;
		$per_page = isset( $_POST['per_page'] ) ? absint( wp_unslash( $_POST['per_page'] ) ) : 10;
		$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';

		$args = [
			'post_type'      => 'pqfw_quotations',
			'post_status'    => $status === 'all' ? 'any' : $status,
			'posts_per_page' => $per_page,
			'paged'          => $page,
			's'              => $search,
		];
		$query = new \WP_Query( $args );
		$quotations = [];
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$quotations[] = [
					'id'          => get_the_ID(),
					'title'       => get_the_title(),
					'date'        => get_the_date(),
					'status'      => get_post_status(),
					'author_name' => get_the_author_meta( 'display_name' ),
				];
			}
			wp_reset_postdata();
		}
		wp_send_json_success( [
			'quotations' => $quotations,
			'total'      => $query->found_posts,
			'pages'      => $query->max_num_pages,
		] );
		wp_die();
	}
}