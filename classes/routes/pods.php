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

class pods extends request_controller {

	/**
	 * Register routes and endpoints
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
						'context'    => array(
							'default' => 'view',
						),
						'where'      => array(
							'sanitize_callback' => '__return_false'
						),
						'order'      => array(
							'default'           => 'DESC',
							'sanitize_callback' => 'sanitize_key'
						),
						'orderby'    => array(
							'default'           => 'id',
							'sanitize_callback' => 'sanitize_key'
						),
						'limit'      => array(
							'default'           => 15,
							'sanitize_callback' => 'absint',
							'validate_callback' => '__return_true',
						),
						'groupby'    => array(
							'sanitize_callback' => ''
						),
						'pagination' => array(
							'sanitize_callback' => '__return_false'
						),
						'page'       => array(
							'sanitize_callback' => '__return_false'
						),
						'cache'      => array(
							'sanitize_callback' => '__return_false'
						),
						'depth'      => array(
							'default'           => 2,
							'sanitize_callback' => 'absint'
						),
						'fields'     => array(
							'default'           => '',
							'sanitize_callback' => '__return_false',
						),
					)
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => array(),
				)
			)
		);

		register_rest_route( $this->config['namespace'], $this->config['route'] . '/(?P<id>[\d]+)', array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_item' ),
				'permission_callback' => array( $this, 'get_item_permissions_check' ),
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
				'permission_callback' => array( $this, 'update_item_permissions_check' ),
				'args'                => array()
			),
			array(
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'delete_item' ),
				'permission_callback' => array( $this, 'delete_item_permissions_check' ),
				'args'                => array(),
			),
		) );

	}

	/**
	 * Get a collection of items
	 *
	 * @since 0.0.2---
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return mixed WP_Error or \pods_rest_api\infrastructure\response_controller
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
		 * @param array $args Map of query var to query value.
		 * @param WP_JSON_Request $request Full details about the request.
		 */
		$args = apply_filters( 'rest_pods_query', $args, $request );
		// $query_args = $this->prepare_items_query( $args );
		$params = array(
			'depth' => $args['depth'],
		);

		$pod = pods( $this->config['name'], $args );

		$items               = $pod->export_data( $params );
		$items['query_args'] = $args;
		$items['pod']        = $this->config['name'];

		$response = \pods_rest_api\main::pods_rest_api_ensure_response( $items );
		$response->query_navigation_headers( $pod );

		return $response;

	}

	/**
	 * Get a collection of items
	 *
	 * @since 0.0.2---
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return mixed WP_Error or \pods_rest_api\infrastructure\response_controller
	 */
	public function get_item( $request ) {
		return parent::get_items( $request ); // TODO: Change the autogenerated stub
	}

	public function create_item( $request ) {
		return parent::create_item( $request ); // TODO: Change the autogenerated stub
	}

	public function update_item( $request ) {
		return parent::update_item( $request ); // TODO: Change the autogenerated stub
	}

	public function delete_item( $request ) {
		return parent::delete_item( $request ); // TODO: Change the autogenerated stub
	}

	public function get_items_permissions_check( $request ) {
		return parent::permissions_check( $request ); // TODO: Change the autogenerated stub
	}

	public function get_item_permissions_check( $request ) {
		return parent::permissions_check( $request ); // TODO: Change the autogenerated stub
	}

	public function create_item_permissions_check( $request ) {
		return parent::permissions_check( $request ); // TODO: Change the autogenerated stub
	}

	public function update_item_permissions_check( $request ) {
		return parent::permissions_check( $request ); // TODO: Change the autogenerated stub
	}

	public function delete_item_permissions_check( $request ) {
		return parent::permissions_check( $request ); // TODO: Change the autogenerated stub
	}

	public function permissions_check() {
		return parent::permissions_check(); // TODO: Change the autogenerated stub
	}


}