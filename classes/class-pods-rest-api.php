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
		add_action( 'wp_json_server_before_serve', array( $this, 'default_routes' ) );
	}

	/**
	 * Include the default routes
	 *
	 * @uses "wp_json_server_before_serve" action
	 *
	 * @since 0.0.1
	 */
	public function default_routes() {
		if ( PODS_REST_API_ENABLE_DEFAULT_ROUTES ) {
			include_once( dirname( __FILE__ ) .'/routes/class-pods-rest-api-route-pods.php' );
			include_once( dirname( __FILE__ ) .'/routes/class-pods-rest-api-route-podsapi.php' );
		}

		new Pods_REST_API_Route_Pods( 'pods' );
		//new Pods_REST_API_Route_PodsAPI( 'podsapi' );

	}

}
