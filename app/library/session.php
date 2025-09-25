<?php
/**
 * Responsible for managing the plugin base scripts.
 *
 * @since   1.0.0
 * @package PQFW
 */

namespace Quotify\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Responsible for managing the plugin base scripts.
 *
 * @since   1.0.0
 * @package PQFW
 */
class Session {
	/**
	 * Quotify cart session key.
	 *
	 * @since 2.5.0
	 */
	const OPTION_GROUP = 'pqfw_products_quotations_list';

	/**
	 * Class instance.
	 *
	 * @var Quotify\Session
	 */
	private static $instance;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Quotify\Session
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
	private function __construct() {
		add_action( 'woocommerce_init', [ $this, 'start' ] );
	}

	/**
	 * Start WooCommerce session for users.
	 *
	 * @since   2.0.3
	 * @return  void
	 */
	public function start() {
		if ( isset( WC()->session ) ) {
			WC()->session->set_customer_session_cookie( true );
		}
	}

	/**
	 * Set data to plugin group session.
	 *
	 * @since   1.0.0
	 * @param   array $payload The payload data to store in session.
	 * @return  bool True if data was set successfully, false otherwise.
	 */
	public function set( $payload ) {
		if ( ! isset( WC()->session ) || ! is_array( $payload ) ) {
			return false;
		}

		$existing = WC()->session->get( self::OPTION_GROUP, [] );
		$new_data = array_merge( $existing, $payload );

		WC()->session->set( self::OPTION_GROUP, $new_data );
		return true;
	}

	/**
	 * Get data from the plugin group session.
	 *
	 * @since   2.5.0
	 *
	 * @param   string $key           The key to pick from the session.
	 * @param   string $method        The optional sanitizer/callback to run before retrieving the data.
	 * @param   mixed  $default_value The default value to return if key doesn't exist.
	 *
	 * @return  mixed
	 */
	public function get( $key = 'all', $method = '', $default_value = null ) {
		if ( isset( WC()->session ) ) {
			$session = WC()->session->get( self::OPTION_GROUP, [] );

			if ( 'all' === $key ) {
				return $session;
			}

			return ! empty( $session[ $key ] ) ?
				( is_callable( $method ) ? $method( $session[ $key ] ) : $session[ $key ] ) : $default_value;
		}

		return $default_value;
	}

	/**
	 * Session exists for the key.
	 *
	 * @since   2.5.0
	 *
	 * @param string $key The session key to retrieve data.
	 * @return bool The boolean if the session exists.
	 */
	public function has( $key ) {
		if ( isset( WC()->session ) ) {
			$session = WC()->session->get( self::OPTION_GROUP );
			return isset( $session[ $key ] );
		}

		return false;
	}

	/**
	 * Remove a specific key from the plugin group session.
	 *
	 * @since   2.5.0
	 *
	 * @param   string $key The key to remove from the session.
	 * @return  bool True if the key was removed successfully, false otherwise.
	 */
	public function remove( $key ) {
		if ( isset( WC()->session ) ) {
			$session = WC()->session->get( self::OPTION_GROUP );

			if ( isset( $session[ $key ] ) ) {
				unset( $session[ $key ] );
				WC()->session->set( self::OPTION_GROUP, $session );
				return true;
			}
		}

		return false;
	}

	/**
	 * Remove a specific key from the plugin group session.
	 *
	 * @since   2.5.0
	 *
	 * @return  bool True if the key was removed successfully, false otherwise.
	 */
	public function reset() {
		if ( isset( WC()->session ) ) {
			$session = WC()->session->get( self::OPTION_GROUP );

			if ( isset( $session ) ) {
				WC()->session->set( self::OPTION_GROUP, null );
				return true;
			}
		}

		return false;
	}
}
