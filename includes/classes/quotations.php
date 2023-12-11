<?php
/**
 * Responsible for handling quotations operations.
 *
 * @since 1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Form Handler class.
 *
 * @package PQFW
 * @since   1.0.0
 */
class Quotations {
	/**
	 * Returns boolean if product present in cart
	 *
	 * @since 1.2.0
	 *
	 * @param  string $hash The product has.
	 * @return boolean      Product existence.
	 */
	public function ifExistsInCart( $hash ) {
		$products = $this->getProducts();
		return isset( $products[ $hash ] );
	}

	/**
	 * Returns boolean if product present in cart
	 *
	 * @since 1.2.0
	 *
	 * @param  array $detail The variation detail.
	 * @return mixed         Sanitized details or false.
	 */
	public function sanitizeVariationDetail( $detail ) {
		if ( is_array( $detail ) && count( $detail ) > 0 ) {
			$sanitizeDetail = [];

			foreach ( $detail as $key => $val ) {
				$sanitizeDetail[ sanitize_text_field( $key ) ] = sanitize_text_field( $val );
			}
			return $sanitizeDetail;
		}

		return false;
	}

	/**
	 * Checks if the product is variable.
	 *
	 * @since 1.2.0
	 *
	 * @param  integer $id The variation detail.
	 * @return mixed       Sanitized details or false.
	 */
	private function isVariable( $id ) {
		$product = wc_get_product( $id );

		return $product->is_type( 'variable' );
	}

	/**
	 * Generates the hash.
	 *
	 * @since 1.2.0
	 *
	 * @param  integer $id               The variation detail.
	 * @param  array   $variationDetails The variation detail.
	 * @return string                    Generated hash.
	 */
	private function generateHash( $id, $variationDetails ) {
		$value = '';

		if ( is_array( $variationDetails ) && count( $variationDetails ) > 0 ) {
			foreach ( $variationDetails as $key => $variation_detail ) {
				$value .= $variation_detail;
			}
		}

		$hash = md5( $id . $value );

		return $hash;
	}

	/**
	 * Removes product from cart.
	 *
	 * @since 1.2.0
	 *
	 * @param  string $hash The product identifier hash.
	 * @return bool
	 */
	public function removeProduct( $hash ) {
		$products = $this->getProducts();

		if ( is_array( $products ) && count( $products ) > 0 ) {
			unset( $products[ $hash ] );
		}

		return $this->addProducts( $products );
	}

	/**
	 * If $new_quantity is false will increment the existing quantity
	 * if it is not false and is a number then will it will update existing quantity
	 * if new quantity is zero it will remove the product from list
	 *
	 * @param string $hash     Product identifier hash.
	 * @param mixed  $quantity New product quantity.
	 */
	private function updateQuantity( $hash, $quantity = false ) {
		$products = $this->getProducts();

		if ( 0 === $quantity ) {
			$this->removeProduct( $hash );
			return;
		}

		if ( is_array( $products ) && count( $products ) > 0 ) {
			if ( $quantity ) {
				$products[ $hash ]['quantity'] = $products[ $hash ]['quantity'] + $quantity;
			} else {
				$products[ $hash ]['quantity'] = $products[ $hash ]['quantity'] + 1;
			}
		}

		$this->addProducts( $products );
	}

	/**
	 * Adds products to the cart.
	 *
	 * @since 1.2.0
	 *
	 * @param  array $products The products for add to the cart.
	 * @return bool
	 */
	public function addProducts( $products ) {
		$products = $this->sanitizeProducts( $products );

		if ( isset( WC()->session ) ) {
			WC()->session->set( 'pqfw_products_quotations_list', $products );
		}

		return $this->getProducts();
	}

	/**
	 * Sanitizes products and prepare for add to cart.
	 *
	 * @since 1.2.0
	 *
	 * @param  array $products The products for add to the cart.
	 * @return array           Sanitized products.
	 */
	private function sanitizeProducts( $products ) {
		if ( is_array( $products ) ) {
			foreach ( $products as $key => $product ) {
				$products[ $key ]['id'] = (int) $products[ $key ]['id'];
				$products[ $key ]['variation'] = (int) $products[ $key ]['variation'];
				$products[ $key ]['variation_detail'] = $this->sanitizeVariationDetail( $products[ $key ]['variation_detail'] );

				$products[ $key ]['quantity'] = (int) $products[ $key ]['quantity'];
				$products[ $key ]['message']  = sanitize_text_field( $products[ $key ]['message'] );

				if ( $products[ $key ]['quantity'] <= 0 ) {
					unset( $products[ $key ] );
				}
			}
		}
		return $products;
	}

	/**
	 * Add product to the cart.
	 *
	 * @since 1.2.0
	 *
	 * @param  integer $id               The product id.
	 * @param  integer $quantity         Product quantity.
	 * @param  integer $variation        Product variation.
	 * @param  integer $variation_detail Product variation details.
	 * @return bool
	 */
	public function addProduct( $id, $quantity, $variation, $variation_detail, $price = 0 ) {
		$products = $this->getProducts();
		$message  = '';

		if ( $this->isVariable( $id ) && false === $variation ) {
			return false;
		}

		$new_product = [
			'id'               => (int) $id,
			'quantity'         => (int) $quantity,
			'variation'        => (int) $variation,
			'price'            => $price,
			'variation_detail' => $variation_detail,
			'message'          => wp_strip_all_tags( $message )
		];

		$hash = $this->generateHash( $new_product['id'], $variation_detail );

		if ( $this->ifExistsInCart( $hash ) ) {
			/**
			 * This will increment it by one,
			 * as we are not entering the new quantity variable
			 */
			$this->updateQuantity( $hash, $new_product['quantity'] );
			$this->updatePrice( $hash, $new_product['price'] );
			return;
		} else {
			$products[ $hash ] = $new_product;
		}

		return $this->addProducts( $products );
	}

	/**
	 * Update price for the product.
	 *
	 * @since 2.0.1
	 * @param string $hash Product identifier hash.
	 * @param float  $price Product price.
	 */
	public function updatePrice( $hash, $price ) {
		$products = $this->getProducts();

		if ( is_array( $products ) && count( $products ) > 0 ) {
			if ( $price ) {
				$products[ $hash ]['price'] = $products[ $hash ]['price'] + $price;
			} else {
				$products[ $hash ]['price'] = $products[ $hash ]['price'] + 1;
			}
		}

		$this->addProducts( $products );
	}

	/**
	 * Retrieves products list from the cart.
	 *
	 * @since  1.2.0
	 * @return array The product collection.
	 */
	public function getProducts() {
		$products = [];

		if ( isset( WC()->session ) ) {
			$products = WC()->session->get( 'pqfw_products_quotations_list' );

			if ( null === $products ) {
				return [];
			}
		}

		return $products;
	}

	/**
	 * Purge the session cart.
	 *
	 * @since 1.2.0
	 */
	public function purge() {
		if ( isset( WC()->session ) ) {
			WC()->session->set( 'pqfw_products_quotations_list', [] );
		}
	}
}