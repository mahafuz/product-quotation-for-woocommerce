<?php
/**
 * Email Template Manager
 *
 * Manages email templates for admin and customer notifications.
 * Supports template overriding and customization.
 *
 * @since 2.6.0
 * @package Quotify
 */

namespace Quotify\Library;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Template Manager Class
 *
 * Handles loading, rendering, and managing email templates.
 *
 * @since 2.6.0
 */
class Template_Manager {

	/**
	 * Class instance.
	 *
	 * @var Template_Manager|null
	 */
	private static $instance = null;

	/**
	 * Template directory paths.
	 *
	 * @var array
	 */
	private $template_paths = [];

	/**
	 * Initialize the template manager.
	 *
	 * @since 2.6.0
	 * @static
	 * @access public
	 *
	 * @return Template_Manager
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * Sets up template paths and initializes theme override support.
	 *
	 * @since 2.6.0
	 * @access public
	 */
	public function __construct() {
		$this->template_paths = [
			'plugin' => QUOTIFY_PLUGIN_VIEWS . 'email/',
			'theme'  => get_template_directory() . '/quotify/emails/',
		];

		// Add theme override support for child themes.
		add_filter( 'quotify/template_paths', [ $this, 'add_child_theme_path' ] );
	}

	/**
	 * Add child theme path to template locations.
	 *
	 * @since 2.6.0
	 * @access public
	 *
	 * @param array $paths Existing template paths.
	 * @return array Modified paths with child theme support.
	 */
	public function add_child_theme_path( $paths ) {
		$child_theme_path = get_stylesheet_directory() . '/quotify/emails/';

		if ( file_exists( $child_theme_path ) ) {
			$paths['child_theme'] = $child_theme_path;
		}

		return $paths;
	}

	/**
	 * Get template path with fallback chain.
	 *
	 * Searches in order: child theme -> parent theme -> plugin.
	 *
	 * @since 2.6.0
	 * @access public
	 *
	 * @param string $template_name Template file name.
	 * @param string $type          Template type (admin/customer).
	 * @return string|false Template path or false if not found.
	 */
	public function get_template_path( $template_name, $type = 'admin' ) {
		$template_file = $type . '/' . $template_name . '.php';
		$paths         = apply_filters( 'quotify/template_paths', $this->template_paths );

		// Search paths in priority order (last added = highest priority).
		$reversed_paths = array_reverse( $paths );

		foreach ( $reversed_paths as $path ) {
			$full_path = $path . $template_file;

			if ( file_exists( $full_path ) ) {
				return $full_path;
			}
		}

		// Fallback to old template location for backward compatibility.
		$legacy_path = QUOTIFY_PLUGIN_VIEWS . 'email/' . $template_name . '.php';
		if ( file_exists( $legacy_path ) ) {
			return $legacy_path;
		}

		return false;
	}

	/**
	 * Render email template with data.
	 *
	 * @since 2.6.0
	 * @access public
	 *
	 * @param string $template_name Template name.
	 * @param string $type          Template type (admin/customer).
	 * @param array  $data          Template data.
	 * @return string Rendered template HTML.
	 */
	public function render( $template_name, $type = 'admin', $data = [] ) {
		$custom_templates_enabled = quotify()->settings()->get( 'pqfw_custom_email_template_enabled' );

		if ( $custom_templates_enabled ) {
			$custom_template_key = 'admin' === $type ? 'pqfw_admin_email_template' : 'pqfw_customer_email_template';
			$custom_template = quotify()->settings()->get( $custom_template_key );

			if ( ! empty( $custom_template ) ) {
				return $this->render_custom_template( $custom_template, $data );
			}
		}

		$template_path = $this->get_template_path( $template_name, $type );

		if ( ! $template_path ) {
			return '';
		}

		// Extract data variables for template use.
		extract( $data, EXTR_OVERWRITE ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		// Start output buffering.
		ob_start();

		// Load template.
		include $template_path;

		// Get buffered content.
		$content = ob_get_clean();

		return $content;
	}

	/**
	 * Render custom email template from settings.
	 *
	 * Replaces placeholders in custom template with data.
	 *
	 * @since 2.6.0
	 * @access private
	 *
	 * @param string $template Custom template HTML.
	 * @param array  $data     Template data.
	 * @return string Rendered template.
	 */
	private function render_custom_template( $template, $data ) {
		$placeholders = [
			'{quotation_id}'       => isset( $data['quotation_id'] ) ? '#' . intval( $data['quotation_id'] ) : '',
			'{customer_name}'      => isset( $data['fullname'] ) ? sanitize_text_field( $data['fullname'] ) : '',
			'{customer_email}'     => isset( $data['email'] ) ? sanitize_email( $data['email'] ) : '',
			'{customer_phone}'     => isset( $data['phone'] ) ? sanitize_text_field( $data['phone'] ) : '',
			'{customer_subject}'   => isset( $data['subject'] ) ? sanitize_text_field( $data['subject'] ) : '',
			'{customer_comments}'  => isset( $data['comments'] ) ? wp_kses_post( $data['comments'] ) : '',
			'{site_name}'          => isset( $data['email_title'] ) ? sanitize_text_field( $data['email_title'] ) : get_bloginfo( 'name' ),
			'{site_url}'           => isset( $data['site_url'] ) ? esc_url( $data['site_url'] ) : home_url( '/' ),
			'{date}'               => isset( $data['date'] ) ? sanitize_text_field( $data['date'] ) : current_time( get_option( 'date_format' ) ),
			'{time}'               => isset( $data['time'] ) ? sanitize_text_field( $data['time'] ) : current_time( get_option( 'time_format' ) ),
			'{admin_edit_url}'     => isset( $data['admin_edit_url'] ) ? esc_url( $data['admin_edit_url'] ) : '',
			'{product_list}'       => isset( $data['products'] ) ? $this->render_product_list( $data['products'], $data ) : '',
		];

		$content = str_replace( array_keys( $placeholders ), array_values( $placeholders ), $template );

		/**
		 * Filter custom email template content.
		 *
		 * @since 2.6.0
		 *
		 * @param string $content    Rendered template content.
		 * @param string $template   Original template.
		 * @param array  $data       Template data.
		 * @param array  $placeholders Placeholder replacements.
		 */
		return apply_filters( 'quotify_custom_email_template', $content, $template, $data, $placeholders );
	}

	/**
	 * Render product list for custom templates.
	 *
	 * @since 2.6.0
	 * @access private
	 *
	 * @param array $products Product list.
	 * @param array $data     Template data.
	 * @return string Product list HTML.
	 */
	private function render_product_list( $products, $data ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		if ( empty( $products ) ) {
			return '';
		}

		ob_start();
		echo '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
		echo '<thead><tr style="background: #f8f9fa;">';
		echo '<th style="padding: 10px; border: 1px solid #ddd; text-align: left;">' . esc_html__( 'Product', 'quotify' ) . '</th>';
		echo '<th style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . esc_html__( 'Quantity', 'quotify' ) . '</th>';
		echo '</tr></thead>';
		echo '<tbody>';

		foreach ( $products as $product ) {
			$product_title = isset( $product['title'] ) ? sanitize_text_field( $product['title'] ) : '';
			$product_qty = isset( $product['quantity'] ) ? absint( $product['quantity'] ) : 1;
			$product_price = isset( $product['price'] ) ? wp_kses_post( $product['price'] ) : '';

			echo '<tr>';
			echo '<td style="padding: 10px; border: 1px solid #ddd;">';
			echo esc_html( $product_title );
			if ( ! empty( $product_price ) ) {
				echo ' - ' . wp_kses_post( $product_price );
			}
			echo '</td>';
			echo '<td style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . esc_html( $product_qty ) . '</td>';
			echo '</tr>';
		}

		echo '</tbody></table>';
		return ob_get_clean();
	}

	/**
	 * Get list of available templates for a type.
	 *
	 * @since 2.6.0
	 * @access public
	 *
	 * @param string $type Template type (admin/customer).
	 * @return array List of available template names.
	 */
	public function get_templates( $type = 'admin' ) {
		$templates = [];
		$paths     = apply_filters( 'quotify/template_paths', $this->template_paths );

		// Check all paths for templates of this type.
		foreach ( $paths as $location => $path ) {
			$type_path = $path . $type . '/';

			if ( is_dir( $type_path ) ) {
				$files = glob( $type_path . '*.php' );

				foreach ( $files as $file ) {
					$template_name = basename( $file, '.php' );
					$templates[ $template_name ] = [
						'name'     => $template_name,
						'path'     => $file,
						'location' => $location,
					];
				}
			}
		}

		// Add default template if no custom templates found.
		if ( empty( $templates ) ) {
			$templates['new-quotation'] = [
				'name'     => 'new-quotation',
				'path'     => $this->get_template_path( 'new-quotation', $type ),
				'location' => 'plugin',
			];
		}

		return $templates;
	}

	/**
	 * Prepare template data from quotation.
	 *
	 * Standardizes data format for templates.
	 *
	 * @since 2.6.0
	 * @access public
	 *
	 * @param int $quotation_id Quotation post ID.
	 * @return array Template data.
	 */
	public function prepare_quotation_data( $quotation_id ) {
		$quote    = get_post( $quotation_id );
		$meta     = Helper::get_post_meta_by_id( $quotation_id );
		$author   = sanitize_user( get_the_author_meta( 'first_name', $quote->post_author ) );
		$title    = get_the_title( $quotation_id );
		$handle   = ! empty( $author ) ? esc_attr( $author ) : esc_attr( $title );
		$email    = sanitize_email( $meta['pqfw_customer_email'] );
		$phone    = Helper::sanitizePhoneNumber( $meta['pqfw_customer_phone'] );
		$subject  = sanitize_text_field( $meta['pqfw_customer_subject'] );
		$comments = sanitize_textarea_field( $meta['pqfw_customer_comments'] );
		$products = $meta['pqfw_products_info'] ?? [];

		return [
			'quotation_id'   => $quotation_id,
			'fullname'       => $handle,
			'email'          => $email,
			'subject'        => $subject,
			'phone'          => $phone,
			'comments'       => $comments,
			'products'       => $products,
			'email_title'    => get_bloginfo( 'name' ),
			'site_url'       => get_bloginfo( 'url' ),
			'admin_edit_url' => admin_url( 'post.php?post=' . $quotation_id . '&action=edit' ),
			'date'           => get_the_date( get_option( 'date_format' ), $quotation_id ),
			'time'           => get_the_date( get_option( 'time_format' ), $quotation_id ),
		];
	}

	/**
	 * Replace template variables.
	 *
	 * Replaces {{variable}} placeholders with actual values.
	 *
	 * @since 2.6.0
	 * @access public
	 *
	 * @param string $content Content with variables.
	 * @param array  $data    Variable values.
	 * @return string Content with replaced variables.
	 */
	public function replace_variables( $content, $data ) {
		$variables = [
			'{{customer_name}}'   => $data['fullname'] ?? '',
			'{{customer_email}}'  => $data['email'] ?? '',
			'{{customer_phone}}'  => $data['phone'] ?? '',
			'{{quotation_id}}'    => $data['quotation_id'] ?? '',
			'{{site_name}}'       => $data['email_title'] ?? '',
			'{{site_url}}'        => $data['site_url'] ?? '',
			'{{admin_edit_link}}' => $data['admin_edit_url'] ?? '',
			'{{quotation_date}}'  => $data['date'] ?? '',
			'{{quotation_time}}'  => $data['time'] ?? '',
		];

		return str_replace( array_keys( $variables ), array_values( $variables ), $content );
	}
}
