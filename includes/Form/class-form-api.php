<?php

namespace PQFW\Form;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Handles testimonial submission form.
 *
 * @since 2.0.7
 */
class Form_Api extends Rest_Controller {

	/**
	 * Initially Invoked
	 */
	public function __construct() {

		$this->namespace = 'pqfw';
		$this->version   = 'v1';
		$this->rest_base = 'form';
		$this->key       = 'pqfw_form_markup';

		add_action( 'rest_api_init', [ $this, 'registerRoute' ] );
	}

	/**
	 * Register Rest Api Routes
	 *
	 * @return void
	 */
	public function registerRoute() {
		register_rest_route(
			$this->namespace(),
			'/' . $this->rest_base,
			[
				[
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get' ],
					'permission_callback' => [ $this, 'check_permission' ]
				],
				[
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create' ],
					'permission_callback' => [ $this, 'check_permission' ],
					'args'                => [
						// TODO: Add Schema.
					]
				],
			]
		);
	}

	/**
	 * Collection param.
	 */
	protected function getCollectionParams() {
		$params = [];

		$params['limit'] = [
			'description'       => __( 'Limit the result set by a specific number of items.' ),
			'type'              => 'integer',
			'validate_callback' => function( $id ) {
				return absint( $id );
			}
		];

		return $params;
	}

	/**
	 * Save form setting.
	 *
	 * @param WP_JSON_RESPONSE $request The request object.
	 */
	public function create( $request ) {
		$data = $request->get_param( 'task_data' );

		$filtered = array_filter( $data, function( $item ) {
			return $item['required'];
		});

		$required_fields = array_map( function( $field ) {
			return str_replace( ' ', '_', strtolower( $field['text'] ) );
		}, $filtered );

		update_option( 'pqfw_form_required_fields', $required_fields );
		$this->saveFormMarkup( $data );
	}

	/**
	 * Save form markup.
	 *
	 * @since 2.0.4
	 * @param array $data The form fields to save.
	 */
	public function saveFormMarkup( $data ) {
		$updated = update_option( $this->key, wp_json_encode( $data ), false );

		if ( $updated ) {
			wp_send_json_success(
				__( 'Form fields updated', 'pqfw' ),
				200
			);
		}
	}

	/**
	 * Get form markup.
	 *
	 * @since 2.0.4
	 */
	public function getFormMarkup() {
		$form = get_option( $this->key, false );

		if ( ! $form ) {
			$default_form = '[{"id":"ECCABDAD-43C6-46BF-A7DD-F5D9F78F3062","element":"TextInput","text":"Full Name","required":false,"canHaveAnswer":true,"canHavePageBreakBefore":true,"canHaveAlternateForm":true,"canHaveDisplayHorizontal":true,"canHaveOptionCorrect":true,"canHaveOptionValue":true,"canPopulateFromApi":true,"label":"Full Name"},{"id":"F6C2115E-A76E-4C6C-AC14-892F69CBD197","element":"TextInput","text":"Subject","required":false,"canHaveAnswer":true,"canHavePageBreakBefore":true,"canHaveAlternateForm":true,"canHaveDisplayHorizontal":true,"canHaveOptionCorrect":true,"canHaveOptionValue":true,"canPopulateFromApi":true,"label":"Subject"},{"id":"7891386B-6EC1-4490-A8CA-E0D5C4C51CC5","element":"TextInput","text":"Email","required":false,"canHaveAnswer":true,"canHavePageBreakBefore":true,"canHaveAlternateForm":true,"canHaveDisplayHorizontal":true,"canHaveOptionCorrect":true,"canHaveOptionValue":true,"canPopulateFromApi":true,"label":"Email"},{"id":"A2417105-B266-4537-A14F-5282951710EF","element":"TextInput","text":"Phone\/Mobile","required":false,"canHaveAnswer":true,"canHavePageBreakBefore":true,"canHaveAlternateForm":true,"canHaveDisplayHorizontal":true,"canHaveOptionCorrect":true,"canHaveOptionValue":true,"canPopulateFromApi":true,"label":"Phone\/Mobile"},{"id":"07C2E624-F875-43E4-94CF-B0A0A0E60237","element":"TextArea","text":"Comments","required":false,"canHaveAnswer":true,"canHavePageBreakBefore":true,"canHaveAlternateForm":true,"canHaveDisplayHorizontal":true,"canHaveOptionCorrect":true,"canHaveOptionValue":true,"canPopulateFromApi":true,"label":"Comments"}]';

			return json_decode( $default_form );
		}

		return json_decode( $form );
	}

	/**
	 * Undocumented function
	 *
	 * @return json
	 */
	public function get() {
		return $this->getFormMarkup();
	}

	/**
	 * Undocumented function
	 *
	 * @return json
	 */
	public function getFormatted() {
		$fields = [];

		foreach ( $this->getFormMarkup() as $field ) {
			$fields[ pqfw()->formBuilder->getFieldName( $field->text ) ] = [
				'name'     => pqfw()->formBuilder->getFieldName( $field->text ),
				'required' => wp_validate_boolean( $field->required )
			];
		}

		return $fields;
	}
}