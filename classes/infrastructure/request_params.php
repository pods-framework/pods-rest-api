<?php
/**
 * Prepares request parameters for Pods::find() query
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */

namespace pods_rest_api\infrastructure;

class request_params {

	/**
	 * An array of substitutions we can use for queries.
	 *
	 * @since 0.0.1
	 *
	 * @return array
	 */
	public static function comparisons() {
		return array(
			'equals'        =>  '=',
			'is'			=>	'=',
			'isnot'			=>	'!=',
			'isin'			=>	'IN',
			'isnotin'		=>	'NOT IN',
			'greater'		=>	'>',
			'smaller'		=>	'<',
			'greatereq'		=>	'>=',
			'smallereq'		=>	'<=',
			'contains'		=>	'LIKE',
			'and'           => 'AND',
			'or'            => 'OR'
		);
	}


	/**
	 * @todo assemble all fields for $pods->export_data including check of allowed fields
	 *
	 * @param array $args from $request->get_params()
	 *
	 * @return mixed
	 */
	public static function prepare_export_args( $args, $pod ) {

		$export_args = array(
			'depth' => pods_v( 'depth', $args ),
			'fields' => pods_v( 'fields', $args )
		);

		$export_args = apply_filters( 'pods_rest_api_export_args', $export_args, $args, $pod);

		return $export_args;
	}

	/**
	 * Build params array from request data.
	 *
	 * @since 0.0.1
	 *
	 * @param array $data Request data.
	 * @param string $pod_name Name of Pod
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return array|int
	 */
	public static function build_params( $data, $pod_name, $request ) {
		$params = array();
		if ( ! isset( $data['id'] ) ) {
			$params = self::page( $data, $params );
			$params = self::limit( $data, $params );
			$params = self::where( $data, $params );
			$params = self::orderby( $data, $params );

		} else {
			$params = absint( $data['id'] );

		}

		/**
		 * Filter request parameters for Pods::find()
		 *
		 * @since 0.0.2
		 *
		 * @param array $params The params array
		 * @param string $pod_name Name of Pod
		 * @param \WP_REST_Request $request Full details about the request.
		 */
		$params = apply_filters( 'pods_rest_api_params', $params, $pod_name, $request );
		return $params;
	}

	/**
	 * Add where clause to $params
	 *
	 * @since 0.0.1
	 *
	 * @param array $data Request data
	 * @param array $params Optional. Query params to add to. Default is an empty array.
	 *
	 * @return array
	 */
	public static function where( $data, $params = array() ) {

		if ( ! is_null( $where = pods_v( 'where', $data ) ) ) {
			if ( 1 < count( $data[ 'where' ][ 'key' ] ) || 1 < count( $data[ 'where' ][ 'value' ] ) ) {
				$keys = pods_v( 'key', $where, array() );
				$values = pods_v( 'value', $where, array() );
				$comparisons = pods_v( 'compare', $where, array() );
				if( count( $keys ) == count( $values ) ) {

					for ( $i = 0; $i < count( $keys ); $i++ ) {
						if ( isset(  $comparisons[ $i ] ) ) {
							$_compare = self::comparison_translate( $comparisons[ $i ] );
						}else{
							$_compare = 'AND';
						}

						$_where[] = array(
							'key' => $keys[ $i ],
							'value' => $values[ $i ],
							'compare' => $_compare
						);

					}
					$where = array(
						'relation' => self::comparison_translate( $where[ 'relation' ], 'AND' ),
					);

					foreach( $_where as $w ) {
						$where[] = $w;
					}

					$params[ 'where' ] = $where;


				}else{
					//@todo make an error here!
				}

			}else{
				$_where = array(
					'key'     => pods_v( 'key', $where ),
					'value'   => pods_v( 'value', $where ),
					'compare' => pods_v( 'compare', $where, 'equals' ),
				);

				$_where['compare'] = self::comparison_translate( $_where['compare'] );
				if ( array_filter( $_where ) ) {
					$relation =  pods_v( 'relation', $where, 'and' );

					$params['where'] = array(
						'relation' => self::comparison_translate( $relation, 'AND' ),
						$_where
					);


					return $params;
				}

			}



			return $params;

		}

		return $params;

	}

	/**
	 * Add orderby clause to $params
	 *
	 * @since 0.0.1
	 *
	 * @param array $data Request data
	 * @param array $params Optional. Query params to add to. Default is an empty array.
	 *
	 * @return array
	 */
	public static function orderby( $data, $params = array() ) {
		if ( ! is_null( $order = pods_v_sanitized( 'orderby', $data ) ) ) {
			if ( ! is_null( $field = pods_v( 'field', $order ) ) && ( ! is_null( $dir = pods_v( 'direction', $order ) ) && in_array( $dir, array(
						'ASC',
						'DESC'
					) ) )
			) {

				$_orderby = sprintf( '%1s %2s', $field, $dir );

				if ( pods_v( 'cast', $order ) && ! is_null( $cast_as = pods_v( 'cast_as', $order ) ) ) {
					$_orderby = sprintf( 'CAST( %1s AS %2s)', $_orderby, $cast_as );
				}

				if ( is_string( $_orderby ) ) {
					$params['orderby'] = $_orderby;

					return $params;
				}

				return $params;

			}

			return $params;

		}

		return $params;
	}

	/**
	 * Add limit clause to $params
	 *
	 * @since 0.0.1
	 *
	 * @param array $data Request data
	 * @param array $params Optional. Query params to add to. Default is an empty array.
	 *
	 * @return array
	 */
	public static function limit( $data, $params = array() ) {
		$params['limit'] = pods_v( 'limit', $data, 15 );

		return $params;
	}

	/**
	 * Add page clause to $params
	 *
	 * @since 0.0.1
	 *
	 * @param array $data Request data
	 * @param array $params Optional. Query params to add to. Default is an empty array.
	 *
	 * @return array
	 */
	public static function page( $data, $params = array() ) {
		if ( ! $page = is_null( pods_v( 'page', $data ) ) ) {
			$params['page'] = $page;

			return $params;
		}

		return $params;

	}

	/**
	 * Translates URL friendly comparisons to actual SQL operators
	 *
	 * @since 0.0.1
	 *
	 * @param string $comparison Comparison, must be in self::$comparisons
	 * @param string $default Optional. Default to return if validation fails.
	 *
	 * @return string The operator, if input was legal, if not return $default
	 */
	public static function comparison_translate( $comparison, $default = '=' ) {
		if ( is_null( $comparison ) ) {
			return $default;
		}

		$comparison = strtolower( $comparison );
		$comparisons = self::comparisons();
		if ( array_key_exists( $comparison, $comparisons ) ) {
			$comparison = $comparisons[ $comparison ];
			return $comparison;
		}else{
			return $default;
		}

	}

}
