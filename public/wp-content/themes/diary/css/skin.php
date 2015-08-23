<?php
require_once( '../../../../wp-load.php' );
header("Content-type: text/css");
?>
/* COLORS*/
body {
 color:<?php echo get_option('diary_blog_text_color')?>;
}
h1, h2 , h3, h4, h5, h6{
 	color:<?php echo get_option('diary_main_h_color')?>;
}

a {
	color:<?php echo get_option('diary_main_links_color')?>;
}

a:hover {
	color:<?php echo get_option('diary_main_links_hover_color')?>;
	border-bottom: 1px dotted <?php echo get_option('diary_main_links_hover_color')?>;
}

#logo h1#title a, #logo h1#title a:hover{
	color:<?php echo get_option('diary_blog_title_color')?>;
}

.post header .date {
	color:<?php echo get_option('diary_post_date_color')?>;
}

.post header h2 a {
	color:<?php echo get_option('diary_post_title_color')?>;
}

.post header h2 a:hover {
	color:<?php echo get_option('diary_post_title_hover_color')?>;
	border-bottom:1px dotted <?php echo get_option('diary_post_title_hover_color')?>;
}

.post section .more-link {
	color:<?php echo get_option('diary_read_more_color')?>;
}

.post section .more-link:hover {
	color:<?php echo get_option('diary_read_more_hover_color')?>;
}

#sidebar h3 {
	color:<?php echo get_option('diary_sidebar_h_color')?>;
}

#sidebar a {
	color:<?php echo get_option('diary_sidebar_links_color')?>;
} 

#page-footer {
	color: <?php echo get_option('diary_footer_color')?>;
	text-shadow:1px 1px <?php 
	if(get_option('diary_footer_shadow_color')){
	echo get_option('diary_footer_shadow_color');
	}else{
		echo "#ffffff";
	}?>;
}

#page-footer a{
	color: <?php echo get_option('diary_footer_links_color')?>;
}

/* FONTS*/

body {
	font-family:<?php echo get_option('diary_main_font')?>;
}

#logo h1#title {
	font-family:<?php echo get_option('diary_font_title')?>;
	font-size:<?php echo get_option('diary_font_title_size').'px'?>;
}

.post header .date {
	font-family:<?php echo get_option('diary_font_date')?>;
	font-size:<?php echo get_option('diary_font_date_size').'px'?>;
}

.post header h2, .page header h2 {
	font-family: <?php echo get_option('diary_font_heading')?>;
	font-size:<?php echo get_option('diary_font_heading_size').'px'?>;
}

.post section .more-link {
	font-family:<?php echo get_option('diary_font_more')?>;
	font-size:<?php echo get_option('diary_font_more_size').'px'?>;
}

#sidebar h3 {
	font-family: <?php echo get_option('diary_font_sideheading')?>;
	font-size:<?php echo get_option('diary_font_sideheading_size').'px'?>;
}

