<?php
/**
 * Plugin installer.
 *
 * @package Quotify
 * @since   2.5.0
 */

namespace Quotify;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for plugin installation.
 *
 * @since 2.4.0
 */
class Install {

	/**
	 * Class instance.
	 *
	 * @var Quotify\Install
	 */
	private static $instance;

	/**
	 * Class instance.
	 *
	 * @var Quotify\Migration
	 */
	private $migration;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Quotify\Install
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
		add_action( 'plugin_action_links_' . PQFW_PLUGIN_BASENAME, [ $this, 'addPluginActionLinks' ] );
		add_action( 'admin_init', [ $this, 'redirect' ] );
		add_option( '_pqfw_activation_redirect', true );

		register_activation_hook( QUOTIFY_PLUGIN_FILE, [ $this, 'activate' ] );
		register_activation_hook( QUOTIFY_PLUGIN_FILE, [ $this, 'deactivate' ] );
	}

	/**
	 * Plugin database migration.
	 *
	 * @return \Quotify\Database
	 */
	public function migration() {
		return \Quotify\Database::init();
	}

	/**
	 * Run on the plugin activation.
	 *
	 * @since 2.5.0
	 * @return void
	 */
	public function activate() {
		$this->define_tables();
	}

	/**
	 * Run on the plugin deactivation.
	 *
	 * @since 2.5.0
	 * @return void
	 */
	public function deactivate() {
		$this->define_tables();
	}

	/**
	 * define_tables
	 *
	 * @return void
	 */
	private function define_tables() {
		$this->migration()->run();
	}

	/**
	 * Add plugin action links
	 *
	 * @since  2.0.1
	 * @param  array $links The links array.
	 * @return array The actions link.
	 */
	public function addPluginActionLinks( $links ) {
		$settings = '<a href="' . admin_url( 'admin.php?page=quotify-settings' ) . '">' . esc_html__( 'Settings', 'quotify' ) . '</a>';

		$help = sprintf(
			'<a href="%s"><span style="color:#f18500; font-weight: bold;">%s</span></a>',
			admin_url( 'admin.php?page=quotify-help' ),
			esc_html__( 'Help', 'quotify' )
		);

		array_unshift( $links, $settings );
		array_push( $links, $help );

		return $links;
	}

	/**
	 * Redirect on the settings page after plugin activation.
	 *
	 * @since 2.0.1
	 * @return void
	 */
	public function redirect() {
		if ( get_option( '_pqfw_activation_redirect', false ) ) {
			delete_option( '_pqfw_activation_redirect' );

			if ( ! isset( $_GET['activate-multi'] ) && ( ! empty( $_GET['activate'] ) ) && ( 'true' === $_GET['activate'] ) ) {//phpcs:ignore
				wp_safe_redirect( admin_url( 'admin.php?page=quotify' ) );
			}
		}
	}
}
