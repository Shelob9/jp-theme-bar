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


//INSERT THEME BAR HTML
require_once ( 'jptb-theme-bar-html.php' );

//INSERT THEME BAR CSS
require_once( 'jptb-frontend.php' );

//THE PLUGIN OPTIONS PAGE
require_once ( 'jptb-theme-bar-options.php' );
