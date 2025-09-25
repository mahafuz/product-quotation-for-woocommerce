<?php
/**
 * Quotify addons manager.
 *
 * @since 2.4.0
 * @package Quotify
 */

namespace Quotify\Internals;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Quotify\Autoload;

/**
 * Responsible for managing plugin addons.
 *
 * @since 2.4.0
 * @package Quotify
 */
class Addons {

	/**
	 * Addons settings group key.
	 *
	 * @var string
	 * @since 2.5.0
	 */
	const OPTION_GROUP = 'quotify_addons';

	/**
	 * Class instance.
	 *
	 * @var Quotify\Internals\Addons
	 */
	private static $instance;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var Quotify\Internals\Addons
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->addons_loader();
	}

	/**
	 * Loads all addons for the plugin.
	 *
	 * @since 2.5.0
	 */
	private function addons_loader() {
		$Autoload = Autoload::init();

		$addons = apply_filters(
			'quotify/addons/loader_args', //phpcs:ignore
			[
				'contact-form-7' => 'Contact_Form_7',
			]
		);

		foreach ( $addons as $addon_name => $addon_class_name ) {
			$addon_root_path = QUOTIFY_PLUGIN_ROOT_PATH . 'addons' . DIRECTORY_SEPARATOR . $addon_name . '/';

			// Register the addon's root namespace and path.
			$addon_namespace = 'Quotify' . $addon_class_name;

			$Autoload->add_namespace_directory( $addon_namespace, $addon_root_path );

			// Initialize the addon's main class.
			$class = $addon_namespace . '\\' . $addon_class_name;

			$class::init();
		}
	}

	/**
	 * Get saved addons.
	 *
	 * @return array
	 */
	public static function get() {
		$addons = (array) json_decode( get_option( self::OPTION_GROUP, '{}' ), true );

		return $addons;
	}

	private function set( $addon, $status ) {
		$saved_addons = (array) json_decode( get_option( self::OPTION_GROUP ), true );
		$saved_addons[ $addon ] = $status;

		$status = update_option( self::OPTION_GROUP, wp_json_encode( $saved_addons ) );

		return $status;
	}

	public function save( $addon, $status ) {
		return $this->set( $addon, $status );
	}
}
