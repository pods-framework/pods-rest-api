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
 * @since 0.1.0
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
		if ( array_key_exists( $field_name, $fields ) ) {
			$pod_options = $pod->pod_data[ 'options' ];
			if ( $read ) {
				if ( pods_v( 'read_all', $pod_options, false ) ) {
					return true;

				}

			} else {
				if ( pods_v( 'write_all', $pod_options, false ) ) {
					return true;

				}

			}

			$field = pods_v( $field_name, $fields, false );
			if ( $field && $read ) {
				if ( 1 == (int) $pod->fields( $field_name, 'rest_read' ) ) {
					return true;

				}

			} else {
				if ( 1 == (int) $pod->fields( $field_name, 'rest_write' ) ) {
					return true;

				}

			}

		}

		return false;

	}

}

/**
 * Check if a Pod supports REST extend core.
 *
 * @since 0.1.0
 *
 * @param array||Pods $pod Pod object or the pod_data array
 *
 * @return bool
 */
function pods_rest_api_pod_extends_core_route( $pod ) {
	$enabled = false;
	if ( is_object( $pod ) ) {
		$pod = $pod->pod_data;
	}

	if ( is_array( $pod ) ) {
		$enabled = pods_v( 'rest_enable', $pod[ 'options' ], false );
	}

	return $enabled;

}
