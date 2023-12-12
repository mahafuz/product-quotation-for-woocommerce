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
	 * Prepare the product to save in db.
	 *
	 * @since 1.2.0
	 */
	public function prepare( $data_to_save ) {
		$products = $this->mapProducts();

		if ( empty( $products ) ) {
			return false;
		}

		$arg        = $this->get_arguments( $data_to_save );
		$postID     = wp_insert_post( $arg );
		$productsId = $this->getProductsID();

		if ( 0 === $postID || is_wp_error( $postID ) ) {
			return false;
		}

		update_post_meta( $postID, 'pqfw_products_info', $products );
		update_post_meta( $postID, 'pqfw_products_ids', $productsId );

		return $postID;
	}

	/**
	 * Prepare post arguments.
	 *
	 * @since 1.2.0
	 */
	private function get_arguments( $data ) {
		$arg = [
			'post_title'  => isset( $data['full_name'] ) ? sanitize_text_field( $data['full_name'] ) : __( 'Untitled Quotation', 'pqfw' ),
			'post_type'   => Admin::POST_TYPE,
			'post_status' => 'publish',
			'meta_input'  => [
				'pqfw_customer_name'     => isset( $data['full_name'] ) ? sanitize_text_field( $data['full_name'] ) : '',
				'pqfw_customer_email'    => isset( $data['email'] ) ? sanitize_email( $data['email'] ) : '',
				'pqfw_customer_subject'  => isset( $data['subject'] ) ? sanitize_text_field( $data['subject'] ) : '',
				'pqfw_customer_phone'    => isset( $data['phone_mobile'] ) ? sanitize_text_field( $data['phone_mobile'] ) : '',
				'pqfw_subject'           => isset( $data['subject'] ) ? sanitize_text_field( $data['subject'] ) : '',
				'pqfw_customer_comments' => isset( $data['comments'] ) ? sanitize_text_field( $data['comments'] ) : '',
				'pqfw_website_url'       => isset( $data['website_url'] ) ? sanitize_text_field( $data['website_url'] ) : ''
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
		$products = pqfw()->quotations->getProducts();
		$result   = [];
		$total_price_inc_tax = 0;
		$total_price_exc_tax = 0;

		foreach ( $products as $product ) {
			$result[] = $this->filterFields( $product, $total_price_inc_tax, $total_price_exc_tax );
		}

		return $result;
	}

	/**
	 * Filter out the particular fields only.
	 *
	 * @since 1.2.0
	 * @param array $product Product array.
	 * @return               Returns product array with additional values.
	 */
	private function filterFields( $product, &$total_price_inc_tax, &$total_price_exc_tax ) {
		$obj       = wc_get_product( $product['id'] );
		$permalink = $obj->get_permalink();
		$imageID   = $obj->get_image_id();
		$img       = wp_get_attachment_thumb_url( $imageID );
		$regular_price     = wp_strip_all_tags( $product['regular_price'] );
		$inc_tax_price     = wp_strip_all_tags( $product['inc_tax_price'] );
		$exc_tax_price     = wp_strip_all_tags( $product['exc_tax_price'] );
		$total_price_inc_tax += $inc_tax_price;
		$total_price_exc_tax += $exc_tax_price;

		$variation_id = ( false !== $product['variation'] ? (int) $product['variation'] : false );
		$variation_detail = $this->variationDetail( $obj, $product['variation_detail'] );

		return [
			'id'               => $obj->ID,
			'name'             => $obj->get_name(),
			'img'              => $img,
			'link'             => $permalink,
			'regular_price'    => $regular_price,
			'inc_tax_price'    => $inc_tax_price,
			'exc_tax_price'    => $exc_tax_price,
			'variation'        => $variation_id,
			'variation_detail' => $variation_detail,
			'quantity'         => $product['quantity'],
			'message'          => wp_strip_all_tags( $product['message'] )
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

		return $this->prepare( $dataToSave );
	}
}