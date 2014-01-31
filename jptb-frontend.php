<?php
    /**
     * Outputs CSS and JS for front end
     */
namespace jptb;

class frontend {
   function __construct() {
       add_action( 'wp_enqueue_scripts', array( $this, 'scriptsNstyles' ) );
       add_action( $this->where(), array( $this, 'html_bar') );
       add_action( 'wp_enqueue_scripts', array( $this, 'inline_style' ) );
       add_filter( 'query_vars', array( $this, 'add_theme_var' ) );
       add_action( 'init', array( $this, 'theme_settings' ) );
       add_action( 'wp_head', array( $this, 'print_mods' ) );
   }

    /**
     * Add our scripts and styles
     *
     * @package jptb
     * @since 0.0.1
     */
    function scriptsNstyles() {
       wp_enqueue_script( 'jptb-js', plugin_dir_url( __FILE__ ).'js/jptb.js', array('jquery'), null, true );
       wp_enqueue_style( 'jptb', plugin_dir_url( __FILE__ ).'css/jptb.css' );
    }

    /**
     * Create stylesheet for the actual switcher bar
     *
     * @package jptb
     * @since 0.0.2
     *
     * @return  string  $style  The styles for the actual switcher bar.
     */
    function inline_style() {
        $colours = $this->options();
        $position = "
            #jptb-theme-bar {
                bottom:0px;
                position:fixed;
                left:0px;
                z-index:9999;
            }
        ";
        /**
         * Change the CSS for the position of the bar.
         *
         * @param   string  $positioning    The CSS, including selector (#jptb-themebar), to position theme bar.
         *
         * @since 0.0.2
         */
        $position = apply_filters( 'jptb_bar_position', $position );
        $main_inline_style = "
                #jptb-theme-bar {
                    background-color: ".$colours[ 'bg_colour'].";
                    color: ".$colours[ 'text_colour' ].";
                }

                #jptb-theme-bar a, #jptb-theme-bar p {
                    color: ".$colours[ 'text_colour' ].";
                }

                #jptb-theme-bar a:hover {
                    color: ".$colours[ 'text_colour' ].";
                }

                p#jptb_label {
                    background-color: ".$colours[ 'label_bg_colour' ].";
                    color: ".$colours[ 'label_text_colour' ].";
                }
            ";
        $inline_style = $position.$main_inline_style;
        /**
         * Filter the styles.
         *
         * Use this filter to completely overide the styles being used.
         *
         * @param   string  $inline_style A style sheet (with no <style></style>
         *
         * @since 0.0.2
         */
        $inline_style = apply_filters( 'jptb_bar_inline_style', $inline_style );
        //add these styles inline
        wp_add_inline_style( 'jptb', $inline_style );
    }

    /**
     * The html for the actual bar
     *
     * @package jptb
     * @since 0.0.1
     */
    function html_bar () {
        //put site's url in a var.
        $siteurl = get_bloginfo('url');
        //get an array of themes.
        $themes = wp_get_themes( array(
            'allowed' => true
        ) );
        //get the label text.
        $options = $this->options();
        $barLabel = $options[ 'label' ];
        //start bar.
        echo "<div id=\"jptb-theme-bar\">";
        echo "<ul>";
        //the label
        echo "<li>";
        echo "<p id='jptb_label'>" . $barLabel . "</p>";
        echo "</li>";
        //output each theme
        foreach ($themes as $theme ) {
            //construct info about theme we need.
            $themename = $theme['Name'];
            $noSpaceName = strtr( $themename," -","__" );
            $nocapsname = strtolower($noSpaceName);
            $link = $siteurl."/?theme=".$theme->stylesheet;
            //Determin if theme is to be included.
            $uniqueOptionName = "jptb_" . $nocapsname;
            $jptb_option_value = get_option($uniqueOptionName);
            //create the switch for each one
            $switch = "<a href=\"$link\">$themename</a>";
            if ($jptb_option_value == '1') {
                echo "<li>";
                echo $switch;
                echo "</li>";
            }
        }
        //end the list
        echo "</ul>";
        //end the bar
        echo "</div> <!-- END #jptb-theme-bar -->";
    }

    /**
     * Set what hook preview bar is outputted on
     *
     * @package jptb
     * @since 0.0.2
     */
    function where() {
        $where = 'wp_footer';
        /**
         * Filter where the bar goes
         *
         * @param   string  $where  A hook to output the bar on.
         *
         * @since 0.0.2
         */
        $where = apply_filters( 'jptb_where_bar', $where );
        return $where;
    }

    /**
     * The default options
     *
     * @package jptb
     * @since 0.0.2
     */
    function default_options() {
        $defaults = array(
            'label'                => 'Switch Themes:',
            'bg_colour'            => '#ffffff',
            'text_colour'          => '#000000',
            'label_bg_colour'      => '#000000',
            'label_text_colour'    => '#ffffff',
        );
        return $defaults;
    }

    /**
     * Return either default or current option.
     *
     * @package jptb
     * @since 0.0.2
     */
    function options() {
        //get defaults
        $default = $this->default_options();
        $options = array(
            'label'             => get_option( 'jptb_label', $default[ 'label' ] ),
            'bg_colour'         => get_option( 'jptb_bg_colour', $default[ 'bg_colour' ] ),
            'text_colour'       => get_option( 'jptb_text_colour', $default[ 'text_colour' ] ),
            'label_bg_colour'   => get_option( 'jptb_label_bg_colour',$default[ 'label_bg_colour' ] ),
            'label_text_colour' => get_option( 'jptb_label_text_colour', $default[ 'label_text_colour' ] ),
        );
        /**
         * Filter to override options set in database
         *
         * @param   array   $options    Array of options.
         *
         * @since 0.0.2
         */
        $options = apply_filters( 'jptb_options', $options );
        return $options;
    }

    /**
     * Make theme a public query bar
     *
     * @package jptb
     * @since 0.0.3
     */
    function add_theme_var($public_query_vars) {
        $public_query_vars[] = 'theme';
        return $public_query_vars;
    }

    /**
     * Method to update theme mods or options
     *
     * @param array $mods An array of theme mods to update with the key as the name of the mod.
     * @param array $options    An array of options to update with the key as the name of the option.
     *
     * @package jptb
     * @since 0.0.3
     */
    function update( $mods = null, $options ) {
        if ( !is_null( $mods ) ) {
            foreach ( $mods as $key=>$data ) {
                $name = $key;
                $value = $data;
                set_theme_mod( $name, $value );
            }
        }
        if (!is_null( $options ) )  {
            foreach ( $options as $key=>$data ) {
                $name = $key;
                $value = $data;
                if ( get_option( $name ) !== false ) {
                    update_option( $name, $value );
                }
                else {
                    add_option( $name, $value );
                }
            }
        }
    }

    /**
     * Function to update settings when theme is switched.
     *
     * @uses this::update()
     * @package jptb
     * @since 0.0.3
     */
    function theme_settings() {
        //get the theme with the query var
        $theme = get_query_var( 'theme' );
        if ( isset( $theme ) ) {
            //check if theme has changed
            if ( $theme != get_option( 'jptb_ct') ) {
                //do the option to update the theme_mods and/or options for the theme.
                do_action( 'jpb_theme_settings_'.$theme, $mods, $options );
                $this->update( $mods, $options );
                //update what is current theme
                update_option( 'jptb_ct', $theme );
            }
        }
    }

    function print_mods() {
        echo '<div class="print-mods" style="color:red;font-size:18px;">';
        print_r(get_theme_mods());
        echo '<br />';
        print_r( get_option( 'jptb_ct') );
        echo '</div>';
    }

}

new frontend();
