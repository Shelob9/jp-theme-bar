<?php
    /**
     * Outputs CSS and JS for front end
     */
//namespace jptb;

class jptb_frontend {
   function __construct() {
       add_action( 'wp_enqueue_scripts', array( $this, 'scriptsNstyles' ) );
       add_action( $this->where(), array( $this, 'html_bar') );
       add_action( 'wp_enqueue_scripts', array( $this, 'inline_style' ) );
       add_filter( 'query_vars', array( $this, 'add_theme_var' ) );
       if ( get_option( 'jptb_mod_switch' ) == 1 ) {
           add_action( 'after_theme_setup', array( $this, 'theme_settings' ) );
       }
       $filters = $this->link_filters();
       foreach ( $filters as $filter ) {
           add_filter( $filter, array( $this, 'keep_query_var' ) );
       }
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
         * Use this filter to completely overide the styles being used or add to the inline styles
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
       /*
       * Append something to the theme list.
       *
       * Be sure to wrap it in <li></li>!
       *
       * @package jptb
       * @since 0.0.3
       */
       do_action( 'jptb_end_of_the_list' );
       //end the list
       echo "</ul>";
       /*
       * Append something to end of bar.
       *
       * Be sure to style the container for this!
       *
       * @package jptb
       * @since 0.0.3
       */
        do_action( 'jptb_end_of_the_bar' );
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
    static function default_options() {
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
        $default = self::default_options();
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
        $filtered_options = apply_filters( 'jptb_options', $options );
        $options = array_merge( $options, $filtered_options );
        return $options;
    }

    /**
     * Make theme a public query var
     *
     * @package jptb
     * @since 0.0.3
     */
    function add_theme_var( $public_query_vars ) {
        $public_query_vars[] = 'theme';
        return $public_query_vars;
    }

    /**
     * Method to update theme mods
     *
     * @param array $mods An array of theme mods to update with the key as the name of the mod.
     *
     * @package jptb
     * @since 0.0.3
     */
    function update( $mods = null ) {
        if ( !is_null( $mods ) && is_array( $mods ) ) {
            foreach ( $mods as $key=>$data ) {
                $name = $key;
                $value = $data;
                set_theme_mod( $name, $value );
            }
        }
    }

    /**
     * Function to update settings when theme is switched.
     *
     * @todo how to get this to run only when it needs to.
     * @package jptb
     * @since 0.0.3
     */
    function theme_settings() {
        //get the theme with the query var
        $theme = get_query_var( 'theme' );
        $c_theme = get_stylesheet();
        if ( isset( $theme)  && $c_theme !== $theme  ) {
            $mods = get_option( "theme_mods_{$theme}" );
            $this->update( $mods );

        }
        else {
            $mods = get_option( "theme_mods_{$c_theme}" );
            $this->update( $mods );
        }
    }

    /**
     * Keep the query var for links
     *
     * @package jptb
     * @since 0.0.3
     */
    function keep_query_var( $link ) {
        //get the theme with the query var
        $theme = get_query_var( 'theme' );
        //set to current stylesheet if none is being previewed.
        if ( $theme === '' ) {
            $theme = get_stylesheet();
        }
        $link = add_query_arg( 'theme', $theme, $link );
        return $link;
    }

    /**
     * A list of filters to hook $this->keep_query_var() to
     *
     * @return array|bool|mixed|void
     * @package jptb
     * @since 0.0.3
     */
    function link_filters() {
        //this list adapted from http://stackoverflow.com/a/3474234/1469799
        //Thanks Mike Schinkel!
        $filters = array(
            'page_link',
            'post_link',
            'term_link',
            'tag_link',
            'category_link',
            'post_type_link',
            'attachment_link',
            'year_link',
            'month_link',
            'day_link',
            'search_link',
            'index_rel_link',
            'parent_post_rel_link',
            'previous_post_rel_link',
            'next_post_rel_link',
            'start_post_rel_link',
            'end_post_rel_link',
            'previous_post_link',
            'next_post_link',
            'get_pagenum_link',
            'get_comments_pagenum_link',
            'shortcut_link',
            'get_shortlink',
        );

        /**
         * Filter which links get the theme query var added on to
         *
         * @since 0.0.3
         *
         * @param array $filters list of link fitlers
         */
        $filters = apply_filters( 'jptb_links_to_filter', $filters );
        return $filters;
    }


}

new jptb_frontend();
