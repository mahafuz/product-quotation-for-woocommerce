<?php
/**
 * Responsible for running the plugin migration.
 *
 * @since   1.0.0
 */
namespace Quotify\Database;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

final class Migration {

	public function __clone() {
		throw new \Exception( 'Cannot clone class Quotify\Database\Migration' );
	}

	/**
	 * Responsible for running the migration process.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		$this->create_pages();
		$this->set_globals();
	}

	/**
	 * Perform global operations.
	 *
	 * @return void
	 */
	private function set_globals() {
	}


	/**
	 * Creates quotation cart page.
	 *
	 * @since 1.0.0
	 */
	private function create_pages() {
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
