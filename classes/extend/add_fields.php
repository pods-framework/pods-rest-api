<?php
/**
 * @TODO What this does.
 *
 * @package   @TODO
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock
 */

namespace pods_rest_api\extend;


class add_fields {

	/**
	 * Pod
	 *
	 * @param \Pods
	 */
	protected $pod;

	/**
	 * @param |Pods $pod
	 */
	public function __construct( $pod ) {

		$this->set_pod( $pod );
		if( $this->pod ) {
			$this->add_fields();
		}

	}

	private function set_pod( $pod ) {
		if( is_string( $pod ) ) {
			$this->set_pod( pods( $pod, null, true ) );

		}else {
			$type =  $pod->pod_data[ 'type' ];
			if( in_array( $type, array(
						'post_type',
						'user',
					)
				) ) {
				$this->pod = $pod;
			}else{
				$this->pod = false;
			}
		}

	}

	public function add_fields() {
		$fields = $this->pod->fields();
		foreach( $fields as $field_name => $field ) {
			$read = pods_rest_api_field_allowed_to_extend( $field_name, $this->pod, true );
			$write = pods_rest_api_field_allowed_to_extend( $field_name, $this->pod, false );
			$this->register( $field_name, $read, $write );
		}

 	}

	private function register( $field_name, $read, $write ) {
		$args = array();
		switch ( $read ){
			case true == $read :
				$args[ 'get_callback' ] = array( "\\pods_rest_api\\extend\\handlers\\", 'get_handler' );
				break;
			case is_callable( $read ) :
				$args[ 'get_callback' ] = $read;
				$read = true;
				break;
		}

		switch ( $write ){
			case true == $write :
				$args[ 'update_callback' ] = array( "\\pods_rest_api\\extend\\handlers\\", 'write_handler' );
				break;
			case is_callable( $write ) :
				$args[ 'update_callback' ] = $write;
				$write = true;
				break;
		}

		if( $read || $write ) {
			register_api_field( $this->pod->pod, $field_name, $args );
		}

	}


}
