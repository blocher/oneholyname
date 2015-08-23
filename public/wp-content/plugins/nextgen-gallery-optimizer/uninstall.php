<?php

// Delete our settings from the wp_options table.

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    
	exit();

}

delete_option( 'nextgen_gallery_optimizer_basic_settings' );