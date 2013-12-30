<?php
    /**
     * Outputs CSS and JS for front end
     */
namespace jptb;

class frontend {
   function __construct() {
       add_action( 'wp_enqueue_scripts', array($this, 'css') );
       add_action( 'wp_head', array( $this, 'js') );
   }
   function js() {
        wp_enqueue_script( 'jptb-js', plugin_dir_url( __FILE__ ).'jptb-frontend.js', array('jquery'), null, true );
   }

function css () { ?>
    <style>
            #jptb-theme-bar {
                bottom:0px;
                position:fixed;
                left:0px;
                width:100%;
                height:28px;
                background-color:<?php echo get_option('jptb_bg_colour') ?>;
                color:<?php echo get_option('jptb_text_colour'); ?>;
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
                content:"" !important;
                content:none !important;
            }

            #jptb-theme-bar ul li:before {
                content:"" !important;
                content:none !important;
            }

            #jptb-theme-bar a, #jptb-theme-bar p {
                color:<?php echo get_option('jptb_text_colour'); ?>;
                text-decoration:none;
                line-height:15px;
                font: normal 13px/28px sans-serif;
            }

            #jptb-theme-bar a:hover {
                color:<?php echo get_option('jptb_text_colour'); ?>;
                text-decoration:underline;
            }

            p#jptb_label {
                background-color:<?php echo get_option('jptb_label_bg_colour') ?>;
                color:<?php echo get_option('jptb_label_text_colour'); ?>;
                padding: 0 4px;
                left: 0px;
                position: absolute;
            }
        </style>
<?php
    }
}

new frontend();
