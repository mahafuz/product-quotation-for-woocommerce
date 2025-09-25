<?php
/**
 * Responsible for running the plugin migration.
 *
 * @since   1.0.0
 * @package PQFW
 */

namespace Quotify\Database;

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
		$this->createCartPage();
		$this->setGlobal();
	}

	/**
	 * Perform global operations.
	 *
	 * @return void
	 */
	private function setGlobal() {
		add_option( PQFW_ADDONS_SETTINGS_KEY, '{}' );
	}


	/**
	 * Creates quotation cart page.
	 *
	 * @since 1.0.0
	 */
	private function createCartPage() {
		$page_saved = get_option( 'pqfw_quotations_cart', 0 );

		if ( 0 === $page_saved || '' === $page_saved ) {
			$page = [
				'post_title'     => __( 'Quotations Cart', 'quotify' ),
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
