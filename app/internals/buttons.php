<?php
/**
 * This class responsible for handling the frontend form.
 *
 * @since 1.0.0
 * @package Quotify
 */

namespace Quotify\Internals;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages the quotation form frontend.
 *
 * @package Quotify
 * @since   1.0.0
 */
class Buttons {
	/**
	 * Class instance.
	 *
	 * @var Quotify\Button
	 */
	private static $instance = null;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Quotify\Button
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor of the class
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->loop();
		$this->single();
	}

	/**
	 * Displays add to quote button on single product page.
	 * Displays add to quote button inside the product loop.
	 *
	 * @return void
	 */
	private function loop() {
		$buttonPosition = quotify()->settings()->get( 'button_position' );

		add_action( $buttonPosition, [ $this, 'addButton' ] );
	}

	/**
	 * Displays add to quote button on single product page.
	 *
	 * @return void
	 */
	private function single() {
		$singlePageButtonPosition = quotify()->settings()->get( 'button_position_single_product' );

		add_action( $singlePageButtonPosition, [ $this, 'addButtonOnSinglePage' ] );
	}

	/**
	 * Generate inline styles for the form or buttons.
	 *
	 * @since  1.0.0
	 * @return mixed
	 */
	public function getInlineStyles() {
		$css = '';
		$buttonNormalColor = quotify()->settings()->get( 'button_normal_color' );
		$buttonNormalBg    = quotify()->settings()->get( 'button_normal_bg_color' );
		$buttonHoverColor  = quotify()->settings()->get( 'button_hover_color' );
		$buttonHoverBg     = quotify()->settings()->get( 'button_hover_bg_color' );
		$buttonFontSize    = quotify()->settings()->get( 'button_font_size' );
		$buttonWidth       = quotify()->settings()->get( 'button_width' );

		$css .= 'a.button.pqfw-button.pqfw-add-to-quotation {';
		if ( ! empty( $buttonNormalColor ) ) {
			$css .= 'color: ' . $buttonNormalColor . ';';
		}

		if ( ! empty( $buttonNormalBg ) ) {
			$css .= 'background-color: ' . $buttonNormalBg . ';';
		}

		if ( ! empty( $buttonFontSize ) ) {
			$css .= 'font-size: ' . $buttonFontSize . 'px;';
		}

		if ( ! empty( $buttonWidth ) ) {
			$css .= 'width: ' . $buttonWidth . 'px;';
		}
		$css .= '}';

		$css .= 'a.button.pqfw-button.pqfw-add-to-quotation:hover {';
		if ( ! empty( $buttonHoverColor ) ) {
			$css .= 'color: ' . $buttonHoverColor . ';';
		}

		if ( ! empty( $buttonHoverBg ) ) {
			$css .= 'background-color: ' . $buttonHoverBg . ';';
		}
		$css .= '}';

		return apply_filters( 'pqfw_frontend_css', $css );
	}

	/**
	 * Add quotation button on the loop.
	 *
	 * @since 1.2.0
	 */
	public function addButton() {
		if ( ! quotify()->settings()->get( 'pqfw_shop_page_button' ) ) {
			return;
		}

		global $product;

		$buttonText = apply_filters(
			'quotify/frontend/product_loop/button_text',
			quotify()->settings()->get( 'button_text' )
		);

		$viewText = '';

		if ( ! empty( $buttonText ) ) {
			if ( $product->is_type( 'variable' ) ) {
				echo '<div class="quotify-add-to-quote-btn quotify-quote-btn-wrap quotify-quote-btn-loop quotify-quote-btn-product-type-variable">';
					echo '<a class="button pqfw-button pqfw-add-to-quotation pqfw-add-to-quotation-variable" href="' . esc_url( $product->get_permalink() ) . '">';
					echo '<div class="loading-spinner"></div>' . esc_html( $buttonText ) . '</a>';
				echo '</div>';
			} else {
				echo '<div class="quotify-add-to-quote-btn quotify-quote-btn-wrap quotify-quote-btn-loop quotify-quote-btn-product-type-regular">';
					echo '<a class="button pqfw-button pqfw-add-to-quotation pqfw-add-to-quotation-single" href="' . esc_url( $product->get_permalink() ) . '"
					data-id="' . absint( $product->get_id() ) . '">';
					echo '<div class="loading-spinner"></div>' . esc_html( $buttonText ) . '</a>';
				echo '</div>';
			}
		}
	}

	/**
	 * Add quotation button on the single page.
	 *
	 * @since 1.2.0
	 */
	public function addButtonOnSinglePage() {
		if ( ! quotify()->settings()->get( 'pqfw_product_page_button' ) ) {
			return;
		}

		$buttonText = apply_filters(
			'quotify/frontend/single_product/button_text',
			quotify()->settings()->get( 'button_text' )
		);

		if ( ! empty( $buttonText ) ) {
			global $product;
			echo '<div class="quotify-add-to-quote-btn quotify-quote-btn-wrap quotify-quote-btn-single">';
			echo '<a class="button pqfw-button pqfw-add-to-quotation pqfw-add-to-quotation-single" href="javascript:void(0)" data-id="' . absint( $product->get_id() ) . '">';
			echo '<div class="loading-spinner"></div>'
			. esc_html( $buttonText ) .
			'</a>';
			echo '</div>';
		}
	}
}
