<?php
namespace PQFW\Addons\Contactform;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use PQFW\Utils\Interfaces\Addon;

class Contactform implements Addon {
	private $addon_name = 'cf7';

	private function __construct() {
		$this->define_constants();
		$this->get_metadata();
		$this->init_addon();
	}

	public function get_metadata() {
		// $metadata = file_get_contents( PQFW_CF7_DIR_PATH . 'metadata.json' );
	}

	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	public function define_constants() {
		/**
		 * Defines CONSTANTS for Whole Addon.
		 */
		define( 'PQFW_CF7_VERSION', '1.0' );
		define( 'PQFW_CF7_VERSION_NAME', 'pqfw_cf7_version' );
		define( 'PQFW_CF7_DIR_PATH', PQFW_PLUGIN_ROOT_DIR_PATH . 'includes/addons/contact-form-7/' );
		define( 'PQFW_CF7_INCLUDES_DIR_PATH', PQFW_PLUGIN_ROOT_DIR_PATH . 'includes/addons/contact-form-7/includes/' );
	}

	public function database() {
	}

	public function ajax() {
	}

	public function init_addon() {
		// fire addon activation hook
		add_action( "pqfw/addons/activated_{$this->addon_name}", [ $this, 'addon_activation_hook' ] );

		// var_dump( pqfw()->helpers->get_addon_active_status( $this->addon_name ) );

		// if disable then stop running addons
		// if ( ! pqfw()->helpers->get_addon_active_status( $this->addon_name ) ) {
		// return;
		// }

		Knot::init();
		// Database::init();
		// API::init();
		// Ajax::init();
		// Miscellaneous::init();
	}

	public function addon_activation_hook() {
		\PQFW\Addons\Contactform\Installer::init();
		flush_rewrite_rules();
	}
}
