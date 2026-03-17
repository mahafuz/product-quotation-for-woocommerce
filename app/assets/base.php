<?php
/**
 * Responsible for managing the plugin base scripts.
 *
 * @since   1.0.0
 * @package Quotify
 */

namespace Quotify\Assets;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

use Quotify\Library\Helper;
use Quotify\Library\Menu;

/**
 * Responsible for managing the plugin base scripts.
 *
 * @since   1.0.0
 * @package Quotify
 */
class Base {

	/**
	 * Get the localizable data.
	 *
	 * @return array
	 */
	public function get_scripts_data() {
		return [
			'nonce'                  => [
				'rest' => wp_create_nonce( 'wp_rest' ),
				'ajax' => wp_create_nonce( 'quotify_ajax' ),
				'cart' => wp_create_nonce( 'quotify_cart' ),
			],
			'rest_url'               => esc_url_raw( rest_url() ),
			'namespace'              => QUOTIFY_PLUGIN_ROOT_URI . '/v1/',
			'ajaxurl'                => esc_url( admin_url( 'admin-ajax.php' ) ),
			'site_url'               => site_url(),
			'route_path'             => wp_parse_url( admin_url(), PHP_URL_PATH ),
			'menu'                   => quotify()->menu()->get(),
			'woocommerce_is_active'  => Helper::is_plugin_active( 'woocommerce/woocommerce.php' ),
			'woocommerce_notice'     => Helper::woocommerce_notice(),
			'current_user_id'        => get_current_user_id(),
			'is_rtl'                 => is_rtl(),
			'current_user_can'       => [
				'manage_options'    => current_user_can( 'manage_options' ),
				'manage_categories' => current_user_can( 'manage_categories' ),
			],
			'plugin_logo'            => Menu::get_plugin_logo(),
			'toplevel_menu_icon_url' => Menu::get_toplevel_menu_icon_url(),
			'toplevel_menu_title'    => Menu::get_toplevel_menu_title(),
			'logo_url'               => Menu::get_logo_url(),
			'version'                => QUOTIFY_PLUGIN_VERSION,
		];
	}

	/**
	 * Get isolated gutenberg settings.
	 *
	 * @return array
	 */
	public function get_isolated_gutenberg_settings() {
		global $post;

		$align_wide = get_theme_support( 'align-wide' );

		$max_upload_size = wp_max_upload_size();
		if ( ! $max_upload_size ) {
			$max_upload_size = 0;
		}

		$image_size_names = apply_filters(
			'image_size_names_choose',
			[
				'thumbnail' => __( 'Thumbnail', 'quotify' ),
				'medium'    => __( 'Medium', 'quotify' ),
				'large'     => __( 'Large', 'quotify' ),
				'full'      => __( 'Full Size', 'quotify' ),
			]
		);

		$available_image_sizes = [];
		foreach ( $image_size_names as $image_size_slug => $image_size_name ) {
			$available_image_sizes[] = [
				'slug' => $image_size_slug,
				'name' => $image_size_name,
			];
		}

		$body_placeholder = apply_filters( 'write_your_story', __( 'Start writing or type / to choose a block', 'quotify' ), $post );
		$allowed_block_types = apply_filters( 'allowed_block_types', true, $post );

		return [
			'editor'               => [
				'alignWide'                              => $align_wide,
				'disableCustomColors'                    => true,
				'disableCustomFontSizes'                 => true,
				'disablePostFormats'                     => ! current_theme_supports( 'post-formats' ),
				/** This filter is documented in wp-admin/edit-form-advanced.php */
				'titlePlaceholder'       => __( 'Add title', 'quotify' ),
				'bodyPlaceholder'        => $body_placeholder,
				'isRTL'                  => is_rtl(),
				'autosaveInterval'       => AUTOSAVE_INTERVAL,
				'maxUploadFileSize'      => $max_upload_size,
				'allowedMimeTypes'       => [],
				'styles'                 => function_exists( 'get_block_editor_theme_styles' ) ? get_block_editor_theme_styles() : [],
				'imageSizes'             => $available_image_sizes,
				'imageDefaultSize'      => 'large',
				'imageEditing'          => true,
				'richEditingEnabled'     => user_can_richedit(),
				'codeEditingEnabled'     => false,
				'allowedBlockTypes'      => $allowed_block_types,
				'__experimentalCanUserUseUnfilteredHTML' => false,
				'__experimentalBlockPatterns' => [],
				'__experimentalBlockPatternCategories' => [],
				'availableTemplates'                   => [],
				'postLock'                             => false,
				'supportsLayout'                       => false,
				'enableCustomFields'                   => false,
				'generateAnchors'                      => true,
				'canLockBlocks'                        => true,
			],
			'iso'                  => [
				'blocks'      => [
					'allowBlocks' => [
						'core/paragraph',
						'core/image',
						'core/heading',
						'core/separator',
						'core/spacer',
						'core/columns',
						'core/column',
						'core/quote',
						'core/code',
						'core/shortcode',
						'core/group',
						'core/list',
						'core/list-item',
						'core/html',
						'core/audio',
						'core/freeform',
					],
				],
				'moreMenu'    => [
					'topToolbar' => true,
				],
				'sidebar'     => [
					'inserter'  => true,
					'inspector' => false,
				],
				'toolbar'     => [
					'navigation' => true,
					'inspector'  => false,
				],
				'allowEmbeds' => [],
			],
			'saveTextarea'         => '',
			'container'            => '',
			'editorType'           => 'core',
			'allowUrlEmbed'        => true,
			'pastePlainText'       => true,
			'replaceParagraphCode' => false,
			'pluginsUrl'           => plugins_url( '', __DIR__ ),
			'version'              => '1.0.0',
		];
	}

	/**
	 * Get frontend scripts data.
	 *
	 * @return array
	 */
	public function get_frontend_scripts_data() {
		$site_url = site_url();
		$args = [];

		$base_data = [
			'ajaxurl'           => admin_url( 'admin-ajax.php' ),
			'pages'             => Helper::getPages(),
			'cart'              => [
				'id'  => Helper::getCart(),
				'url' => Helper::getCart( 'url' ),
			],
			'route_path'        => wp_parse_url( $site_url, PHP_URL_PATH ),
			'current_permalink' => esc_url( get_permalink() ),
		];

		$scripts_data = $this->get_scripts_data();

		// Merge nonce arrays to preserve nested structure
		if ( isset( $scripts_data['nonce'] ) && is_array( $scripts_data['nonce'] ) ) {
			$scripts_data['nonce']['frontend'] = wp_create_nonce( 'pqfw-frontend' );
		}

		return apply_filters(
			'pqfw/assets/frontend_scripts_data',
			array_merge(
				$base_data,
				$scripts_data,
				$args
			)
		);
	}

	/**
	 * Returns the localizable data.
	 *
	 * @return array
	 */
	public function get_backend_scripts_data() {
		$args = [
			'plugin_root_url'  => QUOTIFY_PLUGIN_ROOT_URI,
			'plugin_root_path' => QUOTIFY_PLUGIN_ROOT_PATH,
			'admin_url'        => admin_url(),
			'is_admin'         => is_admin(),
			'addons'           => \Quotify\Internals\Addons::get(),
			'editor_settings'  => $this->get_isolated_gutenberg_settings(),
		];

		return apply_filters(
			'pqfw/assets/backend_scripts_data',
			array_merge(
				[
					'ajaxurl'  => admin_url( 'admin-ajax.php' ),
					'settings' => quotify()->settings()->getAll(),
					'nonce'    => wp_create_nonce( 'pqfw-app-ui' ),
					'pages'    => Helper::getPages(),
					'cart'     => [
						'id'  => Helper::getCart(),
						'url' => Helper::getCart( 'url' ),
					],
				],
				$this->get_scripts_data(),
				$args
			)
		);
	}

	/**
	 * Loads the block editor scripts.
	 *
	 * @return void
	 */
	public function load_block_editor_scripts() {
		// Gutenberg scripts.
		wp_enqueue_script( 'wp-block-library' );
		wp_enqueue_script( 'wp-format-library' );
		wp_enqueue_script( 'wp-editor' );

		// Gutenberg styles.
		wp_enqueue_style( 'wp-edit-post' );
		wp_enqueue_style( 'wp-format-library' );

		wp_tinymce_inline_scripts();
		wp_enqueue_editor();
	}

	/**
	 * Builds the webfont url.
	 *
	 * @param string $font The font parameters for the url.
	 * @return string
	 */
	public function web_fonts_url( $font ) {
		$font_url = '';
		if ( 'off' !== _x( 'on', 'Google font: on or off', 'quotify' ) ) {
			$font_url = add_query_arg( 'family', rawurlencode( $font ), '//fonts.googleapis.com/css' );
		}
		return $font_url;
	}
}
