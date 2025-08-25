<?php

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @since   1.0.0
 * @package PQFW
 */
class Script_Base {
	public function get_scripts_data() {

		return array(
			'nonce'                 => wp_create_nonce( 'wp_rest' ),
			'pqfw_nonce'         => wp_create_nonce( 'pqfw_nonce' ),
			'rest_url'              => esc_url_raw( rest_url() ),
			'namespace'             => PQFW_PLUGIN_ROOT_URI . '/v1/',
			'plugin_root_url'       => PQFW_PLUGIN_ROOT_URI,
			'plugin_root_path'      => PQFW_PLUGIN_ROOT_DIR_PATH,
			'ajaxurl'               => esc_url( admin_url( 'admin-ajax.php' ) ),
			'admin_url'             => admin_url(),
			'site_url'              => site_url(),
			'route_path'            => wp_parse_url( admin_url(), PHP_URL_PATH ),
			'menu'                  => wp_json_encode( Menu::getList() ),
			'woocommerce_is_active' => Helpers::isWoocommerceActive(),
			'current_user_id'       => get_current_user_id(),
			'is_rtl'                => is_rtl(),
			'is_admin'              => is_admin(),
			'addons'                => \PQFW\Addons::get_saved(),
			'current_user_can'      => [
				'manage_options'            => current_user_can( 'manage_options' ),
				'manage_academy_instructor' => current_user_can( 'manage_academy_instructor' ),
				'publish_academy_courses'   => current_user_can( 'publish_academy_courses' ),
				'manage_categories'         => current_user_can( 'manage_categories' ),
			],
			'editor_settings'        => $this->get_isolated_gutenberg_settings(),
			'toplevel_menu_icon_url' => Menu::get_toplevel_menu_icon_url(),
			'toplevel_menu_title'    => Menu::get_toplevel_menu_title(),
			'logo_url'               => Menu::get_logo_url(),
			'version'                => PQFW_PLUGIN_VERSION
		);
	}


	public function get_isolated_gutenberg_settings() {
		global $post;

		$align_wide    = get_theme_support( 'align-wide' );

		$max_upload_size = wp_max_upload_size();
		if ( ! $max_upload_size ) {
			$max_upload_size = 0;
		}

		$image_size_names = apply_filters(
			'image_size_names_choose',
			array(
				'thumbnail' => __( 'Thumbnail', 'pqfw' ),
				'medium'    => __( 'Medium', 'pqfw' ),
				'large'     => __( 'Large', 'pqfw' ),
				'full'      => __( 'Full Size', 'pqfw' ),
			)
		);

		$available_image_sizes = array();
		foreach ( $image_size_names as $image_size_slug => $image_size_name ) {
			$available_image_sizes[] = array(
				'slug' => $image_size_slug,
				'name' => $image_size_name,
			);
		}

		/**
		 * @psalm-suppress TooManyArguments
		 */
		$body_placeholder = apply_filters( 'write_your_story', __( 'Start writing or type / to choose a block', 'pqfw' ), $post );
		$allowed_block_types = apply_filters( 'allowed_block_types', true, $post );

		return array(
			'editor'               => array(
				'alignWide'              => $align_wide,
				'disableCustomColors'    => true,
				'disableCustomFontSizes' => true,
				'disablePostFormats'     => ! current_theme_supports( 'post-formats' ),
				/** This filter is documented in wp-admin/edit-form-advanced.php */
				'titlePlaceholder'       => __( 'Add title', 'pqfw' ),
				'bodyPlaceholder'        => $body_placeholder,
				'isRTL'                  => is_rtl(),
				'autosaveInterval'       => AUTOSAVE_INTERVAL,
				'maxUploadFileSize'      => $max_upload_size,
				'allowedMimeTypes'       => [],
				'styles'                 => function_exists( 'get_block_editor_theme_styles' ) ? get_block_editor_theme_styles() : array(),
				'imageSizes'             => $available_image_sizes,
				'imageDefaultSize'      => 'large',
				'imageEditing'          => true,
				'richEditingEnabled'     => user_can_richedit(),
				'codeEditingEnabled'     => false,
				'allowedBlockTypes'      => $allowed_block_types,
				'__experimentalCanUserUseUnfilteredHTML' => false,
				'__experimentalBlockPatterns' => [],
				'__experimentalBlockPatternCategories' => [],
				'availableTemplates'                   => array(),
				'postLock'                             => false,
				'supportsLayout'                       => false,
				'enableCustomFields'                   => false,
				'generateAnchors'                      => true,
				'canLockBlocks'                        => true,
			),
			'iso'                  => array(
				'blocks'      => array(
					'allowBlocks' => array(
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
					),
				),
				'moreMenu'    => array(
					'topToolbar' => true,
				),
				'sidebar'     => array(
					'inserter'  => true,
					'inspector' => false,
				),
				'toolbar'     => array(
					'navigation' => true,
					'inspector'  => false,
				),
				'allowEmbeds' => array(),
			),
			'saveTextarea'         => '',
			'container'            => '',
			'editorType'           => 'core',
			'allowUrlEmbed'        => true,
			'pastePlainText'       => true,
			'replaceParagraphCode' => false,
			'pluginsUrl'           => plugins_url( '', __DIR__ ),
			'version'              => '1.0.0',
		);
	}

	public function get_backend_scripts_data() {
		$args = array(
		);
		return apply_filters(
			'pqfw/assets/backend_scripts_data',
			array_merge(
				[
					'ajaxurl'  => admin_url( 'admin-ajax.php' ),
					'settings' => pqfw()->settings->getAll(),
					'nonce'    => wp_create_nonce( 'pqfw-app-ui' ),
					'actions'  => [
						'save_settings' => 'pqrf_save_settings'
					],
					'pages'    => pqfw()->helpers->getPages(),
					'cart'     => [
						'id'  => pqfw()->helpers->getCart(),
						'url' => pqfw()->helpers->getCart( 'url' )
					],
					'strings'  => pqfw()->strings->get()
				],
				$this->get_scripts_data(),
				$args
			)
		);
	}

	public function load_block_editor_scripts() {
		// Gutenberg scripts
		wp_enqueue_script( 'wp-block-library' );
		wp_enqueue_script( 'wp-format-library' );
		wp_enqueue_script( 'wp-editor' );

		// Gutenberg styles
		wp_enqueue_style( 'wp-edit-post' );
		wp_enqueue_style( 'wp-format-library' );

		wp_tinymce_inline_scripts();
		wp_enqueue_editor();
	}

	public function web_fonts_url( $font ) {
		$font_url = '';
		if ( 'off' !== _x( 'on', 'Google font: on or off', 'pqfw' ) ) {
			$font_url = add_query_arg( 'family', rawurlencode( $font ), '//fonts.googleapis.com/css' );
		}
		return $font_url;
	}
}