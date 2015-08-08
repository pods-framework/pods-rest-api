<?php
/**
 * Add REST API Options to admin
 *
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */

namespace pods_rest_api\ui;


class admin {

	/**
	 * Constructor for class.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_filter( 'pods_admin_setup_edit_field_options', array( $this, 'add_fields_to_field_editor' ), 12, 2 );
		add_filter( 'pods_admin_setup_edit_options', array( $this, 'add_settings_tab_fields' ), 12, 2 );
		add_filter( 'pods_admin_setup_edit_tabs', array( $this, 'add_settings_tab'), 12, 2 );
	}

	/**
	 * Add a rest api tab.
	 *
	 * @since 0.1.0
	 *
	 * @param array $tabs
	 * @param array $pod
	 *
	 * @return array
	 */
	public function add_settings_tab( $tabs, $pod ) {
		if ( $this->good_pod( $pod ) ) {
			$tabs[ 'rest-api' ] = __( 'REST API', 'pods-rest-api' );
		}

		return $tabs;

	}

	/**
	 * Populate REST API tab.
	 *
	 * @since 0.1.0
	 *
	 * @param array $options
	 * @param array $pod
	 *
	 * @return array
	 */
	public function add_settings_tab_fields( $options, $pod ) {
		if( $this->good_pod( $pod ) ) {
			$options[ 'rest-api' ] = array(
				'rest_enable' => array(
					'label' => __( 'Enable', 'pods' ),
					'help' => __( 'A short descriptive summary of what the post type is.', 'pods' ),
					'type' => 'boolean',
					'default' => '',
					'dependency' => true,
				),
				'rest_base' => array(
					'label' => __( 'Rest Base', 'pods' ),
					'help' => __( 'This will be the url for the route', 'pods' ),
					'type' => 'text',
					'default' => pods_v( 'name', $pod ),
					'boolean_yes_label' => '',
					'depends-on' => array( 'rest_enable' => true ),
				),
				'read_all' => array(
					'label' => __( 'Show All Fields?', 'pods' ),
					'help' => __( 'Show all fields in REST API. If unchecked fields must be enabled on a field by field basis.', 'pods' ),
					'type' => 'boolean',
					'default' => '',
					'boolean_yes_label' => '',
					'depends-on' => array( 'rest_enable' => true ),
				),
				'write_all' => array(
					'label' => __( 'Allow All Fields To Be Update?', 'pods' ),
					'help' => __( 'Allow all fields to be updated via the REST API. If unchecked fields must be enabled on a field by field basis.', 'pods' ),
					'type' => 'boolean',
					'default' => pods_v( 'name', $pod ),
					'boolean_yes_label' => '',
					'depends-on' => array( 'rest_enable' => true ),
				)

			);

		}

		return $options;

	}

	/**
	 * Add a REST API section to advanced tab of field editor.
	 *
	 * @since 0.1.0
	 *
	 * @param array $options
	 * @param array $pod
	 *
	 * @return array
	 */
	public function add_fields_to_field_editor( $options, $pod ) {

		if( $this->good_pod( $pod ) ) {
			$options[ 'advanced' ][ __( 'Rest API', 'pods-rest-api' ) ] =
				array(
					'rest_read' => array(
						'rest_read' => 'read',
						'label' => __( 'Read via REST API?', 'pods-rest-api' ),
						'help' => __( 'Should this field be readable via the REST API? You must enable REST API support for this Pod.', 'pods-rest-api' ),
						'type' => 'boolean',
						'default' => '',
					),
					'rest_write' => array(
						'rest_write' => 'write',
						'label' => __( 'Write via REST API?', 'pods-rest-api' ),
						'help' => __( 'Should this field be readable via the REST API? You must enable REST API support for this Pod.', 'pods-rest-api' ),
						'type' => 'boolean',
						'default' => '',
					),
				);


		}

		return $options;

	}

	/**
	 * Check if Pod type is acceptable.
	 *
	 * @since 0.1.0
	 *
	 * @access protected
	 *
	 * @param array $pod
	 *
	 * @return bool
	 */
	protected function good_pod( $pod ) {
		$type =  $pod[ 'type' ];
		if( in_array( $type, array(
					'post_type',
					'user',
					'taxonomy'
				)
			)
		) {
			return true;

		}

	}

}
