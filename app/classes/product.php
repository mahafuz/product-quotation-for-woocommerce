<?php
/**
 * Can be use as a product model.
 *
 * @since 1.2.0
 * @package PQFW
 */

namespace PQFW\Classes;

use WP_Error;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Product model.
 *
 * @since   1.0.0
 * @package PQFW
 */
class Product {

	/**
	 * Current product data.
	 *
	 * @var mixed
	 */
	private $dataToSave;

	/**
	 * Current products collection.
	 *
	 * @var mixed
	 */
	private $products;

	/**
	 * Get product title.
	 */
	private function getTitle() {
		return isset( $this->dataToSave['fullname'] ) ? sanitize_text_field( $this->dataToSave['fullname'] ) : __( 'Quotation', 'quotify' );
	}

	/**
	 * Get person name.
	 */
	private function getName() {
		return isset( $this->dataToSave['fullname'] ) ? sanitize_text_field( $this->dataToSave['fullname'] ) : '';
	}

	/**
	 * Get person email.
	 */
	private function getEmail() {
		return isset( $this->dataToSave['email'] ) ? sanitize_email( $this->dataToSave['email'] ) : '';
	}

	/**
	 * Get person phone number
	 */
	private function getPhone() {
		return isset( $this->dataToSave['phone'] ) ? sanitize_text_field( $this->dataToSave['phone'] ) : '';
	}

	/**
	 * Get quotation subject.
	 */
	private function getSubject() {
		return isset( $this->dataToSave['subject'] ) ? sanitize_text_field( $this->dataToSave['subject'] ) : '';
	}

	/**
	 * Get person message.
	 */
	private function getMessage() {
		return isset( $this->dataToSave['comments'] ) ? sanitize_text_field( $this->dataToSave['comments'] ) : '';
	}

	/**
	 * Prepare the product to save in db.
	 *
	 * @since 1.2.0
	 */
	public function prepare() {
		$arg    = $this->getArguments();
		$postID = $this->insert( $arg );

		if ( 0 === $postID || is_wp_error( $postID ) ) {
			return false;
		}

		$this->set_attributes( $postID );

		return $postID;
	}

	/**
	 * Insert quotation as post.
	 *
	 * @param  array $args The quotation data.
	 * @return bool|int
	 */
	public function insert( $args ) {
		if ( empty( $args ) ) {
			return false;
		}

		$post_id = wp_insert_post( $args );

		return $post_id;
	}

	/**
	 * Set quotation attributes.
	 *
	 * @param int        $post_id    The quotation id.
	 * @param null|array $attributes The quotation attributes.
	 */
	public function set_attributes( $post_id, $attributes = null ) {
		if ( ! $attributes ) {
			$mappedProducts = $this->mapProducts();

			if ( empty( $mappedProducts ) ) {
				return false;
			}

			$productsId = $this->getProductsID();

			update_post_meta( $post_id, 'pqfw_products_info', $mappedProducts );
			update_post_meta( $post_id, 'pqfw_products_ids', $productsId );

			return [
				'pqfw_products_info' => $mappedProducts,
				'pqfw_products_ids'  => $productsId,
			];
		} else {
			if ( is_array( $attributes ) ) {
				foreach ( $attributes as $key => $value ) {
					update_post_meta( $post_id, $key, $value );
				}
			}

			return $attributes;
		}
	}

	/**
	 * Prepare post arguments.
	 *
	 * @since 1.2.0
	 */
	private function getArguments() {
		$arg = [
			'post_title'  => $this->getTitle(),
			'post_type'   => Admin::POST_TYPE,
			'post_status' => 'pending',
			'meta_input'  => [
				'pqfw_customer_name'     => $this->getName(),
				'pqfw_customer_email'    => $this->getEmail(),
				'pqfw_customer_subject'  => $this->getSubject(),
				'pqfw_customer_phone'    => $this->getPhone(),
				'pqfw_subject'           => $this->getSubject(),
				'pqfw_customer_comments' => $this->getMessage(),
			],
		];

		return $arg;
	}

	/**
	 * Map the products before save.
	 *
	 * @since 1.2.0
	 */
	public function mapProducts() {
		$static_products = [];

		foreach ( $this->products as $product ) {
			$static_products[] = $this->filterFields( $product );
		}

		return $static_products;
	}

	/**
	 * Filter out the particular fields only.
	 *
	 * @since 1.2.0
	 * @param array $product Product array.
	 * @return Returns product array with additional values.
	 */
	private function filterFields( $product ) {
		$obj       = wc_get_product( $product['id'] );
		$permalink = $obj->get_permalink();
		$imageID   = $obj->get_image_id();
		$img       = wp_get_attachment_thumb_url( $imageID );
		$price     = wp_strip_all_tags( wc_price( $product['price'] ) );

		$variation_id = ( false !== $product['variation'] ? (int) $product['variation'] : false );
		$variation_detail = $this->variationDetail( $obj, $product['variation_detail'] );

		return [
			'name'             => $obj->get_name(),
			'img'              => $img,
			'link'             => $permalink,
			'price'            => $price,
			'variation'        => $variation_id,
			'variation_detail' => $variation_detail,
			'quantity'         => $product['quantity'],
			'message'          => wp_strip_all_tags( $product['message'] ),
		];
	}

	/**
	 * Get products id only.
	 *
	 * @sine 1.2.0
	 */
	private function getProductsID() {
		$products_id = [];

		foreach ( $this->products as $product ) {
			$products_id[] = $product['id'];
		}

		return $products_id;
	}

	/**
	 * Get product variation detail.
	 *
	 * @since 1.2.0
	 *
	 * @param object $productOBJ The product object.
	 * @param array  $variationDetail The product variation detail.
	 */
	private function variationDetail( $productOBJ, $variationDetail ) {
		$variations_label = pqfw()->cart->getVariations( $productOBJ, $variationDetail );
		$return = '';

		if ( is_array( $variations_label ) ) {
			foreach ( $variations_label as $key => $value ) {
				$return .= esc_html( $key ) . '|' . esc_html( $value ) . ',';
			}
		}

		return $return;
	}

	/**
	 * Save the product in db.
	 *
	 * @since 1.2.0
	 * @param array $dataToSave The data to save.
	 */
	public function save( $dataToSave ) {
		$this->dataToSave = $dataToSave;
		$this->products   = pqfw()->quotations->getProducts();

		$status = $this->prepare();

		return $status;
	}
}
