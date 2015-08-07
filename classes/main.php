<?php
/**
 * Main Class for Pods REST API
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */
namespace pods_rest_api;

use pods_rest_api\extend\add_fields;
use pods_rest_api\extend\add_rest_support;
use pods_rest_api\routes\pods;
use pods_rest_api\routes\pods_api;

class main {

	/**
	 * Routes configuration registered for pods-rest-api
	 *
	 * @var array $routes
	 */
	protected $routes_config = array();


	/**
	 * Register action @WordPress
	 *
	 */
	public function __construct() {
		do_action( 'pods_rest_api_init', $this );
		add_action( 'rest_api_init', array( $this, 'pods_routes' ) );
		add_action( 'init', array( $this, 'add_rest_support' ), 25 );

	}

	/**
	 * Include the default routes
	 *
	 * @uses "rest_api_init" action
	 * @uses \PodsAPI
	 *
	 * @since 0.0.1
	 */
	public function pods_routes() {
		$pod_names = array_keys( pods_api()->load_pods( array( 'names' => true ) ) );

		if ( PODS_REST_API_ENABLE_DEFAULT_ROUTES ) {

			// maybe if ( $config['type'] )  use different controller for pod, post-type, ....

			/**
			 * Register default REST API routes
			 */
			$this->set_routes_config( PODS_REST_API_NAMESPACE_URL, $pod_names, '\pods_rest_api\routes\pods' );
			$this->set_routes_config( PODS_REST_API_NAMESPACE_URL . '-api', $pod_names, '\pods_rest_api\routes\pods_api' );

		}

		if( PODS_REST_API_EXTEND_CORE ) {
			$this->extend_core( $pod_names );
		}

		$routes_config = apply_filters( 'pods_rest_api_register_routes', $this->routes_config );

		if ( ! empty( $routes_config ) ) {
			$this->register_routes( $routes_config );
		}
	}

	/**
	 * Extend core endpoints if desired by settings.
	 *
	 * @since 0.1.0
	 *
	 * @param array $pod_names
	 */
	public function extend_core( $pod_names ) {
		foreach( $pod_names as $pod ) {
			$pod = pods( $pod, null, true );
			if ( $pod && pods_rest_api_pod_extends_core_route( $pod ) ) {
				new add_fields( $pod );
			}

		}

	}

	/**
	 * Add REST API support to post type and taxonomy objects.
	 *
	 * @todo Inegrate with Pods core post/taxonomy registration.
	 *
	 * @since 0.1.0
	 */
	public function add_rest_support() {
		$pods = pods_api()->load_pods();


		if ( ! empty( $pods ) && is_array( $pods ) ) {

			foreach ( $pods as $pod ) {
				$type = $pod[ 'type'];
				if( in_array( $type, array(
							'post_type',
							'taxonomy'
						)
					)
				) {
					if ( $pod && pods_rest_api_pod_extends_core_route( $pod ) ) {
						if ( 'post_type' == $type ) {
							add_rest_support::post_type_rest_support( $pod[ 'name' ] );
						}

						if ( 'taxonomy' == $type ) {
							add_rest_support::taxonomy_rest_support( $pod[ 'name' ] );
						}

					}

				}




			}
		}

	}

	/**
	 * Create Routes
	 *
	 * @param array $pod_names Array of Pod Names
	 *
	 * @param string $namespace Part of the url after WP-REST-API  e.g. wp-json/namespace/
	 * @param string $class_name Class to use for the Pods in $pod_names
	 *
	 * @since 0.0.2
	 */
	public function set_routes_config( $namespace, $pod_names, $class_name = null ) {

		foreach ( $pod_names as $pod ) {
			$config = array(
				'pod_name'         => $pod,
				'route'            => $pod,
				'controller_class' => $class_name,
				'namespace'        => $namespace
			);

			$config = apply_filters( 'pods_rest_api_create_routes_config_' . $namespace . '_' . $pod, $config );

			$this->routes_config[ $namespace ] [ $pod ] = $config;
		};
	}


	/**
	 * Register configured Routes
	 *
	 * @since 0.0.2
	 */
	protected function register_routes( $routes_config ) {

		foreach ( $routes_config as $routes ) {
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


	/**
	 * Ensure a REST response is a response object.
	 *
	 * This ensures that the response is consistent, and implements
	 * {@see WP_HTTP_ResponseInterface}, allowing usage of
	 * `set_status`/`header`/etc without needing to double-check the object. Will
	 * also allow {@see WP_Error} to indicate error responses, so users should
	 * immediately check for this value.
	 *
	 * @param \WP_Error|\WP_HTTP_ResponseInterface|mixed $response Response to check.
	 * @param integer $status HTTP status code
	 * @param array $headers HTTP header map
	 *
	 * @return mixed WP_Error if present, WP_HTTP_ResponseInterface if instance,
	 *               otherwise pods_rest_api\infrastructure\rest_response.
	 */
	static function pods_rest_api_ensure_response( $response, $status = 200, $headers = array() ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( $response instanceof \WP_HTTP_ResponseInterface ) {
			return $response;
		}

		return new infrastructure\rest_response( $response, $status, $headers );
	}

}
