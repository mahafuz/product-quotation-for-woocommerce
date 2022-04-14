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
		add_action( 'wp_ajax_pqfw_add_product', [ $this, 'addProduct' ] );
		add_action( 'wp_ajax_pqfw_remove_product', [ $this, 'removeProduct' ] );
		add_action( 'wp_ajax_pqfw_update_products', [ $this, 'updateProducts' ] );
	}

	/**
	 * Initialize cart.
	 *
	 * @since 1.0.0
	 */
	public function InitializeCart() {
		$products = pqfw()->quotations->getProducts();
		$cart     = '';

		ob_start();
			pqfw()->cart->generateHTML( $products );
			$cart = ob_get_contents();
		ob_end_clean();

		echo wp_json_encode([
			'html'     => $cart,
			'products' => $products
		], JSON_UNESCAPED_SLASHES );

		die();
	}

	/**
	 * Add product to cart.
	 *
	 * @since 1.0.0
	 */
	public function addProduct() {
		if ( isset( $_POST['productID'] ) && isset( $_POST['variationID'] ) ) {
			$id        = absint( $_POST['productID'] );
			$quantity  = (int) ( isset( $_POST['quantity'] ) ? $_POST['quantity'] : 1 );
			$variation = absint( $_POST['variationID'] );

			$variationDetail = pqfw()->quotations->sanitizeVariationDetail( $_POST['variationDetails'] );
			$products        = pqfw()->quotations->addProduct( $id, $quantity, $variation, $variationDetail );
		}

		die;
	}

	/**
	 * Remove product from cart.
	 *
	 * @since 1.0.0
	 */
	public function removeProduct() {
		$hash     = sanitize_text_field( $_POST['hash'] );
		$cart     = '';
		$products = pqfw()->quotations->removeProduct( $hash );

		ob_start();
			pqfw()->cart->generateHTML( $products );
		$cart = ob_get_contents();
		ob_end_clean();

		echo wp_json_encode([
			'html'     => $cart,
			'products' => $products
		], JSON_UNESCAPED_SLASHES );

		die;
	}

	/**
	 * Update cart products.
	 *
	 * @since 1.0.0
	 */
	public function updateProducts() {
		$cart     = '';
		$products = pqfw()->quotations->addProducts( $_POST['products'] );

		ob_start();
			pqfw()->cart->generateHTML( $products );
			$cart = ob_get_contents();
		ob_end_clean();

		echo wp_json_encode([
			'html'     => $cart,
			'products' => $products
		], JSON_UNESCAPED_SLASHES );

		die;
	}
}