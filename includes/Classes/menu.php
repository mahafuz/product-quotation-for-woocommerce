<?php
/**
 * Responsible for handling the plugin menus.
 *
 * @since 1.2.0
 * @package PQFW
 */

namespace PQFW\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Responsible for managing plugin menu.
 *
 * @since 2.4.0
 */
class Menu {

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
	public function __construct() {
		$this->container = $this->getList();

		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_head', [ $this, 'add_admin_menu_css' ] );
	}

	/**
	 * List of admin menu
	 */
	public function getList() {
		$this->container[ PQFW_PLUGIN_SLUG ] = [
			'parent_slug' => PQFW_PLUGIN_SLUG,
			'title'      => __( 'Dashboard', 'pqfw' ),
			'capability' => 'manage_options',
		];

		$this->container[ PQFW_PLUGIN_SLUG . '-settings' ]    = [
			'parent_slug' => PQFW_PLUGIN_SLUG,
			'title'      => __( 'Settings', 'pqfw' ),
			'capability' => 'manage_options',
		];

		$this->container[ PQFW_PLUGIN_SLUG . '-addons' ]    = [
			'parent_slug' => PQFW_PLUGIN_SLUG,
			'title'      => __( 'Add-ons', 'pqfw' ),
			'capability' => 'manage_options',
		];

		// TODO: coming on the later version.
		//phpcs:disable
		// $this->container[ PQFW_PLUGIN_SLUG . '-tools' ]    = [
		// 	'parent_slug' => PQFW_PLUGIN_SLUG,
		// 	'title'      => __( 'Tools', 'pqfw' ),
		// 	'capability' => 'manage_options',
		// ];
		//phpcs:enable

		$this->container[ PQFW_PLUGIN_SLUG . '-help' ]    = [
			'parent_slug' => PQFW_PLUGIN_SLUG,
			'title'      => __( 'Help', 'pqfw' ),
			'capability' => 'manage_options',
		];

		return apply_filters( 'pqfw/admin_menu_list', $this->container );
	}

	/**
	 * Get the menu slugs only.
	 *
	 * @return array The menu slugs.
	 */
	public function getSlugs() {
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

		add_menu_page( $page_title, $page_title, 'manage_options', PQFW_PLUGIN_SLUG, [ $this, 'load_main_template' ], $icon_url, 2 );

		foreach ( $this->getList() as $item_key => $item ) {
			add_submenu_page( $item['parent_slug'], $item['title'], $item['title'], $item['capability'], $item_key, [ $this, 'load_main_template' ] );
		}
	}

	/**
	 * Loads the admin app root.
	 *
	 * @return void
	 */
	public function load_main_template() {
		$preloader_html = apply_filters( 'pqfw/preloader', pqfwGetPreLoader() );
		echo '<div id="pqfw-backend-dashboard" class="quotify-backend-dashboard">' . wp_kses_post( $preloader_html ) . '</div>';
	}

	/**
	 * Returns the admin menu title.
	 *
	 * @return string
	 */
	public static function get_toplevel_menu_title() {
		return apply_filters( 'pqfw/admin/toplevel_menu_title', __( 'Quotations', 'pqfw' ) );
	}

	/**
	 * Get the menu icon url.
	 *
	 * @return string
	 */
	public static function get_toplevel_menu_icon_url() {
		if ( isset( $_GET['page'] ) && 'pqfw' === $_GET['page'] ) {
			$icon_url = 'data:image/svg+xml;base64, ' . base64_encode( file_get_contents( PQFW_PLUGIN_ASSETS_DIR . 'images/docs.svg' ) );
			return apply_filters( 'pqfw/admin/toplevel_active_menu_icon', $icon_url );
		}
		$icon_url = 'data:image/svg+xml;base64, ' . base64_encode( file_get_contents( PQFW_PLUGIN_ASSETS_DIR . 'images/docs.svg' ) );
		return apply_filters( 'pqfw/admin/toplevel_inactive_menu_icon', $icon_url );
	}

	/**
	 * Get plugin logo url.
	 *
	 * @return string
	 */
	public static function get_logo_url() {
		return apply_filters( 'pqfw/admin/logo_url', PQFW_PLUGIN_ASSETS . 'images/logo.svg' );
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
