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
	 * Contains form errros.
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
		$quotation_button_position                   = pqfw()->settings->get( 'button_position' );
		$quotation_button_position_in_single_product = pqfw()->settings->get( 'button_position_single_product' );

		add_action( $quotation_button_position, [ $this, 'add_button_on_products_loop' ] );
		add_action( $quotation_button_position_in_single_product, [ $this, 'add_button_on_single_product' ] );
	}

	/**
	 * Add quotation button on the loop.
	 *
	 * @since 1.2.0
	 */
	public function add_button_on_single_product() {
		if ( ! pqfw()->settings->get( 'pqfw_product_page_button' ) ) {
			return;
		}

		global $product;

		$buttonText = pqfw()->settings->get( 'button_text' );

		if ( ! empty( $buttonText ) ) {
			echo '<a class="button pqfw-button pqfw-add-to-quotation pqfw-add-to-quotation-single wp-element-button" href="javascript:void(0)" data-id="' . absint( $product->get_id() ) . '">'
			. esc_html( $buttonText ) .
			'</a>';
		}
	}

	/**
	 * Add quotation button on the single page.
	 *
	 * @since 1.2.0
	 */
	public function add_button_on_products_loop() {
		if ( ! pqfw()->settings->get( 'pqfw_shop_page_button' ) ) {
			return;
		}

		$buttonText = pqfw()->settings->get( 'button_text' );

		if ( ! empty( $buttonText ) ) {
			global $product;

			if ( $product->is_type( 'variable' ) ) {
				echo '<div class="pqfw-quote-btn-block wp-block-button wc-block-components-product-button align-center">';
					echo '<a class="button pqfw-button pqfw-add-to-quotation-variable wp-element-button" href="' . esc_url( $product->get_permalink() ) . '">
					' . esc_html( $buttonText ) . '
					</a>';
				echo '</div>';
			} else {
				echo '<div class="pqfw-quote-btn-block wp-block-button wc-block-components-product-button align-center">';
					echo '<a class="button pqfw-button pqfw-add-to-quotation pqfw-add-to-quotation-single wp-element-button" href="javascript:void(0)" data-id="' . absint( $product->get_id() ) . '">'
					. esc_html( $buttonText ) .
					'</a>';
				echo '</div>';
			}
		}
	}

	/**
	 * Form html
	 *
	 * @since   1.0.0
	 */
	public function form() {
		$settings   = pqfw()->settings->get();
		$classes    = [];

		if ( $settings['pqfw_form_default_design'] ) {
			$classes[] = 'use-pqfw-form-default-design';
		}
		if ( $settings['pqfw_floating_form'] ) {
			$classes[] = 'floating-form';
		}
		?>
		<div id="pqfw-frontend-form-wrap" class="<?php echo implode( ' ', $classes ); ?>">
			<?php echo pqfw()->formBuilder->render(); ?>
		</div>
		<?php
	}
}