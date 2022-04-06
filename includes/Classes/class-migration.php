<?php
/**
 * Responsible for running the plugin migration.
 *
 * @since   1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Plugin database migration.
 *
 * @package PQFW
 * @since   1.0.0
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
		// $this->createTable();
		$this->createCartPage();
	}

	/**
	 * Add tables for first installation.
	 *
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function createTable() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table = pqfw()->utils->get_table_name();

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

		dbDelta( $query );
	}

	/**
	 * Cretes quotation cart page.
	 *
	 * @since 1.0.0
	 */
	public function createCartPage() {
		$page_saved = get_option( 'pqfw_quotations_cart', 0 );

		if ( 0 === $page_saved || '' === $page_saved ) {
			$page = [
				'post_title'     => __( 'Quotations Cart', 'pqfw' ),
				'post_type'      => 'page',
				'post_content'   => '[pqfw_quotations_cart]',
				'post_status'    => 'publish',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			];

			$page_id = wp_insert_post( $page, false );

			update_option( 'pqfw_quotations_cart', $page_id );
		}
	}
}