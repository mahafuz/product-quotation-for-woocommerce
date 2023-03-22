<?php

namespace PQFW\Form;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Restcontroller
 */
abstract class Rest_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $namespace;

	/**
	 * The version of this controller's route.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $version;

	/**
	 * The base of this controller's route.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $restBase;

	/**
	 * Item schema.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $schema;

	/**
	 * Register Rest Api Routes
	 *
	 * @return void
	 */
	abstract public function registerRoute();

	/**
	 * Retrieves the query params for the posts collection.
	 *
	 * @since 1.0.0
	 *
	 * @return array Collection parameters.
	 */
	abstract protected function getCollectionParams();

	/**
	 * Retrieves a single item.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	abstract public function get();

	/**
	 * Retrives the route namespace.
	 *
	 * @since 1.0.0
	 * @return  string namespace of this current route.
	 */
	public function namespace() {
		return $this->namespace . '/' . $this->version;
	}

	/**
	 * Permission check for each route
	 *
	 * @return boolean
	 */
	public function check_permission() {
		// return current_user_can('manage_options');
		return true;
	}

	/**
	 * Throw errors on failure.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Error object on failure.
	 */
	protected function error( $type = '' ) {
		switch ( $type ) {
			case 'authorization':
				return $this->formattedError(
					'authorization_error', __( 'Unathorized Access: You have to logged in first.', 'pqfw' ), 401
				);
				break;
			default:
				return $this->formattedError( 'response_error', __( '400 Bad Request.', 'pqfw' ), 400 );
		}
	}

	/**
	 * Build formatted errors.
	 *
	 * @since 1.0.0
	 * @return WP_Error.
	 */
	protected function formattedError( $code, $message, $http_code, $args = [] ) {
		return new \WP_Error( "pqfw_rest_error_$code", $message, [ 'status' => $http_code ] );
	}
}