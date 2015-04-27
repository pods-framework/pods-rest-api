<?php
/**
 * Tests for PodsAPI route
 *
 * @package   Pods_REST_API
 * @copyright 2015 Pods Framework
 */
class Pods_REST_API_Tests_Routes_PodsAPI extends WP_UnitTestCase {

	/**
	 * Setup
	 *
	 * @since 0.1.0
	 */
	function setUp() {
		parent::setUp();

		//@TODO IMPORTANT: Must activate the components we need for ACTs.
		$pod_name = 'frogs';
		$params = array(
			'storage' 	=> 'table',
			'type' 		=> 'pod',
			'name' 		=> $pod_name,
		);

		$this->pod_one_id = pods_api()->save_pod( $params );

		if ( $this->pod_one_id ) {
			$params = array(
				'pod_id' 	=> $this->pod_one_id,
				'pod' 		=> $pod_name,
				'name' 		=> 'text_test',
				'type' 		=> 'text',
			);
			$field_id = pods_api()->save_field ( $params );

		}

		$pod_name = 'toads';
		$params = array(
			'storage' 	=> 'table',
			'type' 		=> 'pod',
			'name' 		=> $pod_name,
		);

		$this->pod_two_id = pods_api()->save_pod( $params );

		if ( $this->pod_two_id ) {
			$params = array(
				'pod_id' 	=> $this->$pod_one_id,
				'pod' 		=> $pod_name,
				'name' 		=> 'text_test',
				'type' 		=> 'text',
			);
			$field_id = pods_api()->save_field ( $params );

		}



	}

	/**
	 * Tear down
	 *
	 * @since 0.1.0
	 */
	function tearDown() {
		parent::tearDown();
	}

	/**
	 * Test that tests work
	 *
	 * @since 0.0.1
	 *
	 * @covers Josh
	 */
	public function test_that_tests_work() {
		$this->assertEquals( 1, 1 );
	}

	/**
	 * Test that we can get data about active Pods
	 *
	 * @since 0.0.1
	 *
	 * @covers \pods_rest_api\routes\pods_api::get_pods
	 */
	public function test_get_pods() {
		$request = new WP_JSON_Request( 'GET', '/pods/pods-api' );
		$response = $this->server->dispatch( $request );
		$this->assertNotInstanceOf( 'WP_Error', $response );
		$response = json_ensure_response( $response );
		$this->assertEquals( 200, $response->get_status() );
		$data = $response->get_data();

		$this->assertEquals( 2, count( $data ) );

		//assuming that new version will key be Pod ID
		$this->assertArrayHasKey( $this->pod_one_id, $data );
		$this->assertArrayHasKey( $this->pod_one_two, $data );

		$this->assertArrayHasKey( $data[ $this->pod_one_id ], 'name' );
		$this->assertEquals( $data[  $this->pod_one_id ][ 'name'], 'frogs' );

		$this->assertArrayHasKey( $data[ $this->pod_two_id ], 'name' );
		$this->assertEquals( $data[  $this->pod_two_id ][ 'name' ], 'toads' );

		//@todo add response filter and try again
		//?? diffrent test

	}

	/**
	 * Test that we can get data about active Pods and limit returned data properly.
	 *
	 * @since 0.0.1
	 *
	 * @covers \pods_rest_api\routes\pods_api::get_pod
	 */
	public function test_get_pod() {
		$request = new WP_JSON_Request( 'GET', '/pods/pods-api/frogs' );
		$response = $this->server->dispatch( $request );
		$this->assertNotInstanceOf( 'WP_Error', $response );
		$response = json_ensure_response( $response );
		$this->assertEquals( 200, $response->get_status() );
		$data = $response->get_data();

		$this->assertArrayHasKey( $data, 'name' );
		$this->assertEquals( $data[ 'name' ], 'frogs' );

		//@todo add response filter and try again
		//?? diffrent test

	}

	/**
	 * Test that we can add a Pod
	 *
	 * @since 0.0.1
	 *
	 * @covers \pods_rest_api\routes\pods_api::add_pod
	 */
	public function test_add_pod() {
		$request = new WP_JSON_Request( 'POST', '/pods/pods-api' );
		$request->add_header( 'content-type', 'application/x-www-form-urlencoded' );
		$params = array(
			'storage' 	=> 'table',
			'type' 		=> 'pod',
			'name' 		=> 'lizards'
		);

		$request->set_body_params( $params );

		$response = $this->server->dispatch( $request );
		$this->assertNotInstanceOf( 'WP_Error', $response );
		$this->assertEquals( 200, $response->get_status() );

		$data = $response->get_data();

		$this->assertArrayHasKey( 'id', $data );
		$this->assertNotEquals( 0, intval( $data[ 'id' ] ) );

		$params = array(
			'name' => 'lizards',
			'id' => $data[ 'id' ]
		);
		$exists = Pods_API()->pod_exists( $params );
		$this->assertTrue( $exists );

	}

	/**
	 * Test that we can update a Pod, specifically change its name
	 *
	 * @since 0.0.1
	 *
	 * @covers \pods_rest_api\routes\pods_api::save_pod
	 */
	public function test_save_pod_change_pod_name() {
		$request = new WP_JSON_Request( 'POST', '/pods/pods-api/frogs' );
		$request->add_header( 'content-type', 'application/x-www-form-urlencoded' );
		$params = array(
			'name' => 'dragons',
		);

		$request->set_body_params( $params );

		$response = $this->server->dispatch( $request );
		$this->assertNotInstanceOf( 'WP_Error', $response );
		$this->assertEquals( 200, $response->get_status() );

		$data = $response->get_data();

		$this->assertArrayHasKey( 'id', $data );
		$this->assertNotEquals( 0, intval( $data[ 'id' ] ) );

		$params = array(
			'name' => 'dragons',
			'id' => $data[ 'id' ]
		);
		$exists = Pods_API()->pod_exists( $params );

		$this->assertTrue( $exists );

		$params = array(
			'name' => 'frogs',
		);
		$exists = Pods_API()->pod_exists( $params );

		$this->assertFalse( $exists );

		$fields = pods( 'dragons' )->fields();

		$fields = wp_list_pluck( $fields, 'name' );

		$this->assertArrayHasKey( 'text_two', $fields );

	}

	/**
	 * Test that we can update a Pod, specifically that we can add a new field
	 *
	 * @since 0.0.1
	 *
	 * @covers \pods_rest_api\routes\pods_api::save_pod
	 */
	public function test_save_pod_add_field() {
		$request = new WP_JSON_Request( 'POST', '/pods/pods-api/frogs' );
		$request->add_header( 'content-type', 'application/x-www-form-urlencoded' );
		$params = array(
			'fields' => array(
				array(
					'name' 		=> 'text_two',
					'type' 		=> 'text',
				)

			)
		);

		$request->set_body_params( $params );

		$response = $this->server->dispatch( $request );
		$this->assertNotInstanceOf( 'WP_Error', $response );
		$this->assertEquals( 200, $response->get_status() );

		$data = $response->get_data();
		$this->assertArrayHasKey( 'id', $data );
		$this->assertNotEquals( 0, intval( $data[ 'id' ] ) );

		$fields = pods( 'frogs' )->fields();

		$fields = wp_list_pluck( $fields, 'name' );

		$this->assertArrayHasKey( 'text_two', $fields );

	}


	/**
	 * Test that we can delete a Pod
	 *
	 * @since 0.0.1
	 *
	 * @covers \pods_rest_api\routes\pods_api::delete_pod
	 */
	public function test_delete_pod() {
		$request = new WP_JSON_Request( 'DELETE', '/pods/pods-api/frogs/delete' );

		$response = $this->server->dispatch( $request );
		$this->assertNotInstanceOf( 'WP_Error', $response );
		$this->assertEquals( 200, $response->get_status() );
		$data = $response->get_data();
		$this->assertArrayHasKey( 'id', $data );

		$params = array(
			'name' => 'frogs',
			'id' => $data[ 'id' ]
		);
		$exists = Pods_API()->pod_exists( $params );

		$this->assertFalse( $exists );

	}

	/**
	 * Test that we can duplicate a Pod
	 *
	 * @since 0.0.1
	 *
	 * @covers \pods_rest_api\routes\pods_api::duplicate_pod
	 */
	public function test_duplicate_pod() {
		$request = new WP_JSON_Request( 'DELETE', '/pods/pods-api/frogs/duplicate' );
		$request->add_header( 'content-type', 'application/x-www-form-urlencoded' );
		$params = array(
			'name' => 'dinosaurs'
		);

		$request->set_body_params( $params );

		$response = $this->server->dispatch( $request );
		$this->assertNotInstanceOf( 'WP_Error', $response );
		$this->assertEquals( 200, $response->get_status() );
		$data = $response->get_data();
		$this->assertArrayHasKey( 'id', $data );

		$params = array(
			'name' => 'frogs',
			'id' => $this->pod_one_id,
		);
		$exists = Pods_API()->pod_exists( $params );

		$this->assertTrue( $exists );

		$params = array(
			'name' => 'dinosaurs',
			'id' => $data[ 'id' ]
		);
		$exists = Pods_API()->pod_exists( $params );

		$this->assertTrue( $exists );
	}

	/**
	 * Test that we can get reset a Pod content.
	 *
	 * Note: Pods can only reset custom table Pods. Don't try and test non-existent functionality here.
	 *
	 * @since 0.0.1
	 *
	 * @covers \pods_rest_api\routes\pods_api::reset_pod
	 */
	public function test_reset_pod() {
		$data = array(
			'name' => 'foo',
			'test_text' => 'bar'
		);
		$frogs = pods( 'frogs' );
		$item_id = $frogs->save( $data );
		$request = new WP_JSON_Request( 'DELETE', '/pods/pods-api/frogs/reset' );
		$response = $this->server->dispatch( $request );
		$this->assertNotInstanceOf( 'WP_Error', $response );
		$this->assertEquals( 200, $response->get_status() );
		$data = $response->get_data();
		$this->assertArrayHasKey( 'id', $data );

		$frogs = pods( 'frogs', $item_id );

		$this->assertNotEquals( $frogs->id, $item_id );
		$this->assertEquals( 0, $frogs->total() );

	}
	
}
