<?php

	/* Because the theme bar will be placed in the <html> margin in the same way as the wp admin bar
	 * this removes the margin that is placed there by core.
	 * Don't worry!  The margin is replaced to show the admin bar in the following CSS section.
	 */
add_action('get_header', 'my_filter_head');
  function my_filter_head() {
    remove_action('wp_head', '_admin_bar_bump_cb');
  }
  
	/* This CSS section begins by checking whither the admin bar is showing.
	 * If it is I add enough space in the <html> margin to accomodate both the admin bar and the theme bar
	 * If not then we only need space for the theme bar.
	 * The rest is just echoing normal CSS to place and style the theme bar
	 */
add_action( 'wp_head', 'IJM_insert_bar_css' );
	function IJM_insert_bar_css () {
		$adminon = false;
		if ( is_admin_bar_showing() ) { $adminon=true; }
		?><style>
			#ijm-theme-bar {
				bottom:0px;
				position:fixed;
				left:0px;
				width:100%;
				height:28px;
				background-color:<?php echo get_option('ijmtb_bg_colour') ?>;
				color:<?php echo get_option('ijmtb_text_colour'); ?>;
				text-shadow:none;
				z-index:9999;
			}
			
			#ijm-theme-bar ul, #ijm-theme-bar p {
				margin:0;
				padding:0;
			}
			
			#ijm-theme-bar ul li {
				float:left;
				list-style:none;
				margin-left:10px;
				content:"" !important;
				content:none !important;
			}
			
			#ijm-theme-bar ul li:before {
				content:"" !important;
				content:none !important;
			}
			
			#ijm-theme-bar a, #ijm-theme-bar p {
				color:<?php echo get_option('ijmtb_text_colour'); ?>;
				text-decoration:none;
				line-height:15px;
				font: normal 13px/28px sans-serif;
			}
		
			#ijm-theme-bar a:hover {
				color:<?php echo get_option('ijmtb_text_colour'); ?>;
				text-decoration:underline;
			}
			
			p#ijmtb_label {
				background-color:<?php echo get_option('ijmtb_label_bg_colour') ?>;
				color:<?php echo get_option('ijmtb_label_text_colour'); ?>;
				padding: 0 4px;
				left: 0px;
				position: absolute;
			}
		</style>
<?php
	}

