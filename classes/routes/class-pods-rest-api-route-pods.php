<?php
/**
 * Handles the PodsAPI class endpoint and routes
 *
 * @package   Pods_REST_API
 * @license   GPL-2.0+
 * @copyright 2015 Josh Pollock
 */

class Pods_REST_API_Route_Pods extends Pods_REST_API_Controller {

	/**
	 * Get a collection of items
	 *
	 * NOTE: This is proof of concept.
	 *
	 * @since 0.0.1
	 *
	 * @param WP_JSON_Request $request Full data about the request.
	 * @return mixed WP_Error or WP_JSON_Response.
	 */
	public function get_items( $request ) {
		//@todo get from request
		$pod_name = 'sugar';

		$data = $request->get_params();

		$method = $request->get_method();
		$params = Pods_REST_API_Request_Params::build_params( $data, $pod_name, $method );

		$headers = array();
		if ( ! empty( $params ) ) {
			$pod = pods( $pod_name, $params );

			//@todo replace with a way to get fields based on config
			$response = $pod->export();
			$status = 200;
		}else{
			$error = $this->error( __( sprintf( 'Bad params in %1s', __METHOD__ ), 'pods-rest-api' ) );
			$response = $error->message;
			$status = $response->code;
		}

		$response = pods_rest_api_response( $pod, $response, $status, $headers );

		return $response;

	}


	/**
	 * Get one item from the collection
	 *
	 * @since 0.0.1
	 */
	public function get_item( $request ) {

	}

	/**
	 * Create one item from the collection
	 *
	 * @since 0.0.1
	 */
	public function create_item( $request ) {

	}

	/**
	 * Update one item from the collection
	 *
	 * @since 0.0.1
	 */
	public function update_item( $request ) {

	}

	/**
	 * Delete one item from the collection
	 *
	 * @since 0.0.1
	 */
	public function delete_item( $request ) {

	}

}
