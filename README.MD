Pods REST API
==========

A new Pods add-on for extending the WordPress REST API. Adds default routes and provides tools for making your own.

<strong>Doesn't Work Yet</strong>

This plugin is still under development, and does not work yet.

### Requirements
This plugin requires that on both sites you activate this plugin and the following dependent plugins:

* [WordPress REST API](https://wordpress.org/plugins/json-rest-api/) -- <strong>Note</strong> this plugin requires version 2.0 of the plugin or later. See "philosophy" below.
* [Pods](https://wordpress.org/plugins/pods/) version 2.5.2 or later.
* PHP Version 5.3 or later. PHP 5.5 or later is strongly recomended.

### This vs Pods JSON API
We are making this as a new plugin instead of making a version two of the [Pods JSON API](https://github.com/pods-framework/pods-json-api) in order to make a better end product and not break backwards-compatibility on the existing plugin that many are using.

Pods JSON API will be deprecated once this plugin is completed. The old plugin will still be available, but it will not be supported. You have the right under the GPL to fork it and do what you want with it, but the Pods Foundation will no longer support or develop it.

### Philosophy
* Making a universal, generic works for every Pods site API is impossible. The goal of this plugin is not to work for everyone. In many cases it is better to make your own, site-specific add-on for the core REST API, wrapped around the Pods or PodsAPI classes. We hope this plugin will be a good inspiration for you.
* This plugin will use a test-driven design philosophy.
* All routes will use a common controller (Pods_JSON_API_Controller) for consistency, which will provide filters for, authentication, white-listing fields to be queried by, a default query and what fields are in the returned data.

### License & Copyright
Copyright 2015  [Pods Foundation, Inc.](http://podsfoundation.org)

Licensed under the terms of the [GNU General Public License version 2](http://www.gnu.org/licenses/gpl-2.0.html) or later. Please share with your neighbor.


