<?php

namespace PQFW\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Plugin install
 *
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */

class PQFW_Install {

    public function __construct() {

        global $wpdb;
        $wpdb->pqfw_entries = $wpdb->prefix . 'pqfw_entries';

    }

    public static function install() {
        self::create_tables();
    }

    /**
     * Add tables for first installation.
     *
     * @access  private
     * @since   1.0.0
     * @return  void
     */
    private static function create_tables() {

        global $wpdb;
        require_once(ABSPATH.'wp-admin/includes/upgrade.php');

        $query = "CREATE TABLE IF NOT EXISTS {$wpdb->pqfw_entries} ( 
                  ID BIGINT(20) NOT NULL AUTO_INCREMENT,
                  product_id BIGINT(20) NOT NULL,
                  quantity INT(11) DEFAULT NULL,
                  fullname VARCHAR(200) DEFAULT NULL,
                  email VARCHAR(200) NOT NULL,
                  phone INT(11) DEFAULT NULL,
                  comments MEDIUMTEXT DEFAULT NULL,
                  dateadded timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (ID),
                  KEY product_id (product_id),
                  KEY email (email)
                ) DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;";

        dbDelta($query);
    }

}

new PQFW_Install;