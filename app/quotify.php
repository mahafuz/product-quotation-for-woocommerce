<?php
/**
 * Quotify class
 *
 * @author      Mahafuz
 * @package     Quotify
 * @since       1.2.0
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * The main class of this plugin.
 *
 * @since 1.2.0
 */
final class Quotify {
	/**
	 * Quotify version.
	 *
	 * @var string
	 */
	public $version = '2.5.0';

	/**
	 * Single instance of the class
	 *
	 * @var Quotify
	 * @since 2.5.0
	 */
	private static $instance = null;

	/**
	 * Session instance.
	 *
	 * @var WC_Session|WC_Session_Handler
	 */
	private $session = null;

	/**
	 * Query instance.
	 *
	 * @var Quotify\Query
	 */
	private $query = null;

	/**
	 * Contains helpers methods.
	 *
	 * @var mixed
	 */
	private $helpers;

	/**
	 * Product factory instance.
	 *
	 * @var Quotify\Factory
	 */
	private $product_factory = null;

	/**
	 * Cart instance.
	 *
	 * @var Quotify\Cart
	 */
	private $cart = null;

	/**
	 * Container for the addons.
	 *
	 * @var Quotify\Addons
	 */
	private $addons = null;

	/**
	 * Container for the menus.
	 *
	 * @var Quotify\Menu
	 */
	private $menu = null;

	/**
	 * Container for the settings.
	 *
	 * @var Quotify\Settings
	 */
	private $settings;

	/**
	 * Responsible for the plugin mail.
	 *
	 * @var mixed
	 */
	private $mailer;
	private $mail; //phpcs:ignore

	/**
	 * Returns single instance of the class
	 *
	 * @return \Quotify
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( null === self::$instance || ! self::$instance instanceof self ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize pqfw
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'load_text_domain', [ $this, 'load_textdomain' ] );
		add_action( 'wp_loaded', [ $this, 'loader' ] );
	}

	/**
	 * Load Plugin Textdomain.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'quotify', false, QUOTIFY_PLUGIN_ROOT_PATH . '/languages' );
	}

	/**
	 * Runs on the plugin loads.
	 *
	 * @return void
	 */
	public function on_plugin_loaded() {
		do_action( 'quotify_plugins_loaded' );
	}

	/**
	 * Including the new files with PHP 5.3 style.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	private function define_constants() {
		define( 'QUOTIFY_ABSPATH', dirname( QUOTIFY_PLUGIN_FILE ) . '/' );
		define( 'QUOTIFY_PLUGIN_VERSION', $this->version );

		define( 'QUOTIFY_PLUGIN_SLUG', 'quotify' );

		define( 'QUOTIFY_PLUGIN_ROOT_URI', plugins_url( '/', QUOTIFY_PLUGIN_FILE ) );

		define( 'QUOTIFY_PLUGIN_ASSETS_URI', ( QUOTIFY_PLUGIN_ROOT_URI . 'assets' ) . '/' );
		define( 'QUOTIFY_PLUGIN_ASSETS_DIR', ( QUOTIFY_PLUGIN_ROOT_PATH . 'assets' ) . '/' );

		define( 'QUOTIFY_ADDONS_DIR_PATH', ( QUOTIFY_PLUGIN_ROOT_PATH . 'addons' ) . '/' );
		define( 'QUOTIFY_ADDONS_DIR_URI', ( QUOTIFY_PLUGIN_ROOT_PATH . 'addons' ) . '/' );

		define( 'QUOTIFY_PLUGIN_LANGUAGES_PATH', ( QUOTIFY_PLUGIN_ROOT_PATH . 'languages' ) . '/' );
		define( 'QUOTIFY_PLUGIN_VIEWS', ( QUOTIFY_PLUGIN_ROOT_PATH . 'templates' ) . '/' );
	}

	/**
	 * Constructor of the class
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function loader() {
		$this->define_constants();
		$this->sessions();
		$this->ajax();
		$this->admin();
		$this->menu();
		$this->assets();
		$this->addons();

		$this->buttons();
		$this->forms();
		$this->shortcodes();
		$this->mail();
		$this->hooks();
		$this->frontend();
	}

	/**
	 * Quotify menu instance.
	 *
	 * @2.5.0
	 * @var Quotify\Menu
	 */
	public function menu() {
		return \Quotify\Library\Menu::init();
	}

	/**
	 * Quotify menu instance.
	 *
	 * @2.5.0
	 * @var Quotify\Menu
	 */
	public function assets() {
		return \Quotify\Assets::init();
	}

	/**
	 * Quotify ajax instance.
	 *
	 * @2.5.0
	 * @var Quotify\Ajax
	 */
	private function ajax() {
		return \Quotify\Ajax::init();
	}

	/**
	 * Quotify settings instance.
	 *
	 * @2.5.0
	 * @var Quotify\Settings
	 */
	public function settings() {
		return \Quotify\Library\Settings::init();
	}

	/**
	 * Quotify settings instance.
	 *
	 * @2.5.0
	 * @var Quotify\Internals\Addons
	 */
	public function addons() {
		// return \Quotify\Internals\Addons::init();
	}

	/**
	 * Quotify settings instance.
	 *
	 * @2.5.0
	 * @var Quotify\Internals\Quotations
	 */
	public function quotations() {
		return \Quotify\Internals\Quotations::init();
	}

	/**
	 * Quotify frontend instance.
	 *
	 * @2.5.0
	 * @var Quotify\Internals\Frontend
	 */
	public function frontend() {
		return Quotify\Internals\Frontend::init();
	}

	/**
	 * Quotify frontend instance.
	 *
	 * @2.5.0
	 * @var Quotify\Internals\Button
	 */
	public function buttons() {
		return Quotify\Internals\Buttons::init();
	}

	/**
	 * Quotify frontend instance.
	 *
	 * @2.5.0
	 * @var Quotify\Internals\Admin
	 */
	public function admin() {
		return \Quotify\Admin::init();
	}

	/**
	 * Quotify cart instance.
	 *
	 * @2.5.0
	 * @var Quotify\Cart
	 */
	public function cart() {
		return \Quotify\Internals\Cart::init();
	}

	/**
	 * Quotify forms instance.
	 *
	 * @2.5.0
	 * @var Quotify\Forms
	 */
	public function forms() {
		return Quotify\Forms::init();
	}

	/**
	 * Quotify forms instance.
	 *
	 * @2.5.0
	 * @var Quotify\Forms\Controls
	 */
	public function controls() {
		return Quotify\Forms\Controls::init();
	}

	/**
	 * Quotify shortcodes instance.
	 *
	 * @2.5.0
	 * @var Quotify\Forms\Controls
	 */
	public function shortcodes() {
		return \Quotify\Shortcodes::init();
	}

	/**
	 * Quotify shortcodes instance.
	 *
	 * @2.5.0
	 * @var Quotify\Forms\Controls
	 */
	public function mail() {
		return \Quotify\Library\Mail::init();
	}

	/**
	 * Quotify shortcodes instance.
	 *
	 * @2.5.0
	 * @var Quotify\Internals\Hooks
	 */
	public function hooks() {
		return \Quotify\Internals\Hooks::init();
	}

	/**
	 * Quotify shortcodes instance.
	 *
	 * @2.5.0
	 * @var Quotify\Internals\Hooks
	 */
	public function sessions() {
		return \Quotify\Library\Session::init();
	}

	/**
	 * Quotify shortcodes instance.
	 *
	 * @2.5.0
	 * @var Quotify\Internals\Product
	 */
	public function product() {
		return \Quotify\Internals\Product::init();
	}
}
