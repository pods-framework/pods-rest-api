<?php
/**
 * Functions for this plugin
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */
function pods_rest_api_response( $pod, $data = null, $status = 200, $headers = array() ) {
	$response = new \pods_rest_api\infrastructure\rest_response( $data, $status, $headers );
	$response->set_pods_object( $pod );

	return $response;
}


/**
 * Check if a field supports read or write via the REST API.
 *
 * @param string $field_name The field name.
 * @param \Pods $pod Pods object.
 * @param bool|true $read Are we checking read or write?
 *
 * @return bool If supports, true, else false.
 */
function pods_rest_api_field_allowed_to_extend( $field_name, $pod, $read = true ) {
	if ( is_object( $pod ) ) {
		$fields = $pod->fields();
		if ( array_key_exists( $field_name, $fields[ $field_name ] ) ) {
			if ( $read ) {
				// @todo test support
				return true;
			} else {
				// @todo test support
				return true;
			}
		}

		return false;
	}

}

/**
 * Check if a Pod supports REST extend core.
 *
 * @todo maybe should return the rest_base arg.
 *
 * @param $pod
 *
 * @return bool
 */
function pods_rest_api_pod_extends_core_route( $pod ) {
	if ( is_object( $pod ) ) {
		// @todo test support
		return true;
	}

	return false;

}
