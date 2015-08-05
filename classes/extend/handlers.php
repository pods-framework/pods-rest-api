<?php
/**
 * register_api_field() Handlers for reading/ writing Pods fields.
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */
namespace pods_rest_api\extend;


class handlers {

	/**
	 *
	 * @since 0.1.0
	 *
	 * @var \Pods
	 */
	private static $pod;

	/**
	 * Get Pod object
	 *
	 * @since 0.1.0
	 *
	 * @param $pod_name
	 * @param $id
	 *
	 * @return bool|\Pods
	 */
	protected static function get_pod( $pod_name, $id ) {
		if ( ! self::$pod || self::$pod->pod != $pod_name ) {
			self::$pod = pods( $pod_name, $id, true );
		}

		if ( self::$pod && self::$pod->id != $id ) {
			self::$pod->fetch( $id );
		}

		return self::$pod;

	}

	/**
	 * Handler for getting custom field data.
	 *
	 * @since 0.1.0
	 *
	 * @param array $object The object from the response
	 * @param string $field_name Name of field
	 * @param \WP_REST_Request $request Current request
	 *
	 * @return mixed
	 */
	public static function get_handler( $object, $field_name, $request ) {
		$pod_name = pods_v( 'type', $object );
		$id = pods_v( 'id', $object );
		$pod = self::get_pod( $pod_name, $id );
		if ( $pod && pods_rest_api_field_allowed_to_extend( $field_name, $pod ) ) {
			return $pod->field( $field_name );
		}

	}

	/**
	 * Handler for updating custom field data.
	 *
	 * @since 0.1.0
	 *
	 * @param object $object The object from the response
	 * @param string $field_name Name of field
	 *
	 * @return bool|int
	 */
	public function write_handler( $value, $object, $field_name ) {
		$pod_name = pods_v( 'type', $object );
		$id = pods_v( 'id', $object );
		$pod = self::get_pod( $pod_name, $id );
		if ( $pod && pods_rest_api_field_allowed_to_extend( $field_name, $pod, false ) ) {
			$saved_id = $pod->save( $field_name, $value, $id );
			return $pod->field( $field_name );
		}

	}

}
