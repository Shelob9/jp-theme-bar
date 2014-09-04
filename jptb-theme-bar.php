<?php
/*
Plugin Name: JP Theme Switcher Bar
Plugin URI: http://jpwp.me/jptb
Description: Adds a theme switcher / theme demo bar to the bottom of your site to allow users to switch the theme they see on your site.
Version: 0.1.0
Author: Josh Pollock
Author URI: http://www.JoshPress.net
License: GPLv2+
License URI:   http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: jptb
Domain Path: /languages
*/
/**
 * Copyright 2013 Josh Pollock
 * Licensed under the terms of the GPL v2 or later.
 *
 * This plugin is based on IJM Theme Switcher Bar  v2.0 (http://iainjmccallum.com/wordpress/live-demo-theme-bar/)
 *  by Iain J McCallum (http://www.iainjmccallum.com/)
 */

//Include the front-end
require_once( 'jptb-frontend.php' );

//Include Admin
require_once( 'jptb-admin.php' );

/**
 * Theme activation hook, creates the option 'jptb_ct' if it does not exist.
 * This will be important later.
 *
 * @package jptb
 * @since 0.0.3
 */
function jptb_activate() {
    $defaults = jptb_frontend::default_options();
    foreach ( $defaults as $key=>$data ) {
        $option= $key;
        $value = $data;
        update_option( $option, $value );
    }

}
register_activation_hook( __FILE__, 'jptb_activate' );


/**
 * Default initialization for the default textdomain.
 *
 * @since 0.1
 */
function jptb_init_domain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'jptb' );
	load_textdomain( 'jptb', WP_LANG_DIR . '/jptb/jptb-' . $locale . '.mo' );
	load_plugin_textdomain( 'jptb', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
