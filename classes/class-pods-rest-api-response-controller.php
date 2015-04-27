<?php

/**
 * Response controller.
 *
 * NOTE: You should not use this class directly. Use pods_rest_api_response() instead.
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */
class Pods_REST_API_Response_Controller extends WP_JSON_Response
{
    /**
     * Pod from result set
     *
     * @since 0.0.1
     *
     * @var object|\Pods
     */
    private $pod;


    /**
     * Set Pod object in this class instance
     *
     * @var object|\Pods
     */
    public function set_pod($pod)
    {
        $this->pod = $pod;
    }

    /**
     * Send navigation-related headers with Pods responses
     *
     * @param \WP_Query|Object $query Not used. Included for sake of strict standardss
     *
     */
    public function query_navigation_headers($query)
    {
        $max_page = ceil($this->pod->total_found() / $this->pod->limit);

        $this->header('X-WP-Total', $this->pod->total_found());
        $this->header('X-WP-TotalPages', $max_page);

        do_action('json_query_navigation_headers', $this, $this->pod);
    }

}
