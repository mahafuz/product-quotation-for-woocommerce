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
class Admin {

	const POST_TYPE = 'pqfw_quotations';

	/**
	 * Constructor of the class
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'registerPostType' ] );
		add_action( 'add_meta_boxes', [ $this, 'QuotationAuthorDetail' ] );
		add_action( 'admin_menu', [ $this, 'menus' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );

		add_action( 'admin_title', [ $this, 'editPageTitle' ] );
		add_filter( 'manage_edit-pqfw_quotations_columns', [ $this, 'manageColumns' ] );
		add_action( 'manage_pqfw_quotations_posts_custom_column', [ $this, 'columnsContent' ], 10, 2 );
		add_action( 'post_row_actions', [ $this, 'modifyQuickActions' ], 10, 2 );
	}

	/**
	 * Adding page on the database.
	 *
	 * @since   1.0.0
	 */
	public function menus() {
		add_submenu_page(
			'edit.php?post_type=pqfw_quotations',
			__( 'Old backed up entries', 'PQFW' ),
			__( 'Backup', 'PQFW' ),
			'manage_options',
			'pqfw-entries-page',
			[ $this, 'display' ]
		);
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

		if ( 'pqfw_quotations_page_pqfw-settings' === $screen->id || 'pqfw_quotations_page_pqfw-entries-page' === $screen->id ) {
			wp_enqueue_style(
				'pqfw-admin',
				PQFW_PLUGIN_URL . 'assets/css/pqfw-admin.css',
				[], '1.0.0', 'all'
			);
		}
	}

	/**
	 * Loading layout page tamplate.
	 *
	 * @since 1.0.0
	 */
	public function display() {
		include PQFW_PLUGIN_VIEWS . 'layout.php';
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
	public function QuotationAuthorDetail() {
		add_meta_box(
			'pqfw_quotation_detail',
			__( 'Person Details', 'pqfw' ),
			[ $this, 'displayQuotationDetail' ],
			self::POST_TYPE
		);

		add_meta_box(
			'pqfw_quotation_products_detail',
			__( 'Product Details', 'pqfw' ),
			[ $this, 'displayQuotationProductsDetail' ],
			self::POST_TYPE
		);
	}

	/**
	 * Change the title of the page.
	 *
	 * @since 1.2.0
	 */
	public function editPageTitle() {
		global $post, $title, $action, $current_screen;

		if ( isset( $current_screen->post_type ) && 'pqfw_quotations' === $current_screen->post_type && 'edit' === $action ) {
			/* %d Quotation Post Id */
			$title = sprintf( __( '#%d Quotation Detail', 'pqfw' ), $post->ID );
		}

		return $title;
	}

	/**
	 * Displays quotation detail meta box.
	 *
	 * @since  1.2.0
	 * @param  array $quotation The single quotation array.
	 * @return void
	 */
	public function displayQuotationDetail( $quotation ) {
		include_once PQFW_PLUGIN_PATH . 'includes/Views/partials/quotation-detail.php';
	}

	/**
	 * Displays quotation products detail meta box.
	 *
	 * @since  1.2.0
	 * @param  array $quotation The single quotation array.
	 * @return void
	 */
	public function displayQuotationProductsDetail( $quotation ) {
		include_once PQFW_PLUGIN_PATH . 'includes/Views/partials/quotation-products-detail.php';
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
	 * Customize the post type column.
	 *
	 * @since  1.2.0
	 * @param  array $columns The column header labels keyed by column ID.
	 * @return array          Customized columns.
	 */
	public function manageColumns( $columns ) {
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
	 * Modified the post type rows action links.
	 *
	 * @since  1.2.0
	 *
	 * @param  array  $actions An associative array of action links.
	 * @param  object $post    The post object.
	 */
	public function modifyQuickActions( $actions, $post ) {
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
	 * Defines post type colums content.
	 *
	 * @since 1.2.0
	 * @param array   $column An associative array of column headings.
	 * @param integer $postID The current post id.
	 */
	public function columnsContent( $column, $postID ) {
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
}