<?php
/**
 * PQFW class
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.2.0
 */

namespace PQFW {

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
				'autoload.php'
			];

			foreach ( $dependencies as $path ) {
				if ( ! file_exists( PQFW_PLUGIN_PATH . $path ) ) {
					status_header( 500 );
					wp_die( esc_html__( 'Plugin is missing required dependencies. Please contact support for more information.', 'pqfw' ) );
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
		}


		/**
		 * Constructor of the class
		 *
		 * @since  1.0.0
		 * @return void
		 */
		private function loader() {
			$this->settings        = new \PQFW\Classes\Settings();
			$this->form            = new \PQFW\Classes\Form();
			$this->admin           = new \PQFW\Classes\Admin();
			$this->cart            = new \PQFW\Classes\Cart();
			$this->request         = new \PQFW\Classes\Request();
			$this->form_handler    = new \PQFW\Classes\Form_Handler();
			$this->quotations      = new \PQFW\Classes\Quotations();
			$this->shortcode       = new \PQFW\Classes\Shortcode();
			$this->utils           = new \PQFW\Classes\Utils();
			$this->table           = new \PQFW\Classes\Table();
			$this->frontend        = new \PQFW\Classes\Frontend();
			$this->controlsManager = new \PQFW\Classes\Controls_Manager();
			$this->product         = new \PQFW\Classes\Product();
			$this->mailer          = new \PQFW\Classes\Mailer();
			$this->helpers         = new \PQFW\Classes\Helpers();

			if ( ! function_exists( 'WC' ) ) {
				add_action( 'admin_notices', [ $this, 'woocommerce_not_loaded' ] );
			}
		}

		/**
		 * Return plugin base url with valid nonce.
		 *
		 * @since 1.0.0
		 *
		 * @param  string $url The raw url.
		 * @return string $url with nonce.
		 */
		public function get_url_with_nonce( $url = '' ) {
			if ( empty( $url ) ) {
				$url = '?page=pqfw-entries-page';
			}

			return esc_url( wp_nonce_url( admin_url( $url ), 'pqfw_admin_nonce_action', '_wpnonce' ) );
		}

		/**
		 * Check if a plugin is installed
		 *
		 * @since 1.0.0
		 * @param string $basename The plugin basename.
		 */
		public function is_plugin_installed( $basename ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				include_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

			$installed_plugins = get_plugins();

			return isset( $installed_plugins[ $basename ] );
		}

		/**
		 * Check if woocommerce plugin is activated
		 *
		 * @since v1.0.0
		 */
		public function woocommerce_not_loaded() {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$woocommerce = 'woocommerce/woocommerce.php';

			if ( $this->is_plugin_installed( $woocommerce ) ) {
				$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $woocommerce . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $woocommerce );

				$message     = __( '<strong>Product Quotation For WooCommerce</strong> requires <strong>WooCommerce</strong> plugin to be active. Please activate WooCommerce to continue.', 'pqfw' );
				$button_text = __( 'Activate WooCommerce', 'pqfw' );
			} else {
				$activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' );
				$message        = __( '<strong>Product Quotation For WooCommerce</strong> requires <strong>WooCommerce</strong> plugin to be installed and activated. Please install WooCommerce to continue.', 'pqfw' );
				$button_text    = __( 'Install WooCommerce', 'pqfw' );
			}

			$button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';

			printf( '<div class="error"><p>%1$s</p>%2$s</div>', $message, $button );
		}
	}

}

namespace {
	// if direct access than exit the file.
	defined( 'ABSPATH' ) || exit;

	/**
	 * Run the plugin after all other plugins.
	 *
	 * @since 1.0.0
	 */
	function pqfw() {
		return \PQFW\PQFW::instance();
	}
}