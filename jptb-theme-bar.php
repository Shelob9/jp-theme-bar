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
session_start();

function esw_set_theme($theme){
  $_SESSION['tema'] = $_GET['theme'];
}

if(!empty($_GET['theme'])):
  
  esw_set_theme($_GET['theme']);

endif;


function esw_determine_theme()
  {

      $theme = $_SESSION['tema'];
      
      $theme_data = get_theme($theme);
      
      if (!empty($theme_data)) {

          if (isset($theme_data['Status']) && $theme_data['Status'] != 'publish') {
              return false;
          }
          return $theme_data;
      }
      

      $themes = get_themes();
      
      foreach ($themes as $theme_data) {

          if ($theme_data['Stylesheet'] == $theme) {

              if (isset($theme_data['Status']) && $theme_data['Status'] != 'publish') {
                  return false;
              }
              return $theme_data;
          }
      }
      
      return false;
  }
  
  function esw_get_template($template)
  {
      $theme = esw_determine_theme();
      if ($theme === false) {
          return $template;
      }
      
      return $theme['Template'];
  }
  
  function esw_get_stylesheet($stylesheet)
  {
      $theme = esw_determine_theme();
      if ($theme === false) {
          return $stylesheet;
      }
      
      return $theme['Stylesheet'];
  }

if(!empty($_SESSION['tema'])):
 add_action('plugins_loaded','ESW_filters');
endif;

 function ESW_filters () {

 		add_filter('template', 'esw_get_template');
 		add_filter('stylesheet', 'esw_get_stylesheet');

 }
 
 function jptb_frontend_js() {
    wp_enqueue_script( 'jptb-js', plugin_dir_url( __FILE__ ).'jptb-frontend.js', array('jquery'), null, true );
 }
 add_action( 'wp_enqueue_scripts', 'jptb_frontend_js');
//====================   Iain's addition   ====================

//INSERT THEME BAR HTML
require_once ( 'jptb-theme-bar-html.php' );

//INSERT THEME BAR CSS
require_once ( 'jptb-theme-bar-css.php' );

//THE PLUGIN OPTIONS PAGE
require_once ( 'jptb-theme-bar-options.php' );




/* Things to add in the future, I'd love any hints! http://iainjmccallum.com/
 * - A mobile style menu for smaller screens and large numbers of themes.
 * - Theme feature/preview pics displayed on hover + small description.
 * - Customization options:
 * 		- text colour
 *		- bar size
 *		- whither to have a bar or widget (or both)
 *		- individual link colours
 *		- Move the bar's CSS top position up or down in case it interfears with other plugins/themes
 *		- Donation link / Subscription link
 * - Clean up after it's self
 * 		- Delete theme setting when theme is deleted
 * 		- Delete all settings when plugin is deactivated
 */
 
?>