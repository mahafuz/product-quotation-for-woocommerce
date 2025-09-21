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

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'wpcf7_skip_mail', [ $this, 'skip_mail' ], 10, 2 );
		add_filter( 'wpcf7_submission_result', [ $this, 'result' ] );
		add_action( 'wpcf7_submit', [ $this, 'handle_submit' ] );
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

		$objs = [];

		foreach ( $posts as $post ) {
			$objs[] = [
				'value' => $post->ID,
				'label' => $post->post_title,
			];
		}

		return $objs;
	}

	/**
	 * Skip mail for quotation form.
	 *
	 * @param bool  $skip_mail The skip mail flag.
	 * @param mixed $form The quotation form.
	 * @return bool
	 */
	public function skip_mail( $skip_mail, $form ) {
		$quote_form_id = absint( \QuotifyContact_Form_7\Database::get_setting( 'form_id' ) );

		if ( $form->id() === $quote_form_id ) {
			$skip_mail = true;
		}

		return $skip_mail;
	}
	
	/**
	 * Change result based on the quotation submission.
	 *
	 * @since 2.5.0
	 * @return array
	 */
	public function result( $result ) {
		$response = [
			'quotify_status' => 'quotation_sent',
			'message'        => __( 'Quotation submitted successfully.', 'quotify' ),
		];

		$response = array_merge( $result, $response );
		$response = apply_filters( 'quotify/addons/contact_form_7/response', $response );
		return $response;
	}


	/**
	 * Handle quotation form submission.
	 *
	 * @param  mixed $form The context form object.
	 * @return mixed
	 */
	public function handle_submit( $form ) {
		$form_id        = $form->id();
		$submission     = \WPCF7_Submission::get_instance();
		$invalid_fields = $submission->get_invalid_fields();
		$quote_form_id  = absint( \QuotifyContact_Form_7\Database::get_setting( 'form_id' ) );

		if ( $form_id === $quote_form_id && empty( $invalid_fields ) && ! $submission->is( 'spam' ) ) {
			$clean_data = [];

			if ( $submission ) {
				$posted_data = $submission->get_posted_data();

				foreach ( $posted_data as $key => $value ) {
					if ( strpos( $key, '_' ) === 0 ) {
						continue;
					}
					$clean_data[ $key ] = $value;
				}
			}

			if ( ! empty( $clean_data ) ) {
				$post_data = $this->map_data( $clean_data );
				$post_id   = pqfw()->product->save( $post_data );

				if ( is_wp_error( $post_id ) ) {
					$submission->add_result_props([
						'quotify_error' => __( 'Something went wrong while sending your quotation.', 'quotify' ),
					]);
				}

				if ( $post_id ) {
					$post_data['meta'] = pqfw()->product->set_attributes( $post_id );
				}

				if ( isset( WC()->session ) ) {
					WC()->session->set( \PQFW\Quotations::CART_KEY, [] );
				}

				$submission->add_result_props([
					'quotify_success' => __( 'Quotation submitted successfully.', 'quotify' ),
				]);
			}
		}
	}

	/**
	 * Handle quotation form submission.
	 *
	 * @param  mixed $form The context form object.
	 * @return mixed
	 */
	public function handle_nsubmit( $form ) {
		$submission = \WPCF7_Submission::get_instance();

		if ( ! $submission ) {
			return;
		}

		$form_id        = $form->id();
		$invalid_fields = $submission->get_invalid_fields();
		$quote_form_id  = absint( \QuotifyContact_Form_7\Database::get_setting( 'form_id' ) );

		if ( $form_id !== $quote_form_id || ! empty( $invalid_fields ) || $submission->is( 'spam' ) ) {
			return;
		}

		$posted_data = $submission->get_posted_data();

		if ( empty( $posted_data ) || ! is_array( $posted_data ) ) {
			return;
		}

		$clean_data = [];

		foreach ( $posted_data as $key => $value ) {
			if ( strpos( $key, '_' ) === 0 ) {
				continue;
			}

			if ( is_array( $value ) ) {
				$clean_data[ $key ] = array_map( 'sanitize_text_field', $value );
			} else {
				$clean_data[ $key ] = sanitize_text_field( $value );
			}
		}

		if ( empty( $clean_data ) ) {
			$submission->add_result_props([
				'quotify_success' => false,
				'quotify_error'   => __( 'Invalid data to save as quotation.', 'quotify' ),
			]);
		}

		$post_data = $this->map_data( $clean_data );
		$post_id   = pqfw()->product->save( $post_data );

		if ( is_wp_error( $post_id ) ) {
			$submission->add_result_props([
				'quotify_success' => false,
				'quotify_error'   => __( 'Something went wrong while sending your quotation.', 'quotify' ),
			]);
		}

		$submission->add_result_props([
			'quotify_success' => true,
			'quotify_message' => __( 'Quotation submitted successfully.', 'quotify' ),
		]);

		return $submission;
	}

	/**
	 * Prepare the post data to save as quotation.
	 *
	 * @param  array $post_data The data to map and prepare as quotation data.
	 * @return array
	 */
	public function map_data( $post_data ) {
		// Default blueprint.
		$collection = [
			'fullname' => '',
			'email'    => '',
			'subject'  => '',
			'phone'    => '',
			'comments' => '',
		];

		if ( empty( $post_data ) || ! is_array( $post_data ) ) {
			return $collection;
		}

		$map = [
			'fullname' => [ 'fullname', 'full_name', 'name', 'your-name' ],
			'email'    => [ 'email', 'your-email', 'user_email' ],
			'subject'  => [ 'subject', 'your-subject', 'title' ],
			'phone'    => [ 'phone', 'your-phone', 'tel', 'telephone' ],
			'comments' => [ 'comments', 'message', 'your-message', 'notes' ],
		];

		foreach ( $map as $key => $aliases ) {
			foreach ( $aliases as $alias ) {
				if ( isset( $post_data[ $alias ] ) && '' !== trim( $post_data[ $alias ] ) ) {
					$collection[ $key ] = sanitize_text_field( $post_data[ $alias ] );
					unset( $post_data[ $alias ] ); // remove so it won’t duplicate into meta.
					break;
				}
			}
		}

		return $collection;
	}

	/**
	 * Get Contact Form 7 fields as key-value pairs.
	 *
	 * @param int $form_id The Contact Form 7 form ID.
	 * @return array|false Array of fields with field names as keys and default values as values, or false if not found.
	 */
	public static function get_cf7_form_fields( $form_id ) {
		if ( ! function_exists( 'wpcf7_contact_form' ) ) {
			return false;
		}

		$form = wpcf7_contact_form( $form_id );

		if ( ! $form ) {
			return false;
		}

		$fields = [];

		$form_tags = $form->scan_form_tags();

		foreach ( $form_tags as $tag ) {
			if ( ! empty( $tag->name ) ) {
				$default_value = isset( $tag->values[0] ) ? $tag->values[0] : '';
				$fields[ $tag->name ] = $default_value;
			}
		}

		return $fields;
	}
}
