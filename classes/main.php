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
		add_action( 'rest_api_init', array( $this, 'default_routes' ) );
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
			/**
			 * Register default JSON API routes
			 */
			self::create_routes( PODS_REST_API_BASE_URL, '\pods_rest_api\routes\pods' );
			self::create_routes( PODS_REST_API_BASE_URL . '-api', '\pods_rest_api\routes\pods_api' );
		}

	}

	/**
	 * Create Routes
	 *
	 * @param string $base_url
	 * @param string $class
	 *
	 * @since 0.0.2
	 */
	public function create_routes( $namespace, $class_name ) {
		$pods_config = pods_api()->load_pods( array( 'key_names' => true ) );
		$routes      = array();

		foreach ( $pods_config as $pod => $config ) {
			// maybe if ( $config['type'] )  use different controller for pod, post-type, ....
			$routes[ $pod ] = array(
				'name'             => $pod,
				'base'             => $pod,
				'controller_class' => $class_name,
				'namespace'        => $namespace
			);
		};

		$routes = apply_filters( 'pods_rest_api_create_routes' . $namespace, $routes, $pods_config );

		foreach ( $routes as $pod => $config ) {
			$class = ! empty( $config['controller_class'] ) ? $config['controller_class'] : '\pods_rest_api\routes\pods';

			if ( ! class_exists( $class ) ) {
				continue;
			}
			$controller = new $class( $config );

			if ( ! is_subclass_of( $controller, 'pods_rest_api\infrastructure\request_controller' ) ) {
				continue;
			}
			$controller->register_routes();
		}

	}

}