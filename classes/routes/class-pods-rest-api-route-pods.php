<?php
/**
 * Handles the PodsAPI class endpoint and routes
 *
 * @package   Pods_REST_API
 * @license   GPL-2.0+
 * @copyright 2015 Josh Pollock
 */

class Pods_REST_API_Route_Pods extends Pods_REST_API_Controller {

	public function get_items( $request ) {
		$data        = $request->get_params();
		$id          = absint( pods_v( 'id', $request->get_url_params() ) );
		$route       = $request->get_route();
		$parse_route = explode( '/', $route );
		end( $parse_route );
		$last   = key( $parse_route );
		$method = $request->get_method();


		$pod_name = $parse_route[ $last ];
		$params   = Pods_REST_API_Request_Params::build_params( $data, $pod_name, $method );


		$headers = $response = array();

		$pods = pods( $pod_name, $params );
		if ( 0 < $pods->total() ) {
			while ( $pods->fetch() ) {
				//@todo replace with a way to get fields based on config
				$response[ (int) $pods->id() ] = $pods->export();
			}
		}
		$status = 200;

		$response = pods_rest_api_response( $pods, $response, $status, $headers );

		return $response;

	}

}
