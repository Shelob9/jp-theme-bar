<?php
/**
 * Admin Page and settings
 *
 * @package jptb
 * @author Josh Pollock
 * @since 0.0.2
 */

//namespace jptb;

class jptb_admin {

    function __construct() {
        add_action( 'admin_menu', array( $this, 'jptb_settings_page' ) );
        add_action( 'admin_init', array( $this, 'jptb_register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'farb' ) );
    }

    /**
     * Add Farbtastic rez for colour pickers
     *
     * @package jptb
     * @since 0.0.2
     */
    function farb() {
        wp_enqueue_style( 'farbtastic' );
        wp_enqueue_script( 'farbtastic' );
        wp_enqueue_script( 'jptb-admin', plugin_dir_url( __FILE__ ).'js/jptb-admin.js', array('jquery', 'farbtastic' ), null, true );
    }
    //add settings page
    function jptb_settings_page() {
        add_theme_page('JP Theme Bar', 'JP Theme Bar', 'administrator', 'jptb_settings', array( $this, 'html') );
    }

    //add settings
    function jptb_register_settings() {

        add_settings_section(
            'jptb_theme_choice',
            __( 'Which Themes Do You Want To Show?', 'jptb' ),
            array( $this, 'themeChoice_HTML' ),
            'jptb_settings'
        );

        add_settings_section(
            'jptb_other_options',
            __( 'Additional Options', 'jptb' ),
            array( $this, 'other_options_HTML' ),
            'jptb_settings'
        );

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
                array( $this, 'Theme_field_HTML' ),
                'jptb_settings',
                'jptb_theme_choice',
                $uniqueOptionName
            );
            register_setting( 'jptb_settings', $uniqueOptionName );
        }

        //LABEL TEXT TO USE
        add_settings_field(
            'jptb_label',
            __( 'Label For Theme Bar', 'jptb' ),
            array( $this, 'label_cb' ),
            'jptb_settings',
            'jptb_other_options'
        );
        register_setting( 'jptb_settings', 'jptb_label' );

        //MAIN BG COLOUR
        add_settings_field(
            'jptb_bg_colour',
            __( 'Background Colour', 'jptb' ),
            array( $this, 'bg_cb' ),
            'jptb_settings',
            'jptb_other_options'
        );
        register_setting( 'jptb_settings', 'jptb_bg_colour' );

        //MAIN TXT COLOUR
        add_settings_field(
            'jptb_text_colour',
            __( 'Text Colour', 'jptb' ),
            array( $this, 'txt_cb' ),
            'jptb_settings',
            'jptb_other_options'
        );
        register_setting( 'jptb_settings', 'jptb_text_colour' );

        //LABEL BG COLOUR
        add_settings_field(
            __( 'jptb_label_bg_colour', 'jptb' ),
            'Label Background Colour',
            array( $this, 'label_bg_cb' ),
            'jptb_settings',
            'jptb_other_options'
        );
        register_setting( 'jptb_settings', 'jptb_label_bg_colour' );

        //LABEL TEXT COLOR
        add_settings_field(
            'jptb_label_text_colour',
            __( 'Label Text Colour', 'jptb' ),
            array( $this, 'label_txt_cb' ),
            'jptb_settings',
            'jptb_other_options'
        );
        register_setting( 'jptb_settings', 'jptb_label_text_colour' );

        //LABEL TEXT COLOR
        add_settings_field(
            'jptb_mod_switch',
            __( 'Update Theme Mods', 'jptb' ),
            array( $this, 'mod_switch_cb' ),
            'jptb_settings',
            'jptb_other_options'
        );
        register_setting( 'jptb_settings', 'jptb_mod_switch' );


    }


    function themeChoice_HTML() {
        //echo 'Some help text goes here.';
    }

    function other_options_HTML() {
        //?
    }

    /*
     * CALLBACKS
     */

    //LABEL TEXT HTML
    function label_cb() {
        $current_label = get_option('jptb_label');
        if (is_null($current_label)) {
            $current_label = 'My themes:';
        }
        echo "<input type='text' id='jptb_label' name='jptb_label' onBlur='updateLabelText()' value='" . $current_label . "'/>";
        echo "<p><em>".__( 'The text that will go before the list of themes.', 'jptb' )."</em></p>";
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

    //MAIN BG COLOR
    function bg_cb() {
        $current_colour = get_option('jptb_bg_colour');
        if (is_null($current_colour)) {
            $current_colour = '#000';
        }
        echo '<div class="color-picker" style="position: relative;">';
        echo "<input type='text' id='jptb_bg_colour' name='jptb_bg_colour'  value='" . $current_colour . "'/>";
        echo '<div style="position: absolute; left:190px; bottom:-101px;" id="farb-cp-1"></div></div>';
    }

    //MAIN TXT COLOR
    function txt_cb() {
        $current_tcolour = get_option('jptb_text_colour');
        if (is_null($current_tcolour)) {
            $current_tcolour = '#fff';
        }
        echo '<div class="color-picker" style="position: relative;">';
        echo "<input type='text' id='jptb_text_colour' name='jptb_text_colour' value='" . $current_tcolour . "'/>";
        echo '<div style="position: absolute; left:190px; bottom:-60px;" id="farb-cp-2"></div></div>';
    }

    //LABEL BG COLOUR
    function label_bg_cb() {
        $current_colour = get_option('jptb_label_bg_colour');
        if (is_null($current_colour)) {
            $current_colour = '#fff';
        }
        echo '<div class="color-picker" style="position: relative;">';
        echo "<input type='text' id='jptb_label_bg_colour' name='jptb_label_bg_colour'  value='" . $current_colour . "'/>";
        echo '<div style="position: absolute; left:190px; bottom:-101px;" id="farb-cp-3"></div></div>';
    }

    //LABEL TXT COLOR
    function label_txt_cb() {
        $current_tcolour = get_option('jptb_label_text_colour');
        if (is_null($current_tcolour)) {
            $current_tcolour = '#000';
        }
        echo '<div class="color-picker" style="position: relative;">';
        echo "<input type='text' id='jptb_label_text_colour' name='jptb_label_text_colour'  value='" . $current_tcolour . "'/>";
        echo '<div style="position: absolute; left:190px; bottom:-60px;" id="farb-cp-4"></div></div>';
    }


    //Theme Mod Switch
    function mod_switch_cb() {
        $setting = esc_attr( get_option( 'jptb_mod_switch' ) );
        if ($setting == '1') {
            $checked = 'checked';
        } else {
            $checked = '';
        }
        echo "<input type='checkbox' id='jptb_mod_switch' name='jptb_mod_switch' onBlur='updateLabelText()' value='1' $checked />";
        echo "<p><em>When enabled, the theme_mods will be updated to match the current theme being previewed.</em></p>";
    }

    //FORM HTML
    function html() {
        ?>
        <div class="wrap">
            <h2><?php _e( 'JP Theme Bar Settings', 'jptb' ); ?></h2>
            <Strong>
				<?php _e( sprintf( 'This plugin requires the plugin %1s by %2s in order for the theme switching to work.', '<a href="http://wordpress.org/plugins/theme-test-drive/" target="_blank">Theme Test Drive</a>', '<a href="http://www.prelovac.com/vladimir/" target="_blank">Vladimir Prelovac</a>' ), 'jptb' ); ?>
			</Strong>
            <form action="options.php" method="POST">
                <?php
                    settings_fields( 'jptb_settings' );
                    do_settings_sections( 'jptb_settings' );
                    submit_button();
                ?>
            </form>
        </div><!-- .wrap -->
        <?php
    }
}

/**
 * Initialize admin class
 *
 * @package jptb
 * @author Josh Pollock
 */
new jptb_admin();
