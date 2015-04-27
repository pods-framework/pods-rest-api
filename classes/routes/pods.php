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
	 * Get a collection of items
	 *
	 * @since 0.0.2
	 *
	 * @param \WP_JSON_Request $request Full data about the request.
	 * @return mixed WP_Error or \pods_rest_api\infrastructure\response_controller
	 */
	public function get_items( $request ) {
		return "Works:)";
		
	}
}
