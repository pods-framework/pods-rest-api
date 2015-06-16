<?php
/**
 * Handles the PodsAPI class endpoint and routes
 *
 * @package   Pods_REST_API
 * @license   GPL-2.0+
 * @copyright 2015 Josh Pollock
 */

namespace pods_rest_api\routes;

use pods_rest_api\infrastructure\request_controller;

class pods_api extends request_controller {

	/**
	 * Register routes and endpoints
	 *
	 * @since 0.0.2
	 */
	public function register_routes() {
		register_rest_route( $this->config['namespace'], $this->config['route'],
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_pods' ),
					'args'                => array(
					),
					'permission_callback' => array( $this, 'permissions_check' )
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'add_pod' ),
					'args'                => array(
						'type' => array(
							'default' => 'pod',
							'sanitize_callback' => 'strip_tags',
							'validate_callback' => array( $this, 'allowed_pod_types' ),

						),
						'storage' => array(
							'default' => 'pod',
							'sanitize_callback' => 'strip_tags',
							'validate_callback' => array( $this, 'allowed_storage_types' ),

						),
						'name' => array(
							'default' => 'pod',
							'sanitize_callback' => 'pods_clean_name',
						),
					),
					'permission_callback' => array( $this, 'permissions_check' )
				),
			)
		);

		register_rest_route( $this->config['namespace'], $this->config['route'] . '/(?P<pod>[\w\-\_]+)',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_pod' ),
					'args'                => array(
						'name' => array(
							'default' => 0,
							'sanitize_callback' => 'pods_clean_name',
						)
					),
					'permission_callback' => array( $this, 'permissions_check' )
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'save_pod' ),
					'args'                => array(
						'name' => array(
							'default' => 0,
							'sanitize_callback' => 'pods_clean_name',
						),
						'data' => array(
							'default' => '__return_false',
							'sanitize_callback' => 'pods_sanitize'
						)
 					),
					'permission_callback' => array( $this, 'permissions_check' )
				),
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_pod' ),
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

	}

	/**
	 * Get all Pods
	 *
	 * @since 0.0.2
	 *
	 * @return \pods_rest_api\infrastructure\rest_response
	 */
	public function get_pods() {


		$api = pods_api();
		$api->display_errors = false;

		$all_pods = $api->load_pods();

		$pods = array();

		foreach ( $all_pods as $pod ) {
			$pods[] = get_object_vars( $this->cleanup_pod( $pod, false ) );
		}

		$response = \pods_rest_api\main::pods_rest_api_ensure_response( $pods );

		return $response;

	}

	/**
	 * Add a Pod
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @since 0.0.2
	 *
	 * @return \pods_rest_api\infrastructure\rest_response
	 */
	public function add_pod( $request ) {
		$params = $request->params();
		$api = pods_api();
		$api->display_errors = false;

		$id = $api->save_pod( $params );

		$response = \pods_rest_api\main::pods_rest_api_ensure_response( $id );

		return $response;
	}

	/**
	 * Get a single Pod
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @since 0.0.2
	 *
	 * @return \pods_rest_api\infrastructure\rest_response
	 */
	public function get_pod( $request) {
		$params = $request->params();
		$api = pods_api();
		$api->display_errors = false;
		$name = $params[ 'name' ];
		if ( is_int( $name ) ) {
			$data[ 'id' ] = $name;
		}
		else {
			$par$dataams[ 'name' ] = $name;
		}

		$pod = $api->load_pod( $data );
		$pod = get_object_vars( $this->cleanup_pod( $pod, false ) );

		$response = \pods_rest_api\main::pods_rest_api_ensure_response( $pod );

		return $response;


	}

	/**
	 * Update a Pod
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @since 0.0.2
	 *
	 * @return \pods_rest_api\infrastructure\rest_response
	 */
	public function save_pod( $request ) {
		$params = $request->params();
		$api = pods_api();
		$api->display_errors = false;
		$name = $params['name'];
		if ( $name ) {
			if ( is_int( $name ) ) {
				$data['id'] = $name;
			} else {
				$data['name'] = $name;
			}
		}

		if ( is_array( $params[ 'data' ] ) ) {
			$data = array_merge( $data, $params[ 'data' ] );
		}

		$id = $api->save_pod( $data );

		$response = \pods_rest_api\main::pods_rest_api_ensure_response( $id );

		return $response;

	}

	/**
	 * Delete a Pod
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @since 0.0.2
	 *
	 * @return \pods_rest_api\infrastructure\rest_response
	 */
	public function delete_pod( $request ) {
		$params = $request->params();
		$api = pods_api();
		$api->display_errors = false;
		$name = $params['name'];

		if ( is_int( $name ) ) {
			$data['id'] = $name;
		} else {
			$data['name'] = $name;
		}

		$deleted = $api->delete_pod( $params );
		if ( $deleted ) {
			$code = 200;
		}else{
			$code = 500;
		}

		$response = \pods_rest_api\main::pods_rest_api_ensure_response( $deleted, $code );

		return $response;
	}

	/**
	 * Cleanup Pod data for return
	 *
	 * @param array $pod Pod array
	 * @param boolean $fields Include fields in pod
	 *
	 * @return object
	 *
	 * @access protected
	 */
	protected function cleanup_pod( $pod, $fields = true ) {

		$options_ignore = array(
			'pod_id',
			'old_name',
			'object_type',
			'object_name',
			'object_hierarchical',
			'table',
			'meta_table',
			'pod_table',
			'field_id',
			'field_index',
			'field_slug',
			'field_type',
			'field_parent',
			'field_parent_select',
			'meta_field_id',
			'meta_field_index',
			'meta_field_value',
			'pod_field_id',
			'pod_field_index',
			'object_fields',
			'join',
			'where',
			'where_default',
			'orderby',
			'pod',
			'recurse',
			'table_info',
			'attributes',
			'group',
			'grouped',
			'developer_mode',
			'dependency',
			'depends-on',
			'excludes-on'
		);

		$empties = array(
			'description',
			'alias',
			'help',
			'class',
			'pick_object',
			'pick_val',
			'sister_id',
			'required',
			'unique',
			'admin_only',
			'restrict_role',
			'restrict_capability',
			'hidden',
			'read_only',
			'object',
			'label_singular'
		);

		if ( isset( $pod[ 'options' ] ) ) {
			$pod = array_merge( $pod, $pod[ 'options' ] );

			unset( $pod[ 'options' ] );
		}

		foreach ( $pod as $option => $option_value ) {
			if ( in_array( $option, $options_ignore ) || null === $option_value ) {
				unset( $pod[ $option ] );
			}
			elseif ( in_array( $option, $empties ) && ( empty( $option_value ) || '0' == $option_value ) ) {
				if ( 'restrict_role' == $option && isset( $pod[ 'roles_allowed' ] ) ) {
					unset( $pod[ 'roles_allowed' ] );
				}
				elseif ( 'restrict_capability' == $option && isset( $pod[ 'capabilities_allowed' ] ) ) {
					unset( $pod[ 'capabilities_allowed' ] );
				}

				unset( $pod[ $option ] );
			}
		}

		if ( $fields ) {
			$pods_form = pods_form();
			$field_types = $pods_form::field_types();

			$field_type_options = array();

			foreach ( $field_types as $type => $field_type_data ) {
				$field_type_options[ $type ] = $pods_form::ui_options( $type );
			}

			foreach ( $pod[ 'fields' ] as &$field ) {
				if ( isset( $field[ 'options' ] ) ) {
					$field = array_merge( $field, $field[ 'options' ] );

					unset( $field[ 'options' ] );
				}

				foreach ( $field as $option => $option_value ) {
					if ( in_array( $option, $options_ignore ) || null === $option_value ) {
						unset( $field[ $option ] );
					}
					elseif ( in_array( $option, $empties ) && ( empty( $option_value ) || '0' == $option_value ) ) {
						if ( 'restrict_role' == $option && isset( $field[ 'roles_allowed' ] ) ) {
							unset( $field[ 'roles_allowed' ] );
						}
						elseif ( 'restrict_capability' == $option && isset( $field[ 'capabilities_allowed' ] ) ) {
							unset( $field[ 'capabilities_allowed' ] );
						}

						unset( $field[ $option ] );
					}
				}

				foreach ( $field_type_options as $type => $options ) {
					if ( $type == pods_v( 'type', $field ) ) {
						continue;
					}

					foreach ( $options as $option_data ) {
						if ( isset( $option_data[ 'group' ] ) && is_array( $option_data[ 'group' ] ) && !empty( $option_data[ 'group' ] ) ) {
							if ( isset( $field[ $option_data[ 'name' ] ] ) ) {
								unset( $field[ $option_data[ 'name' ] ] );
							}

							foreach ( $option_data[ 'group' ] as $group_option_data ) {
								if ( isset( $field[ $group_option_data[ 'name' ] ] ) ) {
									unset( $field[ $group_option_data[ 'name' ] ] );
								}
							}
						}
						elseif ( isset( $field[ $option_data[ 'name' ] ] ) ) {
							unset( $field[ $option_data[ 'name' ] ] );
						}
					}
				}
			}
		}
		else {
			unset( $pod[ 'fields' ] );
		}

		return (object) $pod;

	}

	/**
	 * Validate that a Pod type argument is valid.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return bool
	 */
	public function allowed_pod_types( $request ) {
		$params = $request->get_params();
		$type = $params[ 'type' ];
		if ( in_array( $type, array(
			'pod',
			'post_type',
			'taxonomy'
		))) {
			return true;

		}else{
			return false;

		}
	}

	/**
	 * Validate that a storage type argument is valid.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return bool
	 */
	public function allowed_storage_types( $request ) {
		$params = $request->get_params();
		$type = $params[ 'type' ];
		if ( in_array( $type, array(
			'table',
			'meta',
		))) {
			return true;

		}else{
			return false;

		}

	}

}
