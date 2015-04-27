<?php
/**
 * Load plugin now that we are sure dependencies are met
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */

include_once( dirname( __FILE__ ) . '/includes/autoloader.php' );
$loader = new pods_rest_api_autoloader();
$loader->addNamespace('pods_rest_api', dirname( __FILE__ ) . '/classes' );
$loader->register();

new \pods_rest_api\main();

include_once( dirname( __FILE__ ) . '/includes/functions.php' );
