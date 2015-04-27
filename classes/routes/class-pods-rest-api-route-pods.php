<?php
/**
 * Handles the PodsAPI class endpoint and routes
 *
 * @package   Pods_REST_API
 * @license   GPL-2.0+
 * @copyright 2015 Josh Pollock
 */

class Pods_REST_API_Route_Pods extends Pods_REST_API_Controller {

	/**
	 * URL for this route
	 *
	 * NOTE: Relative to root URL for WordPress' REST API
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 */
	protected $pod;

	/**
	 * Constructor for parent class.
	 *
	 * @param string $route Route name
	 *
	 * @since 0.0.1
	 */
	public function __construct( $pod ) {
		$this->pod = $pod;
	}

	public function register_routes() {
		$pod = $this->pod;
			register_json_route( 'pods', $pod,
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
						'permission_callback' => array( $this, 'permissions_check' )
					)

				)

			);

			register_json_route( PODS_REST_API_BASE_URL, '/' . $pod . '/(?P<id>[\d]+)',
				array (
					array(
						'methods'         => WP_JSON_Server::READABLE,
						'callback'        => array( $this, 'get_item' ),
						'permission_callback' => array( $this, 'permissions_check' ),
						'args'            => array(
							'context'          => array(
								'default'      => 'view'
							)
						)
					)
				)
			);
		}


	public function get_items( $request ) {
		$args = (array) $request->get_params();
		$args = pods_sanitize( $args );
		/**
		 * Alter the query arguments for a request.
		 *
		 * This allows you to set extra arguments or defaults for a post
		 * collection request.
		 *
		 * @param array $args Map of query var to query value.
		 * @param WP_JSON_Request $request Full details about the request.
		 */
		$args = apply_filters( 'json_pods_query', $args, $request );
		// $query_args = $this->prepare_items_query( $args );


		$params = array(
				'depth'  => 2,
				'limit' => 15,
				'params' => $args,
			);

		$items = pods_api()->export( $this->pod, $params );

		$items['query_args'] = $params;
		$items['pod'] = $this->pod;


		$response = json_ensure_response( $items );

		return $response;
	}

}
