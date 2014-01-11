<?php
    /**
     * Outputs CSS and JS for front end
     */
namespace jptb;

class frontend {
   function __construct() {
       add_action( 'wp_enqueue_scripts', array($this, 'css') );
       add_action( 'wp_head', array( $this, 'js') );
       add_action( 'wp_footer', array( $this, 'html_bar') );
   }
   function js() {
        wp_enqueue_script( 'jptb-js', plugin_dir_url( __FILE__ ).'jptb-frontend.js', array('jquery'), null, true );
   }

    /**
     * Create an array of colours from the settings.
     *
     * @package jptb
     * @since 0.0.2
     *
     * @return array $colours All the colours we need for styles
     */
    function colours() {
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

    /**
     * Create stylesheet for the actual switcher bar
     *
     * @package jptb
     * @since 0.0.2
     *
     * @return  string  $style  The styles for the actual switcher bar.
     */
    function style() {
        $colours = $this->colours();
        $style = "
                #jptb-theme-bar {
                    bottom:0px;
                    position:fixed;
                    left:0px;
                    width:100%;
                    height:28px;
                    background-color: ".$colours[ 'bg_colour'].";
                    color: ".$colours[ 'text_colour' ].";
                    text-shadow:none;
                    z-index:9999;
                }

                #jptb-theme-bar ul, #jptb-theme-bar p {
                    margin:0;
                    padding:0;
                }

                #jptb-theme-bar ul li {
                    float:left;
                    list-style:none;
                    margin-left:10px;
                    content:'' !important;
                    content:none !important;
                }

                #jptb-theme-bar ul li:before {
                    content:'' !important;
                    content:none !important;
                }

                #jptb-theme-bar a, #jptb-theme-bar p {
                    color: ".$colours[ 'text_colour' ].";
                    text-decoration:none;
                    line-height:15px;
                    font: normal 13px/28px sans-serif;
                }

                #jptb-theme-bar a:hover {
                    color: ".$colours[ 'text_colour' ].";
                    text-decoration:underline;
                }

                p#jptb_label {
                    background-color: ".$colours[ 'label_bg_colour' ].";
                    color: ".$colours[ 'label_text_colour' ].";
                    padding: 0 4px;
                    left: 0px;
                    position: absolute;
                }
            ";
        /**
         * Filter the styles.
         *
         * Use this filter to completely overide the styles being used.
         *
         * @param   $string $style A style sheet (with no <style></style>
         */
        $style = apply_filters( 'jptb_bar_style', $style );
        return $style;
    }

    /**
     * Output the stylesheet
     *
     * @package jptb
     * @since 0.0.1
     */
    function css() {
        //create the stylesheet, with tags, using this::style()
        $css = '<style>';
        $css .= $this->style();
        $css .= '</style>';
        //make it so
        echo $css;
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
        $barLabel = get_option('jptb_label');
        //start bar.
        echo "<div id=\"jptb-theme-bar\">";
        echo "<ul>";
        //the label
        echo "<li>";
        echo "<p id='jptb_label'>" . $barLabel . "</p>";
        echo "</li>";
        //output each o
        foreach ($themes as $theme ) {
            //construct info about theme we need.
            $themename = $theme['Name'];
            $noSpaceName = strtr( $themename," -","__" );
            $nocapsname = strtolower($noSpaceName);
            $link = $siteurl."/?theme=".$theme->stylesheet;
            //Determin if theme is to be included.
            $uniqueOptionName = "jptb_" . $nocapsname;
            $jptb_option_value = get_option($uniqueOptionName);

            $switch = "<a href=\"$link\">$themename</a>";
            /**
             * Filter to change the switching link
             *
             * This filter allows you to use a different theme switching plugin, or add your own system.
             *
             * @param   string    $switch The complete link, anchor tag and all to do the switch.
             * @since 0.0.2
             */
            $switch = apply_filters( 'jptb_switch', $switch );
            if ($jptb_option_value == '1') {
                echo "<li>";
                echo $switch;
                echo "</li>";
            }
        }
        //end the list/ bar
        echo "</ul>";
        echo "</div> <!-- END #jptb-theme-bar -->";
    }
}

new frontend();
