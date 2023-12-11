<?php
/**
 * Implements features of FREE version of the Product Quotation for WooCommerce plugin.
 *
 * @since   1.2.6
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Implements features of FREE version
 *
 * @since   1.2.6
 * @package PQFW
 */
class Frontend {
	/**
	 * Class constructor.
	 *
	 * @since 1.2.6
	 */
	public function __construct() {
		if ( pqfw()->settings->get( 'hide_add_to_cart_button' ) ) {
			add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'hideAddToCartButton' ], 10, 2 );
		}

		if ( pqfw()->settings->get( 'hide_product_prices' ) ) {
			add_filter( 'woocommerce_get_price_html', [ $this, 'hideProductPrices' ], 10, 2 );
			add_filter( 'woocommerce_get_variation_price_html', [ $this, 'hideProductPrices' ], 10, 2 );
		}

		add_filter( 'the_content', [ $this, 'pageContent' ] );
	}

	/**
	 * Hide add to cart in loop
	 * Hide the button add to cart in the shop page
	 *
	 * @since 1.2.6
	 *
	 * @param string     $html    Link.
	 * @param WC_Product $product Product.
	 * @return mixed|string
	 */
	public function hideAddToCartButton( $html, $product ) {
		if ( $product->is_type( 'simple' ) || ( is_shop() && $product->is_type( 'variable' ) ) || ( is_shop() && $product->is_type( 'grouped' ) ) ) {
			return '';
		}

		return $html;
	}

	/**
	 * Hide product prices
	 * Hide the prices of the products in the shop page
	 *
	 * @since 1.2.6
	 *
	 * @param string $price   Price.
	 * @param object $product Product.
	 * @return string
	 */
	public function hideProductPrices( $price, $product ) {
		return '';
	}

	/**
	 * Page template
	 * Change the page template for the product quotation page
	 * to the template of the product quotation page
	 * instead of the default template
	 *
	 * @since 2.0.1
	 */
	public function pageContent( $content ) {
		global $post;
		if ( absint( pqfw()->settings->get( 'quotation_cart_page' ) ) === $post->ID ) {
			$content = '[pqfw_quotations_cart]';
		}
		return $content;
	}
}