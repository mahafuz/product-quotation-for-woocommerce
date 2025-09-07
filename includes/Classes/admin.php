<?php
/**
 * Admin class
 *
 * @since   1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @since   1.0.0
 * @package PQFW
 */
class Admin {

	const POST_TYPE = 'pqfw_quotations';

	const REGISTERED_SLUGS = [
		'toplevel_page_pqfw-product-quotations',
		'toplevel_page_pqfw-product-quotations',
		'toplevel_page_pqfw-product-quotations',
		'pqfw-product-quotations',
		'quotations_page_pqfw-product-quotations',
		'quotations_page_pqfw-product-quotations-settings',
		'quotations_page_pqfw-product-quotations-addons',
		'quotations_page_pqfw-product-quotations-tools',
		'quotations_page_pqfw-product-quotations-help',
		'pqfw-product-quotations-help',
	];

	/**
	 * Constructor of the class
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'registerPostType' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
		add_action( 'admin_init', [ $this, 'hideNotices' ] );
	}

	/**
	 * Add footer text to the WordPress admin screens.
	 *
	 * @since  4.0.0
	 * @return void
	 */
	public function addFooterText() {
		$linkText = esc_html__( 'Give us a 5-star rating!', 'pqfw' );
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
			esc_html__( 'Please rate %1$s %2$s on %3$s to help us spread the word. Thank you!', 'pqfw' ),
			sprintf( '<strong>%1$s</strong>', esc_html( PQFW_PLUGIN_NAME ) ),
			wp_kses_post( $link1 ),
			wp_kses_post( $link2 )
		);
	}

	/**
	 * Displaying the 'Help' page.
	 *
	 * @since 1.2.6
	 */
	public function displayHelp() {
		require_once PQFW_PLUGIN_PATH . 'includes/Views/help.php';
	}

	/**
	 * Loading admin css.
	 *
	 * @since 1.0.0
	 */
	public function assets() {
		$screen = get_current_screen();

		if ( 'pqfw_quotations' === $screen->post_type ) {
			wp_enqueue_style(
				'pqfw-admin-quotations',
				PQFW_PLUGIN_URL . 'assets/css/pqfw-quotations.css',
				[], '1.0.0', 'all'
			);
		}

		if ( pqfw()->helpers->pageLookUp( $screen ) ) {
			wp_enqueue_style(
				'pqfw-admin',
				PQFW_PLUGIN_URL . 'assets/css/pqfw-admin.css',
				[], '1.0.0', 'all'
			);
		}
	}

	/**
	 * Registers 'Quotations' post type on the dashboard.
	 *
	 * @since 1.2.0
	 */
	public function registerPostType() {
		register_post_type(
			self::POST_TYPE,
			[
				'labels'              => [
					'name'          => __( 'Quotations', 'pqfw' ),
					'singular_name' => __( 'Quotation', 'pqfw' ),
					'add_new_item'  => __( 'Quotation', 'pqfw' ),
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
	}

	/**
	 * Builds the variation tree based on the details.
	 *
	 * @since  1.2.0
	 * @param  string $details The variation details.
	 * @return void
	 */
	public function buildVariations( $details ) {
		$attributesGroup = explode( ',', $details );
		if ( is_array( $attributesGroup ) && count( $attributesGroup ) > 0 ) {
			foreach ( $attributesGroup as $attribute ) {
				if ( '' !== $attribute ) {
					$pair = explode( '|', $attribute );
					echo isset( $pair[0] ) ? '<strong>' . esc_html( $pair[0] ) . '</strong> : ' : '';
					echo isset( $pair[1] ) ? '<span>' . esc_html( $pair[0] ) . '</span><br>' : '';
				}
			}
		}
	}

	/**
	 * This hides the notices from external resources.
	 *
	 * @return void
	 */
	public function hideNotices() {
		$screen = get_current_screen();

		if ( pqfw()->helpers->pageLookUp( $screen ) ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
			remove_all_actions( 'network_admin_notices' );
			remove_all_actions( 'user_admin_notices' );
		}
	}
}
