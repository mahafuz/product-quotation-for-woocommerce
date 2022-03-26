<?php
/**
 * Contains releated class of cart functionalities.
 *
 * @since   1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

// TODO: Rewrite this class methods.

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
	 * Generates cart table html.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $products The products collection to generate the rows.
	 * @return void|html            The generated html rows.
	 */
	public function generateHTML( $products ) {
		if ( ! is_array( $products ) || count( $products ) < 1 ) {
			echo '<tr>';
				echo '<td colspan="6" align="center">';
					echo esc_html__( 'There are no product added in the enquiry cart', 'pqfw' );
				echo '</td>';
			echo '</tr>';
		}

		foreach ( $products as $key => $product ) {
			$product_obj = wc_get_product( $product['id'] );
			$product_permalink = $product_obj->get_permalink();
			?>
			<tr class="woocommerce-cart-form__cart-item" id="<?php echo esc_attr( $key ); ?>">
				<td class="product-remove">
					<a href="javascript:void(0)" class="remove pqfw-remove-product"  data-id="<?php echo $key; ?>">&times;</a>
					<input type="hidden" name="products[<?php echo $key; ?>][id]" value="<?php echo $product['id']; ?>"/>
				</td>
				<td class="product-thumbnail pqfw-thumbnail">
					<?php
						$thumbnail = $this->getImage( $product['id'], $product['variation'] );
						printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );	
					?>
				</td>
				<td class="product-name" data-title="<?php esc_html_e( 'Product', 'woocommerce' ); ?>">
					<?php
						printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), esc_attr( $product_obj->get_name() ) );
						$this->get_variations( $product_obj, $product['variation_detail'], true );
					?>
				</td>
				<td class="product-price" data-title="<?php esc_html_e( 'Price', 'woocommerce' ); ?>">
					<?php echo wc_price( $this->get_price_simple_variation( $product_obj, $product['variation'] ) ); ?>
				</td>
				<td class="product-quantity" data-title="<?php esc_html_e( 'Quantity', 'woocommerce' ); ?>">
					<input
						type="number"
						class="input-text qty text pqfw-quantity"
						value="<?php echo esc_attr( $product['quantity'] ); ?>"
						name="products[<?php echo esc_attr( $key ); ?>][quantity]"
						data-hash="<?php echo esc_attr( $key ); ?>"
					/>
					<input
						type="hidden"
						value="<?php echo ! empty( $product['variation'] ) && is_array( $product['variation'] ) ? wp_json_encode( $product['variation'] ) : ''; ?>"
						data-hash="<?php echo esc_attr( $key ); ?>"
						name="products[<?php echo esc_attr( $key ); ?>][variation]"
					/>
				</td>
				<td class="product-message" data-title="<?php esc_html_e( 'Message', 'woocommerce' ); ?>">
					<textarea
						name="message"
						class="pqfw-message"
						name="products[<?php echo esc_attr( $key ); ?>][message]"
						data-hash="<?php echo esc_attr( $key ); ?>"
					><?php echo esc_html( $product['message'] ); ?></textarea>
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
	public function getImage( $product_id, $variation_id ){
		if ( empty( $variation_id ) ) {
			$product = wc_get_product( $product_id );
		} else {
			$product = wc_get_product( $variation_id );
		}

			$image_id = $product->get_image_id();

			$placeholder = wc_placeholder_img_src( 'thumbnail' );

		if ( ! empty( $image_id ) ) {
			$src       = wp_get_attachment_image_src( $image_id, 'thumbnail' );
			$image_src = isset( $src[0] ) ? $src[0] : $placeholder;
		} else {
			$image_src = $placeholder;
		}

		return sprintf( '<img src="%s" class="pi-eqw-product-thumb">', esc_url( $image_src ) );
	}

	/**
	 * Retrieves product variations.
	 *
	 * @since 1.2.0
	 *
	 * @param  object  $product           The product object.
	 * @param  object  $variations_detail The product variation object.
	 * @param  boolean $echo              false.
	 * @return string|html                Generated image.
	 */
	public function get_variations( $product, $variations_detail, $echo = false ) {
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
		if ( $echo ) {
			$this->variation_html( $variations_label );
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
	public function variation_html( $variations_label ) {
		if ( is_array( $variations_label ) ) {
			echo '<br>';
			foreach ( $variations_label as $key => $value ) {
				echo '<strong class="pi-attribute-label">' . esc_attr( $key ) . '</strong> : <span>' . esc_attr( $value ) . '</span><br>';
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
	public function get_price_simple_variation( $product, $variation_id ) {
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