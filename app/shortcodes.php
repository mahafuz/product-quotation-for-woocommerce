<?php
/**
 * PQFW class
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.2.0
 */

namespace Quotify;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for managing plugin ajax requests.
 *
 * @since 2.4.0
 */
class Shortcodes {
	/**
	 * Class instance.
	 *
	 * @var Quotify\Shortcodes
	 */
	private static $instance = null;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var Quotify\Shortcodes
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
	 * @since 2.5.0
	 */
	private function __construct() {
		$this->cart();
	}

	/**
	 * Contains cart page shortcode.
	 *
	 * @var mixed
	 */
	private function cart() {
		return \Quotify\Shortcodes\Cart::init();
	}
}
