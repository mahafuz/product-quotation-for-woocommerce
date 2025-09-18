<?php
/**
 * Elementor widget that inserts an embed-able content into the page, from any given URL.
 *
 * @since 2.0.3
 * @package Quotify
 */

namespace QuotifyContact_Form_7;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Contact form 7 support for the plugin.
 *
 * @since 2.5.0
 */
class Hook {
	// use WPCF7_SWV_SchemaHolder;
	// use WPCF7_PipesHolder;

	public static $found_items;

	public function __construct() {
	}

	/**
	 * Retrieves contact form data that match given conditions.
	 *
	 * @param string|array $args Optional. Arguments to be passed to WP_Query.
	 * @return array Array of WPCF7_ContactForm objects.
	 */
	public static function find( $args = '' ) {
		$defaults = [
			'post_type'      => 'wpcf7_contact_form',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'offset'         => 0,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		];

		$args = wp_parse_args( $args, $defaults );

		$query = new \WP_Query();
		$posts = $query->query( $args );

		self::$found_items = $query->found_posts;

		$objs = [];

		foreach ( $posts as $post ) {
			$objs[] = [
				'value' => $post->ID,
				'label' => $post->post_title,
			];
		}

		return $objs;
	}
}
