<?php
/*
Plugin Name: Pods REST API
Plugin URI: http://pods.io/
Description: Extends the WordPress REST API for Pods
Version: 0.0.1
Author: Pods Framework Team
Author URI: http://pods.io/about/
Text Domain: pods-rest-api
Domain Path: /languages/
GitHub Plugin URI: https://github.com/pods-rest-api/pods
GitHub Branch: master

Copyright 2015  Pods Foundation, Inc  (email : contact@podsfoundation.org)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * The current version of this plugin.
 *
 * @since 0.0.1
 */
define( 'PODS_REST_API_VERSION', '0.0.1' );

/**
 * The minimum supported version of Pods
 *
 * @todo Make this 2.5.2 or later
 *
 * @since 0.0.1
 */
define( 'PODS_REST_API_MIN_PODS_VERSION', '2.5.1.2' );

if ( ! defined( 'PODS_REST_API_ENABLE_DEFAULT_ROUTES' ) ) {
	/**
	 * Enable default routes
	 *
	 * Set as false in wp-config or anywhere before plugins_loaded to disable default routes.
	 *
	 * @since 0.0.1
	 */
	define( 'PODS_REST_API_ENABLE_DEFAULT_ROUTES', true );
}

if ( ! defined( 'PODS_REST_API_BASE_URL' ) ) {
	/**
	 * Sets the base URL for API.
	 *
	 * This is the first segment(s) after the base prefix for WP REST API. Default is "Pods", can be overridden in wp-config.php or anytime before plugins_loaded
	 *
	 * @since 0.0.1
	 */
	define( 'PODS_REST_API_BASE_URL', 'pods' );
}

/**
 * Load the plugin if dependencies are met.
 */
add_action( 'plugins_loaded', 'pods_rest_api_maybe_load' );
function pods_rest_api_maybe_load() {
	$fail = false;


	if ( ! defined( 'PODS_VERSION' ) ) {
		$fail[] = __( 'Pods REST API requires Pods.', 'pods-rest-api' );
	}

	if ( ! class_exists( 'WP_JSON_Posts_Controller' ) ) {
		$fail[] = __( 'Pods REST API requires the core WordPress REST API be active.', 'pods-rest-api' );
	}

	if ( ! is_array( $fail ) ) {
		if ( ! version_compare( PODS_VERSION, PODS_REST_API_MIN_PODS_VERSION, '>=' ) ) {
			$fail[] = __( sprintf( 'Pods REST API requires Pods version %1s or later. Current version is %2s.', PODS_REST_API_MIN_PODS_VERSION, PODS_VERSION ), 'pods-rest-api' );
		}

		if ( ! version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
			$fail[] = __( sprintf( 'Pods REST API requires PHP version %1s or later. Current version is %2s.', '5.3.0', PHP_VERSION ), 'pods-rest-api' );

		}
	}



	if ( is_array( $fail ) ) {

		if (  is_admin() ) {
			foreach ( $fail as $message ) {
				echo sprintf( '<div id="message" class="error"><p>%s</p></div>',
					$message
				);
			}
		}

		return false;

	}

	include_once( dirname( __FILE__ ) . '/classes/class-pods-rest-api.php' );
	include_once( dirname( __FILE__ ) . '/classes/class-pods-rest-api-controller.php' );
	include_once( dirname( __FILE__ ) . '/classes/class-pods-rest-api-response-controller.php' );
	include_once( dirname( __FILE__ ) . '/classes/class-pods-api-request-params.php' );
	include_once( dirname( __FILE__ ) . '/includes/functions.php' );

	new Pods_REST_API();

}
