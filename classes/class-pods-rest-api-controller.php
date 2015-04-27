<?php

/**
 * Abstract class for all classes that define routes will use.
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */
abstract class Pods_REST_API_Controller
{

    /**
     * Automatically register the routes for this endpoint
     *
     * @todo all routes
     *
     * @since 0.0.1
     */
    public function register_routes()
    {
        _doing_it_wrong('WP_JSON_Controller::register_routes', __('The register_routes() method must be overriden'), 'WPAPI-2.0');
    }

    /**
     * Placeholder method!
     *
     * @return bool
     */
    public function permissions_check()
    {
        return true;
    }

    /**
     * Placeholder method!
     *
     * @return object
     */
    public function error($message, $data = array(), $return_partial = true)
    {
        $error = new stdClass();
        $error->code = 500;
        $error->message = $message;
        if ($return_partial) {
            $error->data = $data;
        } else {
            $error->data = $message;
        }


        return $error;
    }
}
