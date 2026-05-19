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
	 * @var \Quotify
	 * @since 2.5.0
	 */
	private static $instance = null;

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
	 * @since 2.5.0
	 * @return \Quotify\Library\Menu
	 */
	public function menu() {
		return \Quotify\Library\Menu::init();
	}

	/**
	 * Quotify assets instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Assets
	 */
	public function assets() {
		return \Quotify\Assets::init();
	}

	/**
	 * Quotify ajax instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Ajax
	 */
	private function ajax() {
		return \Quotify\Ajax::init();
	}

	/**
	 * Quotify settings instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Library\Settings
	 */
	public function settings() {
		return \Quotify\Library\Settings::init();
	}

	/**
	 * Quotify addons instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Internals\Addons|void
	 */
	public function addons() {}

	/**
	 * Quotify quotations instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Internals\Quotations
	 */
	public function quotations() {
		return \Quotify\Internals\Quotations::init();
	}

	/**
	 * Quotify frontend instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Internals\Frontend
	 */
	public function frontend() {
		return \Quotify\Internals\Frontend::init();
	}

	/**
	 * Quotify buttons instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Internals\Buttons
	 */
	public function buttons() {
		return \Quotify\Internals\Buttons::init();
	}

	/**
	 * Quotify admin instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Admin
	 */
	public function admin() {
		return \Quotify\Admin::init();
	}

	/**
	 * Quotify cart instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Internals\Cart
	 */
	public function cart() {
		return \Quotify\Internals\Cart::init();
	}

	/**
	 * Quotify forms instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Forms
	 */
	public function forms() {
		return \Quotify\Forms::init();
	}

	/**
	 * Quotify form controls instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Forms\Controls
	 */
	public function controls() {
		return \Quotify\Forms\Controls::init();
	}

	/**
	 * Quotify shortcodes instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Shortcodes
	 */
	public function shortcodes() {
		return \Quotify\Shortcodes::init();
	}

	/**
	 * Quotify mail instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Library\Mail
	 */
	public function mail() {
		return \Quotify\Library\Mail::init();
	}

	/**
	 * Quotify hooks instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Internals\Hooks
	 */
	public function hooks() {
		return \Quotify\Internals\Hooks::init();
	}

	/**
	 * Quotify session instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Library\Session
	 */
	public function sessions() {
		return new \Quotify\Library\Session();
	}

	/**
	 * Quotify product instance.
	 *
	 * @since 2.5.0
	 * @return \Quotify\Internals\Product
	 */
	public function product() {
		return \Quotify\Internals\Product::init();
	}

	/**
	 * Quotify template manager instance.
	 *
	 * @since 2.6.0
	 * @return \Quotify\Library\Template_Manager
	 */
	public function templates() {
		return \Quotify\Library\Template_Manager::init();
	}
}
