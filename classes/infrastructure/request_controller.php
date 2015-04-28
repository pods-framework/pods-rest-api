<?php
/**
 * Abstract class for all classes that define routes will use.
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */

namespace pods_rest_api\infrastructure;

abstract class request_controller {

	/**
	 * URL for this route
	 *
	 * NOTE: Relative to root URL for WordPress' REST API
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 */
	protected $config;


	/**
	 * Constructor for parent class.
	 *
	 * @param string $route Route name
	 *
	 * @since 0.0.1
	 */
	public function __construct( $config ) {
		$this->config = $config;
	}

	/**
	 * Register the routes for this endpoint
	 *
	 * @since 0.0.1
	 */
	public function register_routes() {
		_doing_it_wrong( 'WP_REST_Controller::register_routes', __( 'The register_routes() method must be overriden' ), 'WPAPI-2.0' );
	}


	/**
	 * Placeholder method!
	 *
	 * @return bool
	 */
	public function permissions_check() {
		return true;
	}

	/**
	 * Placeholder method!
	 *
	 * @return object
	 */
	public function error( $message, $data = array(), $return_partial = true ) {
		$error          = new stdClass();
		$error->code    = 500;
		$error->message = $message;
		if ( $return_partial ) {
			$error->data = $data;
		} else {
			$error->data = $message;
		}


		return $error;
	}
}
