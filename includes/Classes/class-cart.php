<?php
/**
 * Contains releated class of cart functionalities.
 *
 * @since   1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

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
					echo esc_html__( 'There are no product added in the Quotations Cart', 'pqfw' );
				echo '</td>';
			echo '</tr>';
		}

		// var_dump( $products );

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
						$thumbnail = $this->getThumbnail( $product['id'], $product['variation'] );
						printf( '<a href="%s">%s</a>', esc_url( $permalink ), $thumbnail );
					?>
				</td>
				<td class="product-name" data-title="<?php esc_html_e( 'Product', 'woocommerce' ); ?>">
					<?php
						printf( '<a href="%s">%s</a>', esc_url( $permalink ), esc_attr( $productOBJ->get_name() ) );
						$this->getVariations( $productOBJ, $product['variation_detail'], true );
					?>
				</td>
				<td class="product-price" data-title="<?php esc_html_e( 'Price', 'woocommerce' ); ?>">
					<?php echo wp_kses_post( wc_price( $this->getSimpleVariationPrice( $productOBJ, $product['variation'] ) ) ); ?>
				</td>
				<td class="product-quantity" data-title="<?php esc_html_e( 'Quantity', 'woocommerce' ); ?>">
					<div class="quantity">
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
	public function getThumbnail( $product_id, $variation_id ) {
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
	 * @param  boolean $echo              false.
	 * @return string|html                Generated image.
	 */
	public function getVariations( $product, $variations_detail, $echo = false ) {
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
			$this->getVariationHtml( $variations_label );
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
	public function getVariationHtml( $variations_label ) {
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
	public function getSimpleVariationPrice( $product, $variation_id ) {
		if ( $product->is_type( 'simple' ) ) {
			return $this->getPrice( $product );
		} elseif ( $product->is_type( 'variable' ) ) {
			$variation_product = new \WC_Product_Variation( $variation_id );
			return $this->getPrice( $variation_product );
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
	public function getPrice( $product ) {
		if ( $product->is_on_sale() ) {
			return $product->get_sale_price();
		}

		return $product->get_regular_price();
	}
}