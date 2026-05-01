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
		// Security check.
		if ( ! wp_verify_nonce( $_POST['security'], 'quotify_ajax' ) ) {
			wp_send_json_error( __( 'Security check failed!', 'quotify' ) );
		}

		// Validate product data exists.
		if ( empty( $_POST['data'] ) ) {
			wp_send_json_error( __( 'No product data provided.', 'quotify' ) );
		}

		// Decode and validate product data.
		$product = json_decode( wp_unslash( $_POST['data'] ), true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			wp_send_json_error( __( 'Invalid product data format.', 'quotify' ) );
		}

		// Validate product ID.
		$product_id = isset( $product['productID'] ) ? absint( $product['productID'] ) : 0;

		if ( ! $product_id ) {
			wp_send_json_error( __( 'Invalid product ID.', 'quotify' ) );
		}

		// Check if product exists.
		$wc_product = \wc_get_product( $product_id );

		if ( ! $wc_product ) {
			wp_send_json_error( __( 'Product not found.', 'quotify' ) );
		}

		// Validate quantity.
		$quantity = isset( $product['quantity'] ) ? absint( $product['quantity'] ) : 1;

		if ( $quantity < 1 ) {
			wp_send_json_error( __( 'Quantity must be at least 1.', 'quotify' ) );
		}

		// Check stock status.
		if ( ! $wc_product->is_in_stock() ) {
			wp_send_json_error( __( 'This product is out of stock.', 'quotify' ) );
		}

		// For simple products, check if enough stock available.
		if ( ! $wc_product->is_type( 'variable' ) && ! $wc_product->has_enough_stock( $quantity ) ) {
			/* Translators: %s: stock amount. */
			wp_send_json_error( sprintf( __( 'Insufficient stock. Only %s available.', 'quotify' ), $wc_product->get_stock_quantity() ) );
		}

		// Get variation data.
		$variation  = isset( $product['variationID'] ) ? absint( $product['variationID'] ) : 0;

		// For variable products, validate variation ID.
		if ( $wc_product->is_type( 'variable' ) && ! $variation ) {
			wp_send_json_error( __( 'Please select a product variation.', 'quotify' ) );
		}

		$variationDetail = quotify()->cart()->sanitize_variation_detail( $product['variationDetails'] );
		$price           = quotify()->cart()->get_simple_variations_price( $wc_product, $variation );

		// Add product to cart (will increase quantity if already exists).
		$status = quotify()->cart()->add_product( $product_id, $quantity, $variation, $variationDetail, $price );

		// Note: add_product returns void for existing products (quantity increased).
		// Only show error on explicit failure (returns false).
		if ( false === $status ) {
			wp_send_json_error( __( 'Failed to add product to quotation. Please try again.', 'quotify' ) );
		}

		wp_send_json_success(
			[
				'message' => __( 'Product successfully added to quotation.', 'quotify' ),
			]
		);
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
			quotify()->cart()->render( $products );
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
