<?php
/**
 * Abstract class for all classes that define routes will use.
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */
abstract class Pods_REST_API_Controller extends WP_JSON_Controller {

	/**
	 * URL for this route
	 *
	 * NOTE: Relative to root URL for WordPress' REST API
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 */
	protected $route_url;

	/**
	 * Constructor for parent class.
	 *
	 * @param string $route Route name
	 *
	 * @since 0.0.1
	 */
	public function __construct( $route ) {
		$this->route_url = $route;
		$this->register_routes();
	}

	/**
	 * Automatically register the routes for this endpoint
	 *
	 * @todo all routes
	 *
	 * @since 0.0.1
	 */
	public function register_routes() {
		register_json_route( PODS_REST_API_BASE_URL, $this->route_url,
			array(
				array(
					'methods'             => WP_JSON_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'args'                => array(
						'context' => array(
							'default' => 'view',
						),
						'type'    => array(),
						'page'    => array(),
					),
					'permission_callback' => array( $this, 'permissions_check' ),
				)
			)

		);

	}

	/**
	 * Placeholder method!
	 *
	 * @return bool
	 */
	public function permissions_check() {
		return true;
	}

}
