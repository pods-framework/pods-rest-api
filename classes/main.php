<?php
/**
 * Main Class for Pods REST API
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */
namespace pods_rest_api;
use pods_rest_api\routes\pods;
use pods_rest_api\routes\pods_api;

class main {

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
			new pods( 'pods' );
			new pods_api( 'pods-api' );
		}

	}

}
