<?php
/**
 * Admin class
 * 
 * 
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */

namespace PQFW\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

class Admin_Init {

    /**
     * Single instance of the class
     *
     * @var \Admin
     * @since 1.0.0
     */
    private static $instance = null;

    /**
     * Returns single instance of the class
     *
     * @return \Admin
     * @since 1.0.0
     */
    public static function instance() {
        if( self::$instance === null ) {
            return self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor of the class
     *
     * @return \Bootstrap
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_css' ) );
    }

    public function add_menu_page() {
        add_menu_page(
            __( 'Product Quotations', 'PQFW' ),
            __( 'Product Quotation', 'PQFW' ),
            'manage_options',
            'pqfw-options-page',
            array( $this, 'display_product_quotation_page' ),
            null
        );
    }

    public function admin_css($hook) {
        if( $hook === 'toplevel_page_pqfw-options-page') {
            wp_enqueue_style( 'pqfw-admin', PQFW_PLUGIN_URL . 'assets/css/pqfw-admin.css' );
        }
    }

    public function display_product_quotation_page() {
        include( __DIR__ . '/templates/options.php' );
    }

}