<?php
/**
 * Form Handler class.
 * 
 * 
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */

namespace PQFW\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

class Form_Handler {

    /**
     * Single instance of the class
     *
     * @var \Form_Handler
     * @since 1.0.0
     */
    private static $instance = null;

    /**
     * Returns single instance of the class
     *
     * @return \Form_Handler
     * @since 1.0.0
     */
    public static function instance() {
        if( self::$instance === null ) {
            return self::$instance = new self();
        }

        return self::$instance;
    }
    
}