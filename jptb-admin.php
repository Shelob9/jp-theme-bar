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
        add_action( 'admin_enqueue_scripts', array( $this, 'jptb_admin_scripts' ) );
    }

    //add settings page
    function jptb_settings_page() {
        add_theme_page('JP Theme Bar', 'JP Theme Bar', 'administrator', 'jptb_Menu_ID', array( $this, 'html') );
    }

    //add settings
    function jptb_register_settings() {

        add_settings_section(
            'jptb_themeChoice_sectionID',
            'Which themes do you want to show?',
            array( $this, 'themeChoice_HTML' ),
            'jptb_Menu_ID'
        );

        //LABEL TEXT
        add_settings_field(
            'jptb_label',
            'Label For Theme Bar',
            array( $this, 'label_cb' ),
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
                array( $this, 'Theme_field_HTML' ),
                'jptb_Menu_ID',
                'jptb_themeChoice_sectionID',
                $uniqueOptionName
            );
            register_setting( 'jptb_Menu_ID', $uniqueOptionName );
        }

        //MAIN BG COLOUR
        add_settings_field(
            'jptb_bg_colour',
            'Background Colour',
            array( $this, 'bg_cb' ),
            'jptb_Menu_ID',
            'jptb_themeChoice_sectionID'
        );
        register_setting( 'jptb_Menu_ID', 'jptb_bg_colour' );

        //MAIN TXT COLOUR
        add_settings_field(
            'jptb_text_colour',
            'Text Colour',
            array( $this, 'txt_cb' ),
            'jptb_Menu_ID',
            'jptb_themeChoice_sectionID'
        );
        register_setting( 'jptb_Menu_ID', 'jptb_text_colour' );

        //LABEL BG COLOUR
        add_settings_field(
            'jptb_label_bg_colour',
            'Label Background Colour',
            array( $this, 'label_bg_cb' ),
            'jptb_Menu_ID',
            'jptb_themeChoice_sectionID'
        );
        register_setting( 'jptb_Menu_ID', 'jptb_label_bg_colour' );

        //LABEL TEXT COLOR
        add_settings_field(
            'jptb_label_text_colour',
            'Label Text Colour',
            array( $this, 'label_txt_cb' ),
            'jptb_Menu_ID',
            'jptb_themeChoice_sectionID'
        );
        register_setting( 'jptb_Menu_ID', 'jptb_label_text_colour' );

    }


    function themeChoice_HTML() {
        //echo 'Some help text goes here.';
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
        echo "<input type='text' id='jptb_bg_colour' name='jptb_bg_colour' onblur='changeDemoBgColour()' value='" . $current_colour . "'/>";
        echo '<div style="position: absolute; left:190px; bottom:-101px;" id="colorpicker"></div></div>';
    }

    //MAIN TXT COLOR
    function txt_cb() {
        $current_tcolour = get_option('jptb_text_colour');
        if (is_null($current_tcolour)) {
            $current_tcolour = '#fff';
        }
        echo '<div class="color-picker2" style="position: relative;">';
        echo "<input type='text' id='jptb_text_colour' name='jptb_text_colour' onblur='changeDemoTextColour()' value='" . $current_tcolour . "'/>";
        echo '<div style="position: absolute; left:190px; bottom:-60px;" id="colorpicker2"></div></div>';
    }

    //LABEL BG COLOUR
    function label_bg_cb() {
        $current_colour = get_option('jptb_label_bg_colour');
        if (is_null($current_colour)) {
            $current_colour = '#fff';
        }
        echo '<div class="color-picker" style="position: relative;">';
        echo "<input type='text' id='jptb_label_bg_colour' name='jptb_label_bg_colour' onblur='changeDemoLabelBgColour()' value='" . $current_colour . "'/>";
        echo '<div style="position: absolute; left:190px; bottom:-101px;" id="colorpicker"></div></div>';
    }

    //LABEL TXT COLOR
    function label_txt_cb() {
        $current_tcolour = get_option('jptb_label_text_colour');
        if (is_null($current_tcolour)) {
            $current_tcolour = '#000';
        }
        echo '<div class="color-picker2" style="position: relative;">';
        echo "<input type='text' id='jptb_label_text_colour' name='jptb_label_text_colour' onblur='changeDemoLabeLTextColour()' value='" . $current_tcolour . "'/>";
        echo '<div style="position: absolute; left:190px; bottom:-60px;" id="colorpicker2"></div></div>';
    }

    //COLOUR SCRIPTS

    function jptb_admin_scripts() {
        wp_enqueue_style( 'farbtastic' );
        wp_enqueue_script( 'farbtastic' );
        wp_enqueue_script( 'jptb_colour_script', plugins_url() . '/jptb-theme-bar-pro/jptb_colour.js', array( 'farbtastic', 'jquery' ) );
    }

    /**
     * Create an array of colours from the settings.
     *
     * @package jptb
     * @since 0.0.2
     *
     * @return array $colours All the colours we need for styles
     */
    static function colours() {
        $colours = array(
            'bg_colour'         => get_option('jptb_bg_colour'),
            'text_colour'       => get_option('jptb_text_colour'),
            'label_bg_colour'   => get_option('jptb_label_bg_colour'),
            'label_text_colour' => get_option('jptb_label_text_colour')
        );
        /**
         * Filter to overide colours for the switcher bar
         *
         * @param   array   $colours    An array of colours
         *
         * @since 0.0.1
         */
        $colours = apply_filters( 'jptb_bar_colours', $colours );
        return $colours;
    }

    function bar_style() {
        //get the colour options
        $colours = self::colours();
        //build each part we need in an var named for its id.
        $jptb_demo = "height:28px; margin-top:60px; color:".$colours[ 'text_colour' ].";  background-color: ".$colours[ 'bg_colour' ].";";
        $jptb_demo_p = "padding-left:10px; padding-top:5px; color:".$colours[ 'label_text_colour' ].";";
        //put each one into an array and return it.
        $bar_style = array(
            'jptb_demo'     => $jptb_demo,
            'jptb_demo_p'   => $jptb_demo_p,
        );
        return $bar_style;
    }

    //FORM HTML
    function html() {
        ?>
        <div class="wrap">
            <h2>JP Theme Bar Settings</h2>
            <Strong>This plugin requires the plugin <a href="http://wordpress.org/plugins/theme-test-drive/" target="_blank">Theme Test Drive</a> by <a href="http://www.prelovac.com/vladimir/" target="_blank">Vladimir Prelovac</a> in order for the theme switching to work.</Strong>
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
                <?php
                    //get the inline style
                    $bar_style = $this->bar_style();
                ?>
                <div id="jptb_demo" style="<?php echo $bar_style[ 'jptb_demo' ]; ?>"><p id="jptb_demo_p" style="<?php echo $bar_style[ 'jptb_demo_p' ]; ?></p></div>
                <div class=""><strong>Note:</strong> Demo is broken for label colors.</div>
                <?php submit_button(); ?>
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