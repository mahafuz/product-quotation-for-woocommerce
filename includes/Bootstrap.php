<?php
/**
 * Bootstrap class
 *
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */

namespace PQFW;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use \PQFW\Classes\Admin;
use \PQFW\Classes\Frontend_Form;
use \PQFW\Classes\Form_Handler;
use \PQFW\Classes\Settings;

class Bootstrap {

	/**
	 * Single instance of the class
	 *
	 * @var \Bootstrap
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Returns single instance of the class
	 *
	 * @return \Bootstrap
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			return self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * PQFW frontend form.
	 *
	 * @var         \Frontend_Form
	 * @access      protected
	 * @since       1.0.0
	 */
	protected $form_frontend;

	/**
	 * PQFW admin page
	 *
	 * @var     \Admin
	 * @access  protected
	 * @since   1.0.0
	 */
	protected $admin;

	/**
	 * PQFW form handler container.
	 *
	 * @var     \Form_Handler
	 * @access  protected
	 * @since   1.0.0
	 */
	protected $form_handler;

	/**
	 * PQFW settings handler container.
	 *
	 * @var     \Form_Handler
	 * @access  protected
	 * @since   1.0.0
	 */
	protected $settings;


	/**
	 * Constructor of the class
	 *
	 * @return \Bootstrap
	 * @since 1.0.0
	 */
	private function __construct() {

		$this->form_frontend = Frontend_Form::instance();
		$this->admin         = Admin::instance();
		$this->form_handler  = Form_Handler::instance();
		$this->settings      = Settings::instance();

		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', array ( $this, 'woocommerce_not_loaded' ) );
		}

	}

	/**
	 * Return plugin base url with valid nonce.
	 *
	 * @param string $url
	 *
	 * @access  public
	 * @return  string  $url with nonce.
	 */
	public static function get_url_with_nonce( $url = '' ) {

		if ( empty( $url ) ) {
			$url = '?page=pqfw-entries-page';
		}

		return esc_url( wp_nonce_url( admin_url( $url ), 'pqfw_admin_nonce_action', '_wpnonce' ) );

	}

	/**
	 * Check if a plugin is installed
	 *
	 * @since 1.0.0
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

			$message = sprintf( __( '%1$sProduct Quotation For WooCommerce%2$s requires %1$sWooCommerce%2$s plugin to be active. Please activate WooCommerce to continue.', 'pqfw' ), "<strong>", "</strong>" );

			$button_text = __( 'Activate WooCommerce', 'pqfw' );
		} else {
			$activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' );

			$message     = sprintf( __( '%1$sProduct Quotation For WooCommerce%2$s requires %1$sWooCommerce%2$s plugin to be installed and activated. Please install WooCommerce to continue.', 'pqfw' ), '<strong>', '</strong>' );
			$button_text = __( 'Install WooCommerce', 'pqfw' );
		}

		$button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';

		printf( '<div class="error"><p>%1$s</p>%2$s</div>', __( $message ), $button );

	}

}
