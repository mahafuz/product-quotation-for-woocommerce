<?php
/**
 * PQFW class
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.2.0
 */

namespace Quotify;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Responsible for managing plugin ajax requests.
 *
 * @since 2.4.0
 */
class Assets {
	/**
	 * Class instance.
	 *
	 * @var Quotify\Assets
	 */
	private static $instance;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Quotify\Assets
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
		\Quotify\Assets\Loader::init();
	}
}
