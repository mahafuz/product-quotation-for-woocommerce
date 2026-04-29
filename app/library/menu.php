<?php
/**
 * Responsible for handling the plugin menus.
 *
 * @since 1.2.0
 * @package Quotify
 */

namespace Quotify\Library;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for managing plugin menu.
 *
 * @since 2.4.0
 */
class Menu {

	/**
	 * Class instance.
	 *
	 * @var \Quotify\Library\Menu
	 */
	private static $instance = null;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return \Quotify\Library\Menu
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Contains the all slugs.
	 *
	 * @var mixed
	 */
	private $container;

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	private function __construct() {
		$this->set();

		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_head', [ $this, 'add_admin_menu_css' ] );
	}

	/**
	 * List of admin menu
	 */
	private function set() {
		$this->container[ QUOTIFY_PLUGIN_SLUG ] = [
			'parent_slug' => QUOTIFY_PLUGIN_SLUG,
			'title'       => __( 'Dashboard', 'quotify' ),
			'capability'  => 'manage_options',
		];

		$this->container[ QUOTIFY_PLUGIN_SLUG . '-settings' ] = [
			'parent_slug' => QUOTIFY_PLUGIN_SLUG,
			'title'       => __( 'Settings', 'quotify' ),
			'capability'  => 'manage_options',
		];

		$this->container[ QUOTIFY_PLUGIN_SLUG . '-addons' ] = [
			'parent_slug' => QUOTIFY_PLUGIN_SLUG,
			'title'       => __( 'Add-ons', 'quotify' ),
			'capability'  => 'manage_options',
		];

		//phpcs:disable
		// $this->container[ QUOTIFY_PLUGIN_SLUG . '-tools' ]    = [
		// 	'parent_slug' => QUOTIFY_PLUGIN_SLUG,
		// 	'title'      => __( 'Tools', 'quotify' ),
		// 	'capability' => 'manage_options',
		// ];
		//phpcs:enable

		$this->container[ QUOTIFY_PLUGIN_SLUG . '-help' ] = [
			'parent_slug' => QUOTIFY_PLUGIN_SLUG,
			'title'       => __( 'Help', 'quotify' ),
			'capability'  => 'manage_options',
		];
	}

	/**
	 * Get the menu list
	 *
	 * @return array
	 */
	public function get() {
		return apply_filters( 'pqfw/admin_menu_list', $this->container );
	}

	/**
	 * The plugin logo.
	 *
	 * @return string
	 */
	public static function get_plugin_logo() {
		return QUOTIFY_PLUGIN_ROOT_URI . 'assets/images/logo.png';
	}

	/**
	 * Get the menu slugs only.
	 *
	 * @return array The menu slugs.
	 */
	public function get_slugs() {
		return array_keys( $this->container );
	}

	/**
	 * Add admin menu page
	 *
	 * @return void
	 */
	public function admin_menu() {
		$icon_url   = $this->get_toplevel_menu_icon_url();
		$page_title = $this->get_toplevel_menu_title();

		add_menu_page( $page_title, $page_title, 'manage_options', QUOTIFY_PLUGIN_SLUG, [ $this, 'load_main_template' ], $icon_url, 2 );

		foreach ( $this->get() as $item_key => $item ) {
			add_submenu_page( $item['parent_slug'], $item['title'], $item['title'], $item['capability'], $item_key, [ $this, 'load_main_template' ] );
		}
	}

	/**
	 * Loads the admin app root.
	 *
	 * @return void
	 */
	public function load_main_template() {
		$preloader_html = \Quotify\Library\Helper::get_preloader();
		echo '<div id="quotify-backend-dashboard" class="quotify-backend-dashboard">
		' . wp_kses_post( $preloader_html ) . '
		</div>';
	}

	/**
	 * Returns the admin menu title.
	 *
	 * @return string
	 */
	public static function get_toplevel_menu_title() {
		return apply_filters( 'pqfw/admin/toplevel_menu_title', __( 'Quotify', 'quotify' ) );
	}

	/**
	 * Get the menu icon url.
	 *
	 * @return string
	 */
	public static function get_toplevel_menu_icon_url() {
		if ( isset( $_GET['page'] ) && 'pqfw' === sanitize_key( $_GET['page'] ) ) {//phpcs:ignore
			$icon_url = 'data:image/svg+xml;base64, ' . base64_encode( file_get_contents( QUOTIFY_PLUGIN_ASSETS_DIR . 'images/logo-small.svg' ) );
			return apply_filters( 'pqfw/admin/toplevel_active_menu_icon', $icon_url );
		}

		$icon_url = 'data:image/svg+xml;base64, ' . base64_encode( file_get_contents( QUOTIFY_PLUGIN_ASSETS_DIR . 'images/logo-small.svg' ) );

		return apply_filters( 'pqfw/admin/toplevel_inactive_menu_icon', $icon_url );
	}

	/**
	 * Get plugin logo url.
	 *
	 * @return string
	 */
	public static function get_logo_url() {
		return apply_filters( 'pqfw/admin/logo_url', QUOTIFY_PLUGIN_ASSETS_URI . 'images/logo.svg' );
	}

	/**
	 * Loads admin app page css tweaks
	 *
	 * @return void
	 */
	public function add_admin_menu_css() {
		echo '<style>
			#adminmenu li.toplevel_page_pqfw-product-quotation a.toplevel_page_pqfw-product-quotation > .wp-menu-image { 
				display: flex;
				justify-content: center;
				align-items: center;
			}
			#adminmenu li.toplevel_page_pqfw-product-quotation a.toplevel_page_pqfw-product-quotation > .wp-menu-image img {
				max-width: 20px;
				height: auto;
				padding: 0 !important;
			}
		</style>';
	}
}
