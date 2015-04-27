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
		add_action( 'wp_json_init', array( $this, 'default_routes' ) );
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

		/**
		 * Register default JSON API routes
		 */

		$pods_config = pods_api()->load_pods( array( 'key_names' => true ) );

		//todo creat a list of default_routes&filter to iterate on (PODs&PodsAPI )
		foreach ( $pods_config as $pod => $config  ) {

			// @todo if ( $config['type'] )  use different controller for pod, post-type, .... 
			// @todo get class from default_routes  (see _add_extra_api_post_type_arguments)

			$class = 'Pods_REST_API_Route_Pods';

			if ( ! class_exists( $class ) ) {
			// continue;
			}

			$controller = new $class( $pod );
			$controller->register_routes();

		}

	}

}
