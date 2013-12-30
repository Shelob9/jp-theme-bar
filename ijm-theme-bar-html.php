<?php
/* This spits out the html for the IJM-Theme-bar.
	 * It it set up as a ul within a div.
	 * I have put together a loop to go through every theme installed.
	 * The loop lists the name of each theme within an <a> linking to a live demo on the current site. 
	 * The link runs the original 'Easy Theme Switcher' code above.
	 * Finally I have added a link to end the session and return the site to normal.
	 */
add_action( 'wp_footer', 'IJM_insert_html_bar' );
	function IJM_insert_html_bar () {
		$siteurl = get_bloginfo('url');
		$themes=wp_get_themes();
		$barLabel = get_option('ijmtb_label');
		echo "<div id=\"ijm-theme-bar\">";
		echo "<ul>";
			echo "<li>";
			
					echo "<p id='ijmtb_label'>" . $barLabel . "</p>";
			echo "</li>";
			
			//Thank you to MichaelH for a way to get the theme names!
			foreach ($themes as $theme ) {
				//preperation
				$themename = $theme['Name'];
				$link = $siteurl."/?theme=".$themename;
				$noSpaceName = strtr( $themename," -","__" );
				$nocapsname = strtolower($noSpaceName);
				$uniqueOptionName = "ijmtb_" . $nocapsname;
       			$ijmtb_option_value = get_option($uniqueOptionName);
				if ($ijmtb_option_value == '1') {
					echo "<li>";
						echo "<a href=\"$link\">$themename</a>";
					echo "</li>";
				}
			}
		
			//link to return to normal, end session
		echo "</ul>";
		echo "</div> <!-- END #ijm-theme-bar -->";
	}