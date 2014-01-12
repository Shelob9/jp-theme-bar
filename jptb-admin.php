<?php
/**
 * Admin Page and settings
 *
 * @package jptb
 * @author Josh Pollock
 * @since 0.0.2
 */

namespace jptb;

class admin {

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
            'Which Themes Do You Want To Show?',
            array( $this, 'themeChoice_HTML' ),
            'jptb_settings'
        );

        add_settings_section(
            'jptb_other_options',
            'Additional Options',
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

        //MAIN BG COLOUR
        add_settings_field(
            'jptb_bg_colour',
            'Background Colour',
            array( $this, 'bg_cb' ),
            'jptb_settings',
            'jptb_other_options'
        );
        register_setting( 'jptb_settings', 'jptb_bg_colour' );

        //MAIN TXT COLOUR
        add_settings_field(
            'jptb_text_colour',
            'Text Colour',
            array( $this, 'txt_cb' ),
            'jptb_settings',
            'jptb_other_options'
        );
        register_setting( 'jptb_settings', 'jptb_text_colour' );

        //LABEL BG COLOUR
        add_settings_field(
            'jptb_label_bg_colour',
            'Label Background Colour',
            array( $this, 'label_bg_cb' ),
            'jptb_settings',
            'jptb_other_options'
        );
        register_setting( 'jptb_settings', 'jptb_label_bg_colour' );

        //LABEL TEXT COLOR
        add_settings_field(
            'jptb_label_text_colour',
            'Label Text Colour',
            array( $this, 'label_txt_cb' ),
            'jptb_settings',
            'jptb_other_options'
        );
        register_setting( 'jptb_settings', 'jptb_label_text_colour' );
        //LABEL TEXT TO USE
        add_settings_field(
            'jptb_other_options',
            'Label For Theme Bar',
            array( $this, 'label_cb' ),
            'jptb_settings',
            'jptb_themeChoice_sectionID'
        );
        register_setting( 'jptb_settings', 'jptb_label' );

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

    //FORM HTML
    function html() {
        ?>
        <div class="wrap">
            <h2>JP Theme Bar Settings</h2>
            <Strong>This plugin requires the plugin <a href="http://wordpress.org/plugins/theme-test-drive/" target="_blank">Theme Test Drive</a> by <a href="http://www.prelovac.com/vladimir/" target="_blank">Vladimir Prelovac</a> in order for the theme switching to work.</Strong>
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
new admin();