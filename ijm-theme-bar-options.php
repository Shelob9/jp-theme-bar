<?php
/*==================================================Creating the options page
 * Now we have a page for options.  There's nothing in it yet, but that will come.
 * First we have to explain to WordPress what we're going to make it do
 * It has to know what it will be dealing with before it will populate the page
 */
add_action( 'admin_menu', 'theme_bar_page' );

function theme_bar_page() {
	$page = add_options_page('IJM Theme Bar', 'IJM Theme bar', 'administrator', 'ijmtb_Menu_ID', 'ijmtb_html');
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
add_action( 'admin_init', 'register_ijmtb_settings' );
function register_ijmtb_settings() {
	
	add_settings_section(
		'ijmtb_themeChoice_sectionID',
		'Which themes do you want to show?',
		'ijmtb_themeChoice_HTML',
		'ijmtb_Menu_ID'
	);
	
	//Label text field
	add_settings_field(
		'ijmtb_label',
		'Label text',
		'ijmtb_label_HTML',
		'ijmtb_Menu_ID',
		'ijmtb_themeChoice_sectionID'
	);
	register_setting( 'ijmtb_Menu_ID', 'ijmtb_label' );
	
	$themes=wp_get_themes();
   	foreach ($themes as $theme ) {
		//the preperation
		$themename = $theme['Name']; 
		$noSpaceName = strtr( $themename," -","__" );
		$nocapsname = strtolower($noSpaceName);
		$uniqueOptionName = "ijmtb_" . $nocapsname;
		
		//This will have to loop, make a create settings field function and pass the field ID
		add_settings_field(
			$uniqueOptionName,
			$themename,
			'Theme_field_HTML',
			'ijmtb_Menu_ID',
			'ijmtb_themeChoice_sectionID',
			$uniqueOptionName
		);
		register_setting( 'ijmtb_Menu_ID', $uniqueOptionName );
	}
	
	//the main colour field
	add_settings_field(
		'ijmtb_bg_colour',
		'Background Colour',
		'colour_field_HTML',
		'ijmtb_Menu_ID',
		'ijmtb_themeChoice_sectionID'
	);
	register_setting( 'ijmtb_Menu_ID', 'ijmtb_bg_colour' );

	add_settings_field(
		'ijmtb_text_colour',
		'Text Colour',
		'Tcolour_field_HTML',
		'ijmtb_Menu_ID',
		'ijmtb_themeChoice_sectionID'
	);
	register_setting( 'ijmtb_Menu_ID', 'ijmtb_text_colour' );
	
	//the label colour field
	add_settings_field(
		'ijmtb_label_bg_colour',
		'Label Background Colour',
		'label_colour_field_HTML',
		'ijmtb_Menu_ID',
		'ijmtb_themeChoice_sectionID'
	);
	register_setting( 'ijmtb_Menu_ID', 'ijmtb_label_bg_colour' );

	add_settings_field(
		'ijmtb_label_text_colour',
		'Label Text Colour',
		'label_Tcolour_field_HTML',
		'ijmtb_Menu_ID',
		'ijmtb_themeChoice_sectionID'
	);
	register_setting( 'ijmtb_Menu_ID', 'ijmtb_label_text_colour' );

}


function ijmtb_themeChoice_HTML() {
	//echo 'Some help text goes here.';
}

//==================================================================================
//==========================================         HTML CALLBACKS         ========
//==================================================================================

//LABEL TEXT HTML
function ijmtb_label_HTML() {
	$current_label = get_option('ijmtb_label');
	if (is_null($current_label)) {
		$current_label = 'My themes:';
	}
	echo "<input type='text' id='ijmtb_label' name='ijmtb_label' onBlur='updateLabelText()' value='" . $current_label . "'/>";
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
	$current_colour = get_option('ijmtb_bg_colour');
	if (is_null($current_colour)) {
		$current_colour = '#000';
	}
	echo '<div class="color-picker" style="position: relative;">';
	echo "<input type='text' id='ijmtb_bg_colour' name='ijmtb_bg_colour' onblur='changeDemoBgColour()' value='" . $current_colour . "'/>";
	echo '<div style="position: absolute; left:190px; bottom:-101px;" id="colorpicker"></div></div>';
}
function Tcolour_field_HTML() {
	$current_tcolour = get_option('ijmtb_text_colour');
	if (is_null($current_tcolour)) {
		$current_tcolour = '#fff';
	}
	echo '<div class="color-picker2" style="position: relative;">';
	echo "<input type='text' id='ijmtb_text_colour' name='ijmtb_text_colour' onblur='changeDemoTextColour()' value='" . $current_tcolour . "'/>";
	echo '<div style="position: absolute; left:190px; bottom:-60px;" id="colorpicker2"></div></div>';
}

//LABEL COLOUR HTML
function label_colour_field_HTML() {
	$current_colour = get_option('ijmtb_label_bg_colour');
	if (is_null($current_colour)) {
		$current_colour = '#fff';
	}
	echo '<div class="color-picker" style="position: relative;">';
	echo "<input type='text' id='ijmtb_label_bg_colour' name='ijmtb_label_bg_colour' onblur='changeDemoLabelBgColour()' value='" . $current_colour . "'/>";
	echo '<div style="position: absolute; left:190px; bottom:-101px;" id="colorpicker"></div></div>';
}
function label_Tcolour_field_HTML() {
	$current_tcolour = get_option('ijmtb_label_text_colour');
	if (is_null($current_tcolour)) {
		$current_tcolour = '#000';
	}
	echo '<div class="color-picker2" style="position: relative;">';
	echo "<input type='text' id='ijmtb_label_text_colour' name='ijmtb_label_text_colour' onblur='changeDemoLabeLTextColour()' value='" . $current_tcolour . "'/>";
	echo '<div style="position: absolute; left:190px; bottom:-60px;" id="colorpicker2"></div></div>';
}

//COLOUR SCRIPTS
function my_admin_scripts() {
    wp_enqueue_style( 'farbtastic' );
    wp_enqueue_script( 'farbtastic' );
    wp_enqueue_script( 'ijmtb_colour_script', plugins_url() . '/ijm-theme-bar-pro/ijmtb_colour.js', array( 'farbtastic', 'jquery' ) );
}

//FORM HTML
function ijmtb_html() {
	?>
    <div class="wrap">
        <h2>IJM Theme Bar Settings</h2>
        <form action="options.php" method="POST">
            <?php settings_fields( 'ijmtb_Menu_ID' ); ?>
            <?php do_settings_sections( 'ijmtb_Menu_ID' ); ?>
            <script>
			function updateLabelText() {
				var labtext = document.getElementById('ijmtb_label').value;
				document.getElementById('ijmtb_demo_p').innerHTML=labtext;
			}
			//when #ijmtb_text_colour changes, update ijmtb_demo colour to #ijmtb_text_colour value
			function changeDemoTextColour() {
				var texthex = document.getElementById('ijmtb_text_colour').value;
				document.getElementById('ijmtb_demo').style.color=texthex;
			}
			
			//when #ijmtb_bg_colour changes, update ijmtb_demo background-color to #ijmtb_bg_colour value
			function changeDemoBgColour() {
				var bghex = document.getElementById('ijmtb_bg_colour').value;
				document.getElementById('ijmtb_demo').style.backgroundColor=bghex;
				document.getElementById('ijmtb_demo_p').innerhtml="hello";
			}
			
			
			//when #ijmtb_label_text_colour changes, update ijmtb_demo colour to #ijmtb_label_text_colour value
			function changeDemoLabelTextColour() {
				var texthex = document.getElementById('ijmtb_label_text_colour').value;
				document.getElementById('ijmtb_demo').style.color=texthex;
			}
			
			//when #ijmtb_label_bg_colour changes, update ijmtb_demo background-color to #ijmtb_label_bg_colour value
			function changeDemoLabelBgColour() {
				var bghex = document.getElementById('ijmtb_label_bg_colour').value;
				document.getElementById('ijmtb_demo').style.backgroundColor=bghex;
				document.getElementById('ijmtb_demo_p').innerhtml="hello";
			}
			</script>
            <div id="ijmtb_demo" style="height:28px; margin-top:60px; color:<?php echo get_option('ijmtb_text_colour'); ?>; background-color:<?php echo get_option('ijmtb_bg_colour') ?>;"><p id="ijmtb_demo_p" style="padding-left:10px; padding-top:5px;"><?php echo get_option('ijmtb_label'); ?></p></div>
            
            <?php submit_button(); ?>
        </form>
    </div><!-- .wrap -->
    <?php
}
