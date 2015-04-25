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
	 * @covers Pods_JSON_API_Pods_API::get_pods
	 */
	public function test_get_pods() {

	}

	/**
	 * Test that we can get data about active Pods and limit returned data properly.
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_JSON_API_Pods_API::get_pod
	 */
	public function test_get_pod() {

	}

	/**
	 * Test that we can get data about a specific Pod and limit returned data properly.
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_JSON_API_Pods_API::add_pod
	 */
	public function test_add_pod() {

	}

	/**
	 * Test that we can  update, or create a Pod
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_JSON_API_Pods_API::save_pod
	 */
	public function test_save_pod() {

	}

	/**
	 * Test that we can delete a Pod
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_JSON_API_Pods_API::delete_pod
	 */
	public function test_delete_pod() {

	}

	/**
	 * Test that we can duplicate a Pod
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_JSON_API_Pods_API::duplicate_pod
	 */
	public function test_duplicate_pod() {

	}

	/**
	 * Test that we can get reset a Pod content.
	 *
	 * Note: Pods can only reset custom table Pods. Don't try and test non-existent functionality here.
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_JSON_API_Pods_API::reset_pod
	 */
	public function test_reset_pod() {

	}
}
