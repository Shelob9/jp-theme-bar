<?php
/*
Plugin Name: IJM Theme Switcher Bar
Plugin URI: http://iainjmccallum.com/wordpress/live-demo-theme-bar/
Description: Add a theme switcher / theme demo bar to your site. Allows users to switch the theme they see on your site.
Version: 2.0
Author: Iain J McCallum
Author URI: http://www.iainjmccallum.com/
License: GPL
*/


 function jptb_frontend_js() {
    wp_enqueue_script( 'jptb-js', plugin_dir_url( __FILE__ ).'jptb-frontend.js', array('jquery'), null, true );
 }
 add_action( 'wp_enqueue_scripts', 'jptb_frontend_js');

//INSERT THEME BAR HTML
require_once ( 'jptb-theme-bar-html.php' );

//INSERT THEME BAR CSS
require_once ( 'jptb-theme-bar-css.php' );

//THE PLUGIN OPTIONS PAGE
require_once ( 'jptb-theme-bar-options.php' );
