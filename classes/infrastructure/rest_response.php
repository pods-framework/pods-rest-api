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
	 * The Pods object
	 *
	 * @since 0.0.2
	 *
	 * @var \Pods $pods_object
	 */
	protected $pods_object;

	/**
	 * Set Pods object for this response.
	 *
	 * @since 0.0.2
	 *
	 * @param $pods_object
	 */
	public function set_pods_object( $pods_object ) {
		$this->pods_object = $pods_object;
	}

	/**
	 * Send navigation-related headers with Pods responses
	 *
	 * @since 0.0.1
	 *
	 * @param \WP_Query|object $query Not used. Exists in order to not violate strict standards.
	 *
	 */
	public function query_navigation_headers( $query ) {
		if ( ! is_object( $this->pods_object ) ) {
			return new \WP_Error( 'pods-rest-api-bad-response-class', __( 'You must pass the Pods object to the Pods response', 'pods-rest-api' ) );
		}

		$max_page = ceil( $this->pods_object->total_found() / $this->pods_object->limit );

		$this->header( 'X-WP-Total', $this->pods_object->total_found() );
		$this->header( 'X-WP-TotalPages', $max_page );

		/**
		 * Runs after headers are set.
		 *
		 * @since 0.0.1
		 *
		 * @param rest_response|object $this Current class instance
		 * @param \Pods|object $pods_object Current Pods object.
		 */
		do_action( 'pods_rest_api_query_navigation_headers', $this, $this->pods_object );

	}

}
