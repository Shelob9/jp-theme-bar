<?php
/* This spits out the html for the jptb-Theme-bar. */
add_action( 'wp_footer', 'jptb_insert_html_bar' );
	function jptb_insert_html_bar () {
		$siteurl = get_bloginfo('url');
        $themes = wp_get_themes( array(
            'allowed' => true
        ) );
		$barLabel = get_option('jptb_label');
		echo "<div id=\"jptb-theme-bar\">";
		echo "<ul>";
			echo "<li>";
			
					echo "<p id='jptb_label'>" . $barLabel . "</p>";
			echo "</li>";
			

			foreach ($themes as $theme ) {
				$themename = $theme['Name'];
				$noSpaceName = strtr( $themename," -","__" );
				$nocapsname = strtolower($noSpaceName);
				$link = $siteurl."/?theme=".$theme->stylesheet;
				$uniqueOptionName = "jptb_" . $nocapsname;
       			$jptb_option_value = get_option($uniqueOptionName);
				if ($jptb_option_value == '1') {
					echo "<li>";
						echo "<a href=\"$link\">$themename</a>";
					echo "</li>";
				}
			}
		
			//link to return to normal, end session
		echo "</ul>";
		echo "</div> <!-- END #jptb-theme-bar -->";
	}