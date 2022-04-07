<?php
/**
 * Can be use as a product model.
 *
 * @since 1.2.0
 * @package PQFW
 */

namespace PQFW\Classes;

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
	 * Get product title.
	 */
	private function getTitle() {
		return isset( $this->dataToSave['fullname'] ) ? sanitize_text_field( $this->dataToSave['fullname'] ) : __( 'Quotation', 'pqfw' );
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
		$mappedProducts       = $this->mapProducts();
		$unserializedProducts = unserialize( $mappedProducts );

		if ( empty( $unserializedProducts ) ) {
			return false;
		}

		$arg        = $this->getAruguments();
		$postID     = wp_insert_post( $arg );
		$productsId = $this->getProductsID();

		if ( 0 === $postID || is_wp_error( $postID ) ) {
			return false;
		}

		update_post_meta( $postID, 'pqfw_products_info', $mappedProducts );
		update_post_meta( $postID, 'pqfw_products_ids', $productsId );

		return $postID;
	}

	/**
	 * Prepare post arugments.
	 *
	 * @since 1.2.0
	 */
	private function getAruguments() {
		$arg = [
			'post_title'  => $this->getTitle(),
			'post_type'   => Admin::POST_TYPE,
			'post_status' => 'publish',
			'meta_input'  => [
				'pqfw_customer_name'     => $this->getName(),
				'pqfw_customer_email'    => $this->getEmail(),
				'pqfw_customer_subject'  => $this->getSubject(),
				'pqfw_customer_phone'    => $this->getPhone(),
				'pqfw_subject'           => $this->getSubject(),
				'pqfw_customer_comments' => $this->getMessage()
			]
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

		return serialize( $static_products );
	}

	/**
	 * Filter out the particular fields only.
	 *
	 * @since 1.2.0
	 * @param array $product Product array.
	 * @retrun               Returns product array with additional values.
	 */
	private function filterFields( $product ) {
		$obj       = wc_get_product( $product['id'] );
		$permalink = $obj->get_permalink();
		$imageID   = $obj->get_image_id();
		$img       = wp_get_attachment_thumb_url( $imageID );
		$price     = wp_strip_all_tags( wc_price( pqfw()->cart->getSimpleVariationPrice( $obj, $product['variation'] ) ) );

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
			'message'          => strip_tags( $product['message'] )
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

		return $this->prepare();
	}
}