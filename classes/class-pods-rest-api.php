<?php
/**
 * Main Class for Pods REST API
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */

class Pods_REST_API {

	public function __construct() {

	}

	/**
	 * Include the default routes
	 *
	 * @since 0.0.1
	 */
	public function default_routes() {
		if ( PODS_REST_API_ENABLE_DEFAULT_ROUTES ) {
			include_once( dirname( __FILE__ ) .'/routes/class-pods-rest-api-route-pods.php' );
			include_once( dirname( __FILE__ ) .'/routes/class-pods-rest-api-route-podsapi.php' );
		}
	}

}
