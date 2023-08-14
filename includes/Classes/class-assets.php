<?php

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @since   2.0.3
 * @package PQFW
 */
class Assets {

	/**
	 * Constructor of the class
	 *
	 * @since  2.0.3
	 * @return void
	 */
	public function __construct() {
		if ( defined( 'ELEMENTOR_PATH' ) ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_editor_style' ] );
			add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'elementor_editor_scripts' ] );
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'backend_assets' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts_and_stuffs' ] );
	}

	/**
	 * Enqueue elementor editor scripts.
	 *
	 * @return void
	 */
	public function elementor_editor_scripts() {
		wp_enqueue_script(
			'pqfw-elementor',
			PQFW_PLUGIN_URL . 'assets/js/pqfw-elementor.js',
			[ 'jquery' ], '1.0.0', true
		);

		wp_enqueue_script(
			'pqfw-frontend',
			PQFW_PLUGIN_URL . 'assets/js/pqfw-frontend.js',
			[ 'jquery' ], '1.0.0', true
		);

		$cartPageId = get_option( 'pqfw_quotations_cart', false );

		if ( ! $cartPageId ) {
			pqfw()->migration->run();
			$cartPageId = get_option( 'pqfw_quotations_cart', false );
		}

		wp_localize_script(
			'pqfw-elementor',
			'PQFW_OBJECT',
			[
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'ViewCartLabel' => __( 'View Quotation Cart', 'pqfw' ),
				'cartPageUrl'   => get_permalink( $cartPageId ),
				'loader'        => PQFW_PLUGIN_URL . 'assets/images/loader.gif',
				'nonce'         => wp_create_nonce( 'pqfw_cart_actions' ),
				'fields'        => pqfw()->formApi->getFormMarkup()
			]
		);
	}

	/**
	 * Print styles for the elementor editor.
	 *
	 * @since 2.0.3
	 */
	public function elementor_editor_style() {
		?>
		<style>
			body #elementor-panel-elements-wrapper .icon .pqfw-quote-cart-icon {
				background: url('https://ps.w.org/product-quotation-for-woocommerce/assets/icon-128x128.png?rev=2445332') no-repeat center center;
				background-size: contain;
				height: 29px;
				display: block;
			}
		</style>
		<?php
	}

	/**
	 * Loading admin css.
	 *
	 * @since 1.0.0
	 */
	public function assets() {
		$screen        = get_current_screen();
		$valid_screens = [
			'pqfw_quotations_page_pqfw-settings',
			'pqfw_quotations_page_pqfw-entries-page',
			'pqfw_quotations_page_pqfw-help',
			'pqfw_quotations'
		];

		if ( 'pqfw_quotations' === $screen->post_type ) {
			wp_enqueue_style(
				'pqfw-admin-quotations',
				PQFW_PLUGIN_URL . 'assets/css/pqfw-quotations.css',
				[], '1.0.0', 'all'
			);

			wp_enqueue_script(
				'pqfw-admin-edit-quote',
				PQFW_PLUGIN_URL . 'assets/js/pqfw-edit-quote.js',
				[ 'jquery' ], '1.0.0', true
			);
		}

		if ( in_array( $screen->id, $valid_screens, true ) ) {
			wp_enqueue_style(
				'pqfw-admin',
				PQFW_PLUGIN_URL . 'assets/css/pqfw-admin.css',
				[], '1.0.0', 'all'
			);
		}
	}

	/**
	 * Enqueue scripts and stuffs for settings page.
	 *
	 * @access  public
	 * @return  void
	 */
	public function backend_assets() {
		$screen = get_current_screen();

		if ( 'pqfw_quotations_page_pqfw-settings' === $screen->id ) {

			$dependencies = require_once PQFW_PLUGIN_PATH . 'build/index.asset.php';
			array_push( $dependencies['dependencies'], 'wp-util' );

			wp_enqueue_style(
				'pqfw-app',
				PQFW_PLUGIN_URL . 'build/index.css',
				[ 'wp-components' ],
				PQFW_PLUGIN_VERSION,
				'all'
			);

			wp_register_script(
				'pqfw-app',
				PQFW_PLUGIN_URL . 'build/index.js',
				$dependencies['dependencies'],
				PQFW_PLUGIN_VERSION,
				true
			);

			wp_localize_script(
				'pqfw-app',
				'PQFW_OBJECT',
				[
					'ajaxurl'  => admin_url( 'admin-ajax.php' ),
					'settings' => pqfw()->settings->getAll(),
					'nonce'    => wp_create_nonce( 'pqfw-app-ui' ),
					'actions'  => [
						'save_settings' => 'pqfw_save_settings'
					],
					'pages'    => pqfw()->helpers->get_pages(),
					'cart'     => [
						'id'  => pqfw()->helpers->get_cart(),
						'url' => pqfw()->helpers->get_cart( 'url' )
					],
					'strings'     => pqfw()->strings->get(),
					'tax_enabled' => wc_tax_enabled()
				]
			);

			wp_enqueue_script( 'pqfw-app' );
			load_plugin_textdomain( 'pqfw', false, PQFW_PLUGIN_LANGUAGES_PATH );
		}
	}

	/**
	 * Enqueue styles, scripts and other stuffs needed in the <footer>.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function enqueue_scripts_and_stuffs() {
		wp_enqueue_script(
			'pqfw-frontend',
			PQFW_PLUGIN_URL . 'assets/js/pqfw-frontend.js',
			[ 'jquery' ], '1.0.0', true
		);

		wp_enqueue_script(
			'pqfw-quotation-cart',
			PQFW_PLUGIN_URL . 'assets/js/pqfw-cart.js',
			[ 'jquery', 'wp-util' ], '1.0.0', true
		);

		$cartPageId = get_option( 'pqfw_quotations_cart', false );

		if ( ! $cartPageId ) {
			pqfw()->migration->run();
			$cartPageId = get_option( 'pqfw_quotations_cart', false );
		}

		wp_localize_script(
			'pqfw-frontend',
			'PQFW_OBJECT',
			[
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'ViewCartLabel' => __( 'View Quotation Cart', 'pqfw' ),
				'cartPageUrl'   => get_permalink( $cartPageId ),
				'loader'        => PQFW_PLUGIN_URL . 'assets/images/loader.gif',
				'nonce'         => wp_create_nonce( 'pqfw_cart_actions' ),
				'fields'        => pqfw()->formApi->getFormMarkup()
			]
		);

		wp_enqueue_style(
			'pqfw-frontend',
			PQFW_PLUGIN_URL . 'assets/css/pqfw-frontend.css',
			[],
			'1.0.0',
			'all'
		);

		wp_add_inline_style( 'pqfw-frontend', $this->getInlineStyles() );
	}

	/**
	 * Generate inline styles for the form or buttons.
	 *
	 * @since  1.0.0
	 * @return mixed
	 */
	public function getInlineStyles() {
		$css = '';
		$buttonNormalColor = pqfw()->settings->get( 'button_normal_color' );
		$buttonNormalBg    = pqfw()->settings->get( 'button_normal_bg_color' );
		$buttonHoverColor  = pqfw()->settings->get( 'button_hover_color' );
		$buttonHoverBg     = pqfw()->settings->get( 'button_hover_bg_color' );
		$buttonFontSize    = pqfw()->settings->get( 'button_font_size' );
		$buttonWidth       = pqfw()->settings->get( 'button_width' );

		$hide_add_to_cart_button = wp_validate_boolean( pqfw()->settings->get( 'hide_add_to_cart_button' ) );
		$hide_product_prices     = wp_validate_boolean( pqfw()->settings->get( 'hide_product_prices' ) );

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

		if ( $hide_add_to_cart_button ) {
			$css .= ".woocommerce .products .product .button.add_to_cart_button, .woocommerce .single_add_to_cart_button {display: none !important;}";
		}

		if ( $hide_product_prices ) {
			$css .= '.woocommerce .products .product .price {display: none !important;}';
		}

		return apply_filters( 'pqfw_frontend_css', $css );
	}
}