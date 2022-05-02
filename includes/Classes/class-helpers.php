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
}