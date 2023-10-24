<?php

namespace PQFW;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @since   2.0.3
 * @package PQFW
 */
class Assets {
	public static function init() {
		$self = new self();
		add_action( 'admin_enqueue_scripts', [ $self, 'backend_scripts' ] );
	}

	public function backend_scripts() {
		wp_enqueue_script(
			'quote-packer-admin-scripts',
			PQFW_PLUGIN_URL . 'build/backend.js',
			['jquery', 'wp-util'],
			'1.0.0',
			true
		);

		wp_localize_script( 'quote-packer-admin-scripts', 'QuotePacker', [
			'quotations' => get_posts([
				'post_type' => 'pqfw_quotations',
				'post_status' => 'publish',
				'numberposts' => -1
			])
		]);
	}


}