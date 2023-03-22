<?php
/**
 * Responsible for handling the plugin admin panel.
 *
 * @since 1.0.0
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

	/**
	 * Contains the plugin quotations post type.
	 *
	 * @var string
	 */
	const POST_TYPE = 'pqfw_quotations';

	/**
	 * Constructor of the class
	 *
	 * @since  1.0.0
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_action( 'add_meta_boxes', [ $this, 'quotation_author_detail' ] );
		add_action( 'admin_menu', [ $this, 'menus' ] );
		add_action( 'admin_title', [ $this, 'edit_page_title' ] );
		add_filter( 'manage_edit-pqfw_quotations_columns', [ $this, 'manage_columns' ] );
		add_action( 'manage_pqfw_quotations_posts_custom_column', [ $this, 'columns_content' ], 10, 2 );
		add_action( 'post_row_actions', [ $this, 'add_quick_actions' ], 10, 2 );
	}

	/**
	 * Registers 'Quotations' post type on the dashboard.
	 *
	 * @since 1.2.0
	 */
	public function register_post_type() {
		register_post_type(
			self::POST_TYPE,
			[
				'labels'              => [
					'name'          => __( 'Quotations', 'pqfw' ),
					'singular_name' => __( 'Quotation', 'pqfw' ),
					'add_new_item'  => __( 'Quotation', 'pqfw' )
				],
				'public'              => false,
				'exclude_from_search' => true,
				'publicaly_queryable' => false,
				'show_ui'             => true,
				'rewrite'             => false,
				'show_in_nav_menus'   => false,
				'query_var'           => false,
				'has_archive'         => false,
				'supports'            => [ 'title' ],
				'menu_icon'           => PQFW_PLUGIN_URL . 'assets/images/pqfw-dashboard-icon.png',
				'capability_type'     => 'post',
				'capabilities'        => [
					'create_posts' => 'do_not_allow'
				],
				'map_meta_cap'        => true
			]
		);

		remove_post_type_support( self::POST_TYPE, 'title' );
		remove_post_type_support( self::POST_TYPE, 'slugdiv' );
	}

	/**
	 * Add meta boxes to the post type.
	 *
	 * @since 1.2.0
	 */
	public function quotation_author_detail() {
		add_meta_box(
			'pqfw_quotation_detail',
			__( 'Customer Information', 'pqfw' ),
			[ $this, 'display_quotation_detail' ],
			self::POST_TYPE
		);

		add_meta_box(
			'pqfw_quotation_products_detail',
			__( 'Quote Details', 'pqfw' ),
			[ $this, 'display_quotation_products_detail' ],
			self::POST_TYPE
		);
	}

	/**
	 * Adding page on the database.
	 *
	 * @since   1.0.0
	 */
	public function menus() {
		add_submenu_page(
			'edit.php?post_type=pqfw_quotations',
			__( 'Help', 'pqfw' ),
			'<span style="color:#f18500">Help</span>',
			'manage_options',
			'pqfw-help',
			[ $this, 'display_help_page' ]
		);

		if ( isset( $_GET['post_type'] ) && 'pqfw_quotations' === $_GET['post_type'] ) { //phpcs:ignore
			// We don't want any plugin adding notices to our screens. Let's clear them out here.
			add_action( 'admin_footer_text', [ $this, 'add_footer_text' ] );
		}
	}

	/**
	 * Change the title of the page.
	 *
	 * @since 1.2.0
	 */
	public function edit_page_title() {
		global $post, $title, $action, $current_screen;

		if ( isset( $current_screen->post_type ) && 'pqfw_quotations' === $current_screen->post_type && 'edit' === $action ) {
			/* translators: %d Quotation Post Id */
			$title = sprintf( __( '#%d Quotation Detail', 'pqfw' ), $post->ID ); //phpcs:ignore
		}

		return $title;
	}

	/**
	 * Customize the post type column.
	 *
	 * @since  1.2.0
	 * @param  array $columns The column header labels keyed by column ID.
	 * @return array          Customized columns.
	 */
	public function manage_columns( $columns ) {
		$columns = [
			'cb'           => '<input type="checkbox" />',
			'id'           => __( 'Serial', 'pqfw' ),
			'title'        => __( 'Name', 'pqfw' ),
			'pqfw_email'   => __( 'Email', 'pqfw' ),
			'pqfw_phone'   => __( 'Phone', 'pqfw' ),
			'pqfw_subject' => __( 'Subject', 'pqfw' ),
			'pqfw_message' => __( 'Message', 'pqfw' ),
			'date'         => __( 'Date', 'pqfw' )
		];

		return $columns;
	}

	/**
	 * Defines post type colums content.
	 *
	 * @since 1.2.0
	 * @param array   $column An associative array of column headings.
	 * @param integer $postID The current post id.
	 */
	public function columns_content( $column, $postID ) {
		switch ( $column ) {
			case 'id':
				echo '#' . absint( $postID );
				break;

			case 'pqfw_email':
					echo esc_html( get_post_meta( $postID, 'pqfw_customer_email', true ) );
				break;

			case 'pqfw_phone':
				echo esc_html( get_post_meta( $postID, 'pqfw_customer_phone', true ) );
				break;

			case 'pqfw_subject':
				echo esc_html( get_post_meta( $postID, 'pqfw_customer_subject', true ) );
				break;

			case 'pqfw_message':
				echo esc_html( get_post_meta( $postID, 'pqfw_customer_comments', true ) );
				break;
		}
	}

	/**
	 * Modified the post type rows action links.
	 *
	 * @since  1.2.0
	 *
	 * @param  array  $actions An associative array of action links.
	 * @param  object $post    The post object.
	 */
	public function add_quick_actions( $actions, $post ) {
		if ( self::POST_TYPE === $post->post_type ) {
			unset( $actions['inline hide-if-no-js'] );
			unset( $actions['edit'] );

			$url  = admin_url( 'post.php?post=' . $post->ID );
			$view = wp_nonce_url( add_query_arg( [ 'action' => 'edit' ], $url ) );

			$actions = array_merge(
				[
					'details' => '<a href="' . esc_url( $view ) . '">' . __( 'Details', 'pqfw' ) . '</a>',
				],
				$actions
			);
		}

		return $actions;
	}

	/**
	 * Add footer text to the WordPress admin screens.
	 *
	 * @since  4.0.0
	 * @return void
	 */
	public function add_footer_text() {
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
			// Translators: 1 - The plugin name ("PageSpeed Optimizer for Elementor"), - 2 - This placeholder will be replaced with star icons.
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
	public function display_help_page() {
		require_once PQFW_PLUGIN_PATH . 'includes/Views/help.php';
	}

	/**
	 * Displays quotation detail meta box.
	 *
	 * @since  1.2.0
	 * @param  array $quotation The single quotation array.
	 * @return void
	 */
	public function display_quotation_detail( $quotation ) { //phpcs:ignore
		include_once PQFW_PLUGIN_VIEWS . 'quotation-detail.php';
	}

	/**
	 * Displays quotation products detail meta box.
	 *
	 * @since  1.2.0
	 * @param  array $quotation The single quotation array.
	 * @return void
	 */
	public function display_quotation_products_detail( $quotation ) { //phpcs:ignore
		include_once PQFW_PLUGIN_VIEWS . 'quotation-products-detail.php';
	}
}