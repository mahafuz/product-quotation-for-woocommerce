<?php

namespace PQFW\Database;

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
class Migration {

    /**
     * Responsible for running the migration process.
     *
     * @access  protected
     * @since   1.0.0
     * @return  void
     */
    public function run() {

        $this->create_tables();

    }

    /**
     * Add tables for first installation.
     *
     * @access  private
     * @since   1.0.0
     * @return  void
     */
    private function create_tables() {

        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table = Utils::get_table_name();

        $query = "CREATE TABLE IF NOT EXISTS {$table} ( 
                  ID BIGINT(20) NOT NULL AUTO_INCREMENT,
                  product_id BIGINT(20) NOT NULL,
                  product_title TEXT NOT NULL,
                  quantity INT(11) DEFAULT NULL,
                  fullname VARCHAR(200) DEFAULT NULL,
                  email VARCHAR(200) NOT NULL,
                  phone INT(11) DEFAULT NULL,
                  comments MEDIUMTEXT DEFAULT NULL,
                  status TEXT NOT NULL,
                  dateadded timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (ID),
                  KEY product_id (product_id),
                  KEY email (email)
                ) DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;";

        dbDelta($query);

    }

}