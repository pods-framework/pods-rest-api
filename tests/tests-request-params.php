<?php
/**
 * Tests for Pods_REST_API_Request_Params class
 *
 * @package   Pods_REST_API
 * @copyright 2015 Pods Framework
 */

class Pods_REST_API_Tests_Pods_REST_API_Tests_Request_Params extends WP_UnitTestCase {
	/**
	 * Setup
	 *
	 * @since 0.1.0
	 */
	function setUp() {
		parent::setUp();

		$this->params = array(
			'where'  => array(
				'key' => 'foo',
				'value' => 'bar',
				'comparison' => 'equals'
			),
			'orderby'  => array(
				'field' => 'text_test.metavalue',
				'direction' => 'ASC',
			),
			'page' => 5,
			'limit' => 11,
		);

		$this->add_params = array(
			'hats' => 'bats'
		);

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
	 * Test we can build where params
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_REST_API_Request_Params::where
	 */
	public function test_where() {
		$data = Pods_REST_API_Request_Params::where( $this->params[ 'where' ] );
		$this->assertSame( $this->params[ 'where' ], $data );

		$data_plus_hats = Pods_REST_API_Request_Params::where( $this->params[ 'where' ], array( 'hats', 'bats' ) );
		$should_be = $this->params[ 'where' ];
		$should_be[ 'hats' ] = 'bats';
		$this->assertSame( $data_plus_hats, $should_be );

	}

	/**
	 * Test we can build orderby params
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_REST_API_Request_Params::orderby
	 */
	public function test_orderby() {
		$data = Pods_REST_API_Request_Params::orderby( $this->params[ 'orderby' ] );
		$this->assertSame( $this->params[ 'orderby' ], $data );

		$data_plus_hats = Pods_REST_API_Request_Params::where( $this->params[ 'orderby' ], array( 'hats', 'bats' ) );
		$should_be = $this->params[ 'orderby' ];
		$should_be[ 'hats' ] = 'bats';
		$this->assertSame( $data_plus_hats, $should_be );

	}

	/**
	 * Test we can build limit params
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_REST_API_Request_Params::limit
	 */
	public function test_limit() {
		$data = Pods_REST_API_Request_Params::limit( $this->params[ 'limit' ] );
		$this->assertSame( $this->params[ 'limit' ], $data );

		$data_plus_hats = Pods_REST_API_Request_Params::limit( $this->params[ 'limit' ], array( 'hats', 'bats' ) );
		$should_be = $this->params[ 'limit' ];
		$should_be[ 'hats' ] = 'bats';
		$this->assertSame( $data_plus_hats, $should_be );

	}

	/**
	 * Test we can build page params
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_REST_API_Request_Params::page
	 */
	public function test_page() {
		$data = Pods_REST_API_Request_Params::page( $this->params[ 'page' ] );
		$this->assertSame( $this->params[ 'page' ], $data );

		$data_plus_hats = Pods_REST_API_Request_Params::page( $this->params[ 'page' ], array( 'hats', 'bats' ) );
		$should_be = $this->params[ 'page' ];
		$should_be[ 'hats' ] = 'bats';
		$this->assertSame( $data_plus_hats, $should_be );

	}

	/**
	 * Test we can build full params
	 *
	 * @since 0.0.1
	 *
	 * @covers Pods_REST_API_Request_Params::page
	 */
	public function build_params() {
		$data = Pods_REST_API_Request_Params::build_params( $this->params[ 'page' ], 'frogs', 'GET' );
		$this->assertSame( $this->params, $data );
		
	}






}
