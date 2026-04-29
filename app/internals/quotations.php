<?php
/**
 * Product model for quotation system with contact form integration capabilities.
 *
 * @since      1.2.0
 * @package    Quotify
 */

namespace Quotify\Internals;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Product model for quotation system.
 *
 * @since   1.0.0
 * @package Quotify
 */
class Quotations {
	/**
	 * Class instance.
	 *
	 * @var \Quotify\Internals\Quotations
	 */
	private static $instance;

	/**
	 * Query arguments
	 *
	 * @var array
	 */
	private $arguments;

	/**
	 * Data to save.
	 *
	 * @var array
	 */
	private $data_to_save;

	/**
	 * Query results.
	 *
	 * @var array
	 */
	private $response;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Quotify\Internals\Quotations
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 *  The quotation query.
	 *
	 * @param  array $arguments The query arguments.
	 * @return array
	 */
	public function query( $arguments ) {
		$this->arguments = (array) $arguments;

		$default = [
			'post_type' => 'pqfw_quotations',
		];

		$this->arguments = array_merge( $default, $this->arguments );

		$response = [];
		$query    = new \WP_Query( $this->arguments );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$response [] = $this->prepare_response_item();
			}
			wp_reset_postdata();
		}

		$this->response = $this->prepare_response( $response, $query, $this->arguments['paged'] );

		return $this;
	}

	/**
	 * Get author by post id.
	 *
	 * @param  int $post_id The post id.
	 * @return string
	 */
	public function get_author( $post_id ) {
		$author_id   = get_post_field( 'post_author', $post_id );
		$post_author = get_the_author_meta( 'display_name', get_the_author_meta( 'display_name', $author_id ) );

		if ( ! $post_author ) {
			$post_author = get_post_meta( $post_id, 'pqfw_customer_name', true );
		}

		return $post_author;
	}

	/**
	 * Prepare each response item.
	 *
	 * @return array
	 */
	private function prepare_response_item() {
		return [
			'id'          => absint( get_the_ID() ),
			'title'       => esc_html( get_the_title() ),
			'date'        => esc_html( get_the_date() ),
			'status'      => sanitize_key( get_post_status() ),
			'author_name' => $this->get_author( get_the_ID() ),
		];
	}

	/**
	 * Prepare the response.
	 *
	 * @param  mixed $response The response.
	 * @param  mixed $query The query.
	 * @param  mixed $page The page.
	 * @return array
	 */
	private function prepare_response( $response, $query, $page = 1 ) {
		return [
			'quotations'  => $response,
			'total'       => $query->found_posts,
			'pages'       => $query->max_num_pages,
			'currentPage' => $page,
		];
	}

	/**
	 * Get response.
	 *
	 * @return array
	 */
	public function get() {
		return $this->response;
	}

	/**
	 * Formats quotation metadata to display.
	 *
	 * @param  int $id The quotation id.
	 * @return array
	 */
	public function format_meta( $id ) {
		$metadata = get_post_meta( $id );
		$response = [];

		if ( empty( $metadata ) ) {
			return $response;
		}

		foreach ( $metadata as $key => $value ) {
			$data = array_shift( $value );
			$data = maybe_unserialize( $data );

			// Sanitize customer data fields
			if ( 'pqfw_customer_name' === $key ) {
				$data = sanitize_text_field( $data );
			} elseif ( 'pqfw_customer_email' === $key ) {
				$data = sanitize_email( $data );
			} elseif ( 'pqfw_customer_phone' === $key ) {
				$data = sanitize_text_field( $data );
			} elseif ( 'pqfw_customer_subject' === $key ) {
				$data = sanitize_text_field( $data );
			} elseif ( 'pqfw_customer_comments' === $key ) {
				$data = sanitize_textarea_field( $data );
			}

			$response[ $key ] = $data;
		}

		return $response;
	}

	/**
	 * Insert quotation as post.
	 *
	 * @param  array $args The quotation data.
	 * @return int|false
	 */
	public function insert( $args ) {
		if ( empty( $args ) ) {
			return false;
		}

		return wp_insert_post( $args );
	}

	/**
	 * Prepare post arguments.
	 *
	 * @param array $meta_input The meta inputs.
	 * @since 1.2.0
	 * @return array
	 */
	private function get_arguments( $meta_input ) {
		return [
			'post_title'  => $meta_input['pqfw_customer_name'] ?? __( 'Untitled', 'quotify' ),
			'post_type'   => \Quotify\Admin::POST_TYPE,
			'post_status' => 'pending',
			'meta_input'  => $meta_input,
		];
	}

	/**
	 * Save the product in db.
	 *
	 * @since 1.2.0
	 * @param array $form_entries The data to save.
	 * @return int|false
	 */
	public function save( $form_entries ) {
		$products    = quotify()->cart()->get_products();
		$form_entries['pqfw_products_info'] = quotify()->product()->map( $products );
		$form_entries['pqfw_products_ids']  = quotify()->product()->ids( $products );
		$arguments = $this->get_arguments( $form_entries );

		return $this->insert( $arguments );
	}
}
