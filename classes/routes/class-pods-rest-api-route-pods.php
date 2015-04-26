<?php
/**
 * Handles the PodsAPI class endpoint and routes
 *
 * @package   Pods_REST_API
 * @license   GPL-2.0+
 * @copyright 2015 Josh Pollock
 */

class Pods_REST_API_Route_Pods extends Pods_REST_API_Controller {

	public function  __call( $method, $args ) {
		if ( ! method_exists( $this, $method ) && in_array( $method, array(
				'get_items',
				'get_item',
				'create_item',
				'update_item',
				'delete_item'
			)
		) ) {

			if ( isset( $args[0] ) && is_a( $args[0], 'WP_JSON_Request' ) ) {
				$request = $args[0];
			}else{
				//@todo error
				return;
			}


			$data = $request->get_params();
			$id = absint( pods_v( 'id', $request->get_url_params() ) );
			$route = $request->get_route();
			$parse_route = explode( '/', $route );
			end( $parse_route);
			$last = key($parse_route );
			$method = $request->get_method();

			if ( 0 < (int) $id ) {
				$single_item = true;
				$params = $id;
				$key = $last - 1;
				$pod_name = $parse_route[ $key ];
			}else{
				$single_item = false;
				$pod_name = $parse_route[ $last ];
				$params = Pods_REST_API_Request_Params::build_params( $data, $pod_name, $method );

			}

			$headers = array();
			if ( ! empty( $params ) ) {
				$pods = pods( $pod_name, $params );

				if ( $single_item ) {
					//@todo replace with a way to get fields based on config
					$response[ (int) $pods->id() ] = $pods->export();
				} else {
					if ( 0 < $pods->total() ) {
						while ( $pods->fetch() ) {
							//@todo replace with a way to get fields based on config
							$response[ (int) $pods->id() ] = $pods->export();
						}
					} else {
						$response = array();
					}
				}

				$status = 200;
			}else{
				$error = $this->error( __( sprintf( 'Bad params in %1s', __METHOD__ ), 'pods-rest-api' ) );
				$response = $error->message;
				$status = $response->code;
			}

			$response = pods_rest_api_response( $pods, $response, $status, $headers );

			return $response;

		}

	}


}
