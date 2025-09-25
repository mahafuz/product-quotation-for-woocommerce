<?php
/**
 * Product model for quotation system with contact form integration capabilities.
 *
 * @since      1.2.0
 * @package    PQFW
 * @subpackage Models
 */

namespace Quotify\Internals;

use Quotify;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Product model for quotation system.
 *
 * @since   1.0.0
 * @package PQFW
 */
class Product {
	/**
	 * Class instance.
	 *
	 * @var Quotify\Internals\Product
	 */
	private static $instance;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Quotify\Internals\Product
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Current products collection.
	 *
	 * @var array
	 */
	private $products;

	/**
	 * Map the products before save.
	 *
	 * @param array $products The products data to map.
	 *
	 * @since 1.2.0
	 * @return array
	 */
	public function map( array $products ) {
		$collection = [];

		foreach ( $products as $product ) {
			$collection[] = $this->processed( $product );
		}

		return $collection;
	}

	/**
	 * Filter out the particular fields only.
	 *
	 * @since 1.2.0
	 * @param array $product Product array.
	 * @return array
	 */
	private function processed( $product ) {
		$obj       = wc_get_product( $product['id'] );
		$permalink = $obj->get_permalink();
		$image_id  = $obj->get_image_id();
		$img       = wp_get_attachment_thumb_url( $image_id );
		$price     = wp_strip_all_tags( wc_price( $product['price'] ) );

		$variation_id = ( false !== $product['variation'] ? (int) $product['variation'] : false );
		$variation_detail = $this->variation_detail( $obj, $product['variation_detail'] );

		return [
			'id'               => $product['id'],
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
	 * @param array $products The products.
	 * @since 1.2.0
	 * @return array
	 */
	public function ids( $products ) {
		$container = [];

		foreach ( $products as $product ) {
			$container[] = $product['id'];
		}

		return $container;
	}


	/**
	 * Get product variation detail.
	 *
	 * @since 1.2.0
	 *
	 * @param object $product_obj The product object.
	 * @param array  $variation_detail The product variation detail.
	 * @return string
	 */
	private function variation_detail( $product_obj, $variation_detail ) {
		$variations_label = quotify()->cart()->get_variations( $product_obj, $variation_detail );
		$return = '';

		if ( is_array( $variations_label ) ) {
			foreach ( $variations_label as $key => $value ) {
				$return .= esc_html( $key ) . '|' . esc_html( $value ) . ',';
			}
		}

		return $return;
	}
}
