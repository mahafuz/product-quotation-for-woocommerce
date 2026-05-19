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
	 * The cart instance.
	 *
	 * @var \Quotify\Internals\Cart
	 */
	private $cart;

	/**
	 * Class constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->cart = new \Quotify\Internals\Cart();

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

		$products = $this->cart->get_products();
		$cart     = '';

		ob_start();
			$this->cart->render( $products );
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

		if ( empty( $_POST['data'] ) ) {
			wp_send_json_error( __( 'No product data provided.', 'quotify' ) );
		}

		$product = json_decode( wp_unslash( $_POST['data'] ), true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			wp_send_json_error( __( 'Invalid product data format.', 'quotify' ) );
		}

		$product_id = isset( $product['productID'] ) ? absint( $product['productID'] ) : 0;

		if ( ! $product_id ) {
			wp_send_json_error( __( 'Invalid product ID.', 'quotify' ) );
		}

		$wc_product = \wc_get_product( $product_id );

		if ( ! $wc_product ) {
			wp_send_json_error( __( 'Product not found.', 'quotify' ) );
		}

		$is_variable_product = $wc_product->is_type( 'variable' );

		$quantity = isset( $product['quantity'] ) ? absint( $product['quantity'] ) : 1;

		if ( $quantity < 1 ) {
			wp_send_json_error( __( 'Quantity must be at least 1.', 'quotify' ) );
		}

		$variation = isset( $product['variationID'] ) ? absint( $product['variationID'] ) : 0;

		if ( $is_variable_product ) {
			$validated = $this->validate_variation( $wc_product, $variation, $product['variationDetails'] );
			$status = $this->cart->add_product( $product_id, $quantity, $validated['variation_id'], $validated['variation_detail'], $validated['price'] );

			if ( false === $status ) {
				wp_send_json_error( __( 'Failed to add product to quotation. Please try again.', 'quotify' ) );
			}
		} else {
			$this->validate_stock( $wc_product, $quantity );

			$variationDetail = $this->cart->sanitize_variation_detail( $product['variationDetails'] );
			$price           = $this->cart->get_simple_variations_price( $wc_product, 0 );

			if ( ! $price ) {
				wp_send_json_error( __( 'Unable to determine product price. Please try again.', 'quotify' ) );
			}

			$status = $this->cart->add_product( $product_id, $quantity, $variation, $variationDetail, $price );

			if ( false === $status ) {
				wp_send_json_error( __( 'Failed to add product to quotation. Please try again.', 'quotify' ) );
			}
		}

		$success_message = quotify()->settings()->get( 'add_to_cart_success_message' );
		wp_send_json_success(
			[
				'message' => $success_message ? $success_message : __( 'Product successfully added to quotation.', 'quotify' ),
			]
		);
	}

	/**
	 * Validate variation for variable products.
	 *
	 * @since 2.6.0
	 * @param \WC_Product_Variable $wc_product     The variable product object.
	 * @param int                  $variation      Variation ID.
	 * @param array                $variationDetail Variation details from form.
	 * @return array Validated variation data with price.
	 */
	private function validate_variation( $wc_product, $variation, $variationDetail ) {
		if ( ! $variation ) {
			wp_send_json_error( __( 'Please select a product variation.', 'quotify' ) );
		}

		$variation_product = wc_get_product( $variation );

		if ( ! $variation_product ) {
			wp_send_json_error( __( 'Selected variation is not available.', 'quotify' ) );
		}

		$this->validate_stock( $variation_product, 1 );

		$variationDetail = $this->cart->sanitize_variation_detail( $variationDetail );

		if ( empty( $variationDetail ) ) {
			wp_send_json_error( __( 'Please complete all variation options.', 'quotify' ) );
		}

		$available_variations = $wc_product->get_available_variations();

		if ( empty( $available_variations ) ) {
			wp_send_json_error( __( 'No variations available for this product.', 'quotify' ) );
		}

		$variation_found            = false;
		$variation_attributes_valid = true;
		$missing_attributes         = [];

		foreach ( $available_variations as $available_variation ) {
			if ( absint( $available_variation['variation_id'] ) === $variation ) {
				$variation_found = true;

				foreach ( $available_variation['attributes'] as $attr_name => $attr_value ) {
					$taxonomy   = str_replace( 'attribute_', '', $attr_name );
					$attr_label = wc_attribute_label( $taxonomy );

					if ( ! isset( $variationDetail[ $attr_name ] ) ) {
						$missing_attributes[]       = $attr_label;
						$variation_attributes_valid = false;
					} elseif ( $variationDetail[ $attr_name ] !== $attr_value ) {
						$missing_attributes[] = sprintf(
							/* translators: %1$s: attribute name, %2$s: selected value, %3$s: expected value */
							__( '%1$s: You selected "%2$s" but this variation requires "%3$s".', 'quotify' ),
							$attr_label,
							$variationDetail[ $attr_name ],
							$attr_value
						);
						$variation_attributes_valid = false;
					}
				}

				break;
			}
		}

		if ( ! $variation_found ) {
			wp_send_json_error( __( 'Selected variation is not available for this product.', 'quotify' ) );
		}

		if ( ! $variation_attributes_valid ) {
			/* translators: %s: list of missing attributes */
			wp_send_json_error( sprintf( __( 'Invalid variation selection. Missing or invalid options: %s', 'quotify' ), implode( ', ', $missing_attributes ) ) );
		}

		$price = $variation_product->get_price();

		if ( ! $price ) {
			wp_send_json_error( __( 'Unable to determine variation price. Please try again.', 'quotify' ) );
		}

		return [
			'variation_id'     => $variation,
			'variation_detail' => $variationDetail,
			'price'            => $price,
		];
	}

	/**
	 * Validate stock for product or variation.
	 *
	 * @since 2.6.0
	 * @param \WC_Product $product Product or variation object.
	 * @param int         $quantity Quantity to check.
	 * @return void
	 */
	private function validate_stock( $product, $quantity ) {
		if ( ! $product->is_in_stock() ) {
			wp_send_json_error( __( 'This product is out of stock.', 'quotify' ) );
		}

		if ( ! $product->has_enough_stock( $quantity ) ) {
			wp_send_json_error(
				sprintf(
					/* translators: %s: stock amount. */
					__( 'Insufficient stock. Only %s available.', 'quotify' ),
					$product->get_stock_quantity()
				)
			);
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

		$cart     = '';
		$hash     = sanitize_text_field( $_POST['hash'] );
		$removed  = $this->cart->remove_product( $hash );
		$products = $this->cart->get_products();

		ob_start();
			$this->cart->render( $products );
			$cart = ob_get_contents();
		ob_end_clean();

		wp_send_json_success([
			'html'         => $cart,
			'products'     => $products,
			'removed_item' => $removed,
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

		if ( ! isset( $_POST['products'] ) ) {
			wp_send_json_error( __( 'No products data provided.', 'quotify' ) );
		}

		if ( is_string( $_POST['products'] ) ) {
			$products = json_decode( wp_unslash( $_POST['products'] ), true );
			if ( json_last_error() !== JSON_ERROR_NONE ) {
				wp_send_json_error( __( 'Invalid products data format.', 'quotify' ) );
			}
		} elseif ( is_array( $_POST['products'] ) ) {
			$products = $_POST['products'];
		} else {
			wp_send_json_error( __( 'Invalid products data type.', 'quotify' ) );
		}

		if ( ! is_array( $products ) || empty( $products ) ) {
			wp_send_json_error( __( 'Products data must be a non-empty array.', 'quotify' ) );
		}

		foreach ( $products as $hash => $product ) {
			if ( ! isset( $product['id'] ) || ! isset( $product['quantity'] ) ) {
				unset( $products[ $hash ] );
				continue;
			}

			$products[ $hash ]['id']               = absint( $product['id'] );
			$products[ $hash ]['quantity']         = intval( $product['quantity'] );
			$products[ $hash ]['variation']        = isset( $product['variation'] ) ? absint( $product['variation'] ) : 0;
			$products[ $hash ]['variation_detail'] = isset( $product['variation_detail'] ) && is_array( $product['variation_detail'] )
				? $this->cart->sanitize_variation_detail( $product['variation_detail'] )
				: [];
			$products[ $hash ]['message']          = isset( $product['message'] ) ? sanitize_text_field( $product['message'] ) : '';

			if ( $products[ $hash ]['quantity'] < 1 ) {
				wp_send_json_error( sprintf( __( 'Invalid quantity for product. Minimum quantity is 1.', 'quotify' ) ) );
			}

			$product_obj = wc_get_product( $products[ $hash ]['id'] );

			if ( $product_obj ) {
				$single_price = $this->cart->get_simple_variations_price( $product_obj, $products[ $hash ]['variation'] );
				$products[ $hash ]['price'] = floatval( $single_price ) * $products[ $hash ]['quantity'];
			} else {
				unset( $products[ $hash ] );
			}
		}

		$products = $this->cart->add_products( $products );

		ob_start();
			$this->cart->render( $products );
			$cart = ob_get_contents();
		ob_end_clean();

		wp_send_json_success([
			'html'     => $cart,
			'products' => $products,
		]);
	}
}
