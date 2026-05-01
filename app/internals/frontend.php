<?php
/**
 * Implements features of FREE version of the Product Quotation for WooCommerce plugin.
 *
 * @since   1.2.6
 * @package Quotify
 */

namespace Quotify\Internals;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Implements features of FREE version
 *
 * @since   1.2.6
 * @package Quotify
 */
class Frontend {
	/**
	 * Class instance.
	 *
	 * @var \Quotify\Internals\Frontend
	 */
	private static $instance = null;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return \Quotify\Internals\Frontend
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @since 1.2.6
	 */
	private function __construct() {
		// Strict type checking for hide_add_to_cart_button setting.
		$hide_cart = quotify()->settings()->get( 'hide_add_to_cart_button' );

		if ( is_bool( $hide_cart ) && true === $hide_cart ) {
			// Shop/archive pages.
			add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'hideAddToCartButton' ], 10, 2 );

			// Single product pages.
			add_filter( 'woocommerce_single_product_add_to_cart_button', [ $this, 'hideAddToCartButton' ], 10, 2 );
			add_filter( 'woocommerce_variable_add_to_cart_button', [ $this, 'hideAddToCartButton' ], 10, 2 );
			add_filter( 'woocommerce_grouped_add_to_cart_button', [ $this, 'hideAddToCartButton' ], 10, 2 );
			add_filter( 'woocommerce_external_add_to_cart_button', [ $this, 'hideAddToCartButton' ], 10, 2 );

			// Add CSS fallback for theme compatibility.
			add_action( 'wp_head', [ $this, 'addHideCartButtonCSS' ] );

			// Add body class for targeting.
			add_filter( 'body_class', [ $this, 'addHideCartBodyClass' ] );
		}

		// Strict type checking to ensure prices only hide when explicitly enabled.
		$hide_prices = quotify()->settings()->get( 'hide_product_prices' );

		if ( is_bool( $hide_prices ) && true === $hide_prices ) {
			// Main price display hooks.
			add_filter( 'woocommerce_get_price_html', [ $this, 'hideProductPrices' ], 10, 2 );
			add_filter( 'woocommerce_get_variation_price_html', [ $this, 'hideProductPrices' ], 10, 2 );

			// Additional price hooks for complete coverage.
			add_filter( 'woocommerce_single_product_price', [ $this, 'hideProductPrices' ], 10, 2 );
			add_filter( 'woocommerce_grouped_price_html', [ $this, 'hideProductPrices' ], 10, 2 );
			add_filter( 'woocommerce_widget_price_display', [ $this, 'hideProductPrices' ], 10, 2 );
			add_filter( 'woocommerce_format_price_range', [ $this, 'hideProductPrices' ], 10, 2 );
			add_filter( 'woocommerce_get_price_suffix', [ $this, 'hideProductPrices' ], 10, 2 );

			// Core price retrieval hooks with higher priority.
			add_filter( 'woocommerce_get_price', [ $this, 'hideProductPrices' ], 999, 2 );
			add_filter( 'woocommerce_get_regular_price', [ $this, 'hideProductPrices' ], 999, 2 );
			add_filter( 'woocommerce_get_sale_price', [ $this, 'hideProductPrices' ], 999, 2 );

			// Add CSS fallback for theme compatibility.
			add_action( 'wp_head', [ $this, 'addHidePriceCSS' ] );

			// Add JavaScript fallback for dynamic content.
			add_action( 'wp_footer', [ $this, 'addHidePriceJS' ] );

			// Add body class for targeting.
			add_filter( 'body_class', [ $this, 'addHidePriceBodyClass' ] );
		}

		add_filter( 'the_content', [ $this, 'pageContent' ] );
	}

	/**
	 * Hide add to cart buttons.
	 * Hides add to cart buttons on all pages and product types.
	 *
	 * @since 1.2.6
	 *
	 * @param string     $html    Link.
	 * @param WC_Product $product Product.
	 * @return mixed|string
	 */
	public function hideAddToCartButton( $html, $product ) {
		// Hide for all product types - no restrictions.
		return '';
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
	public function hideProductPrices( $price, $product ) {//phpcs:ignore
		return '';
	}

	/**
	 * Page template
	 * Change the page template for the product quotation page
	 * to the template of the product quotation page
	 * instead of the default template
	 *
	 * @param mixed $content The page content.
	 *
	 * @since 2.0.1
	 */
	public function pageContent( $content ) {
		global $post;
		if ( absint( quotify()->settings()->get( 'quotation_cart_page' ) ) === $post->ID ) {
			$content = '[pqfw_quotations_cart]';
		}
		return $content;
	}

	/**
	 * Add CSS to hide add to cart buttons as fallback.
	 * Provides theme compatibility for themes that override templates.
	 *
	 * @since 2.6.0
	 */
	public function addHideCartButtonCSS() {
		?>
		<style>
			/* Hide add to cart buttons as fallback for theme compatibility. */
			.quotify-hide-cart .single_add_to_cart_button,
			.quotify-hide-cart .add_to_cart_button,
			.quotify-hide-cart button[type="submit"][name="add-to-cart"],
			.quotify-hide-cart .ajax_add_to_cart,
			.quotify-hide-cart .single_variation_wrap .variations_button:disabled {
				display: none !important;
			}

			/* Hide for specific WooCommerce blocks. */
			.quotify-hide-cart .wp-block-button__link.wp-block-button__add-to-cart,
			.quotify-hide-cart .wc-block-components-add-to-cart-button {
				display: none !important;
			}
		</style>
		<?php
	}

	/**
	 * Add body class when hiding add to cart buttons.
	 * Allows CSS targeting for theme compatibility.
	 *
	 * @since 2.6.0
	 *
	 * @param array $classes Body classes.
	 * @return array Modified body classes.
	 */
	public function addHideCartBodyClass( $classes ) {
		$classes[] = 'quotify-hide-cart';
		return $classes;
	}

	/**
	 * Add CSS to hide product prices as fallback.
	 * Provides theme compatibility for themes that override templates.
	 *
	 * @since 2.6.0
	 */
	public function addHidePriceCSS() {
		?>
		<style>
			/* Comprehensive price hiding fallback for theme compatibility. */
			.quotify-hide-prices .price,
			.quotify-hide-prices .product-price,
			.quotify-hide-prices span.price,
			.quotify-hide-prices ins .amount,
			.quotify-hide-prices del .amount,
			.quotify-hide-prices del .woocommerce-Price-amount,
			.quotify-hide-prices ins .woocommerce-Price-amount,
			.quotify-hide-prices .woocommerce-Price-amount,
			.quotify-hide-prices .woocommerce-price-suffix,
			.quotify-hide-prices .price-wrapper {
				display: none !important;
			}

			/* Hide WooCommerce block prices. */
			.quotify-hide-prices .wp-block-woocommerce-price {
				display: none !important;
			}

			/* Hide price in widgets specifically. */
			.quotify-hide-prices .widget .price,
			.quotify-hide-prices .woocommerce_widget_price {
				display: none !important;
			}
		</style>
		<?php
	}

	/**
	 * Add JavaScript to hide prices dynamically.
	 * Handles AJAX-loaded content and dynamic elements.
	 *
	 * @since 2.6.0
	 */
	public function addHidePriceJS() {
		?>
		<script>
			jQuery(document).ready(function($) {
				// JavaScript fallback for dynamic content.
				$('.quotify-hide-prices .price, .quotify-hide-prices .product-price, .quotify-hide-prices .woocommerce-Price-amount').hide();

				// Watch for dynamically added prices (AJAX, page builders, etc.).
				if (window.MutationObserver) {
					var priceObserver = new MutationObserver(function(mutations) {
						$('.quotify-hide-prices .price, .quotify-hide-prices .product-price, .quotify-hide-prices .woocommerce-Price-amount').hide();
					});
					priceObserver.observe(document.body, { childList: true, subtree: true });
				}
			});
		</script>
		<?php
	}

	/**
	 * Add body class when hiding product prices.
	 * Allows CSS targeting for theme compatibility.
	 *
	 * @since 2.6.0
	 *
	 * @param array $classes Body classes.
	 * @return array Modified body classes.
	 */
	public function addHidePriceBodyClass( $classes ) {
		$classes[] = 'quotify-hide-prices';
		return $classes;
	}
}
