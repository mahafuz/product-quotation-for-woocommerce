<?php
/**
 * PQFW class
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.2.0
 */

namespace PQFW\Ajax;

/**
 * PQFW class
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.2.0
 */
class Product {
	/**
	 * Initialize ajax actions.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_quotify/product/add', [ $this, 'add' ] );
		add_action( 'wp_ajax_nopriv_quotify/product/add', [ $this, 'add' ] );
	}

		/**
		 * Add product to cart.
		 *
		 * @since 1.0.0
		 */
	public function add() {
		check_ajax_referer( 'pqfw_nonce', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		if ( isset( $_POST['productId'] ) && isset( $_POST['variationID'] ) ) {
			$id        = absint( $_POST['productId'] );
			$quantity  = (int) ( isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1 );
			$variation = absint( $_POST['variationID'] );
			$product   = wc_get_product( $id );

			$variationDetail = pqfw()->quotations->sanitizeVariationDetail( $_POST['variationDetails'] );
			$price           = pqfw()->cart->getSimpleVariationPrice( $product, $variation );

			$status = pqfw()->quotations->addProduct( $id, $quantity, $variation, $variationDetail, $price );

			wp_send_json_success([
				/* Translators: %d product id */
				'message' => sprintf( __( '%d Product Successfully added.', 'pqfw' ), $id ),
			]);
		} else {
			wp_send_json_success([
				'message' => __( 'Invalid product data to add to quote.', 'pqfw' ),
			]);
		}
	}
}
