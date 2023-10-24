<?php
/**
 * Responsible for handling the plugin admin panel.
 *
 * @since 1.0.0
 * @package PQFW
 */

namespace PQFW\Admin;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @since   1.0.0
 * @package PQFW
 */
class Menu {

	public static function init() {
		$self = new self();
		add_action( 'admin_menu', array( $self, 'admin_menu' ) );
		add_action( 'admin_head', array( $self, 'add_admin_menu_css' ) );
	}
	
	public function admin_menu() {
		add_menu_page(
			'Quote Packer',
			'Quote Packer',
			'manage_options',
			QUOTE_PACKER_PLUGIN_SLUG,
			[ $this, 'load_main_template' ],
			'',
			2
		);
	}

	public function load_main_template() {
		$preloader_html = apply_filters( 'quote_packer/preloader', '' );
		echo '<div id="quote-packer-wrap" class="quote-packer-wrap">' . wp_kses_post( $preloader_html ) . '</div>';
	}

	function add_admin_menu_css() {
		echo '<style>
			#adminmenu li.toplevel_page_academy a.toplevel_page_academy > .wp-menu-image { 
				display: flex;
				justify-content: center;
				align-items: center;
			}
			#adminmenu li.toplevel_page_academy a.toplevel_page_academy > .wp-menu-image img {
				max-width: 20px;
				height: auto;
				padding: 0 !important;
			}
		</style>';
	}
}