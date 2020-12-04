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

		return wp_nonce_url( admin_url( $url ), 'pqfw_admin_nonce_action', '_wpnonce' );

	}

}
