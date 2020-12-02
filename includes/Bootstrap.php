<?php
/**
 * Bootstrap class
 * 
 * 
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */

namespace PQFW;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

use \PQFW\Admin\Admin_Init;
use \PQFW\Classes\Form_Frontend;
use \PQFW\Classes\Form_Handler;
use \PQFW\Classes\Options_Handler;

class Bootstrap {

    /**
     * Single instance of the class
     *
     * @var \Bootstrap
     * @since 1.0.0
     */
    private static $instance = null;

    /**
     * Returns single instance of the class
     *
     * @return \Bootstrap
     * @since 1.0.0
     */
    public static function instance() {
        if( self::$instance === null ) {
            return self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * PQFW frontend form.
     * 
     * @var         \Form_Frontend
     * @access      protected
     * @since       1.0.0
     */
    protected $form_frontend;

    /**
     * PQFW admin page
     *
     * @var     \Admin
     * @access  protected
     * @since   1.0.0
     */
    protected $admin;

    /**
     * PQFW form handler container.
     *
     * @var     \Form_Handler
     * @access  protected
     * @since   1.0.0
     */
    protected $form_handler;

    protected $options_handler;


    /**
     * Constructor of the class
     *
     * @return \Bootstrap
     * @since 1.0.0
     */
    private function __construct() {
        $this->form_frontend = Form_Frontend::instance();
        $this->admin = Admin_Init::instance();
        $this->form_handler = Form_Handler::instance();
        $this->options_handler = Options_Handler::instance();
    }

    public static function get_url_with_nonce( $url = '' ) {

        if( empty( $url ) ) {
            $url = '?page=pqfw-entries-page';
        }

        return wp_nonce_url( admin_url( $url ), 'pqfw_admin_nonce_action', '_wpnonce' );

    }

}
