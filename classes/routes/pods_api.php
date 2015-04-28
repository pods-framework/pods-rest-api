<?php
/**
 * Handles the PodsAPI class endpoint and routes
 *
 * @package   Pods_REST_API
 * @license   GPL-2.0+
 * @copyright 2015 Josh Pollock
 */

namespace pods_rest_api\routes;

use pods_rest_api\infrastructure\request_controller;

class pods_api extends request_controller {

	/**
	 * Register routes and endpoints
	 *
	 * @since 0.0.2
	 */
	public function register_routes() {
		register_rest_route( $this->config['namespace'], $this->config['base'],
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'args'                => array(
						'context' => array(
							'default' => 'view',
						),
						'type'    => array(),
						'page'    => array(),
					),
					'permission_callback' => array( $this, 'permissions_check' )
				)
			)
		);

	}

	/**
	 * Get a collection of items
	 *
	 * @since 0.0.2
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return mixed WP_Error or \pods_rest_api\infrastructure\response_controller
	 */
	public function get_items( $request ) {
		return "Works:)";

	}


}
