<?php
/**
 * Adds Pods field to REST API routes.
 *
 * @package   Pods REST API
 * @license   GPL-2.0+
 * @copyright 2015 Pods Framework
 */
namespace pods_rest_api\extend;


class add_rest_support {

	/**
	 * Add REST API support to a post type
	 *
	 * @since 0.1.0
	 *
	 * @param string $post_type_name Name of post type
	 * @param bool|false $rest_base Optional. Base url segment. If not set, post type name is used
	 * @param string $controller Optional, controller class for route. If not set "WP_REST_Posts_Controller" is used.
	 */
	public static function post_type_rest_support( $post_type_name, $rest_base = false, $controller = 'WP_REST_Posts_Controller' ) {
		global $wp_post_types;

		if( isset( $wp_post_types[ $post_type_name ] ) ) {
			if ( ! $rest_base ) {
				$rest_base = $post_type_name;
			}

			$wp_post_types[$post_type_name]->show_in_rest = true;
			$wp_post_types[$post_type_name]->rest_base = $rest_base;
			$wp_post_types[$post_type_name]->rest_controller_class = $controller;
		}

	}

	/**
	 * Add REST API support to an already registered taxonomy.
	 *
	 * @since 0.1.0
	 * @param string $taxonomy_name Taxonomy name.
	 * @param bool|false $rest_base Optional. Base url segment. If not set, taxonomy name is used.
	 * @param string $controller Optional, controller class for route. If not set "WP_REST_Terms_Controller" is used.
	 */
	public static function taxonomy_rest_support( $taxonomy_name, $rest_base = false, $controller = 'WP_REST_Terms_Controller' ) {
		global $wp_taxonomies;

		if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) {
			if ( ! $rest_base ) {
				$rest_base = $taxonomy_name;
			}


			$wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;
			$wp_taxonomies[ $taxonomy_name ]->rest_base = $rest_base;
			$wp_taxonomies[ $taxonomy_name ]->rest_controller_class = $controller;
		}


	}

}
