<?php
/**
 * This class responsible for handling the frontend form.
 *
 * @since 1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages the quotation form frontend.
 *
 * @package PQFW
 * @since   1.0.0
 */
class Form {

	/**
	 * Contains form errors.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $errors = [];

	/**
	 * Constructor of the class
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$buttonPosition           = pqfw()->settings->get( 'button_position' );
		$singlePageButtonPosition = pqfw()->settings->get( 'button_position_single_product' );

		add_action( $singlePageButtonPosition, [ $this, 'addButtonOnSinglePage' ] );
		add_action( $buttonPosition, [ $this, 'addButton' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts_and_stuffs' ] );
		add_action( 'quotify/templates/cart/form', [ $this, 'form' ] );
	}

	/**
	 * Get form type based on the addon and others configuration.
	 *
	 * @since 2.5.0
	 * @return string
	 */
	private function get_form_type() {
		$form_type = json_decode( pqfw()->addons->get_saved(), true );

		if ( ! empty( $form_type['cf7'] ) && wp_validate_boolean( $form_type['cf7'] ) ) {
			return 'cf7';
		}

		return 'default';
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
				'ViewCartLabel' => __( 'View Quotation Cart', 'quotify' ),
				'cartPageUrl'   => get_permalink( $cartPageId ),
				'loader'        => PQFW_PLUGIN_URL . 'assets/images/loader.gif',
				'nonce'         => wp_create_nonce( 'pqfw_cart_actions' ),
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
		if ( ! pqfw()->settings->get( 'pqfw_shop_page_button' ) ) {
			return;
		}

		global $product;

		$buttonText = apply_filters(
			'quotify/frontend/product_loop/button_text',
			pqfw()->settings->get( 'button_text' )
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
		if ( ! pqfw()->settings->get( 'pqfw_product_page_button' ) ) {
			return;
		}

		$buttonText = apply_filters(
			'quotify/frontend/single_product/button_text',
			pqfw()->settings->get( 'button_text' )
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

	/**
	 * Get form HTML based on integration type.
	 *
	 * @since 2.5.0
	 */
	private function get_form_html() {
		$form_type = $this->get_form_type();
		$form_id   = 0;

		switch ( $form_type ) {
			case 'cf7':
				if ( class_exists( '\QuotifyContact_Form_7\Database' ) ) {
					$form_id = absint( \QuotifyContact_Form_7\Database::get_setting( 'form_id' ) );
				}

				$shortcode = sprintf( '[contact-form-7 id="%d"]', $form_id );
				echo do_shortcode( $shortcode );
				break;

			default:
				ob_start();
					require_once PQFW_PLUGIN_VIEWS . 'form/default.php';
				$form_html = ob_get_clean();
				echo pqfw()->helpers->escape_html_form( $form_html );//phpcs:ignore
		}
	}

	/**
	 * Form html
	 *
	 * @since   1.0.0
	 */
	public function form() {
		$settings = pqfw()->settings->get();
		$classes  = [];

		if ( $settings['pqfw_form_default_design'] ) {
			$classes[] = 'use-pqfw-form-default-design';
		}
		if ( $settings['pqfw_floating_form'] ) {
			$classes[] = 'floating-form';
		}

		$classes = apply_filters( 'quotify/form/wrapper_class', $classes );
		?>
		<div id="pqfw-frontend-form-wrap" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<div class="pqfw-form">
				<?php $this->get_form_html(); //phpcs:ignore ?>
			</div>
		</div>
		<?php
	}
}
