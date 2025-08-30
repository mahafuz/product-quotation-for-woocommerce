<?php

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @since   2.0.3
 * @package PQFW
 */
class Assets extends \PQFW\Classes\Script_Base {

	/**
	 * Constructor of the class
	 *
	 * @since  2.0.3
	 * @return void
	 */
	public function __construct() {
		//enqueue backend scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueueAdminScripts' ] );
	
		// enqueue frontend scripts.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueFrontendScripts' ] );
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @since 2.0.3
	 */
	public function enqueueAdminScripts() {
		wp_enqueue_style( 'pqfw-admin-style', PQFW_PLUGIN_ASSETS . 'build/backend.css', array( 'wp-components' ), filemtime( PQFW_PLUGIN_ASSETS . 'build/backend.css' ), 'all' );

		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		$this->load_block_editor_scripts();

		// js
		$dependencies = include_once PQFW_PLUGIN_ASSETS_DIR . sprintf( 'build/backend.%s.asset.php', PQFW_PLUGIN_VERSION );
		wp_enqueue_style( 'pqfw-web-font', $this->web_fonts_url( 'DM Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700|Inter:wght@300;400;500;600;700;800;900|Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap' ), array(), $dependencies['version'] );
		wp_enqueue_script(
			'pqfw-admin-scripts',
			PQFW_PLUGIN_ASSETS . sprintf( 'build/backend.%s.js', PQFW_PLUGIN_VERSION ),
			$dependencies['dependencies'],
			$dependencies['version'],
			true
		);

		wp_localize_script( 'pqfw-admin-scripts', 'PqfwGlobal', $this->get_backend_scripts_data() );
		wp_set_script_translations( 'pqfw-admin-scripts', 'pqfw', PQFW_PLUGIN_ROOT_DIR_PATH . 'languages' );
	}


	/**
	 * Enqueue frontend scripts.
	 *
	 * @since 2.0.3
	 */
	public function enqueueFrontendScripts() {
		wp_enqueue_script(
			'pqfw-quotation-button',
			PQFW_PLUGIN_ASSETS . sprintf( 'build/button.%s.js', PQFW_PLUGIN_VERSION ),
			[ 'wp-util' ],
			PQFW_PLUGIN_VERSION,
			true
		);


		wp_enqueue_script(
			'pqfw-quotation-cart',
			PQFW_PLUGIN_ASSETS . sprintf( 'build/cart.%s.js', PQFW_PLUGIN_VERSION ),
			[ 'wp-util', 'jquery' ],
			PQFW_PLUGIN_VERSION,
			true
		);

		wp_localize_script( 'pqfw-quotation-cart', 'PqfwGlobal', $this->get_frontend_scripts_data() );
	}

	/**
	 * Print styles for the elementor editor.
	 *
	 * @since 2.0.3
	 */
	public function elmentorEditorStyle() {
		?>
		<style>
			body #elementor-panel-elements-wrapper .icon .pqfw-quote-cart-icon {
				background: url('https://ps.w.org/product-quotation-for-woocommerce/assets/icon-128x128.png?rev=2445332') no-repeat center center;
				background-size: contain;
				height: 29px;
				display: block;
			}
		</style>
		<?php
	}

}