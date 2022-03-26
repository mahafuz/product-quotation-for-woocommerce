<?php
/**
 * Responsible for handling the plugin settings.
 *
 * @since 1.2.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

// TODO: optimize getAll & get_all_settings

/**
 * Manages the options form dashboard.
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */
class Settings {

	/**
	 * Contain default settings.
	 *
	 * @var   array Default settings.
	 * @since 1.0.0
	 */
	protected $default;

	/**
	 * Contain saved settings.
	 *
	 * @var   array Saved settings.
	 * @since 1.0.0
	 */
	protected $saved;

	/**
	 * Contain saved and unsaved settings.
	 *
	 * @var   array All combined settings.
	 * @since 1.0.0
	 */
	private $all;

	/**
	 * Constructor of the class
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'wp_ajax_pqrf_save_settings', [ $this, 'save' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
	}

	/**
	 * Adding a submenu page under the product quotation toplevel menu.
	 *
	 * @return  void
	 * @since   1.0.0
	 */
	public function menu() {
		add_submenu_page(
			'pqfw-entries-page',
			__( 'Settings', 'pqfw' ),
			__( 'Settings', 'pqfw' ),
			'manage_options',
			'pqfw-settings',
			[ $this, 'display_pqfw_settings_page' ],
			null
		);
	}

	/**
	 * Renders settings tabs.
	 *
	 * @since 1.0.0
	 * @return array Settings tabs list.
	 */
	public function tabs() {
		return apply_filters(
			'pqfw_elements_lite_admin_settings_tabs',
			[
				'button'  => [
					'title'      => esc_html__( 'Button', 'powerpack' ),
					'show'       => true,
					'capability' => 'edit_posts',
					'file'       => PQFW_PLUGIN_VIEWS . 'pages/settings/button.php',
					'priority'   => 150,
				],
				'form'    => [
					'title'      => esc_html__( 'Form', 'powerpack' ),
					'show'       => true,
					'capability' => 'edit_posts',
					'file'       => PQFW_PLUGIN_VIEWS . 'pages/settings/form.php',
					'priority'   => 200,
				],
				'email'   => [
					'title'      => esc_html__( 'Email', 'powerpack' ),
					'show'       => true,
					'capability' => 'edit_posts',
					'file'       => PQFW_PLUGIN_VIEWS . 'pages/settings/email.php',
					'priority'   => 300,
				],
				'advance' => [
					'title'      => esc_html__( 'Advance', 'powerpack' ),
					'show'       => true,
					'capability' => 'edit_posts',
					'file'       => PQFW_PLUGIN_VIEWS . 'pages/settings/advance.php',
					'priority'   => 400,
				]
			]
		);
	}

	/**
	 * Returns the form action type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type The action type.
	 * @return string      The admin url with action type.
	 */
	public function getFormAction( $type = '' ) {
		return admin_url( '/admin.php?page=pqfw-settings&tab=' . $type );
	}

	/**
	 * Displays settings tabs.
	 *
	 * @since 1.0.0
	 *
	 * @param string $activeTab The active tab.
	 */
	public function displayTabs( $activeTab ) {
		$sortedTabs = [];

		foreach ( $this->tabs() as $index => $tab ) {
			$tab['key'] = $index;
			$sortedTabs[ $tab['priority'] ] = $tab;
		}

		ksort( $sortedTabs );

		foreach ( $sortedTabs as $tab ) {
			if ( $tab['show'] ) {
				if ( isset( $tab['cap'] ) && ! current_user_can( $tab['capability'] ) ) {
					continue;
				}
				printf(
					'<a href="%s" class="nav-tab%s"><span>%s</span></a>',
					esc_attr( $this->getFormAction( $tab['key'] ) ),
					( $activeTab == $tab['key'] ? ' nav-tab-active' : '' ),
					esc_html( $tab['title'] )
				);
			}
		}
	}

	/**
	 * Displays settings page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $activeTab The active tab.
	 * @return void
	 */
	public function displaySettingsPage( $activeTab ) {
		$tabs = $this->tabs();

		if ( isset( $tabs[ $activeTab ] ) ) {

			if ( ! isset( $tabs[ $activeTab ]['file'] ) || empty( $tabs[ $activeTab ]['file'] ) || ! file_exists( $tabs[ $activeTab ]['file'] ) ) {
				echo esc_html__( 'Setting page file could not be located.', 'powerpack' );
				return;
			}

			$show = ! isset( $tabs[ $activeTab ]['show'] ) ? true : $tabs[ $activeTab ]['show'];
			$cap  = 'manage_options';

			if ( ! empty( $tabs[ $activeTab ]['capability'] ) ) {
				$cap = $tabs[ $activeTab ]['capability'];
			} else {
				$cap = ! is_network_admin() ? 'manage_options' : 'manage_network_plugins';
			}

			if ( ! $show || ! current_user_can( $cap ) ) {
				esc_html_e( 'You do not have permission to view this setting.', 'powerpack' );
				return;
			}

			include $tabs[ $activeTab ]['file'];
		}
	}

	/**
	 * Loading settings page tamplate.
	 *
	 * @since 1.0.0
	 */
	public function display_pqfw_settings_page() {
		$settings = $this->getAll();
		include PQFW_PLUGIN_VIEWS . 'pages/settings/index.php';
	}

	/**
	 * Process and return the saved(wp_options) settings.
	 *
	 * @access  protected
	 * @return  array $settings
	 */
	protected function getAll() {
		$this->default = [
			'pqfw_form_default_design',
			'pqfw_floating_form',
			'pqfw_form_send_mail'
		];

		$this->default = array_fill_keys( $this->default, true );
		$this->saved   = get_option( 'pqfw_settings', $this->default );

		$this->all = wp_parse_args( $this->saved, $this->default );
		return $this->all;
	}

	/**
	 * Enqueue scripts and stuffs for settings page.
	 *
	 * @access  public
	 * @return  void
	 */
	public function assets() {

		$screen = get_current_screen();

		if ( 'product-quotation_page_pqfw-settings' === $screen->id ) {
			wp_enqueue_script(
				'pqfw-options-handler', PQFW_PLUGIN_URL . 'assets/js/pqfw-settings.js',
				[ 'jquery' ], PQFW_PLUGIN_VERSION, true
			);

			wp_localize_script(
				'pqfw-options-handler',
				'PQFW_OBJECT',
				[
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'actions' => [
						'save_settings' => 'pqrf_save_settings'
					]
				]
			);
		}
	}

	/**
	 * Saving settings.
	 *
	 * @access  public
	 * @return  void
	 */
	public function save() {
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'pqfw_settings_form_action' ) ) {
			die( esc_html__( 'Unauthorized Action', 'pqfw' ) );
		}

		$name     = sanitize_text_field( $_REQUEST['name'] );
		$status   = filter_var( $_REQUEST['status'], FILTER_VALIDATE_BOOLEAN );
		$settings = $this->getAll();

		if ( array_key_exists( $name, $settings ) ) {
			$settings[ $name ] = $status;
			update_option( 'pqfw_settings', $settings );

			wp_send_json_success( __( 'Settings Saved', 'pqfw' ) );
		}

		wp_send_json_error( __( 'Error while saving settings', 'pqfw' ) );

		die;
	}

	/**
	 * Get settings by key or get all settings without any key.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Setting key.
	 * @return mixed      Saved settings.
	 */
	public function get( $key = null ) {
		if ( empty( $key ) ) {
			return $this->getAll();
		}

		$settings = $this->getAll();

		if ( isset( $settings[ $key ] ) ) {
			return $settings[ $key ];
		}

		return false;
	}
}