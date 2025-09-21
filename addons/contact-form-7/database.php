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
	 * Get settings for the CF7 addon.
	 *
	 * @param  string $key           The setting key to retrieve, or 'all' to return all settings.
	 * @param  mixed  $default_value The default value if the setting does not exist.
	 * @param  bool   $raw           The return value type.
	 * @return mixed                 The requested setting value, all settings, or default.
	 */
	public static function get_setting( $key = 'all', $default_value = false, $raw = false ) {
		if ( $raw ) {
			$settings = get_option( self::SETTINGS_KEY, [] );
		} else {
			$settings = json_decode( wp_unslash( get_option( self::SETTINGS_KEY, [] ) ), true );
		}

		if ( 'all' === $key ) {
			return ! empty( $settings ) ? $settings : $default_value;
		}

		return isset( $settings[ $key ] ) ? $settings[ $key ] : $default_value;
	}
}
