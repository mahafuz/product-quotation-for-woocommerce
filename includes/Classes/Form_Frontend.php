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
        if( self::$instance === null ) {
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
        add_action( 'woocommerce_share', array( $this, 'form' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_stuffs' ) );
    }

    /**
     * Enqueue styles, scripts and other stuffs needed in the <footer>.
     *
     * @return void
     * @since 1.0.0
     */
    public function enqueue_scripts_and_stuffs() {
        wp_enqueue_script( 'pqfw-frontend', PQFW_PLUGIN_URL . 'assets/js/pqfw-frontend.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_style( 'pqfw-frontend', PQFW_PLUGIN_URL . 'assets/css/pqfw-frontend.css' );
    }

    /**
     * Form html
     * 
     * @since   1.0.0
     */
    public function form() {
        $this->fragments['product_id'] = get_the_ID();
        $this->fragments['product_title'] = get_the_title();
        $this->fragments['product_sku'] = get_post_meta( $this->fragments['product_id'], '_sku', true );
        $this->fragments['product_quantity'] = apply_filters( 'pqfw_product_quantity', 1 );

        $form_title = apply_filters( 'pqfw_form_title', __( 'Request quotation for: ', 'pqfw' ) );
    ?>
    <div id="pqfw-frontend-form-wrap">
        <h4 class="pqfw-form-title"><?php echo $form_title; ?><span class="title"><?php echo $this->fragments['product_title']; ?></span></h4>
        <div class="pqfw-form">
            <form id="pqfw-frontend-form">

                <ul class="pqfw-frontend-form">

                    <?php (new Controls_Manager)->generate_fields(); ?>

                    <li class="pqfw-form-field pqfw-submit">
                        <input
                            data-fragments='<?php echo json_encode($this->fragments); ?>'
                            type="submit"
                            id="rsrfqfwc_submit"
                            name="rsrfqfwc_submit"
                            value="<?php echo __('Submit Query', 'pqfw'); ?>"
                            class="submit"
                        />

                        <input type="hidden" id="pqfw_form_nonce" name="pqfw_form_nonce" value="<?php echo wp_create_nonce( 'pqfw_form_nonce' . get_the_ID() ); ?> " />

                        <div class="loading-spinner"></div>
                    </li>
                </ul>

            </form>
        </div>
    </div>
    <?php
    }

}