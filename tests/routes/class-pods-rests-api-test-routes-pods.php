<?php
/**
 * Tests for Pods route
 *
 * @package   Pods_REST_API
 * @copyright 2015 Pods Framework
 */

class Pods_REST_API_Tests_Routes_Pods extends WP_UnitTestCase {

	/**
	 * Setup
	 *
	 * @since 0.1.0
	 */
	function setUp() {
		parent::setUp();
		$this->server = new WP_JSON_Server;
		$this->class = new Pods_REST_API_Route_Pods( 'pods' );

		$pod_name = 'frogs';
		$params = array(
			'storage' 	=> 'meta',
			'type' 		=> 'post_type',
			'name' 		=> $pod_name,
		);

		$this->$pod_id = pods_api()->save_pod( $params );

		if ( $pod_id ) {
			$params = array(
				'pod_id' 	=> $pod_id,
				'pod' 		=> $pod_name,
				'name' 		=> 'text_test',
				'type' 		=> 'text',
			);
			$field_id = pods_api()->save_field ( $params );

		}

		$this->pod = pods( $pod_name );
		$data = array(
			'text_test' => 'foo',
			'post_title' => 'one'
		);
		$this->item_one_id = $this->pod->save( $data );

		$this->pod->reset();

		$data = array(
			'text_test' => 'bar',
			'post_title' => 'two'
		);
		$this->item_two_id = $this->pod->save( $data );


	}

	/**
	 * Tear down
	 *
	 * @since 0.1.0
	 */
	function tearDown() {
		parent::tearDown();
	}

	public function test_that_tests_work() {
		$this->assertEquals( 1, 1);
	}

	/**
	 * Test that a where query gets the right post only
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_REST_API_Route_Pods::get_items
	 * @covers Pods_REST_API_Route_Pods::__call
	 */
	public function test_get_items_where() {
		$request = new WP_JSON_Request( 'GET', '/pods/pods/frogs' );
		$request->set_query_params( array(
			'where'  => array(
				'key' => 'foo',
				'value' => 'bar',
				'comparison' => 'equals'
			)
		) );
		$response = $this->server->dispatch( $request );

		$this->assertNotInstanceOf( 'WP_Error', $response );
		$response = json_ensure_response( $response );
		$this->assertEquals( 200, $response->get_status() );

		$data = $response->get_data();

		$this->assertArrayHasKey( $this->item_one_id, $data );
		$this->assertArrayNotHasKey( $this->item_two_id, $data );


	}

	/**
	 * Test that a orderby query orders right
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_REST_API_Route_Pods::get_items
	 * * @covers Pods_REST_API_Route_Pods::__call
	 */
	public function test_get_items_orderby() {
		$request = new WP_JSON_Request( 'GET', '/pods/pods/frogs' );
		$request->set_query_params( array(
			'orderby'  => array(
				'field' => 'text_test.metavalue',
				'direction' => 'ASC',
			)
		) );
		$response = $this->server->dispatch( $request );

		$this->assertNotInstanceOf( 'WP_Error', $response );
		$response = json_ensure_response( $response );
		$this->assertEquals( 200, $response->get_status() );

		$data = $response->get_data();

		reset( $data );
		$first_key = key( $data );
		end( $data );
		$last_key = key( $data );


		$this->assertEquals( $this->item_two_id, $first_key );
		$this->assertEquals( $this->item_one_id, $last_key );

	}

	/**
	 * Test that we can query by ID
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_REST_API_Route_Pods::get_item
	 * @covers Pods_REST_API_Route_Pods::__call
	 */
	public function test_get_item() {
		$request = new WP_JSON_Request( 'GET', '/pods/pods/frogs' . $this->item_one_id );
		$request->set_query_params( array(
					) );
		$response = $this->server->dispatch( $request );

		$this->assertNotInstanceOf( 'WP_Error', $response );
		$response = json_ensure_response( $response );
		$this->assertEquals( 200, $response->get_status() );

		$data = $response->get_data();

		$this->assertArrayHasKey( $this->item_one_id, $data );
		$this->assertArrayNotHasKey( $this->item_two_id, $data );

	}

	/**
	 * Test that we can create an item
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_REST_API_Route_Pods::create_item
	 * @covers Pods_REST_API_Route_Pods::__call
	 */
	public function test_create_item() {
		$request = new WP_JSON_Request( 'POST', '/pods/pods/frogs' );
		$request->add_header( 'content-type', 'application/x-www-form-urlencoded' );
		$params = array(
			'post_title' => 'dogs',
			'test_text'  => 'cats'
		);

		$request->set_body_params( $params );

		$response = $this->server->dispatch( $request );

		$this->assertNotInstanceOf( 'WP_Error', $response );
		$response = json_ensure_response( $response );

		$this->assertEquals( 201, $response->get_status() );

		$data = $response->get_data();

		$this->assertEquals( $data[ 'post_title' ], 'dogs' );
		$this->assertEquals( $data[ 'test_text' ], 'cats' );
		$pods = pods( 'frogs', $data['id'] );


		$this->assertEquals( $pods->id(), $data['id'] );
		$this->assertEquals( $pods->display( 'post_title' ), 'dogs' );
		$this->assertEquals( $pods->display( 'test_text' ), 'cats' );

	}

	/**
	 * Test that we can update an item
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_REST_API_Route_Pods::update_item
	 * @covers Pods_REST_API_Route_Pods::__call
	 */
	public function test_update_item() {
		$request = new WP_JSON_Request( 'POST', '/pods/pods/frogs' . $this->item_one_id );
		$request->add_header( 'content-type', 'application/x-www-form-urlencoded' );
		$params = array(
			'post_title' => 'dogs',
			'test_text'  => 'cats'
		);

		$request->set_body_params( $params );

		$response = $this->server->dispatch( $request );

		$this->assertNotInstanceOf( 'WP_Error', $response );
		$response = json_ensure_response( $response );

		$this->assertEquals( 201, $response->get_status() );

		$data = $response->get_data();

		$this->assertEquals( $data[ 'post_title' ], 'dogs' );
		$this->assertEquals( $data[ 'test_text' ], 'cats' );
		$pods = pods( 'frogs', $data['id'] );

		$this->assertEquals( $data['id'], $pods->id() );
		$this->assertEquals( $pods->display( 'post_title' ), 'dogs' );
		$this->assertEquals( $pods->display( 'test_text' ), 'cats' );

	}

	/**
	 * Test that we can delete an item
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_REST_API_Route_Pods::delete_item
	 * @covers Pods_REST_API_Route_Pods::__call
	 */
	public function test_delete_item() {
		$request = new WP_JSON_Request( 'DELETE', '/pods/pods/frogs' . $this->item_one_id );


		$response = $this->server->dispatch( $request );

		$this->assertNotInstanceOf( 'WP_Error', $response );
		$response = json_ensure_response( $response );

		$this->assertEquals( 200, $response->get_status() );


		$pods = pods( 'frogs', $this->item_one_id  );

		$this->assertEquals( 0, $pods->total() );
		$this->assertNotEquals( $this->item_one_id, $pods->id() );

	}



}
