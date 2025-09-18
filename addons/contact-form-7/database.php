<?php
/**
 * Elementor widget that inserts an embed-able content into the page, from any given URL.
 *
 * @since 2.0.3
 * @package Quotify
 */

namespace QuotifyContact_Form_7;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Contact form 7 support for the plugin.
 *
 * @since 2.5.0
 */
class Database {

	const SETTINGS_KEY = 'quotify_addons_cf7_settings';

	/**
	 * Initializer for the addon.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
	}

	/**
	 * Save settings for the cf7 addon.
	 *
	 * @param  string $settings The settings to save.
	 * @return bool
	 */
	public static function save_settings( $settings ) {
		return update_option( self::SETTINGS_KEY, $settings, false );
	}

	/**
	 * Get settings for the cf7 addon.
	 *
	 * @param  string $key The settings to save.
	 * @param  mixed  $default The default value to save.
	 * @return bool
	 */
	public static function get_setting( $key = 'all', $default = false ) {
		return get_option( self::SETTINGS_KEY, $default );
	}
}
