<?php
/**
 * Contains related class of cart functionalities.
 *
 * @since   1.0.0
 * @package Quotify
 */

namespace Quotify\Internals;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for handling the plugin cart.
 *
 * @since   1.0.0
 * @package Quotify
 */
class Cart {
	/**
	 * Class instance.
	 *
	 * @var \Quotify\Internals\Cart|null
	 */
	private static $instance = null;

	/**
	 * The cart session.
	 *
	 * @var \Quotify\Library\Session
	 */
	private $session;

	/**
	 * Cart products array.
	 *
	 * @var array
	 */
	private $products = [];

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return \Quotify\Internals\Cart
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Checks if the current page is quotify cart page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool True if on cart page, false otherwise.
	 */
	public function is_cart_page() {
		$cart_page_id = get_option( 'pqfw_quotations_cart', 0 );

		if ( empty( $cart_page_id ) ) {
			return false;
		}

		$current_page_id = get_the_ID();

		if ( empty( $current_page_id ) && wp_doing_ajax() ) {
			$current_page_id = $this->get_page_id_from_ajax();
		}

		if ( empty( $current_page_id ) ) {
			return false;
		}

		return absint( $current_page_id ) === absint( $cart_page_id );
	}

	/**
	 * Get page ID from AJAX request using multiple fallback methods.
	 *
	 * @since 2.6.0
	 *
	 * @return int|false Page ID or false if not found.
	 */
	private function get_page_id_from_ajax() {
		$page_id = false;
		$referer = isset( $_SERVER['HTTP_REFERER'] ) ? wp_unslash( $_SERVER['HTTP_REFERER'] ) : '';

		if ( empty( $referer ) || $this->is_external_referer( $referer ) ) {
			return false;
		}

		$page_id = url_to_postid( $referer );

		if ( ! $page_id ) {
			$page_id = $this->extract_page_id_from_url( $referer );
		}

		if ( ! $page_id ) {
			$page_id = $this->get_page_id_by_path( $referer );
		}

		return $page_id ? absint( $page_id ) : false;
	}

	/**
	 * Check if referer is from external site.
	 *
	 * @since 2.6.0
	 *
	 * @param string $referer The referer URL.
	 * @return bool True if external, false if internal.
	 */
	private function is_external_referer( $referer ) {
		$home_url = home_url();
		$referer_host = wp_parse_url( $referer, PHP_URL_HOST );
		$home_host = wp_parse_url( $home_url, PHP_URL_HOST );

		// Compare hosts (case-insensitive).
		return strtolower( $referer_host ) !== strtolower( $home_host );
	}

	/**
	 * Extract page ID from URL parameters.
	 *
	 * Handles:
	 * - ?page_id=123
	 * - ?p=123
	 * - Custom query vars
	 *
	 * @since 2.6.0
	 *
	 * @param string $url The URL to parse.
	 * @return int|false Page ID or false if not found.
	 */
	private function extract_page_id_from_url( $url ) {
		$parsed_url = wp_parse_url( $url );

		if ( ! isset( $parsed_url['query'] ) ) {
			return false;
		}

		parse_str( $parsed_url['query'], $query_params );

		// Check for standard WordPress page ID parameters.
		if ( isset( $query_params['page_id'] ) ) {
			return absint( $query_params['page_id'] );
		}

		if ( isset( $query_params['p'] ) ) {
			return absint( $query_params['p'] );
		}

		if ( isset( $query_params['post_id'] ) ) {
			return absint( $query_params['post_id'] );
		}

		// Check for custom post type query vars.
		if ( isset( $query_params['product'] ) ) {
			$product_slug = sanitize_title_for_query( $query_params['product'] );
			$product_page = get_page_by_path( $product_slug, OBJECT, 'product' );

			if ( $product_page ) {
				return $product_page->ID;
			}
		}

		return false;
	}

	/**
	 * Get page ID by comparing against known cart page path.
	 *
	 * @since 2.6.0
	 *
	 * @param string $url The URL to check.
	 * @return int|false Page ID or false if not found.
	 */
	private function get_page_id_by_path( $url ) {
		$cart_page_id = get_option( 'pqfw_quotations_cart', 0 );

		if ( empty( $cart_page_id ) ) {
			return false;
		}

		$cart_page_url = get_permalink( $cart_page_id );

		if ( ! $cart_page_url ) {
			return false;
		}

		// Extract path from both URLs for comparison.
		$referer_path = wp_parse_url( $url, PHP_URL_PATH );
		$cart_path = wp_parse_url( $cart_page_url, PHP_URL_PATH );

		// Direct path match.
		if ( $referer_path === $cart_path ) {
			return $cart_page_id;
		}

		// Try with trailing slash normalization.
		if ( untrailingslashit( $referer_path ) === untrailingslashit( $cart_path ) ) {
			return $cart_page_id;
		}

		return false;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->session = new \Quotify\Library\Session();

		if ( isset( $this->session ) ) {
			$this->products = $this->session->get();
		}
	}

	/**
	 * Retrieves a product object.
	 *
	 * @param int $id The product id.
	 * @since 2.5.0
	 * @return \WC_Product|null Product object or null if not found.
	 */
	private function product( $id ) {
		$product = wc_get_product( $id );

		return $product ? $product : null;
	}

	/**
	 * Checks if product exists in cart.
	 *
	 * @since 1.2.0
	 *
	 * @param string $hash The product hash.
	 * @return bool Product existence.
	 */
	public function has( $hash ) {
		return isset( $this->products[ $hash ] );
	}

	/**
	 * Sanitizes variation detail array.
	 *
	 * @since 1.2.0
	 *
	 * @param array $detail The variation detail.
	 * @return array|false Sanitized details or false.
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
	 * @param int $id The product ID.
	 * @return bool True if variable product.
	 */
	private function is_variable( $id ) {
		$product = $this->product( $id );

		return $product && $product->is_type( 'variable' );
	}

	/**
	 * Generates the hash for a product.
	 *
	 * @since 1.2.0
	 *
	 * @param int   $id               The product ID.
	 * @param array $variationDetails The variation detail.
	 * @return string Generated hash.
	 */
	private function generate_hash( $id, $variationDetails = '' ) {
		$value = '';

		if ( is_array( $variationDetails ) && count( $variationDetails ) > 0 ) {
			foreach ( $variationDetails as $key => $variation_detail ) {
				$value .= $variation_detail;
			}
		}

		return md5( $id . $value );
	}

	/**
	 * Saves current cart state to session.
	 *
	 * @since 2.6.0
	 *
	 * @return bool True if saved successfully.
	 */
	private function save_to_session() {
		if ( isset( $this->session ) ) {
			return $this->session->set( $this->products );
		}

		return false;
	}

	/**
	 * Removes product from cart.
	 *
	 * @since 1.2.0
	 *
	 * @param string $hash The product identifier hash.
	 * @return array|false Removed product or false if not found.
	 */
	public function remove_product( $hash ) {
		if ( ! $this->has( $hash ) ) {
			return false;
		}

		$removed_item = $this->products[ $hash ];
		unset( $this->products[ $hash ] );

		$this->save_to_session();

		return $removed_item;
	}

	/**
	 * Updates product quantity in cart.
	 *
	 * If $new_quantity is false will increment the existing quantity by 1.
	 * If it is a number then will add to existing quantity.
	 * If new quantity after update is zero it will remove the product from list.
	 *
	 * @since 1.2.0
	 *
	 * @param string   $hash     Product identifier hash.
	 * @param int|bool $quantity New product quantity to add (false to increment by 1).
	 * @return bool True if updated successfully, false otherwise.
	 */
	private function update_quantity( $hash, $quantity = false ) {
		if ( ! $this->has( $hash ) ) {
			return false;
		}

		$new_quantity = $this->products[ $hash ]['quantity'] + ( false === $quantity ? 1 : (int) $quantity );

		if ( 0 >= $new_quantity ) {
			$this->remove_product( $hash );
			return true;
		}

		$this->products[ $hash ]['quantity'] = $new_quantity;
		$this->save_to_session();

		return true;
	}

	/**
	 * Sets products array and sanitizes.
	 *
	 * @since 1.2.0
	 *
	 * @param array $products The products to add to the cart.
	 * @return array Sanitized products.
	 */
	public function add_products( $products ) {
		$this->products = $this->sanitize_products( $products );
		$this->save_to_session();

		return $this->products;
	}

	/**
	 * Sanitizes products and prepare for add to cart.
	 *
	 * @since 1.2.0
	 *
	 * @param array $products The products to add to the cart.
	 * @return array Sanitized products.
	 */
	private function sanitize_products( $products ) {
		if ( ! is_array( $products ) ) {
			return [];
		}

		foreach ( $products as $key => $product ) {
			$products[ $key ]['id']               = (int) $products[ $key ]['id'];
			$products[ $key ]['variation']        = isset( $products[ $key ]['variation'] ) ? (int) $products[ $key ]['variation'] : 0;
			$products[ $key ]['variation_detail'] = $this->sanitize_variation_detail( $products[ $key ]['variation_detail'] );
			$products[ $key ]['quantity']         = (int) $products[ $key ]['quantity'];
			$products[ $key ]['message']          = isset( $products[ $key ]['message'] ) ? sanitize_text_field( $products[ $key ]['message'] ) : '';
			$products[ $key ]['price']            = isset( $products[ $key ]['price'] ) ? floatval( $products[ $key ]['price'] ) : 0.0;

			if ( $products[ $key ]['quantity'] <= 0 ) {
				unset( $products[ $key ] );
			}
		}

		return $products;
	}

	/**
	 * Adds product to the cart.
	 *
	 * @since 1.2.0
	 *
	 * @param int      $id               The product id.
	 * @param int      $quantity         Product quantity.
	 * @param int|null $variation        Product variation ID (null for simple products).
	 * @param array    $variation_detail Product variation details.
	 * @param float    $price            Product price.
	 * @param string   $message          Optional customer message.
	 * @return array|false Updated cart or false on validation failure.
	 */
	public function add_product( $id, $quantity, $variation = null, $variation_detail = [], $price = 0.0, $message = '' ) {
		// Validate product exists.
		$product = $this->product( $id );

		if ( ! $product ) {
			return false;
		}

		// For variable products, variation must be provided.
		if ( $this->is_variable( $id ) && null === $variation ) {
			return false;
		}

		$new_product = [
			'id'               => (int) $id,
			'quantity'         => (int) $quantity,
			'variation'        => (int) $variation,
			'price'            => floatval( $price ),
			'variation_detail' => $variation_detail,
			'message'          => sanitize_text_field( $message ),
		];

		$hash = $this->generate_hash( $new_product['id'], $variation_detail );

		if ( $this->has( $hash ) ) {
			// Update existing product quantity and price.
			$this->update_quantity( $hash, $new_product['quantity'] );
			$this->update_price( $hash, $new_product['price'] );
			return $this->products;
		}

		// Add new product.
		$this->products[ $hash ] = $new_product;
		$this->save_to_session();

		return $this->products;
	}

	/**
	 * Update price for the product in cart.
	 *
	 * @since 2.0.1
	 *
	 * @param string $hash Product identifier hash.
	 * @param float  $price Product price to add.
	 * @return bool True if updated, false on failure.
	 */
	public function update_price( $hash, $price ) {
		if ( ! $this->has( $hash ) ) {
			return false;
		}

		$this->products[ $hash ]['price'] += floatval( $price );
		$this->save_to_session();

		return true;
	}

	/**
	 * Retrieves products list from the cart.
	 *
	 * @since  1.2.0
	 * @return array The product collection.
	 */
	public function get_products() {
		return (array) $this->products;
	}

	/**
	 * Purge the session cart.
	 *
	 * @since 1.2.0
	 *
	 * @return array Empty products array.
	 */
	public function purge() {
		$this->products = [];
		$this->save_to_session();

		return $this->products;
	}

	/**
	 * Builds the variation tree based on the details.
	 *
	 * @since  1.2.0
	 *
	 * @param string $details The variation details (comma-separated pairs).
	 * @return string Generated HTML.
	 */
	public function build_variations( $details ) {
		$html = '';

		if ( empty( $details ) ) {
			return $html;
		}

		$attributesGroup = explode( ',', $details );

		if ( is_array( $attributesGroup ) && count( $attributesGroup ) > 0 ) {
			foreach ( $attributesGroup as $attribute ) {
				if ( '' !== $attribute ) {
					$pair = explode( '|', $attribute );
					$html .= isset( $pair[0] ) ? '<strong>' . esc_html( $pair[0] ) . '</strong> : ' : '';
					$html .= isset( $pair[1] ) ? '<span>' . esc_html( $pair[1] ) . '</span><br>' : '';
				}
			}
		}

		return $html;
	}

	/**
	 * Generates cart table html.
	 *
	 * @param array $products The products.
	 *
	 * @since 1.0.0
	 */
	public function render( $products ) {
		if ( ! is_array( $products ) || count( $products ) < 1 ) {
			$empty_message = quotify()->settings()->get( 'empty_cart_message' );

			if ( empty( $empty_message ) ) {
				$empty_message = __( 'Your quotation cart is currently empty.', 'quotify' );
			}

			echo '<tr>';
			echo '<td colspan="6" align="center">';
			echo esc_html( $empty_message );
			echo '</td>';
			echo '</tr>';
		} else {
			foreach ( $products as $key => $product ) {
				$productOBJ = $this->product( $product['id'] );

				if ( ! $productOBJ ) {
					continue;
				}

				$permalink = $productOBJ->get_permalink();
				?>
				<tr class="woocommerce-cart-form__cart-item" id="<?php echo esc_attr( $key ); ?>">
					<td class="product-remove">
						<a href="javascript:void(0)" class="remove pqfw-remove-product"
							data-id="<?php echo esc_attr( $key ); ?>">&times;</a>
						<input type="hidden" name="products[<?php echo esc_attr( $key ); ?>][id]"
							value="<?php echo absint( $product['id'] ); ?>" />
					</td>
					<td class="product-thumbnail pqfw-thumbnail">
						<?php
							$thumbnail = $this->get_thumbnail( $product['id'], $product['variation'] );
							printf( '<a href="%s">%s</a>', esc_url( $permalink ), wp_kses_post( $thumbnail ) );
						?>
					</td>
					<td class="product-name" data-title="<?php esc_html_e( 'Product', 'woocommerce' ); ?>">
						<?php
							printf( '<a href="%s">%s</a>', esc_url( $permalink ), esc_html( $productOBJ->get_name() ) );
							$this->get_variations( $productOBJ, $product['variation_detail'], true );
						?>
					</td>
					<td class="product-price" data-title="<?php esc_html_e( 'Price', 'woocommerce' ); ?>">
						<?php echo wp_kses_post( wc_price( $product['price'] ) ); ?>
					</td>
					<td class="product-quantity" data-title="<?php esc_html_e( 'Quantity', 'woocommerce' ); ?>">
						<div class="quantity">
							<input type="number" class="input-text qty text pqfw-quantity"
								value="<?php echo esc_attr( $product['quantity'] ); ?>"
								name="products[<?php echo esc_attr( $key ); ?>][quantity]"
								data-single="<?php echo esc_attr( $this->get_simple_variations_price( $productOBJ, $product['variation'] ) ); ?>"
								data-hash="<?php echo esc_attr( $key ); ?>" />
							<input type="hidden"
								value="<?php echo ! empty( $product['variation'] ) && is_array( $product['variation'] ) ? esc_attr( wp_json_encode( $product['variation'] ) ) : ''; ?>"
								data-hash="<?php echo esc_attr( $key ); ?>"
								name="products[<?php echo esc_attr( $key ); ?>][variation]" />
						</div>
					</td>
					<td class="product-message" data-title="<?php esc_html_e( 'Message', 'woocommerce' ); ?>">
						<div class="pqfw-message">
							<textarea class="input-text" name="products[<?php echo esc_attr( $key ); ?>][message]"
								data-hash="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $product['message'] ); ?></textarea>
						</div>
					</td>
				</tr>
				<?php
			}
		}
	}

	/**
	 * Retrieves product image.
	 *
	 * @since 1.2.0
	 *
	 * @param int      $product_id   The product ID.
	 * @param int|null $variation_id The product variation ID.
	 * @return string Generated image HTML.
	 */
	public function get_thumbnail( $product_id, $variation_id = null ) {
		if ( empty( $variation_id ) ) {
			$product = $this->product( $product_id );
		} else {
			$product = wc_get_product( $variation_id );
		}

		if ( ! $product ) {
			$image_src = wc_placeholder_img_src( 'thumbnail' );
			return sprintf( '<img src="%s" class="pqfw-product-thumbnail" alt="">', esc_url( $image_src ) );
		}

		$image_id    = $product->get_image_id();
		$placeholder = wc_placeholder_img_src( 'thumbnail' );

		if ( ! empty( $image_id ) ) {
			$src       = wp_get_attachment_image_src( $image_id, 'thumbnail' );
			$image_src = isset( $src[0] ) ? $src[0] : $placeholder;
		} else {
			$image_src = $placeholder;
		}

		return sprintf( '<img src="%s" class="pqfw-product-thumbnail" alt="">', esc_url( $image_src ) );
	}

	/**
	 * Retrieves product variations.
	 *
	 * @since 1.2.0
	 *
	 * @param \WC_Product $product           The product object.
	 * @param array|null  $variations_detail The product variation details.
	 * @param bool        $show              Whether to render the variations.
	 * @return array|string|void Variations array or rendered HTML.
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
	 * @param array $variations_label Variations labels.
	 * @return void Renders HTML output.
	 */
	public function render_variation( $variations_label ) {
		if ( is_array( $variations_label ) ) {
			echo '<br>';
			foreach ( $variations_label as $key => $value ) {
				echo '<strong class="pqfw-attribute-label">' . esc_html( $key ) . '</strong> : <span>' . esc_html( $value ) . '</span><br>';
			}
		}
	}

	/**
	 * Retrieves simple variation price.
	 *
	 * @since 1.2.0
	 *
	 * @param \WC_Product $product      Product object.
	 * @param int|null    $variation_id Variation ID.
	 * @return float|false Variation price or false on failure.
	 */
	public function get_simple_variations_price( $product, $variation_id = null ) {
		if ( ! $product ) {
			return false;
		}

		if ( $product->is_type( 'simple' ) ) {
			return $this->get_price( $product );
		} elseif ( $product->is_type( 'variable' ) && ! empty( $variation_id ) ) {
			$variation_product = wc_get_product( $variation_id );

			if ( $variation_product ) {
				return $this->get_price( $variation_product );
			}
		}

		return false;
	}

	/**
	 * Retrieves product price (sale or regular).
	 *
	 * @since 1.2.0
	 *
	 * @param \WC_Product $product Product object.
	 * @return float Product price.
	 */
	public function get_price( $product ) {
		if ( ! $product ) {
			return 0.0;
		}

		if ( $product->is_on_sale() ) {
			return floatval( $product->get_sale_price() );
		}

		return floatval( $product->get_regular_price() );
	}
}