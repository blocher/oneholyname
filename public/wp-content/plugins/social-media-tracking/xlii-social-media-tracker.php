<?php
/* 
Plugin Name: 42functions Social Media Tracking
Plugin URI: http://www.42functions.nl
Version: v2.3
Author: 42functions
Author URI: http://42functions.nl/
Description: Plugin used to track social media events with google analytics. 
*/

include_once('xlii-core.php');
include_once('xlii-page.php');

// Append action menu
add_action('admin_menu', 'xlii_smt_adminmenu');

function xlii_smt_adminmenu()
{
    add_options_page('42functions Social Media Tracker', 'Social Media Tracker', 'administrator', 'xlii-smt-main', '_xlii_smt_router');
}

// Only include plugin when we need to
global $pagenow;
if($pagenow == 'options-general.php' && strpos($_GET['page'], 'xlii-smt') !== false)
    include_once('xlii-admin.php');