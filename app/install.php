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
	 * @var \Quotify\Install
	 */
	private static $instance;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return \Quotify\Install
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
		add_action( 'plugin_action_links_' . QUOTIFY_PLUGIN_BASENAME, [ $this, 'add_plugin_links' ] );
		add_action( 'admin_init', [ $this, 'redirect' ] );
		add_option( '_pqfw_activation_redirect', true );

		// Init Appsero.
		$this->appsero_init();

		add_action( 'woocommerce_init', [ $this, 'start' ] );

		register_activation_hook( QUOTIFY_PLUGIN_FILE, [ $this, 'activate' ] );
		register_activation_hook( QUOTIFY_PLUGIN_FILE, [ $this, 'deactivate' ] );
	}


	/**
	 * Initialize the plugin tracker
	 *
	 * @return void
	 */
	public function appsero_init() {
		if ( ! class_exists( 'Appsero\Client' ) ) {
			require_once QUOTIFY_PLUGIN_ROOT_PATH . '/appsero/src/Client.php';
		}

		$client = new \Appsero\Client(
			'e806fe7d-f314-425d-8be4-9f62fdaf71cf',
			'Product Quotation &#8211; Product Quotation For WooCommerce',
			QUOTIFY_PLUGIN_FILE
		);

		$client->insights()->init();
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
	 * Plugin database migration.
	 *
	 * @return \Quotify\Database
	 */
	private function migration() {
		return new \Quotify\Database\Migration();
	}

	/**
	 * Run on the plugin activation.
	 *
	 * @since 2.5.0
	 * @return void
	 */
	public function activate() {
		$this->migration();
	}

	/**
	 * Run on the plugin deactivation.
	 *
	 * @since 2.5.0
	 * @return void
	 */
	public function deactivate() {
	}

	/**
	 * Add plugin action links
	 *
	 * @since  2.0.1
	 * @param  array $links The links array.
	 * @return array The actions link.
	 */
	public function add_plugin_links( $links ) {
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

Install::init();
