<?php
/**
 * Handles the Pods class endpoint and routes
 *
 * @package   Pods_REST_API
 * @license   GPL-2.0+
 * @copyright 2015 Josh Pollock
 */
namespace pods_rest_api\routes;

use pods_rest_api\infrastructure\request_controller;
use pods_rest_api\infrastructure\request_params;

class pods extends request_controller {

	/**
	 * Constructor for this class instance
	 *
	 * Creates a separate instance of this class for each Pod.
	 *
	 * @since 0.0.1
	 *
	 * @param array $config
	 */
	public function __construct( $config ) {
		parent::__construct( $config );


	}

	/**
	 * Register routes and endpoints for this Pod
	 *
	 * @since 0.0.2
	 */
	public function register_routes() {
		register_rest_route( $this->config['namespace'], $this->config['route'],
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => array(
							'default' => 'view',
						),
						'depth'   => array(
							'default'           => 2,
							'sanitize_callback' => 'absint',
						),
						'fields'  => array(
							'default' => '',
						),
					)
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'permissions_check' ),
					'args'                => array(),
				)
			)
		);

		register_rest_route( $this->config['namespace'], $this->config['route'] . '/(?P<id>[\d]+)', array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_item' ),
				'permission_callback' => array( $this, 'permissions_check' ),
				'args'                => array(
					'depth' => array(
						'default'           => 2,
						'sanitize_callback' => 'absint'
					),
				),
			),
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'update_item' ),
				'permission_callback' => array( $this, 'permissions_check' ),
				'args'                => array()
			),
			array(
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'delete_item' ),
				'permission_callback' => array( $this, 'permissions_check' ),
				'args'                => array(),
			),
		) );

	}

	/**
	 * Get a collection of items
	 *
	 * @since 0.0.2
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return mixed WP_Error or \pods_rest_api\infrastructure\response_controller
	 * @use
	 */
	public function get_items( $request ) {

		$args = (array) $request->get_params();
		$args = pods_sanitize( $args );

		/**
		 * Alter the query arguments for a request.
		 *
		 * This allows you to set extra arguments or defaults for a post
		 * collection request.
		 *
		 * @uses request_params
		 *
		 * @param array $args Map of query var to query value.
		 * @param \WP_REST_Request $request Full details about the request.
		 */
		$args = apply_filters( 'rest_pods_args', $args, $request );

		$find_args   = request_params::build_params( $args, $this->pods_object->pod, $request );
		$export_args = request_params::prepare_export_args( $args, $this->pods_object );

		$this->pods_object->find( $find_args );
		$items = $this->pods_object->export_data( $export_args );

		// Debugging Output
		$items['query_args'] = $args;
		$items['pod_name']   = $this->config['pod_name'];

		// Assemble response
		$response = \pods_rest_api\main::pods_rest_api_ensure_response( $items );
		$response->query_navigation_headers( $this->pods_object );

		return $response;

	}

	/**
	 * Get a single item
	 *
	 * @since 0.0.2
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return mixed WP_Error or \pods_rest_api\infrastructure\response_controller
	 */
	public function get_item( $request ) {
		$args        = (array) $request->get_params();
		$args        = pods_sanitize( $args );
		$export_args = request_params::prepare_export_args( $args, $this->pods_object );

		$this->pods_object->fetch( $args['id'] );

		$item = $this->pods_object->export_data( $export_args );

		// Assemble response
		$response = \pods_rest_api\main::pods_rest_api_ensure_response( $item );
		$response->query_navigation_headers( $this->pods_object );

		return $response;
	}

	/**
	 * Create a single item
	 *
	 * @since 0.0.2
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return mixed WP_Error or \pods_rest_api\infrastructure\response_controller
	 */
	public function create_item( $request ) {
		$args = (array) $request->get_params();
		$args = pods_sanitize( $args );
		$id   = $this->pods_object->save( $args );

		$export_args = request_params::prepare_export_args( $args, $this->pods_object );
		$this->pods_object->fetch( $id );

		$item = $this->pods_object->export_data( $export_args );
		// Assemble response
		$response = \pods_rest_api\main::pods_rest_api_ensure_response( $item );
		$response->query_navigation_headers( $this->pods_object );

		return $response;
	}

	/**
	 * Update an item
	 *
	 * @since 0.0.2
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return mixed WP_Error or \pods_rest_api\infrastructure\response_controller
	 */
	public function update_item( $request ) {
		$args = (array) $request->get_params();
		$args = pods_sanitize( $args );
		$id   = $this->pods_object->save( $args, null, $args['id'] );

		$export_args = request_params::prepare_export_args( $args, $this->pods_object );
		$this->pods_object->fetch( $id );

		$item = $this->pods_object->export_data( $export_args );
		// Assemble response
		$response = \pods_rest_api\main::pods_rest_api_ensure_response( $item );
		$response->query_navigation_headers( $this->pods_object );

		return $response;
	}

	/**
	 * Delete an item
	 *
	 * @since 0.0.2
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return mixed WP_Error or \pods_rest_api\infrastructure\response_controller
	 */
	public function delete_item( $request ) {
		$args = (array) $request->get_params();
		$args = pods_sanitize( $args );
		$id   = $this->pods_object->delete( $args['id'] );

		$export_args = request_params::prepare_export_args( $args, $this->pods_object );
		$this->pods_object->fetch( $id );

		$item = $this->pods_object->export_data( $export_args );
		// Assemble response
		$response = \pods_rest_api\main::pods_rest_api_ensure_response( $item );
		$response->query_navigation_headers( $this->pods_object );

		return $response;
	}


}
