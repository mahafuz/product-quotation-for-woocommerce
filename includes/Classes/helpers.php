<?php
/**
 * Contains the plugin helper methods.
 *
 * @since   1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

use WP_Error;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Contains the plugin helper methods.
 *
 * @since   1.0.0
 * @package PQFW
 */
class Helpers {

	/**
	 * Replaces placeholders with links to WooCommerce policy pages.
	 *
	 * @since  2.0.0
	 * @param  string $text Text to find/replace within.
	 * @return string       Text with replaced placeholders.
	 */
	public function generatePrivacyPolicy( $text ) {
		return function_exists( 'wc_replace_policy_page_link_placeholders' ) ? wc_replace_policy_page_link_placeholders( $text ) : $text;
	}

	/**
	 * Sanitize phone number.
	 * Allows only numbers and "+" (plus sign).
	 *
	 * @param string $phone Phone number.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function sanitizePhoneNumber( $phone ) {
		return preg_replace( '/[^\d+]/', '', $phone );
	}

	/**
	 * Validate user data.
	 *
	 * @param array $fields The user submitted form data.
	 * @return array
	 */
	public function validate( $fields ) {
		$errors = new \WP_Error();

		$requiredFields = [
			'fullname',
			'email',
		];

		foreach ( $requiredFields as $required ) {
			if ( empty( $fields[ $required ] ) ) {
				$errors->add( 'field', sprintf( '%s %s', $required, __( 'is required.', 'pqfw' ) ) );
			}
		}

		if ( $errors->has_errors() ) {
			return $errors;
		}

		if ( strlen( $fields['fullname'] ) < 4 ) {
			$errors->add( 'username_length', __( 'Username too short. At least 4 characters is required', 'pqfw' ) );
		}

		if ( ! validate_username( $fields['fullname'] ) ) {
			$errors->add( 'username_invalid', __( 'Sorry, the username you entered is not valid', 'pqfw' ) );
		}

		if ( ! is_email( $fields['email'] ) ) {
			$errors->add( 'email_invalid', __( 'Email is not valid', 'pqfw' ) );
		}

		return $errors;
	}

	/**
	 * Sanitize a checkbox field.
	 *
	 * @param  mixed $boolean The checkbox field.
	 * @return bool
	 */
	public function sanitize_checkbox_field( $boolean ) {
		return filter_var( sanitize_text_field( $boolean ), FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Get the addon status.
	 *
	 * @param  string $addon_name The addon name.
	 * @return bool
	 */
	public function get_addon_active_status( $addon_name ) {
		global $pqfw_addons;
		if ( isset( $pqfw_addons->{$addon_name} ) ) {
			return (bool) $pqfw_addons->{$addon_name};
		}
		return false;
	}

	/**
	 * Get pages list.
	 *
	 * @since 2.0.1
	 */
	public function getPages() {
		$result = [
			[
				'value' => 0,
				'label' => __( 'Select page for Quotations cart', 'pqfw' ),
			],
		];

		$pages = get_posts([
			'numberposts' => -1,
			'post_type'   => 'page',
			'post_status' => 'publish',
		]);

		if ( ! is_array( $pages ) || empty( $pages ) ) {
			return [];
		}

		foreach ( $pages as $page ) {
			$result[] = [
				'value' => $page->ID,
				'label' => $page->post_title,
			];
		}

		return $result;
	}

	/**
	 * Get default cart page.
	 *
	 * @param string $field The field type id.
	 *
	 * @since 2.0.1
	 */
	public function getCart( $field = 'id' ) {
		$id = absint( get_option( 'pqfw_quotations_cart' ) );

		return 'url' === $field ? esc_url( get_permalink( $id ) ) : $id;
	}

	/**
	 * Is the woocommerce plugin active.
	 *
	 * @return bool
	 */
	public static function isWoocommerceActive() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		return is_plugin_active( 'woocommerce/woocommerce.php' );
	}

	/**
	 * Escape a complete HTML form while keeping allowed tags safe.
	 *
	 * @param string $html The raw HTML form content.
	 * @return string Escaped HTML with only allowed tags/attributes.
	 */
	public function escape_html_form( $html ) {
		$allowed_tags = [
			'form' => [
				'action'   => true,
				'method'   => true,
				'id'       => true,
				'class'    => true,
				'enctype'  => true,
			],
			'input' => [
				'type'     => true,
				'name'     => true,
				'value'    => true,
				'id'       => true,
				'class'    => true,
				'checked'  => true,
				'placeholder' => true,
				'required' => true,
			],
			'textarea' => [
				'name'     => true,
				'id'       => true,
				'class'    => true,
				'rows'     => true,
				'cols'     => true,
				'placeholder' => true,
				'required' => true,
			],
			'select' => [
				'name'     => true,
				'id'       => true,
				'class'    => true,
				'required' => true,
			],
			'option' => [
				'value'    => true,
				'selected' => true,
			],
			'button' => [
				'type'     => true,
				'name'     => true,
				'value'    => true,
				'id'       => true,
				'class'    => true,
			],
			'label' => [
				'for'      => true,
				'class'    => true,
			],
			'p' => [
				'class'    => true,
			],
			'ul' => [
				'class'    => true,
			],
			'ol' => [
				'class'    => true,
			],
			'li' => [
				'class'    => true,
			],
			'div' => [
				'class'    => true,
				'id'       => true,
			],
			'span' => [
				'class'    => true,
				'id'       => true,
			],
			'br' => [],
			'strong' => [],
			'em' => [],
		];

		return wp_kses( $html, $allowed_tags );
	}

	/**
	 * Retrieves all post meta data for a given post ID as a key-value array.
	 *
	 * @since 2.0.4
	 * @access public
	 *
	 * @param int $id The post ID for which to retrieve metadata.
	 *
	 * @return array|WP_Error Associative array of meta key-value pairs on success.
	 *                        Returns WP_Error if the provided ID is invalid.
	 *                        Returns empty array if no meta data exists for the post.
	 *
	 * @throws WP_Error If the provided ID is not a valid numeric post ID.
	 */
	public function get_post_meta_by_id( $id ) {
		global $wpdb;

		if ( ! is_numeric( $id ) || ! absint( $id ) ) {
			new WP_Error( 'invalid', __( 'Illegal operation', 'quotify' ) );
		}

		$meta = $wpdb->get_results(
			$wpdb->prepare( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d", absint( $id ) ),
			OBJECT_K
		);

		$excluded = [
			'_edit_lock',
			'_edit_last',
			'_wp_old_slug',
			'_wp_attached_file',
			'_wp_attachment_metadata',
		];

		$updated = [];

		if ( ! empty( $meta ) ) {
			foreach ( $meta as $item ) {
				if ( isset( $excluded[ $item->key ] ) ) {
					continue;
				}

				$updated[ $item->meta_key ] = maybe_unserialize( $item->meta_value );
			}
		}

		return $updated;
	}
}
