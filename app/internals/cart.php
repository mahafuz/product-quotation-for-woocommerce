<?php
/**
 * Contains related class of cart functionalities.
 *
 * @since   1.0.0
 * @package PQFW
 */

namespace Quotify\Internals;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for handling the plugin cart.
 *
 * @since   1.0.0
 * @package PQFW
 */
class Cart {
	/**
	 * Class instance.
	 *
	 * @var Quotify\Internals\Cart
	 */
	private static $instance = null;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var Quotify\Internals\Cart
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class Constructor
	 *
	 * @param int $id The product id.
	 * @since 2.5.0
	 */
	private function product( $id ) {
		return wc_get_product( $id );
	}

	/**
	 * Returns boolean if product present in cart
	 *
	 * @since 1.2.0
	 *
	 * @param  string $hash The product has.
	 * @return boolean      Product existence.
	 */
	public function has( $hash ) {
		return quotify()->sessions()->has( $hash );
	}

	/**
	 * Returns boolean if product present in cart
	 *
	 * @since 1.2.0
	 *
	 * @param  array $detail The variation detail.
	 * @return mixed         Sanitized details or false.
	 */
	public function sanitize_variation_detail( $detail ) {
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
	private function is_variable( $id ) {
		$product = $this->product( $id );

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
	private function generate_hash( $id, $variationDetails = '' ) {
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
	public function remove_product( $hash ) {
		$products = $this->get_products();

		if ( is_array( $products ) && count( $products ) > 0 ) {
			unset( $products[ $hash ] );
		}

		return $this->add_products( $products );
	}

	/**
	 * If $new_quantity is false will increment the existing quantity
	 * if it is not false and is a number then will it will update existing quantity
	 * if new quantity is zero it will remove the product from list
	 *
	 * @param string $hash     Product identifier hash.
	 * @param mixed  $quantity New product quantity.
	 */
	private function update_quantity( $hash, $quantity = false ) {
		$products = $this->get_products();

		if ( 0 === $quantity ) {
			$this->remove_product( $hash );
			return;
		}

		if ( is_array( $products ) && count( $products ) > 0 ) {
			if ( $quantity ) {
				$products[ $hash ]['quantity'] = $products[ $hash ]['quantity'] + $quantity;
			} else {
				$products[ $hash ]['quantity'] = $products[ $hash ]['quantity'] + 1;
			}
		}

		$this->add_products( $products );
	}

	/**
	 * Adds products to the cart.
	 *
	 * @since 1.2.0
	 *
	 * @param  array $products The products for add to the cart.
	 * @return bool
	 */
	public function add_products( $products ) {
		$products = $this->sanitize_products( $products );
		$products = quotify()->sessions()->set( $products );

		return $products;
	}

	/**
	 * Sanitizes products and prepare for add to cart.
	 *
	 * @since 1.2.0
	 *
	 * @param  array $products The products for add to the cart.
	 * @return array           Sanitized products.
	 */
	private function sanitize_products( $products ) {
		if ( is_array( $products ) ) {
			foreach ( $products as $key => $product ) {
				$products[ $key ]['id'] = (int) $products[ $key ]['id'];
				$products[ $key ]['variation'] = (int) $products[ $key ]['variation'];
				$products[ $key ]['variation_detail'] = $this->sanitize_variation_detail( $products[ $key ]['variation_detail'] );

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
	 * @param  integer $price            Product price.
	 * @return bool
	 */
	public function add_product( $id, $quantity, $variation, $variation_detail, $price = 0 ) {
		$products = $this->get_products();
		$message  = '';

		if ( $this->is_variable( $id ) && false === $variation ) {
			return false;
		}

		$new_product = [
			'id'               => (int) $id,
			'quantity'         => (int) $quantity,
			'variation'        => (int) $variation,
			'price'            => $price,
			'variation_detail' => $variation_detail,
			'message'          => wp_strip_all_tags( $message ),
		];

		$hash = $this->generate_hash( $new_product['id'], $variation_detail );

		if ( $this->has( $hash ) ) {
			/**
			 * This will increment it by one,
			 * as we are not entering the new quantity variable
			 */
			$this->update_quantity( $hash, $new_product['quantity'] );
			$this->update_price( $hash, $new_product['price'] );
			return;
		} else {
			$products[ $hash ] = $new_product;
		}

		return $this->add_products( $products );
	}

	/**
	 * Update price for the product.
	 *
	 * @since 2.0.1
	 * @param string $hash Product identifier hash.
	 * @param float  $price Product price.
	 */
	public function update_price( $hash, $price ) {
		$products = $this->get_products();

		if ( is_array( $products ) && count( $products ) > 0 ) {
			if ( $price ) {
				$products[ $hash ]['price'] = $products[ $hash ]['price'] + $price;
			} else {
				$products[ $hash ]['price'] = $products[ $hash ]['price'] + 1;
			}
		}

		$this->add_products( $products );
	}

	/**
	 * Retrieves products list from the cart.
	 *
	 * @param bool $only_ids The product ids.
	 *
	 * @since  1.2.0
	 * @return array The product collection.
	 */
	public function get_products( $only_ids = false ) {
		$products = quotify()->sessions()->get();

		if ( $only_ids && ! empty( $products ) && is_array( $products ) ) {
			$product_ids = [];

			foreach ( $products as $product ) {
				$products_id[] = $product['id'];
			}

			return $product_ids;
		}

		return $products;
	}

	/**
	 * Purge the session cart.
	 *
	 * @since 1.2.0
	 */
	public function purge() {
		return quotify()->sessions()->reset();
	}

	/**
	 * Builds the variation tree based on the details.
	 *
	 * @since  1.2.0
	 * @param  string $details The variation details.
	 * @return void
	 */
	public function build_variations( $details ) {
		$attributesGroup = explode( ',', $details );
		if ( is_array( $attributesGroup ) && count( $attributesGroup ) > 0 ) {
			foreach ( $attributesGroup as $attribute ) {
				if ( '' !== $attribute ) {
					$pair = explode( '|', $attribute );
					echo isset( $pair[0] ) ? '<strong>' . esc_html( $pair[0] ) . '</strong> : ' : '';
					echo isset( $pair[1] ) ? '<span>' . esc_html( $pair[0] ) . '</span><br>' : '';
				}
			}
		}
	}

	/**
	 * Generates cart table html.
	 *
	 * @since 1.0.0
	 *
	 * @return void|html            The generated html rows.
	 */
	public function render() {
		$products = quotify()->cart()->get_products();

		if ( ! is_array( $products ) || count( $products ) < 1 ) {
			echo '<tr>';
				echo '<td colspan="6" align="center">';
					echo esc_html__( 'There are no product added in the Quotations Cart', 'quotify' );
				echo '</td>';
			echo '</tr>';
		}

		foreach ( $products as $key => $product ) {
			$productOBJ = wc_get_product( $product['id'] );
			$permalink  = $productOBJ->get_permalink();
			?>
			<tr class="woocommerce-cart-form__cart-item" id="<?php echo esc_attr( $key ); ?>">
				<td class="product-remove">
					<a href="javascript:void(0)" class="remove pqfw-remove-product"  data-id="<?php echo esc_attr( $key ); ?>">&times;</a>
					<input type="hidden" name="products[<?php echo esc_attr( $key ); ?>][id]" value="<?php echo absint( $product['id'] ); ?>"/>
				</td>
				<td class="product-thumbnail pqfw-thumbnail">
					<?php
						$thumbnail = $this->get_thumbnail( $product['id'], $product['variation'] );
						printf( '<a href="%s">%s</a>', esc_url( $permalink ), wp_kses_post( $thumbnail ) );
					?>
				</td>
				<td class="product-name" data-title="<?php esc_html_e( 'Product', 'woocommerce' ); ?>">
					<?php
						printf( '<a href="%s">%s</a>', esc_url( $permalink ), esc_attr( $productOBJ->get_name() ) );
						$this->get_variations( $productOBJ, $product['variation_detail'], true );
					?>
				</td>
				<td class="product-price" data-title="<?php esc_html_e( 'Price', 'woocommerce' ); ?>">
					<?php echo wp_kses_post( wc_price( $product['price'] ) ); ?>
				</td>
				<td class="product-quantity" data-title="<?php esc_html_e( 'Quantity', 'woocommerce' ); ?>">
					<div class="quantity">
						<input
							type="number"
							class="input-text qty text pqfw-quantity"
							value="<?php echo esc_attr( $product['quantity'] ); ?>"
							name="products[<?php echo esc_attr( $key ); ?>][quantity]"
							data-single="<?php echo esc_attr( $this->get_simple_variations_price( $productOBJ, $product['variation'] ) ); ?>"
							data-hash="<?php echo esc_attr( $key ); ?>"
						/>
						<input
							type="hidden"
							value="<?php echo ! empty( $product['variation'] ) && is_array( $product['variation'] ) ? wp_json_encode( $product['variation'] ) : ''; ?>"
							data-hash="<?php echo esc_attr( $key ); ?>"
							name="products[<?php echo esc_attr( $key ); ?>][variation]"
						/>
					</div>
				</td>
				<td class="product-message" data-title="<?php esc_html_e( 'Message', 'woocommerce' ); ?>">
					<div class="pqfw-message">
						<textarea
							name="message"
							class="input-text"
							name="products[<?php echo esc_attr( $key ); ?>][message]"
							data-hash="<?php echo esc_attr( $key ); ?>"
						><?php echo esc_html( $product['message'] ); ?></textarea>
					</div>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Retrieves product image.
	 *
	 * @since 1.2.0
	 *
	 * @param  integer $product_id   The product ID.
	 * @param  integer $variation_id The product variation ID.
	 * @return string|html           Generated image.
	 */
	public function get_thumbnail( $product_id, $variation_id ) {
		if ( empty( $variation_id ) ) {
			$product = wc_get_product( $product_id );
		} else {
			$product = wc_get_product( $variation_id );
		}

			$image_id    = $product->get_image_id();
			$placeholder = wc_placeholder_img_src( 'thumbnail' );

		if ( ! empty( $image_id ) ) {
			$src       = wp_get_attachment_image_src( $image_id, 'thumbnail' );
			$image_src = isset( $src[0] ) ? $src[0] : $placeholder;
		} else {
			$image_src = $placeholder;
		}

		return sprintf( '<img src="%s" class="pqfw-product-thumbnail">', esc_url( $image_src ) );
	}

	/**
	 * Retrieves product variations.
	 *
	 * @since 1.2.0
	 *
	 * @param  object  $product           The product object.
	 * @param  object  $variations_detail The product variation object.
	 * @param  boolean $show              false.
	 * @return string|html                Generated image.
	 */
	public function get_variations( $product, $variations_detail, $show = false ) {
		if ( null === $variations_detail || '' === $variations_detail || false === $variations_detail ) {
			return;
		}

		$variations_label = [];

		if ( $product->is_type( 'variable' ) ) {
			foreach ( $variations_detail as $attribute => $term_slug ) {
				$taxonomy        = str_replace( 'attribute_', '', $attribute );
				$attr_label_name = wc_attribute_label( $taxonomy );
				$term_obj        = get_term_by( 'slug', $term_slug, $taxonomy );
				$term_name       = is_object( $term_obj ) ? $term_obj->name : $term_slug;

				$variations_label[ $attr_label_name ] = $term_name;
			}
		}
		if ( $show ) {
			$this->render_variation( $variations_label );
		} else {
			return $variations_label;
		}
	}

	/**
	 * Generates variation html.
	 *
	 * @since 1.2.0
	 *
	 * @param  array $variations_label Variations labels.
	 * @return void                    Generated image.
	 */
	public function render_variation( $variations_label ) {
		if ( is_array( $variations_label ) ) {
			echo '<br>';
			foreach ( $variations_label as $key => $value ) {
				echo '<strong class="pqfw-attribute-label">' . esc_attr( $key ) . '</strong> : <span>' . esc_attr( $value ) . '</span><br>';
			}
		}
	}

	/**
	 * Retrieves simple variation price
	 *
	 * @since 1.2.0
	 *
	 * @param  object  $product      Product object.
	 * @param  integer $variation_id Variation ID.
	 * @return integer               Variation price.
	 */
	public function get_simple_variations_price( $product, $variation_id ) {
		if ( $product->is_type( 'simple' ) ) {
			return $this->get_price( $product );
		} elseif ( $product->is_type( 'variable' ) ) {
			$variation_product = new \WC_Product_Variation( $variation_id );
			return $this->get_price( $variation_product );
		}
	}

	/**
	 * Retrieves simple variation price
	 *
	 * @since 1.2.0
	 *
	 * @param  object $product Product object.
	 * @return integer         Sale price.
	 */
	public function get_price( $product ) {
		if ( $product->is_on_sale() ) {
			return $product->get_sale_price();
		}

		return $product->get_regular_price();
	}
}
