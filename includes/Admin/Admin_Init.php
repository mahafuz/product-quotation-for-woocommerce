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

    public function display_product_quotation_page() {
        echo '<h1>Hello World</h1>';
    }

}