<?php
/**
 * Response controller.
 *
 * NOTE: You should not use this class directly. Use pods_rest_api_response() instead.
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */
namespace pods_rest_api\infrastructure;

class rest_response extends \WP_REST_Response {

	/**
	 * Send navigation-related headers with Pods responses
	 *
	 * @param \Pods|Object $pod_object Not used. Included for sake of strict standards
	 *
	 */
	public function query_navigation_headers( $pod_object ) {

		$max_page = ceil( $pod_object->total_found() / $pod_object->limit );

		$this->header( 'X-WP-Total', $pod_object->total_found() );
		$this->header( 'X-WP-TotalPages', $max_page );

		do_action( 'pods_rest_query_navigation_headers', $this, $pod_object );
	}

}
