<?php
/**
 * Quotify database class
 *
 * @author      Mahafuz
 * @package     Quotify
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
class Database {
	/**
	 * Class instance.
	 *
	 * @var Quotify\Database
	 */
	private static $instance;

	/**
	 * Class instance
	 *
	 * @return Quotify\Database
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize ajax actions.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
	}
}
