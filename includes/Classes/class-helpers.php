<?php
/**
 * Contains the plugin helper methods.
 *
 * @since   1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

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
	 * @since 2.0.0
	 * @param array $fields The input fields value to validate.
	 * return mixed
	 */
	public function validate( $fields ) {
		$errors = new \WP_Error();

		$requiredFields = [
			'fullname',
			'email'
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
	 * Get pages list.
	 *
	 * @since 2.0.1
	 */
	public function getPages() {
		$result = [
			[
				'value' => 0,
				'label' => __( 'Select page for Quotations cart', 'pqfw' )
			]
		];

		$pages = get_posts([
			'numberposts' => -1,
			'post_type'   => 'page',
			'post_status' => 'publish'
		]);

		if ( ! is_array( $pages ) || empty( $pages ) ) {
			return [];
		}

		foreach ( $pages as $page ) {
			$result[] = [
				'value' => $page->ID,
				'label' => $page->post_title
			];
		}

		return $result;
	}

	/**
	 * Get default cart page.
	 *
	 * @since 2.0.1
	 */
	public function getCart( $field = 'id' ) {
		$id = absint( get_option( 'pqfw_quotations_cart' ) );

		return 'url' === $field ? esc_url( get_permalink( $id ) ) : $id;
	}
}