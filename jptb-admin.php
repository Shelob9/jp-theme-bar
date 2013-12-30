<?php
/*==================================================Creating the options page
 * Now we have a page for options.  There's nothing in it yet, but that will come.
 * First we have to explain to WordPress what we're going to make it do
 * It has to know what it will be dealing with before it will populate the page
 */
add_action( 'admin_menu', 'theme_bar_page' );

function theme_bar_page() {
	$page = add_options_page('jptb Theme Bar', 'jptb Theme bar', 'administrator', 'jptb_Menu_ID', 'jptb_html');
	add_action( 'admin_print_styles-' . $page, 'my_admin_scripts' );
}

/*==================================================Setting up the options page
 * This is where we deal with the Settings which are organised within Fields which 
 * are themselves organised in Sections.  These are the things WordPress needs to know,
 * Once it knows these things it will be happy and begin to play ball with the user!
 * Page
 * 		Section
 *			Field
 *				Setting
 */
add_action( 'admin_init', 'register_jptb_settings' );
function register_jptb_settings() {
	
	add_settings_section(
		'jptb_themeChoice_sectionID',
		'Which themes do you want to show?',
		'jptb_themeChoice_HTML',
		'jptb_Menu_ID'
	);
	
	//Label text field
	add_settings_field(
		'jptb_label',
		'Label text',
		'jptb_label_HTML',
		'jptb_Menu_ID',
		'jptb_themeChoice_sectionID'
	);
	register_setting( 'jptb_Menu_ID', 'jptb_label' );
	
	$themes=wp_get_themes();
   	foreach ($themes as $theme ) {
		//the preperation
		$themename = $theme['Name']; 
		$noSpaceName = strtr( $themename," -","__" );
		$nocapsname = strtolower($noSpaceName);
		$uniqueOptionName = "jptb_" . $nocapsname;
		
		//This will have to loop, make a create settings field function and pass the field ID
		add_settings_field(
			$uniqueOptionName,
			$themename,
			'Theme_field_HTML',
			'jptb_Menu_ID',
			'jptb_themeChoice_sectionID',
			$uniqueOptionName
		);
		register_setting( 'jptb_Menu_ID', $uniqueOptionName );
	}
	
	//the main colour field
	add_settings_field(
		'jptb_bg_colour',
		'Background Colour',
		'colour_field_HTML',
		'jptb_Menu_ID',
		'jptb_themeChoice_sectionID'
	);
	register_setting( 'jptb_Menu_ID', 'jptb_bg_colour' );

	add_settings_field(
		'jptb_text_colour',
		'Text Colour',
		'Tcolour_field_HTML',
		'jptb_Menu_ID',
		'jptb_themeChoice_sectionID'
	);
	register_setting( 'jptb_Menu_ID', 'jptb_text_colour' );
	
	//the label colour field
	add_settings_field(
		'jptb_label_bg_colour',
		'Label Background Colour',
		'label_colour_field_HTML',
		'jptb_Menu_ID',
		'jptb_themeChoice_sectionID'
	);
	register_setting( 'jptb_Menu_ID', 'jptb_label_bg_colour' );

	add_settings_field(
		'jptb_label_text_colour',
		'Label Text Colour',
		'label_Tcolour_field_HTML',
		'jptb_Menu_ID',
		'jptb_themeChoice_sectionID'
	);
	register_setting( 'jptb_Menu_ID', 'jptb_label_text_colour' );

}


function jptb_themeChoice_HTML() {
	//echo 'Some help text goes here.';
}

//==================================================================================
//==========================================         HTML CALLBACKS         ========
//==================================================================================

//LABEL TEXT HTML
function jptb_label_HTML() {
	$current_label = get_option('jptb_label');
	if (is_null($current_label)) {
		$current_label = 'My themes:';
	}
	echo "<input type='text' id='jptb_label' name='jptb_label' onBlur='updateLabelText()' value='" . $current_label . "'/>";
}

//THEME CHOICE HTML
function Theme_field_HTML($uniqueOptionName) {
	$setting = esc_attr( get_option( $uniqueOptionName ) );
	if ($setting == '1') {
		$checked = 'checked';
	} else {
		$checked = '';
	}
	echo "<input type='checkbox' id='$uniqueOptionName' name='$uniqueOptionName' value='1' $checked/>";
}

//MAIN COLOUR HTML
function colour_field_HTML() {
	$current_colour = get_option('jptb_bg_colour');
	if (is_null($current_colour)) {
		$current_colour = '#000';
	}
	echo '<div class="color-picker" style="position: relative;">';
	echo "<input type='text' id='jptb_bg_colour' name='jptb_bg_colour' onblur='changeDemoBgColour()' value='" . $current_colour . "'/>";
	echo '<div style="position: absolute; left:190px; bottom:-101px;" id="colorpicker"></div></div>';
}
function Tcolour_field_HTML() {
	$current_tcolour = get_option('jptb_text_colour');
	if (is_null($current_tcolour)) {
		$current_tcolour = '#fff';
	}
	echo '<div class="color-picker2" style="position: relative;">';
	echo "<input type='text' id='jptb_text_colour' name='jptb_text_colour' onblur='changeDemoTextColour()' value='" . $current_tcolour . "'/>";
	echo '<div style="position: absolute; left:190px; bottom:-60px;" id="colorpicker2"></div></div>';
}

//LABEL COLOUR HTML
function label_colour_field_HTML() {
	$current_colour = get_option('jptb_label_bg_colour');
	if (is_null($current_colour)) {
		$current_colour = '#fff';
	}
	echo '<div class="color-picker" style="position: relative;">';
	echo "<input type='text' id='jptb_label_bg_colour' name='jptb_label_bg_colour' onblur='changeDemoLabelBgColour()' value='" . $current_colour . "'/>";
	echo '<div style="position: absolute; left:190px; bottom:-101px;" id="colorpicker"></div></div>';
}
function label_Tcolour_field_HTML() {
	$current_tcolour = get_option('jptb_label_text_colour');
	if (is_null($current_tcolour)) {
		$current_tcolour = '#000';
	}
	echo '<div class="color-picker2" style="position: relative;">';
	echo "<input type='text' id='jptb_label_text_colour' name='jptb_label_text_colour' onblur='changeDemoLabeLTextColour()' value='" . $current_tcolour . "'/>";
	echo '<div style="position: absolute; left:190px; bottom:-60px;" id="colorpicker2"></div></div>';
}

//COLOUR SCRIPTS
function my_admin_scripts() {
    wp_enqueue_style( 'farbtastic' );
    wp_enqueue_script( 'farbtastic' );
    wp_enqueue_script( 'jptb_colour_script', plugins_url() . '/jptb-theme-bar-pro/jptb_colour.js', array( 'farbtastic', 'jquery' ) );
}

//FORM HTML
function jptb_html() {
	?>
    <div class="wrap">
        <h2>jptb Theme Bar Settings</h2>
        <form action="options.php" method="POST">
            <?php settings_fields( 'jptb_Menu_ID' ); ?>
            <?php do_settings_sections( 'jptb_Menu_ID' ); ?>
            <script>
			function updateLabelText() {
				var labtext = document.getElementById('jptb_label').value;
				document.getElementById('jptb_demo_p').innerHTML=labtext;
			}
			//when #jptb_text_colour changes, update jptb_demo colour to #jptb_text_colour value
			function changeDemoTextColour() {
				var texthex = document.getElementById('jptb_text_colour').value;
				document.getElementById('jptb_demo').style.color=texthex;
			}
			
			//when #jptb_bg_colour changes, update jptb_demo background-color to #jptb_bg_colour value
			function changeDemoBgColour() {
				var bghex = document.getElementById('jptb_bg_colour').value;
				document.getElementById('jptb_demo').style.backgroundColor=bghex;
				document.getElementById('jptb_demo_p').innerhtml="hello";
			}
			
			
			//when #jptb_label_text_colour changes, update jptb_demo colour to #jptb_label_text_colour value
			function changeDemoLabelTextColour() {
				var texthex = document.getElementById('jptb_label_text_colour').value;
				document.getElementById('jptb_demo').style.color=texthex;
			}
			
			//when #jptb_label_bg_colour changes, update jptb_demo background-color to #jptb_label_bg_colour value
			function changeDemoLabelBgColour() {
				var bghex = document.getElementById('jptb_label_bg_colour').value;
				document.getElementById('jptb_demo').style.backgroundColor=bghex;
				document.getElementById('jptb_demo_p').innerhtml="hello";
			}
			</script>
            <div id="jptb_demo" style="height:28px; margin-top:60px; color:<?php echo get_option('jptb_text_colour'); ?>; background-color:<?php echo get_option('jptb_bg_colour') ?>;"><p id="jptb_demo_p" style="padding-left:10px; padding-top:5px;"><?php echo get_option('jptb_label'); ?></p></div>
            
            <?php submit_button(); ?>
        </form>
    </div><!-- .wrap -->
    <?php
}
