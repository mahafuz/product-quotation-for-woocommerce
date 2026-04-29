<?php
/**
 * Admin class
 *
 * @since   1.0.0
 * @package Quotify
 */

namespace Quotify;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @since   1.0.0
 * @package Quotify
 */
class Admin {

	/**
	 * Class instance.
	 *
	 * @var \Quotify\Admin
	 */
	private static $instance = null;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return \Quotify\Admin
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	const POST_TYPE = 'pqfw_quotations';

	const REGISTERED_SLUGS = [
		'toplevel_page_quotify',
		'quotify-quotations',
		'quotify-settings',
		'quotify_page_quotify-settings',
		'quotify-addons',
		'quotify_page_quotify-addons',
		'quotify-tools',
		'quotify_page_quotify-tools',
		'quotify-help',
		'quotify_page_quotify-help',
	];

	/**
	 * Constructor of the class
	 *
	 * @since  1.0.0
	 * @return void
	 */
	private function __construct() {
		add_action( 'init', [ $this, 'registerPostType' ] );
		add_action( 'admin_init', [ $this, 'hideNotices' ] );

		add_action( 'wp_untrash_post_status', [ $this, 'quotations_post_status' ], 10, 2 );
	}

	/**
	 * Add footer text to the WordPress admin screens.
	 *
	 * @since  4.0.0
	 * @return void
	 */
	public function addFooterText() {
		$linkText = esc_html__( 'Give us a 5-star rating!', 'quotify' );
		$href     = 'https://wordpress.org/support/plugin/product-quotation-for-woocommerce/reviews/#new-post';

		$link1 = sprintf(
			'<a href="%1$s" target="_blank" title="%2$s">&#9733;&#9733;&#9733;&#9733;&#9733;</a>',
			$href,
			$linkText
		);

		$link2 = sprintf(
			'<a href="%1$s" target="_blank" title="%2$s">WordPress.org</a>',
			$href,
			$linkText
		);

		printf(
			// Translators: 1 - The plugin name ("Product Quotation For WooCommerce"), - 2 - This placeholder will be replaced with star icons.
			esc_html__( 'Please rate %1$s %2$s on %3$s to help us spread the word. Thank you!', 'quotify' ),
			sprintf( '<strong>%1$s</strong>', esc_html( QUOTIFY_PLUGIN_FILE ) ),
			wp_kses_post( $link1 ),
			wp_kses_post( $link2 )
		);
	}

	/**
	 * Registers 'Quotations' post type on the dashboard.
	 *
	 * @since 1.2.0
	 */
	public function registerPostType() {
		global $post;

		register_post_type(
			self::POST_TYPE,
			[
				'labels'              => [
					'name'          => __( 'Quotations', 'quotify' ),
					'singular_name' => __( 'Quotation', 'quotify' ),
					'add_new_item'  => __( 'Quotation', 'quotify' ),
				],
				'public'              => false,
				'exclude_from_search' => true,
				'publicaly_queryable' => false,
				'show_ui'             => false,
				'rewrite'             => false,
				'show_in_nav_menus'   => false,
				'query_var'           => false,
				'has_archive'         => false,
				'supports'            => [ 'title' ],
				'capability_type'     => 'post',
				'capabilities'        => [
					'create_posts' => 'do_not_allow',
				],
				'map_meta_cap'        => true,
			]
		);

		remove_post_type_support( self::POST_TYPE, 'title' );
		remove_post_type_support( self::POST_TYPE, 'slugdiv' );

		register_post_status(
			'approved',
			[
				'label'   => __( 'Approved', 'quotify' ),
				'private' => true,
			]
		);
	}

	/**
	 * This hides the notices from external resources.
	 *
	 * @return void
	 */
	public function hideNotices() {
		$screen = get_current_screen();

		if ( \Quotify\Library\Helper::pageLookUp( $screen ) ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
			remove_all_actions( 'network_admin_notices' );
			remove_all_actions( 'user_admin_notices' );
		}
	}

	/**
	 * Settings for quotations post type status.
	 *
	 * @param  string $new_status Post new status.
	 * @param  int    $post_id    Post ID.
	 * @return string
	 */
	public function quotations_post_status( $new_status, $post_id ) {
		if ( 'pqfw_quotations' === get_post_type( $post_id ) ) {
			$new_status = 'approved';
		}

		return $new_status;
	}
}
