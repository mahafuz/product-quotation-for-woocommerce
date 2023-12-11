<?php
/**
 * Responsible for handling Ajax requests.
 *
 * @since 1.2.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Registers Ajax requests.
 *
 * @since 1.2.0
 * @package PQFW
 */
class Request {

	/**
	 * Class constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_pqfw_load_cart_data', [ $this, 'InitializeCart' ] );
		add_action( 'wp_ajax_nopriv_pqfw_load_cart_data', [ $this, 'InitializeCart' ] );
		add_action( 'wp_ajax_pqfw_add_product', [ $this, 'addProduct' ] );
		add_action( 'wp_ajax_nopriv_pqfw_add_product', [ $this, 'addProduct' ] );
		add_action( 'wp_ajax_pqfw_remove_product', [ $this, 'removeProduct' ] );
		add_action( 'wp_ajax_nopriv_pqfw_remove_product', [ $this, 'removeProduct' ] );
		add_action( 'wp_ajax_pqfw_update_products', [ $this, 'updateProducts' ] );
		add_action( 'wp_ajax_nopriv_pqfw_update_products', [ $this, 'updateProducts' ] );
	}

	/**
	 * Initialize cart.
	 *
	 * @since 1.0.0
	 */
	public function InitializeCart() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'pqfw_cart_actions' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid operation, could not verify nonce.', 'pqfw' )
			], 403);
		}

		$products = pqfw()->quotations->getProducts();
		$cart     = '';

		ob_start();
			pqfw()->cart->generateHTML( $products );
			$cart = ob_get_contents();
		ob_end_clean();

		wp_send_json_success([
			'html'     => $cart,
			'products' => $products
		]);
	}

	/**
	 * Add product to cart.
	 *
	 * @since 1.0.0
	 */
	public function addProduct() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'pqfw_cart_actions' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid operation, could not verify nonce.', 'pqfw' )
			], 403);
		}

		if ( isset( $_POST['productID'] ) && isset( $_POST['variationID'] ) ) {
			$id        = absint( $_POST['productID'] );
			$quantity  = (int) ( isset( $_POST['quantity'] ) ? $_POST['quantity'] : 1 );
			$variation = absint( $_POST['variationID'] );
			$product   = wc_get_product( $id );

			$variationDetail = pqfw()->quotations->sanitizeVariationDetail( $_POST['variationDetails'] );
			$price           = pqfw()->cart->getSimpleVariationPrice( $product, $variation );
			$products        = pqfw()->quotations->addProduct( $id, $quantity, $variation, $variationDetail, $price );

			wp_send_json_success([
				/* Translators: %d product id */
				'message' => sprintf( __( '%d Product Successfully added.', 'pqfw' ), $id )
			]);
		} else {
			wp_send_json_success([
				'message' => __( 'Invalid product data to add to quote.', 'pqfw' )
			]);
		}
	}

	/**
	 * Remove product from cart.
	 *
	 * @since 1.0.0
	 */
	public function removeProduct() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'pqfw_cart_actions' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid operation, could not verify nonce.', 'pqfw' )
			], 403);
		}

		$hash     = sanitize_text_field( $_POST['hash'] );
		$cart     = '';
		$products = pqfw()->quotations->removeProduct( $hash );

		ob_start();
			pqfw()->cart->generateHTML( $products );
		$cart = ob_get_contents();
		ob_end_clean();

		wp_send_json_success([
			'html'     => $cart,
			'products' => $products
		]);
	}

	/**
	 * Update cart products.
	 *
	 * @since 1.0.0
	 */
	public function updateProducts() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'pqfw_cart_actions' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid operation, could not verify nonce.', 'pqfw' )
			], 403);
		}

		$cart     = '';
		$products = pqfw()->quotations->addProducts( $_POST['products'] );

		ob_start();
			pqfw()->cart->generateHTML( $products );
			$cart = ob_get_contents();
		ob_end_clean();

		wp_send_json_success([
			'html'     => $cart,
			'products' => $products
		]);
	}
}