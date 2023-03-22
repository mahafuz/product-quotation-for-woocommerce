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
	public function generate_privacy_policy( $text ) {
		return function_exists( 'wc_replace_policy_page_link_placeholders' ) ? wc_replace_policy_page_link_placeholders( $text ) : $text;
	}

	/**
	 * Sanitize phone number.
	 * Allows only numbers and "+" (plus sign).
	 *
	 * @since 1.0.0
	 * @param string $phone Phone number.
	 * @return string
	 */
	public function sanitize_phone_number( $phone ) {
		return preg_replace( '/[^\d+]/', '', $phone );
	}

	/**
	 * Get pages list.
	 *
	 * @since 2.0.1
	 */
	public function get_pages() {
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
	 * @param string $field The return field type.
	 * @since 2.0.1
	 */
	public function get_cart( $field = 'id' ) {
		$id = absint( get_option( 'pqfw_quotations_cart' ) );

		return 'url' === $field ? esc_url( get_permalink( $id ) ) : $id;
	}

	/**
	 * Builds the variation tree based on the details.
	 *
	 * @since  1.2.0
	 * @param  string $details The variation details.
	 * @return void
	 */
	public function build_variations( $details ) {
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
}