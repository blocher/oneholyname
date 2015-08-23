<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta name="keywords" content="<?php echo get_option('diary_keywords'); ?>" />
<meta name="description" content="<?php echo get_option('diary_description'); ?>" />
<meta charset="<?php bloginfo('charset'); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	?></title>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/skin.php" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/prettyPhoto.css" />
<!-- [if IE 7]>
<link rel="stylesheet" media="all" href="<?php bloginfo('template_directory'); ?>/css/ie7.css" />
<![endif]-->

<!-- Google Fonts -->
<link href='http://fonts.googleapis.com/css?family=Droid+Serif:regular,bold' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Kristi' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Bevan' rel='stylesheet' type='text/css'>
<?php webfonts_render()?>
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	wp_head();
?>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.tweet.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.form.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.prettyPhoto.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/custom.js" type="text/javascript"></script>
</head>

<body>
<div id="wrapper">
	<!-- Begin Header -->
	<header id="page-header">
	  <div id="logo">
			<h1 id="title"><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<span><?php bloginfo( 'description');?></span>	  </div>
	 <div id="topSearch">
		<form id="searchform" action="<?php bloginfo( 'url' ); ?>" method="get">
			<input type="text" id="s" name="s" value="type your search and hit enter" onFocus="this.value=''" />
		</form>
	</div>
	</header>
	<!-- End Header -->
	<!-- Begin Content -->
	<div id="content-wrapper">
		<div id="content-inner-wrapper">
