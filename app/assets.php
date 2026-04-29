<?php
/**
 * Assets class
 *
 * @since   2.0.3
 * @package Quotify
 */

namespace Quotify;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

use Quotify\Assets\Base;

/**
 * Admin class
 *
 * @since   2.0.3
 * @package Quotify
 */
class Assets extends Base {
	/**
	 * Class instance.
	 *
	 * @var \Quotify\Assets
	 */
	private static $instance;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return \Quotify\Assets
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
	 * @since  2.0.3
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @param string $hook The current page slug.
	 * @since 2.0.3
	 */
	public function admin_scripts( $hook ) {
		if ( \Quotify\Library\Helper::pageLookUp( $hook ) ) {
			$dependencies = include_once QUOTIFY_PLUGIN_ASSETS_DIR . sprintf( 'build/backend.%s.asset.php', QUOTIFY_PLUGIN_VERSION );

			wp_enqueue_style(
				'pqfw-admin-style',
				QUOTIFY_PLUGIN_ASSETS_URI . 'build/backend.css',
				[ 'wp-components' ],
				$dependencies['version'],
				'all'
			);

			if ( ! did_action( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}

			$this->load_block_editor_scripts();

			wp_enqueue_style(
				'pqfw-web-font',
				$this->web_fonts_url(
				'DM Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700|Inter:wght@300;400;500;600;700;800;900|Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap'//phpcs:ignore
				),
				null,
				$dependencies['version']
			);

			wp_enqueue_script(
				'pqfw-admin-scripts',
				QUOTIFY_PLUGIN_ASSETS_URI . sprintf( 'build/backend.%s.js', QUOTIFY_PLUGIN_VERSION ),
				$dependencies['dependencies'],
				$dependencies['version'],
				true
			);

			wp_localize_script( 'pqfw-admin-scripts', 'QUOTIFY_CONFIG', $this->get_backend_scripts_data() );
			wp_set_script_translations( 'pqfw-admin-scripts', 'quotify', QUOTIFY_PLUGIN_ROOT_PATH . 'languages' );
		}
	}

	/**
	 * Enqueue frontend scripts.
	 *
	 * @since 2.0.3
	 */
	public function frontend_scripts() {
		$frontend_data = $this->get_frontend_scripts_data();
		$suffix = QUOTIFY_PLUGIN_VERSION;

		wp_enqueue_script(
			'pqfw-quotation-button',
			QUOTIFY_PLUGIN_ASSETS_URI . sprintf( 'build/button.%s.js', $suffix ),
			[ 'wp-util', 'wp-i18n', 'jquery' ],
			$suffix,
			true
		);

		wp_localize_script( 'pqfw-quotation-button', 'QUOTIFY_CONFIG', $frontend_data );
		wp_set_script_translations( 'pqfw-quotation-button', 'quotify' );

		wp_enqueue_script(
			'pqfw-quotation-cart',
			QUOTIFY_PLUGIN_ASSETS_URI . sprintf( 'build/cart.%s.js', defined( WP_DEBUG ) ? time() : $suffix ),
			[ 'wp-util', 'jquery', 'wp-i18n' ],
			$suffix,
			true
		);

		wp_localize_script( 'pqfw-quotation-cart', 'QUOTIFY_CONFIG', $frontend_data );
		wp_set_script_translations( 'pqfw-quotation-cart', 'quotify' );

		wp_enqueue_script(
			'pqfw-form',
			QUOTIFY_PLUGIN_ASSETS_URI . sprintf( 'build/form.%s.js', defined( WP_DEBUG ) ? time() : $suffix ),
			[ 'jquery', 'wp-i18n' ],
			$suffix,
			true
		);
		wp_set_script_translations( 'pqfw-form', 'quotify' );

		wp_enqueue_style(
			'pqfw-frontend',
			QUOTIFY_PLUGIN_ROOT_URI . 'assets/css/pqfw-frontend.css',
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
}
