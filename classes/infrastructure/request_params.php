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
	 * @var array
	 */
	public static $comparisons = array(
		'equals'        => '=',
		'is'			=>	'=',
		'isnot'			=>	'!=',
		'isin'			=>	'IN',
		'isnotin'		=>	'NOT IN',
		'greater'		=>	'>',
		'smaller'		=>	'<',
		'greatereq'		=>	'>=',
		'smallereq'		=>	'<=',
		'contains'		=>	'LIKE',
	);

	/**
	 * Build params array from request data.
	 *
	 * @since 0.0.1
	 *
	 * @param array $data Request data.
	 * @param string $pod_name Name of Pod
	 * @param string $method Transfer method being used.
	 *
	 * @return array|int
	 */
	public static function build_params( $data, $pod_name, $method ) {
		$params = array();
		if ( ! isset( $data['id'] ) ) {
			$params = self::page( $data, $params );
			$params = self::limit( $data, $params );
			$params = self::where( $data, $params );
			$params = self::orderby( $data, $params );
			$params = array( $params );


		} else {
			$params = absint( $data['id'] );

		}

		//@todo a filter here for adding additional query
		//will use $pod_name & $method
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

		if ( ! is_null( $where = pods_v_sanitized( 'where', $data ) ) ) {
			$_where = array(
				'key'     => pods_v( 'key', $where ),
				'value'   => pods_v( 'value', $where ),
				'compare' => pods_v( 'compare', $where )
			);

			$_where['compare'] = self::comparison_translate( $_where['compare'] );

			if ( array_filter( $_where ) ) {
				$params['where'] = $_where;

				return $params;
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
	 *
	 * @return string|void The operator if input was legal.
	 */
	public static function comparison_translate( $comparison ) {
		$comparison = strtolower( $comparison );
		if ( array_key_exists( $comparison, self::$comparisons ) ) {
			return self::$comparisons[ $comparison ];
		}

	}

}
