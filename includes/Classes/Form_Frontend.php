<?php
/**
 * Manages the quotation form frontend.
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */

namespace PQFW\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Form_Frontend {

	/**
	 * Single instance of the class
	 *
	 * @var \Form_Frontend
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Returns single instance of the class
	 *
	 * @return \Form_Frontend
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			return self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Product fragments container.
	 *
	 * @var     array
	 * @access  protected
	 * @since   1.0.0
	 */
	protected $fragments;

	/**
	 * Constructor of the class
	 *
	 * @return \Form_Frontend
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'woocommerce_share', array ( $this, 'form' ) );
		add_action( 'wp_enqueue_scripts', array ( $this, 'enqueue_scripts_and_stuffs' ) );
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
			array ( 'jquery' ), '1.0.0', true
		);

		wp_localize_script(
			'pqfw-frontend',
			'PQFW_OBJECT',
			array (
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'actions' => array (
					'insert_entry' => 'handle_insert_entry'
				)
			)
		);
		wp_enqueue_style( 'pqfw-frontend', PQFW_PLUGIN_URL . 'assets/css/pqfw-frontend.css' );
	}

	/**
	 * Form html
	 *
	 * @since   1.0.0
	 */
	public function form() {
		$this->fragments['product_id']       = get_the_ID();
		$this->fragments['product_title']    = get_the_title();
		$this->fragments['product_sku']      = get_post_meta( $this->fragments['product_id'], '_sku', true );
		$this->fragments['product_quantity'] = apply_filters( 'pqfw_product_quantity', 1 );

		$form_title = apply_filters( 'pqfw_form_title', __( 'Request quotation for: ', 'pqfw' ) );
		$settings   = get_option( 'pqfw_settings' );

		$classes = array ();
		if ( $settings['pqfw_form_default_design'] ) {
			$classes[] = 'use-pqfw-form-default-design';
		}
		if ( $settings['pqfw_floating_form'] ) {
			$classes[] = 'floating-form';
		}
		?>
        <div id="pqfw-frontend-form-wrap" class="<?php echo implode(' ', $classes ); ?>">
            <h4 class="pqfw-form-title"><?php echo $form_title; ?><span class="title"><?php echo $this->fragments['product_title']; ?></span></h4>
            <div class="pqfw-form">
                <form id="pqfw-frontend-form">

                    <ul class="pqfw-frontend-form">

						<?php ( new Controls_Manager )->generate_fields(); ?>

                    </ul>

                    <div class="pqfw-form-field pqfw-submit">
                        <input
                                data-fragments='<?php echo json_encode( $this->fragments ); ?>'
                                type="submit"
                                id="rsrfqfwc_submit"
                                name="rsrfqfwc_submit"
                                value="<?php echo __( 'Submit Query', 'pqfw' ); ?>"
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