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
	 * Product fragments container.
	 *
	 * @var    array
	 * @access protected
	 * @since  1.0.0
	 */
	protected $fragments;

	/**
	 * Constructor of the class
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->quotationButtonPosition = 'woocommerce_after_shop_loop_item';

		/* add_action( 'woocommerce_share', array( $this, 'form' ) ); */
		add_action( 'woocommerce_single_product_summary', [ $this, 'add_quotation_button_single_page' ] );
		add_action( $this->quotationButtonPosition, [ $this, 'add_quotation_button' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts_and_stuffs' ] );
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
			[ 'jquery' ], '1.0.0', true
		);

		$cartPageId = get_option( 'pqfw_quotations_cart', false );

		wp_localize_script(
			'pqfw-frontend',
			'PQFW_OBJECT',
			[
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'ViewCartLabel' => __( 'View Quotation Cart', 'pqfw' ),
				'cartPageUrl'   => get_permalink( $cartPageId ),
				'loader'        => PQFW_PLUGIN_URL . 'assets/images/loader.gif',
				'actions'       => [
					'addToQuotations' => 'handle_insert_entry'
				]
			]
		);

		wp_enqueue_style(
			'pqfw-frontend',
			PQFW_PLUGIN_URL . 'assets/css/pqfw-frontend.css',
			[],
			'1.0.0',
			'all'
		);
	}

	/**
	 * Add quotation button on the loop.
	 *
	 * @since 1.2.0
	 */
	public function add_quotation_button() {
		global $product;

		if ( $product->is_type( 'variable' ) ) {
			echo '<a class="button pqfw-button pqfw-add-to-enquiry pqfw-add-to-enquiry-variable" href="' . esc_url( $product->get_permalink() ) . '">
			' . esc_html__( 'Add to Quotation', 'pqfw' ) . '
			</a>';
		} else {
			echo '<a class="button pqfw-button pqfw-add-to-enquiry pqfw-add-to-enquiry-single" href="javascript:void(0)" data-id="' . absint( $product->get_id() ) . '">'
			. esc_html__( 'Add to Quotation', 'pqfw' ) .
			'</a>';
		}
	}

	/**
	 * Add quotation button on the single page.
	 *
	 * @since 1.2.0
	 */
	public function add_quotation_button_single_page() {
		global $product;

		echo '<a class="button pqfw-button pqfw-add-to-enquiry pqfw-add-to-enquiry-single" href="javascript:void(0)" data-id="'.$product->get_id().'">'
		. esc_html__( 'Add to Quotation', 'pqfw' ) .
		'</a>';
	}

	/**
	 * Form html
	 *
	 * @since   1.0.0
	 */
	public function form() {
		$this->fragments['products'] = wp_json_encode( pqfw()->quotations->getProducts() );

		$form_title = apply_filters( 'pqfw_form_title', __( 'Request quotation for: ', 'pqfw' ) );
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
			<div class="pqfw-form">
				<form id="pqfw-frontend-form">

					<ul class="pqfw-frontend-form">
						<?php pqfw()->controlsManager->generate_fields(); ?>
					</ul>

					<div class="pqfw-form-field pqfw-submit">
						<input
							data-fragments='<?php echo wp_json_encode( $this->fragments ); ?>'
							type="submit"
							id="rsrfqfwc_submit"
							name="rsrfqfwc_submit"
							value="<?php echo esc_html__( 'Submit Query', 'pqfw' ); ?>"
							class="submit"
						/>
						<div class="loading-spinner"></div>
						<?php wp_nonce_field( 'pqfw_form_nonce_action', 'pqfw_form_nonce_field' ); ?>
					</div>

					<div class="pqfw-form-response-status"></div>
				</form>
			</div>
		</div>
		<?php
	}
}