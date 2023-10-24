<?php
/**
 * Responsible for handling submission of the frontend form.
 *
 * @since 1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Form Handler class.
 *
 * @since   1.0.0
 * @package PQFW
 */
class Edit_Quote {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
	}

	public function assets() {
		$screen = get_current_screen();

		if ( $screen->post_type === 'pqfw_quotations' && $screen->base === 'post' ) {
			
		}
	}
}