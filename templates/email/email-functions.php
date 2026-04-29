<?php
/**
 * Email Template Helper Functions
 *
 * Helper functions for rendering email template parts.
 *
 * @since 2.6.0
 * @package Quotify
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Get email template styles.
 *
 * @since 2.6.0
 *
 * @return array Array of CSS styles.
 */
function quotify_get_email_styles() {
	$styles = include QUOTIFY_PLUGIN_VIEWS . 'email/email-styles.php';
	return apply_filters( 'quotify_email_styles', $styles );
}

/**
 * Get email template part.
 *
 * Loads template parts with fallback to plugin defaults.
 * Follows WordPress template loading pattern.
 *
 * @since 2.6.0
 *
 * @param string $slug Template slug.
 * @param string $name Optional. Template name.
 * @param array  $data Optional. Template data to pass to template.
 * @return void
 */
function quotify_get_email_template_part( $slug, $name = null, $data = [] ) {
	// Extract data variables for template use.
	if ( ! empty( $data ) ) {
		extract( $data, EXTR_OVERWRITE );
	}

	$template = false;

	// Look in theme first.
	if ( $name ) {
		$template = locate_template(
			[
				"quotify/emails/{$slug}-{$name}.php",
				"quotify/{$slug}-{$name}.php",
			]
		);
	}

	// Try without name.
	if ( ! $template ) {
		$template = locate_template(
			[
				"quotify/emails/{$slug}.php",
				"quotify/{$slug}.php",
			]
		);
	}

	// Fall back to plugin default.
	if ( ! $template ) {
		if ( $name ) {
			$template = QUOTIFY_PLUGIN_VIEWS . "email/{$slug}-{$name}.php";
		} else {
			$template = QUOTIFY_PLUGIN_VIEWS . "email/{$slug}.php";
		}
	}

	// Allow template override via filter.
	$template = apply_filters( 'quotify_email_template_part', $template, $slug, $name );

	// Load template if it exists.
	if ( file_exists( $template ) ) {
		include $template;
	}
}

/**
 * Render email template part as string.
 *
 * Same as quotify_get_email_template_part but returns output as string.
 *
 * @since 2.6.0
 *
 * @param string $slug Template slug.
 * @param string $name Optional. Template name.
 * @param array  $data Optional. Template data to pass to template.
 * @return string Rendered template output.
 */
function quotify_render_email_template_part( $slug, $name = null, $data = [] ) {
	ob_start();
	quotify_get_email_template_part( $slug, $name, $data );
	return ob_get_clean();
}

/**
 * Get email template data with defaults.
 *
 * Ensures all expected data keys exist with fallback values.
 *
 * @since 2.6.0
 *
 * @param array $data Template data.
 * @return array Normalized data with defaults.
 */
function quotify_get_email_data( $data = [] ) {
	$defaults = [
		'quotation_id'   => 0,
		'fullname'       => '',
		'email'          => '',
		'phone'          => '',
		'subject'        => '',
		'comments'       => '',
		'products'       => [],
		'email_title'    => get_bloginfo( 'name' ),
		'site_url'       => get_bloginfo( 'url' ),
		'admin_edit_url' => '',
		'date'           => '',
		'time'           => '',
	];

	$data = wp_parse_args( $data, $defaults );

	// Add computed fields if not set.
	if ( empty( $data['admin_edit_url'] ) && ! empty( $data['quotation_id'] ) ) {
		$data['admin_edit_url'] = admin_url( 'post.php?post=' . $data['quotation_id'] . '&action=edit' );
	}

	if ( empty( $data['date'] ) && ! empty( $data['quotation_id'] ) ) {
		$data['date'] = get_the_date( get_option( 'date_format' ), $data['quotation_id'] );
	}

	if ( empty( $data['time'] ) && ! empty( $data['quotation_id'] ) ) {
		$data['time'] = get_the_date( get_option( 'time_format' ), $data['quotation_id'] );
	}

	return apply_filters( 'quotify_email_data', $data );
}

/**
 * Format product data for email display.
 *
 * @since 2.6.0
 *
 * @param array $product Raw product data.
 * @return array Normalized product data.
 */
function quotify_format_email_product( $product ) {
	$defaults = [
		'name'     => __( 'Unknown Product', 'quotify' ),
		'quantity' => 1,
		'price'    => '',
		'img'      => '',
		'link'     => '#',
		'message'  => '',
	];

	return wp_parse_args( $product, $defaults );
}

/**
 * Sanitize email content.
 *
 * Applies appropriate sanitization based on content type.
 *
 * @since 2.6.0
 *
 * @param string $content Content to sanitize.
 * @param string $type    Content type (text, html, email, url).
 * @return string Sanitized content.
 */
function quotify_sanitize_email_content( $content, $type = 'text' ) {
	switch ( $type ) {
		case 'html':
			return wp_kses_post( $content );
		case 'email':
			return sanitize_email( $content );
		case 'url':
			return esc_url( $content );
		case 'attr':
			return esc_attr( $content );
		case 'textarea':
			return esc_textarea( $content );
		case 'text':
		default:
			return sanitize_text_field( $content );
	}
}
