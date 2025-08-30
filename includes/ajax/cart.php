<?php
/**
 * Responsible for handling Ajax requests.
 *
 * @since 1.2.0
 * @package PQFW
 */

namespace PQFW\Ajax;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Registers Ajax requests.
 *
 * @since 1.2.0
 * @package PQFW
 */
class Cart {

	/**
	 * Class constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_quotify/ajax/cart/load', [ $this, 'InitializeCart' ] );
		add_action( 'wp_ajax_nopriv_quotify/ajax/cart/load', [ $this, 'InitializeCart' ] );

		add_action( 'wp_ajax_pqfw_remove_product', [ $this, 'removeProduct' ] );
		add_action( 'wp_ajax_nopriv_pqfw_remove_product', [ $this, 'removeProduct' ] );
		
		add_action( 'wp_ajax_quotify/ajax/cart/update', [ $this, 'update' ] );
		add_action( 'wp_ajax_nopriv_quotify/ajax/cart/update', [ $this, 'update' ] );
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
	public function update() {
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