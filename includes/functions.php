<?php
/**
 * Functions for this plugin
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */
function pods_rest_api_response( $pod, $data = null, $status = 200, $headers = array() ) {
	$response = new Pods_REST_API_Response_Controller( $data, $status, $headers );
	$response->set_pod( $pod );

	return $response;
}
