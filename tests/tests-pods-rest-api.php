<?php
/**
 * Tests for Pods REST API outside of routes
 *
 * @package   Pods_REST_API
 * @copyright 2015 Pods Framework
 */

$pods_dir = dirname( dirname( dirname( __FILE__ ) ) ) . '/pods';
if ( ! defined( 'PODS_TEST_PLUGIN_DIR' ) ) {
	define('PODS_TEST_PLUGIN_DIR', $pods_dir );
}

//require $pods_dir . '/tests/includes/testcase.php';

class Pods_REST_API_Tests extends WP_UnitTestCase {

	/**
	 * Setup
	 *
	 * @since 0.1.0
	 */
	function setUp() {
		parent::setUp();
		//$this->pods_test_case = $pods_test_case = new Pods_Unit_Tests\Pods_UnitTestCase();

		activate_plugin( 'pods-rest-api/pods-rest-api.php' );
		activate_plugin( 'pods/init.php' );
		activate_plugin( 'json-rest-api/plugin.php' );
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

	public function test_plugin_active() {
		$this->assertTrue( is_plugin_active( 'pods-rest-api/pods-rest-api.php' ) );
	}

	public function test_pods_active() {
		$this->assertTrue( is_plugin_active( 'pods/init.php' ) );
	}

	public function test_core_api_active() {
		$this->assertTrue( is_plugin_active( 'json-rest-api/plugin.php' ) );
	}
}
