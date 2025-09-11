<?php
/**
 * PQFW class
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.2.0
 */

namespace PQFW {//phpcs:ignore

	// if direct access than exit the file.
	defined( 'ABSPATH' ) || exit;

	/**
	 * The main class of this plugin.
	 *
	 * @since 1.2.0
	 */
	final class PQFW {

		/**
		 * Single instance of the class
		 *
		 * @var \PQFW
		 * @since 1.0.0
		 */
		private static $instance = null;

		/**
		 * Contains plugin migration.
		 *
		 * @var mixed
		 */
		public $migration;

		/**
		 * Container for the quotations
		 *
		 * @var mixed
		 */
		public $quotations;

		/**
		 * Contains helpers methods.
		 *
		 * @var mixed
		 */
		public $helpers;

		/**
		 * Container for the addons.
		 *
		 * @var mixed
		 */
		public $addons;

		/**
		 * Container for the menus.
		 *
		 * @var mixed
		 */
		public $menu;

		/**
		 * Container for the settings.
		 *
		 * @var mixed
		 */
		public $settings;

		/**
		 * Contains the cart.
		 *
		 * @var mixed
		 */
		public $cart;

		/**
		 * Contains the form controls.
		 *
		 * @var mixed
		 */
		public $controlsManager;

		/**
		 * Collect quotation products details.
		 *
		 * @var mixed
		 */
		public $product;

		/**
		 * Responsible for the plugin mail.
		 *
		 * @var mixed
		 */
		public $mailer;
		public $mail; //phpcs:ignore

		/**
		 * Returns single instance of the class
		 *
		 * @return \PQFW
		 * @since 1.0.0
		 */
		public static function instance() {
			if ( null === self::$instance || ! self::$instance instanceof self ) {
				self::$instance = new self();

				self::$instance->init();
			}

			return self::$instance;
		}

		/**
		 * Initialize pqfw
		 *
		 * @since 1.2.0
		 */
		public function init() {
			$this->includes();
			$this->preLoad();
			$this->loader();
		}

		/**
		 * Including the new files with PHP 5.3 style.
		 *
		 * @since 1.2.0
		 *
		 * @return void
		 */
		private function includes() {
			$dependencies = [
				'autoload.php',
				'functions.php',
			];

			foreach ( $dependencies as $path ) {
				if ( ! file_exists( PQFW_PLUGIN_PATH . $path ) ) {
					status_header( 500 );
					wp_die( esc_html__( 'Plugin is missing required dependencies. Please contact support for more information.', 'quotify' ) );
				}

				require PQFW_PLUGIN_PATH . $path;
			}
		}

		/**
		 * Runs before load the plugin.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function preLoad() {
			$this->migration = new \PQFW\Classes\Migration();
			add_action( 'woocommerce_init', [ $this, 'sessionStart' ] );
		}


		/**
		 * Constructor of the class
		 *
		 * @since  1.0.0
		 * @return void
		 */
		private function loader() {
			$this->helpers         = new \PQFW\Classes\Helpers();
			$this->menu            = new \PQFW\Classes\Menu();
			$this->settings        = new \PQFW\Classes\Settings();

			new \PQFW\Ajax();
			new \PQFW\Classes\Assets();

			new \PQFW\Classes\Form_Handler();
			new \PQFW\Classes\Shortcode();

			$this->quotations      = new \PQFW\Quotations();
			$this->addons          = new \PQFW\Addons();

			$this->cart            = new \PQFW\Classes\Cart();
			$this->controlsManager = new \PQFW\Classes\Controls_Manager();
			$this->product         = new \PQFW\Classes\Product();
			$this->mail            = new \PQFW\Classes\Mail();

			new \PQFW\Classes\Form();
			new \PQFW\Classes\Frontend();
			new \PQFW\Classes\Admin();
			\PQFW\Classes\Hooks::init();

			add_action( 'plugin_action_links_' . PQFW_PLUGIN_BASENAME, [ $this, 'addPluginActionLinks' ] );
			add_action( 'admin_init', [ $this, 'redirect' ] );
			add_action( 'quotify/templates/form', [ $this, 'display_form' ] );
		}

		/**
		 * Displays the contact form.
		 *
		 * @return void
		 */
		public function display_form() {
		}

		/**
		 * Add plugin action links
		 *
		 * @since  2.0.1
		 * @param  array $links The links array.
		 * @return array
		 */
		public function addPluginActionLinks( $links ) {
			// return if pro is active.
			$settings = '<a href="' . admin_url( 'admin.php?page=pqfw-product-quotations-settings' ) . '">' . esc_html__( 'Settings', 'quotify' ) . '</a>';
			$help = sprintf(
				'<a href="%s"><span style="color:#f18500; font-weight: bold;">%s</span></a>', admin_url( 'admin.php?page=pqfw-product-quotations-help' ),
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
		 */
		public function redirect() {
			if ( get_option( '_pqfw_activation_redirect', false ) ) {
				delete_option( '_pqfw_activation_redirect' );

				if ( ! isset( $_GET['activate-multi'] ) && ( ! empty( $_GET['activate'] ) ) && ( 'true' === $_GET['activate'] ) ) {
					wp_safe_redirect( admin_url( 'admin.php?page=pqfw-product-quotations' ) );
				}
			}
		}

		/**
		 * Start woocommerce session for users.
		 *
		 * @since 2.0.3
		 */
		public function sessionStart() {
			if ( isset( WC()->session ) ) {
				WC()->session->set_customer_session_cookie( true );
			}
		}
	}

}

namespace {//phpcs:ignore
	// if direct access than exit the file.
	defined( 'ABSPATH' ) || exit;

	/**
	 * Run the plugin after all other plugins.
	 *
	 * @since 1.0.0
	 */
	function pqfw() {//phpcs:ignore
		return \PQFW\PQFW::instance();
	}
}
