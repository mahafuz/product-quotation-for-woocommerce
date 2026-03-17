<?php
/**
 * Responsible for handling Ajax requests.
 *
 * @since 1.2.0
 * @package Quotify
 */

namespace Quotify\Ajax;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Registers Ajax requests.
 *
 * @since 1.2.0
 * @package Quotify
 */
class Cart {

	/**
	 * Class constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_quotify/ajax/cart/load', [ $this, 'load' ] );
		add_action( 'wp_ajax_nopriv_quotify/ajax/cart/load', [ $this, 'load' ] );

		add_action( 'wp_ajax_quotify/ajax/cart/add_product', [ $this, 'add_product' ] );
		add_action( 'wp_ajax_nopriv_quotify/ajax/cart/add_product', [ $this, 'add_product' ] );

		add_action( 'wp_ajax_pqfw_remove_product', [ $this, 'remove_product' ] );
		add_action( 'wp_ajax_nopriv_pqfw_remove_product', [ $this, 'remove_product' ] );

		add_action( 'wp_ajax_quotify/ajax/cart/update', [ $this, 'update_product' ] );
		add_action( 'wp_ajax_nopriv_quotify/ajax/cart/update', [ $this, 'update_product' ] );
	}

	/**
	 * Initialize cart.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		if ( ! wp_verify_nonce( $_POST['security'], 'quotify_ajax' ) ) {
			wp_send_json_error( __( 'Security check failed!', 'quotify' ) );
		}

		$products = quotify()->cart()->get_products();
		$cart     = '';

		ob_start();
			quotify()->cart()->render();
			$cart = ob_get_contents();
		ob_end_clean();

		wp_send_json_success([
			'html'     => $cart,
			'products' => $products,
		]);
	}

	/**
	 * Add to quotation cart.
	 *
	 * @return void
	 */
	public function add_product() {
		if ( ! wp_verify_nonce( $_POST['security'], 'quotify_ajax' ) ) {
			wp_send_json_error( __( 'Security check failed!', 'quotify' ) );
		}

		if ( ! empty( $_POST['data'] ) ) {
			$product    = json_decode( wp_unslash( $_POST['data'] ), true );
			$product_id = isset( $product['productID'] ) ? absint( $product['productID'] ) : 0;

			if ( $product_id ) {
				$variation  = isset( $product['variationID'] ) ? absint( $product['variationID'] ) : 0;
				$quantity   = isset( $product['quantity'] ) ? absint( $product['quantity'] ) : 1;
				$wc_product = \wc_get_product( $product_id );

				$variationDetail = quotify()->cart()->sanitize_variation_detail( $product['variationDetails'] );
				$price           = quotify()->cart()->get_simple_variations_price( $wc_product, $variation );
				$status          = quotify()->cart()->add_product( $product_id, $quantity, $variation, $variationDetail, $price );

				wp_send_json_success([
					/* Translators: %d product product_id */
					'message' => sprintf( __( '%d Product Successfully added.', 'quotify' ), $product_id ),
				]);
			}
		} else {
			wp_send_json_success([
				'message' => __( 'Invalid product data to add to quote.', 'quotify' ),
			]);
		}
	}

	/**
	 * Remove product from cart.
	 *
	 * @since 1.0.0
	 */
	public function remove_product() {
		if ( ! wp_verify_nonce( $_POST['security'], 'quotify_ajax' ) ) {
			wp_send_json_error( __( 'Security check failed!', 'quotify' ) );
		}

		$hash     = sanitize_text_field( $_POST['hash'] );
		$cart     = '';
		$products = quotify()->cart()->remove_product( $hash );

		ob_start();
			quotify()->cart()->generateHTML( $products );
		$cart = ob_get_contents();
		ob_end_clean();

		wp_send_json_success([
			'html'     => $cart,
			'products' => $products,
		]);
	}

	/**
	 * Update cart products.
	 *
	 * @since 1.0.0
	 */
	public function update_product() {
		if ( ! wp_verify_nonce( $_POST['security'], 'quotify_ajax' ) ) {
			wp_send_json_error( __( 'Security check failed!', 'quotify' ) );
		}

		$cart = '';
		if ( is_string( $_POST['products'] ) ) {
			$products = json_decode( wp_unslash( $_POST['products'] ), true );
		}

		$products = quotify()->cart()->add_products( $products );

		ob_start();
			quotify()->cart()->render();
			$cart = ob_get_contents();
		ob_end_clean();

		wp_send_json_success([
			'html'     => $cart,
			'products' => quotify()->cart()->get_products(),
		]);
	}
}
