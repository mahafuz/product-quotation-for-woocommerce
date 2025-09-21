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
class Contact_Form_7 {

	private $database;
	private $hooks;
	private $ajax;

	/**
	 * Initializer for the addon.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		$self = new self();
		$self->database = new \QuotifyContact_Form_7\Hook();
		$self->hooks    = new \QuotifyContact_Form_7\Hook();
		$self->ajax     = new \QuotifyContact_Form_7\Ajax();
	}

}
