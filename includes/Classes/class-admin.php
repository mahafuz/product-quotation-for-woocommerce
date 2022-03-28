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

		add_filter( 'manage_edit-pqfw_quotations', [ $this, 'manageColumns' ] );
		// add_action( 'manage_pisol_enquiry_posts_custom_column', array($this,'columnsContent'), 10, 2 );
	}

	/**
	 * Adding page on the database.
	 *
	 * @since   1.0.0
	 */
	public function menus() {
		add_menu_page(
			__( 'Entries', 'PQFW' ),
			__( 'Product Quotation', 'PQFW' ),
			'manage_options',
			'pqfw-entries-page',
			[ $this, 'display' ],
			PQFW_PLUGIN_URL . 'assets/images/pqfw-dashboard-icon.png'
		);
	}

	/**
	 * Loading admin css.
	 *
	 * @since 1.0.0
	 */
	public function assets() {
		$screen = get_current_screen();

		if ( 'toplevel_page_pqfw-entries-page' === $screen->id || 'product-quotation_page_pqfw-settings' === $screen->id ) {
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
			__( 'Quotation Detail', 'pqfw' ),
			[ $this, 'displayQuotationDetail' ],
			$this->enquiry_type
		);
	}

	/**
	 * Displays quotation detail meta box.
	 *
	 * @since  1.2.0
	 * @param  array $quotation The single quotation array.
	 * @return void
	 */
	public function displayQuotationDetail( $quotation ) {
		include_once 'partials/enquiry_detail.php';
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
			// 'cb' => '<input type="checkbox" />',
			// 'id' => __( 'Enq no.', 'pisol-enquiry-quotation-woocommerce' ),
			// 'title' => __( 'Name', 'pisol-enquiry-quotation-woocommerce'  ),
			// 'pi_email' => __( 'Email', 'pisol-enquiry-quotation-woocommerce'  ),
			// 'pi_phone' => __( 'Phone', 'pisol-enquiry-quotation-woocommerce'  ),
			// 'pi_subject' => __( 'Subject', 'pisol-enquiry-quotation-woocommerce'  ),
			// 'pi_message' => __( 'Message', 'pisol-enquiry-quotation-woocommerce'  ),
			// 'date' => __( 'Date', 'pisol-enquiry-quotation-woocommerce'  )
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
	public function columnsContent( $column, $postID ) {
		global $post;

		switch ( $column ) {
			case 'id':
				echo '#' . absint( $postID );
				break;

			case 'pi_email':
				$pi_email = get_post_meta( $postID, 'pi_email', true );
					echo sanitize_email( $pi_email );
				break;

			case 'pi_phone':
				$pi_phone = get_post_meta( $postID, 'pi_phone', true );
				echo esc_html( $pi_phone );
				break;

			case 'pi_subject':
				$pi_subject = get_post_meta( $postID, 'pi_subject', true );
				echo esc_html( $pi_subject );
				break;

			case 'pi_message':
				$pi_message = get_post_meta( $postID, 'pi_message', true );
				echo esc_html( $pi_message );
				break;
		}
	}
}