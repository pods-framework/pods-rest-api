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
	 * @uses "rest_api_init" action
	 *
	 * @since 0.0.1
	 */
	public function default_routes() {
		if ( PODS_REST_API_ENABLE_DEFAULT_ROUTES ) {
			/**
			 * Register default REST API routes
			 */
			$pod_names = array_keys( pods_api()->load_pods( array( 'names' => true ) ) );

			// maybe if ( $config['type'] )  use different controller for pod, post-type, ....

			self::create_routes( $pod_names, PODS_REST_API_BASE_URL, '\pods_rest_api\routes\pods' );
			self::create_routes( $pod_names, PODS_REST_API_BASE_URL . '-api', '\pods_rest_api\routes\pods_api' );
		}

	}

	/**
	 * Create Routes
	 *
	 * @param array $pod_names
	 *
	 * @param string $namespace
	 * @param string $class_name
	 *
	 * @since 0.0.2
	 */
	public function create_routes( $pod_names, $namespace, $class_name ) {
		$routes = array();

		foreach ( $pod_names as $pod ) {
			$routes[ $pod ] = array(
				'name'             => $pod,
				'base'             => $pod,
				'controller_class' => $class_name,
				'namespace'        => $namespace
			);
		};

		$routes = apply_filters( 'pods_rest_api_create_routes' . $namespace, $routes );

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