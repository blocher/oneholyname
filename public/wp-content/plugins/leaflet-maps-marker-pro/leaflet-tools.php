<?php
/*
    Tools - Maps Marker Pro
*/
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'leaflet-tools.php') { die ("Please do not access this file directly. Thanks!<br/><a href='https://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }
?>
<div class="wrap">
<?php
include('inc' . DIRECTORY_SEPARATOR . 'admin-header.php');
global $wpdb;
$lmm_options = get_option( 'leafletmapsmarker_options' );
//info: set custom marker icon dir/url
if ( $lmm_options['defaults_marker_custom_icon_url_dir'] == 'no' ) {
	$defaults_marker_icon_dir = LEAFLET_PLUGIN_ICONS_DIR;
	$defaults_marker_icon_url = LEAFLET_PLUGIN_ICONS_URL;
} else {
	$defaults_marker_icon_dir = htmlspecialchars($lmm_options['defaults_marker_icon_dir']);
	$defaults_marker_icon_url = htmlspecialchars($lmm_options['defaults_marker_icon_url']);
}
$table_name_markers = $wpdb->prefix.'leafletmapsmarker_markers';
$table_name_layers = $wpdb->prefix.'leafletmapsmarker_layers';
$markercount_all = $wpdb->get_var('SELECT count(*) FROM '.$table_name_markers.'');
$layercount_all = $wpdb->get_var('SELECT count(*) FROM '.$table_name_layers.'') - 1;
$pro_version = get_option("leafletmapsmarker_version_pro");
$action = isset($_POST['action']) ? $_POST['action'] : '';

if (!empty($action)) {
	$toolnonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : (isset($_GET['_wpnonce']) ? $_GET['_wpnonce'] : '');
	if (! wp_verify_nonce($toolnonce, 'tool-nonce') ) { die('<br/>'.__('Security check failed - please call this function from the according admin page!','lmm').''); };
	if ($action == 'update-settings') {
		$serialized_options_new = stripslashes($_POST['settings-array']);
		if (is_serialized($serialized_options_new)) {
			if (!isset($_POST['multisite_options_propagate'])) {
				$options_table = $wpdb->prefix.'options';
				$update_options = $wpdb->prepare( "UPDATE `$options_table` SET `option_value` = %s where `option_name` = 'leafletmapsmarker_options'", $serialized_options_new );
				$wpdb->query( $update_options );
				echo '<p><div class="updated" style="padding:10px;">' . __('Plugin options updated.','lmm') . '<br/>' . sprintf(__('Please be aware that restoring settings from a version smaller than %1$s could result in breaking the plugin unless you <a href="%2$s">save the plugin settings once</a> afterwards to include settings added with newer versions!','lmm'), $pro_version, LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_settings') . '</div><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
			} else {
				if (is_multisite()) {
					if (current_user_can( 'activate_plugins' )) {
						global $wpdb;
						$blogs = $wpdb->get_results("SELECT `blog_id` FROM {$wpdb->blogs}", ARRAY_A);
						if ($blogs) {
							foreach($blogs as $blog) {
								switch_to_blog($blog['blog_id']);
								$options_table = $wpdb->prefix.'options';
								$update_options = $wpdb->prepare( "UPDATE `$options_table` SET `option_value` = %s where `option_name` = 'leafletmapsmarker_options'", $serialized_options_new );
								$wpdb->query( $update_options );
							}
							restore_current_blog();
						}
						echo '<p><div class="updated" style="padding:10px;">' . __('Plugin options updated on all subsites.','lmm') . '<br/>' . sprintf(__('Please be aware that restoring settings from a version smaller than %1$s could result in breaking the plugin unless you <a href="%2$s">save the plugin settings once</a> afterwards to include settings added with newer versions!','lmm'), $pro_version, LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_settings') . '</div><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
					}
				}
			}
		} else {
			echo '<p><div class="error" style="padding:10px;"><strong>' . __('Error: settings were not updated as your input could not be serialized.','lmm') . '</strong></div><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
		}
	}
	else if ($action == 'deleted_maps_errors') {
		delete_option('leafletmapsmarkerpro_deleted_maps_errors');
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('The list of "Active shortcodes with invalid map IDs" has been deleted!','lmm'), htmlspecialchars($_POST['layer_assign_from']), htmlspecialchars($_POST['layer_assign_to'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
		echo '<script type="text/javascript">
				jQuery(function($) {
					$(document).ready(function(){
						$("#deleted_maps_errors").hide();
					});
				});
			</script>';
	}
	else if ($action == 'mass_assign') {
		$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `layer` = %d where `layer` = %d", $_POST['layer_assign_to'], $_POST['layer_assign_from'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('All markers from layer ID %1$s have been successfully assigned to layer ID %2$s','lmm'), htmlspecialchars($_POST['layer_assign_from']), htmlspecialchars($_POST['layer_assign_to'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';

	}
	elseif ($action == 'basemap') {
		if ($_POST['marker-basemap-layer'] == 'all') {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `basemap` = %s", $_POST['basemap'] );
		} else {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `basemap` = %s WHERE `layer` = %d", $_POST['basemap'], intval($_POST['marker-basemap-layer']) );
		}
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('The basemap for the selected markers has been successfully set to %1$s','lmm'), htmlspecialchars($_POST['basemap'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'overlays') {
		$overlays_checkbox = isset($_POST['overlays_custom']) ? '1' : '0';
		$overlays2_checkbox = isset($_POST['overlays_custom2']) ? '1' : '0';
		$overlays3_checkbox = isset($_POST['overlays_custom3']) ? '1' : '0';
		$overlays4_checkbox = isset($_POST['overlays_custom4']) ? '1' : '0';
		if ($_POST['marker-overlays-layer'] == 'all') {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `overlays_custom` = %s, `overlays_custom2` = %s, `overlays_custom3` = %s, `overlays_custom4` = %s", $overlays_checkbox, $overlays2_checkbox, $overlays3_checkbox, $overlays4_checkbox );
		} else {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `overlays_custom` = %s, `overlays_custom2` = %s, `overlays_custom3` = %s, `overlays_custom4` = %s WHERE `layer` = %d", $overlays_checkbox, $overlays2_checkbox, $overlays3_checkbox, $overlays4_checkbox, intval($_POST['marker-overlays-layer']) );
		}
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The overlays status for the selected markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'wms') {
		$wms_checkbox = isset($_POST['wms']) ? '1' : '0';
		$wms2_checkbox = isset($_POST['wms2']) ? '1' : '0';
		$wms3_checkbox = isset($_POST['wms3']) ? '1' : '0';
		$wms4_checkbox = isset($_POST['wms4']) ? '1' : '0';
		$wms5_checkbox = isset($_POST['wms5']) ? '1' : '0';
		$wms6_checkbox = isset($_POST['wms6']) ? '1' : '0';
		$wms7_checkbox = isset($_POST['wms7']) ? '1' : '0';
		$wms8_checkbox = isset($_POST['wms8']) ? '1' : '0';
		$wms9_checkbox = isset($_POST['wms9']) ? '1' : '0';
		$wms10_checkbox = isset($_POST['wms10']) ? '1' : '0';
		if ($_POST['marker-wms-layer'] == 'all') {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `wms` = %d, `wms2` = %d, `wms3` = %d, `wms4` = %d, `wms5` = %d, `wms6` = %d, `wms7` = %d, `wms8` = %d, `wms9` = %d, `wms10` = %d", $wms_checkbox, $wms2_checkbox, $wms3_checkbox, $wms4_checkbox, $wms5_checkbox, $wms6_checkbox, $wms7_checkbox, $wms8_checkbox, $wms9_checkbox, $wms10_checkbox );
		} else {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `wms` = %d, `wms2` = %d, `wms3` = %d, `wms4` = %d, `wms5` = %d, `wms6` = %d, `wms7` = %d, `wms8` = %d, `wms9` = %d, `wms10` = %d WHERE `layer` = %d", $wms_checkbox, $wms2_checkbox, $wms3_checkbox, $wms4_checkbox, $wms5_checkbox, $wms6_checkbox, $wms7_checkbox, $wms8_checkbox, $wms9_checkbox, $wms10_checkbox, intval($_POST['marker-wms-layer']) );
		}
		$wpdb->query( $result );
		echo '<p><div class="updated" style="padding:10px;">' . __('The WMS status for the selected markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'mapsize') {
		if ($_POST['marker-mapsize-layer'] == 'all') {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `mapwidth` = %d, `mapwidthunit` = %s, `mapheight` = %d", $_POST['mapwidth'], $_POST['mapwidthunit'], $_POST['mapheight'] );
		} else {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `mapwidth` = %d, `mapwidthunit` = %s, `mapheight` = %d WHERE `layer` = %d", $_POST['mapwidth'], $_POST['mapwidthunit'], $_POST['mapheight'], intval($_POST['marker-mapsize-layer']) );
		}
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('The map size for all markers has been successfully set to width =  %1$s %2$s and height = %3$s px','lmm'), htmlspecialchars($_POST['mapwidth']), htmlspecialchars($_POST['mapwidthunit']), htmlspecialchars($_POST['mapheight'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'zoom') {
		if ($_POST['marker-zoom-layer'] == 'all') {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `zoom` = %d", $_POST['zoom'] );
		} else {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `zoom` = %d WHERE `layer` = %d", $_POST['zoom'], intval($_POST['marker-zoom-layer']) );
		}
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('Zoom level for the selected markers has been successfully set to %1$s','lmm'), htmlspecialchars($_POST['zoom'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'controlbox') {
		if ($_POST['marker-controlbox-layer'] == 'all') {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `controlbox` = %d", $_POST['controlbox'] );
		} else {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `controlbox` = %d WHERE `layer` = %d", $_POST['controlbox'], intval($_POST['marker-controlbox-layer']) );
		}
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
		echo '<p><div class="updated" style="padding:10px;">' . __('Controlbox status for the selected markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'panel') {
		if ($_POST['marker-panel-layer'] == 'all') {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `panel` = %d", $_POST['panel'] );
		} else {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `panel` = %d WHERE `layer` = %d", $_POST['panel'], intval($_POST['marker-panel-layer']) );
		}
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
		echo '<p><div class="updated" style="padding:10px;">' . __('Panel status for the selected markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'icon') {
		if ($_POST['marker-icon-layer'] == 'all') {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `icon` = %s", $_POST['icon'] );
		} else {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `icon` = %s WHERE `layer` = %d", $_POST['icon'], intval($_POST['marker-icon-layer']) );
		}
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The icon for the selected markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'openpopup') {
		if ($_POST['marker-openpopup-layer'] == 'all') {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `openpopup` = %d", $_POST['openpopup'] );
		} else {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `openpopup` = %d WHERE `layer` = %d", $_POST['openpopup'], intval($_POST['marker-openpopup-layer']) );
		}
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The popup status for the selected markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'popuptext') {
		$popuptext = preg_replace("/\t/", " ", $_POST['popuptext']); //info: tabs break geojson
		if ($_POST['marker-popuptext-layer'] == 'all') {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `popuptext` = %s", $popuptext );
		} else {
			$result = $wpdb->prepare( "UPDATE `$table_name_markers` SET `popuptext` = %s WHERE `layer` = %d", $popuptext, intval($_POST['marker-popuptext-layer']) );
		}
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The popup text for the selected markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'basemap-layer') {
		$result = $wpdb->prepare( "UPDATE `$table_name_layers` SET `basemap` = %s", $_POST['basemap-layer'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_layers`" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('The basemap for all layers has been successfully set to %1$s','lmm'), htmlspecialchars($_POST['basemap-layer'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'overlays-layer') {
		$overlays_checkbox = isset($_POST['overlays_custom-layer']) ? '1' : '0';
		$overlays2_checkbox = isset($_POST['overlays_custom2-layer']) ? '1' : '0';
		$overlays3_checkbox = isset($_POST['overlays_custom3-layer']) ? '1' : '0';
		$overlays4_checkbox = isset($_POST['overlays_custom4-layer']) ? '1' : '0';
		$result = $wpdb->prepare( "UPDATE `$table_name_layers` SET `overlays_custom` = %s, `overlays_custom2` = %s, `overlays_custom3` = %s, `overlays_custom4` = %s", $overlays_checkbox, $overlays2_checkbox, $overlays3_checkbox, $overlays4_checkbox );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_layers`" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The overlays status for all layers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'wms-layer') {
		$wms_checkbox = isset($_POST['wms-layer']) ? '1' : '0';
		$wms2_checkbox = isset($_POST['wms2-layer']) ? '1' : '0';
		$wms3_checkbox = isset($_POST['wms3-layer']) ? '1' : '0';
		$wms4_checkbox = isset($_POST['wms4-layer']) ? '1' : '0';
		$wms5_checkbox = isset($_POST['wms5-layer']) ? '1' : '0';
		$wms6_checkbox = isset($_POST['wms6-layer']) ? '1' : '0';
		$wms7_checkbox = isset($_POST['wms7-layer']) ? '1' : '0';
		$wms8_checkbox = isset($_POST['wms8-layer']) ? '1' : '0';
		$wms9_checkbox = isset($_POST['wms9-layer']) ? '1' : '0';
		$wms10_checkbox = isset($_POST['wms10-layer']) ? '1' : '0';
		$result = $wpdb->prepare( "UPDATE `$table_name_layers` SET `wms` = %d, `wms2` = %d, `wms3` = %d, `wms4` = %d, `wms5` = %d, `wms6` = %d, `wms7` = %d, `wms8` = %d, `wms9` = %d, `wms10` = %d", $wms_checkbox, $wms2_checkbox, $wms3_checkbox, $wms4_checkbox, $wms5_checkbox, $wms6_checkbox, $wms7_checkbox, $wms8_checkbox, $wms9_checkbox, $wms10_checkbox );
		$wpdb->query( $result );
		echo '<p><div class="updated" style="padding:10px;">' . __('The WMS status for all layers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'mapsize-layer') {
		$result = $wpdb->prepare( "UPDATE `$table_name_layers` SET `mapwidth` = %d, `mapwidthunit` = %s, `mapheight` = %d", $_POST['mapwidth-layer'], $_POST['mapwidthunit-layer'], $_POST['mapheight-layer'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_layers`" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('The map size for all layers has been successfully set to width =  %1$s %2$s and height = %3$s px','lmm'), htmlspecialchars($_POST['mapwidth-layer']), htmlspecialchars($_POST['mapwidthunit-layer']), htmlspecialchars($_POST['mapheight-layer'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'zoom-layer') {
		$result = $wpdb->prepare( "UPDATE `$table_name_layers` SET `layerzoom` = %s", $_POST['zoom-layer'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_layers`" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('Zoom level for all layers has been successfully set to %1$s','lmm'), htmlspecialchars($_POST['zoom-layer'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'controlbox-layer') {
		$result = $wpdb->prepare( "UPDATE `$table_name_layers` SET `controlbox` = %d", $_POST['controlbox-layer'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_layers`" );
		echo '<p><div class="updated" style="padding:10px;">' . __('Controlbox status for all layers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'panel-layer') {
		$result = $wpdb->prepare( "UPDATE `$table_name_layers` SET `panel` = %d", $_POST['panel-layer'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_layers`" );
		echo '<p><div class="updated" style="padding:10px;">' . __('Panel status for all layers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'listmarkers-layer') {
		$result = $wpdb->prepare( "UPDATE `$table_name_layers` SET `listmarkers` = %d", $_POST['listmarkers-layer'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_layers`" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The list marker-status for all layers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'listmarkers-clustering') {
		$result = $wpdb->prepare( "UPDATE `$table_name_layers` SET `clustering` = %d", $_POST['listmarkers-clustering'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_layers`" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The clustering status for all layers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'change_marker_id') {
		$old_marker_id_exists = $wpdb->get_var($wpdb->prepare( "SELECT `id` from `$table_name_markers` WHERE `id` = %d", intval($_POST['marker_id_old']) ));
		if ($old_marker_id_exists == NULL) {
			echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('Error: a marker with the ID %1$s does not exist!','lmm'), $_POST['marker_id_old']) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
		} else {
			$new_marker_id_exists = $wpdb->get_var($wpdb->prepare( "SELECT `id` from `$table_name_markers` WHERE `id` = %d", intval($_POST['marker_id_new']) ));
			if ($new_marker_id_exists != NULL) {
				echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('Error: a marker with the ID %1$s already exists!','lmm'), $_POST['marker_id_new']) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
			} else {
				$update_id_sql = $wpdb->prepare( "UPDATE `$table_name_markers` SET `id` = %d WHERE `id` = %d", intval($_POST['marker_id_new']), intval($_POST['marker_id_old']));
				$wpdb->query( $update_id_sql );
				$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
				echo '<p><div class="updated" style="padding:10px;">' . sprintf(__('The marker ID %1$s has been successfully changed to ID %2$s','lmm'), intval($_POST['marker_id_old']), intval($_POST['marker_id_new'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
			}
		}
	}
	elseif ($action == 'change_layer_id') {
		$old_layer_id_exists = $wpdb->get_var($wpdb->prepare( "SELECT `id` from `$table_name_layers` WHERE `id` = %d", intval($_POST['layer_id_old']) ));
		if ($old_layer_id_exists == NULL) {
			echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('Error: a layer with the ID %1$s does not exist!','lmm'), $_POST['layer_id_old']) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
		} else {
			$new_layer_id_exists = $wpdb->get_var($wpdb->prepare( "SELECT `id` from `$table_name_layers` WHERE `id` = %d", intval($_POST['layer_id_new']) ));
			if ($new_layer_id_exists != NULL) {
				echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('Error: a layer with the ID %1$s already exists!','lmm'), $_POST['layer_id_new']) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
			} else {
				$update_id_sql = $wpdb->prepare( "UPDATE `$table_name_layers` SET `id` = %d WHERE `id` = %d", intval($_POST['layer_id_new']), intval($_POST['layer_id_old']));
				$wpdb->query( $update_id_sql );
				$update_id_sql_assigned_markers = $wpdb->prepare( "UPDATE `$table_name_markers` SET `layer` = %d WHERE `layer` = %d", intval($_POST['layer_id_new']), intval($_POST['layer_id_old']));
				$wpdb->query( $update_id_sql_assigned_markers );
				$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
				$wpdb->query( "OPTIMIZE TABLE `$table_name_layers`" );
				echo '<p><div class="updated" style="padding:10px;">' . sprintf(__('The layer ID %1$s has been successfully changed to ID %2$s, all assigned markers have been updated too. Multi-layer-maps where this layer was assigned to have NOT been updated!','lmm'), intval($_POST['layer_id_old']), intval($_POST['layer_id_new'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
			}
		}
	}
	elseif ($action == 'mass_delete_from_layer') {
		//info: delete qr code cache images for assigned markers
		$layer_marker_list_qr = $wpdb->get_results('SELECT m.id as markerid,m.layer as mlayer,l.id as lid FROM `'.$table_name_layers.'` as l INNER JOIN `'.$table_name_markers.'` AS m ON l.id=m.layer WHERE l.id=' . intval($_POST['delete_from_layer']), ARRAY_A);
		foreach ($layer_marker_list_qr as $row){
			if ( file_exists(LEAFLET_PLUGIN_QR_DIR . DIRECTORY_SEPARATOR . 'marker-' . $row['markerid'] . '.png') ) {
				unlink(LEAFLET_PLUGIN_QR_DIR . DIRECTORY_SEPARATOR . 'marker-' . $row['markerid'] . '.png');
			}
		}
		$result = $wpdb->prepare( "DELETE FROM `$table_name_markers` where `layer` = %d", $_POST['delete_from_layer']);
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE `$table_name_markers`" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('All markers from layer ID %1$s have been successfully deleted','lmm'), htmlspecialchars($_POST['delete_from_layer'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
	}
	elseif ($action == 'mass_delete_all_markers') {
		//info: delete qr code cache images
		$layer_marker_list_qr = $wpdb->get_results('SELECT id as markerid FROM '.$table_name_markers, ARRAY_A);
		foreach ($layer_marker_list_qr as $row){
			if ( file_exists(LEAFLET_PLUGIN_QR_DIR . DIRECTORY_SEPARATOR . 'marker-' . $row['markerid'] . '.png') ) {
				unlink(LEAFLET_PLUGIN_QR_DIR . DIRECTORY_SEPARATOR . 'marker-' . $row['markerid'] . '.png');
			}
		}
		$result = "DELETE FROM $table_name_markers";
		$wpdb->query( $result );
  		$delete_confirm_checkbox = isset($_POST['delete_confirm_checkbox']) ? '1' : '0';
	  	if ($delete_confirm_checkbox == 1) {
			echo '<p><div class="updated" style="padding:10px;">' . __('All markers from all layers have been successfully deleted','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
		} else {
			echo '<p><div class="error" style="padding:10px;">' . __('Please confirm that you want to delete all markers by checking the checkbox','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
		}
  } elseif ($action == 'clear_qr_cache') {
		if (is_dir(LEAFLET_PLUGIN_QR_DIR)) {
			foreach(glob(LEAFLET_PLUGIN_QR_DIR . DIRECTORY_SEPARATOR .'*.*') as $v){
				unlink($v);
			}
		}
		echo '<p><div class="updated" style="padding:10px;">' . sprintf(__('The QR code images cache at %1$s has been successfully cleared.','lmm'), LEAFLET_PLUGIN_QR_URL) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
} else {
	$layerlist = $wpdb->get_results('SELECT * FROM `' . $table_name_layers . '` WHERE `id` > 0', ARRAY_A);
	?>
	<h3 style="font-size:23px;"><?php _e('Tools','lmm'); ?></h3>
	<?php $nonce= wp_create_nonce('tool-nonce'); ?>

	<?php
	echo __('Please use the following tools with care and consider making a database backup or export first, as most actions cannot be undone:','lmm');
	echo '
	<ul>
	<li>- <a href="#backup-restore" style="text-decoration:none;">' . __('Backup/Restore settings','lmm') . '</a></li>
	<li>- <a href="#deleted-maps-errors" style="text-decoration:none;">' . __('Active shortcodes with invalid map IDs','lmm') . '</a></li>
	<li>- <a href="#move-markers" style="text-decoration:none;">' . __('Move markers to a layer','lmm') . '</a></li>
	<li>- <a href="#bulk-update-markers" style="text-decoration:none;">' . __('Bulk updates for marker maps','lmm') . '</a></li>
	<li>- <a href="#bulk-update-layers" style="text-decoration:none;">' . sprintf( esc_attr__('Bulk updates for all %1$s existing layer maps','lmm'), $layercount_all) . '</a></li>
	<li>- <a href="#change-marker-id" style="text-decoration:none;">' . __('Change marker ID','lmm') . '</a></li>
	<li>- <a href="#change-layer-id" style="text-decoration:none;">' . __('Change layer ID','lmm') . '</a></li>
	<li>- <a href="#api-url-generator" style="text-decoration:none;">' . __('API URL generator','lmm') . '</a></li>
	<li>- <a href="#api-url-tester" style="text-decoration:none;">' . __('API URL tester','lmm') . '</a></li>
	<li>- <a href="#clear-qr-cache" style="text-decoration:none;">' . __('Clear QR code images cache','lmm') . '</a></li>
	<li>- <a href="#delete-selected-markers" style="text-decoration:none;">' . __('Delete all markers from a layer','lmm') . '</a></li>
	<li>- <a href="#delete-all-markers" style="text-decoration:none;">' . sprintf( esc_attr__('Delete all %1$s markers from all %2$s layers','lmm'), $markercount_all, $layercount_all) . '</a></li>
	</ul>';
	?>
	<a name="backup-restore"></a>
	<br/>
	<form method="post">
	<input type="hidden" name="action" value="update-settings" />
	<?php
	wp_nonce_field('tool-nonce');
	$serialized_options = serialize($lmm_options);
	?>
	<table class="widefat" style="width:100%;height:100px;">
		<tr style="background-color:#d6d5d5;">
			<td colspan="2"><strong><?php _e('Backup/Restore settings','lmm'); ?> <img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/help-pro-feature.png" /></strong></td>
		</tr>
		<tr>
			<td style="vertical-align:top;">
				<p><?php _e('Below you find you current settings. Use copy and paste to make a backup or restore.','lmm'); ?><br/><?php echo sprintf(__('Please be aware that restoring settings from a version smaller than %1$s could result in breaking the plugin unless you <a href="%2$s">save the plugin settings once</a> afterwards to include settings added with newer versions!','lmm'), $pro_version, LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_settings'); ?></p>
				<?php
					$settings_tinymce = array(
					'wpautop' => false,
					'media_buttons' => false,
					'tinymce' => array(
					 ),
					'quicktags' => false
					);
					wp_editor( $serialized_options, 'settings-array', $settings_tinymce);
				echo '</p>';
				if (is_multisite()) {
					if (current_user_can( 'activate_plugins' )) {
						echo '<div style="margin-top:10px;"><input type="checkbox" name="multisite_options_propagate" /> <label for="multisite_options_propagate">' . __('Multisite-only: also update settings on all subsites','lmm') . '</label></div>';
					}
				}
				echo '<input style="font-weight:bold;margin:10px 0;" class="submit button-primary" type="submit" name="update-settings" value="' . __('update settings','lmm') . ' &raquo;" onclick="return confirm(\'' . __('Do you really want to update your settings?','lmm') . '\')" />';
				echo '<p>' . sprintf(__('In case of any issues you can always <a href="%1$s">reset the plugin settings</a>','lmm'), LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_settings#reset') . '</p>';
				?>
				<script type="text/javascript">
					(function($) {
						$("#settings-array").click(function(){
							this.select();
						});
					})(jQuery);
				</script>
			</td>
		</tr>
	</table>
	</form>
	<p><a href="#top" style="text-decoration:none;"><?php _e('back to top','lmm'); ?></a></p>

	<a name="deleted-maps-errors"></a>
	<br/><br/>
	<?php $nonce= wp_create_nonce('tool-nonce'); ?>
	<form method="post">
	<input type="hidden" name="action" value="deleted_maps_errors" />
	<?php wp_nonce_field('tool-nonce'); ?>
	<table class="widefat fixed" style="width:auto;">
		<tr style="background-color:#d6d5d5;">
			<td colspan="2"><strong><?php _e('Active shortcodes with invalid map IDs','lmm') ?></strong></td>
		</tr>
		<tr>
			<td style="vertical-align:middle;">
				<?php
					$deleted_maps_errors = get_option( 'leafletmapsmarkerpro_deleted_maps_errors' );
					if ($deleted_maps_errors === FALSE) {
						echo __('No shortcodes with invalid map IDs have been detected','lmm');
					} else {
						echo sprintf(__('Warning: you are using Maps Marker Pro shortcodes with invalid map IDs! As a result instead of a map an <a href="%1$s" target="_blank">error image</a> will be shown!','lmm'), LEAFLET_PLUGIN_URL . 'inc/img/map-deleted-image.png');
						echo '<ul style="list-style-type:disc;margin-left:15px;">';
						foreach ($deleted_maps_errors as $key => $value) {
							echo '<li><a href="'.$value.'" target="_blank">'.$value.'</a></li>';
						}
						echo '</ul>';
						echo __('Please fix those shortcodes and press the button "clear list" below for the admin notice to disappear!','lmm');
						echo '<p><input style="font-weight:bold;" class="submit button-primary" type="submit" name="mass_asign-submit" value="' . __('clear list','lmm') . ' &raquo;" /></p>';
					}
				?>				
			</td>
		</tr>
	</table>
	</form>
	<p><a href="#top" style="text-decoration:none;"><?php _e('back to top','lmm'); ?></a></p>

	<a name="move-markers"></a>
	<br/><br/>
	<?php $nonce= wp_create_nonce('tool-nonce'); ?>
	<form method="post">
	<input type="hidden" name="action" value="mass_assign" />
	<?php wp_nonce_field('tool-nonce'); ?>
	<table class="widefat fixed" style="width:auto;">
		<tr style="background-color:#d6d5d5;">
			<td colspan="2"><strong><?php _e('Move markers to a layer','lmm') ?></strong></td>
		</tr>
		<tr>
			<td style="vertical-align:middle;">
				<?php _e('Source','lmm') ?>:
				<select id="layer_assign_from" name="layer_assign_from">
				<?php $markercount_layer0 = $wpdb->get_var('SELECT count(*) FROM `'.$table_name_layers.'` as l INNER JOIN `'.$table_name_markers.'` AS m ON l.id=m.layer WHERE l.id=0'); ?>
				<option value="0">ID 0 - <?php _e('unassigned','lmm') ?> (<?php echo $markercount_layer0; ?> <?php _e('marker','lmm'); ?>)</option>
				<?php
				foreach ($layerlist as $row) {
					$markercount = $wpdb->get_var('SELECT count(*) FROM '.$table_name_layers.' as l INNER JOIN '.$table_name_markers.' AS m ON l.id=m.layer WHERE l.id='.$row['id']);
					echo '<option value="' . $row['id'] . '">ID ' . $row['id'] . ' - ' . stripslashes(htmlspecialchars($row['name'])) . ' (' . $markercount .' ' . __('marker','lmm') . ')</option>';
				}
				?>
				</select>
				<?php _e('Target','lmm') ?>:
				<select id="layer_assign_to" name="layer_assign_to">
				<option value="0">ID 0 - <?php _e('unassigned','lmm') ?> (<?php echo $markercount_layer0; ?> <?php _e('marker','lmm'); ?>)</option>
				<?php
				foreach ($layerlist as $row) {
					$markercount = $wpdb->get_var('SELECT count(*) FROM `'.$table_name_layers.'` as l INNER JOIN `'.$table_name_markers.'` AS m ON l.id=m.layer WHERE l.id='.$row['id']);
					echo '<option value="' . $row['id'] . '">ID ' . $row['id'] . ' - ' . stripslashes(htmlspecialchars($row['name'])) . ' (' . $markercount .' ' . __('marker','lmm') . ')</option>';
				}
				?>
				</select>
			</td>
			<td>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mass_asign-submit" value="<?php _e('move markers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to move the selected markers?','lmm') ?>')" />
			</td>
		</tr>
	</table>
	</form>
	<p><a href="#top" style="text-decoration:none;"><?php _e('back to top','lmm'); ?></a></p>

	<a name="bulk-update-markers"></a>
	<br/><br/>
	<?php $nonce= wp_create_nonce('tool-nonce'); ?>
	<table class="widefat fixed" style="width:auto;">
		<tr style="background-color:#d6d5d5;">
			<td colspan="3"><strong><?php echo __('Bulk updates for marker maps','lmm'); ?></strong></td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="basemap" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Basemap','lmm') ?></strong>
			</td>
			<td class="lmm-border">
				<input id="markermaps_osm_mapnik" type="radio" name="basemap" value="osm_mapnik" checked /> <label for="markermaps_osm_mapnik"><?php echo $lmm_options['default_basemap_name_osm_mapnik']; ?></label><br />
				<input id="markermaps_mapquest_osm" type="radio" name="basemap" value="mapquest_osm" /> <label for="markermaps_mapquest_osm"><?php echo $lmm_options['default_basemap_name_mapquest_osm']; ?></label><br />
				<input id="markermaps_mapquest_aerial" type="radio" name="basemap" value="mapquest_aerial" /> <label for="markermaps_mapquest_aerial"><?php echo $lmm_options['default_basemap_name_mapquest_aerial']; ?></label><br />
				<input id="markermaps_googleLayer_roadmap" type="radio" name="basemap" value="googleLayer_roadmap" /> <label for="markermaps_googleLayer_roadmap"><?php echo $lmm_options['default_basemap_name_googleLayer_roadmap']; ?></label><br />
				<input id="markermaps_googleLayer_satellite" type="radio" name="basemap" value="googleLayer_satellite" /> <label for="markermaps_googleLayer_satellite"><?php echo $lmm_options['default_basemap_name_googleLayer_satellite']; ?></label><br />
				<input id="markermaps_googleLayer_hybrid" type="radio" name="basemap" value="googleLayer_hybrid" /> <label for="markermaps_googleLayer_hybrid"><?php echo $lmm_options['default_basemap_name_googleLayer_hybrid']; ?></label><br />
				<input id="markermaps_googleLayer_terrain" type="radio" name="basemap" value="googleLayer_terrain" /> <label for="markermaps_googleLayer_terrain"><?php echo $lmm_options['default_basemap_name_googleLayer_terrain']; ?></label><br />
				<input id="markermaps_bingaerial" type="radio" name="basemap" value="bingaerial" /> <label for="markermaps_bingaerial"><?php echo $lmm_options['default_basemap_name_bingaerial']; ?></label><br />
				<input id="markermaps_bingaerialwithlabels" type="radio" name="basemap" value="bingaerialwithlabels" /> <label for="markermaps_bingaerialwithlabels"><?php echo $lmm_options['default_basemap_name_bingaerialwithlabels']; ?></label><br />
				<input id="markermaps_bingroad" type="radio" name="basemap" value="bingroad" /> <label for="markermaps_bingroad"><?php echo $lmm_options['default_basemap_name_bingroad']; ?></label><br />
				<input id="markermaps_ogdwien_basemap" type="radio" name="basemap" value="ogdwien_basemap" /> <label for="markermaps_ogdwien_basemap"><?php echo $lmm_options['default_basemap_name_ogdwien_basemap']; ?></label><br />
				<input id="markermaps_ogdwien_satellite" type="radio" name="basemap" value="ogdwien_satellite" /> <label for="markermaps_ogdwien_satellite"><?php echo $lmm_options['default_basemap_name_ogdwien_satellite']; ?></label><br />
				<input id="markermaps_mapbox" type="radio" name="basemap" value="mapbox" /> <label for="markermaps_mapbox"><?php echo $lmm_options['mapbox_name']; ?></label><br />
				<input id="markermaps_mapbox2" type="radio" name="basemap" value="mapbox2" /> <label for="markermaps_mapbox2"><?php echo $lmm_options['mapbox2_name']; ?></label><br />
				<input id="markermaps_mapbox3" type="radio" name="basemap" value="mapbox3" /> <label for="markermaps_mapbox3"><?php echo $lmm_options['mapbox3_name']; ?></label><br />
				<input id="markermaps_custom_basemap" type="radio" name="basemap" value="custom_basemap" /> <label for="markermaps_custom_basemap"><?php echo $lmm_options['custom_basemap_name']; ?></label><br />
				<input id="markermaps_custom_basemap2" type="radio" name="basemap" value="custom_basemap2" /> <label for="markermaps_custom_basemap2"><?php echo $lmm_options['custom_basemap2_name']; ?></label><br />
				<input id="markermaps_custom_basemap3" type="radio" name="basemap" value="custom_basemap3" /> <label for="markermaps_custom_basemap3"><?php echo $lmm_options['custom_basemap3_name']; ?></label>
			</td>
			<td style="vertical-align:middle;text-align:center;" class="lmm-border">
				<?php _e('Which markers should be updated?','lmm'); ?>
				<select id="marker-basemap-layer" name="marker-basemap-layer" style="width:230px;">
					<option value="all">
					<?php echo sprintf(__('all %1$s markers','lmm'), $markercount_all) ?>
					</option>
					<option value="0">
					<?php _e('markers not assigned to a layer','lmm') ?>
					</option>
					<?php
						foreach ($layerlist as $row) {
							$layername_abstract = (strlen($row['name']) >= 21) ? '...': '';
							echo '<option value="' . $row['id'] . '" title="' . sprintf(esc_attr__('Update markers from the following layer only: "%1$s"','lmm'), stripslashes(htmlspecialchars($row['name']))) . '">' . mb_substr(stripslashes(htmlspecialchars($row['name'])), 0, 28) . $layername_abstract . ' (ID ' . $row['id'] . ')</option>';
						}
					?>
				</select><br/><br/>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="basemap-submit" value="<?php _e('change basemap','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the basemap for the selected markers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="overlays" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Checked overlays in control box','lmm') ?></strong>
				</td>
				<td class="lmm-border">
				<input id="markermaps_overlays_custom" type="checkbox" name="overlays_custom" /> <label for="markermaps_overlays_custom"><?php echo $lmm_options['overlays_custom_name']; ?></label><br />
				<input id="markermaps_overlays_custom2" type="checkbox" name="overlays_custom2" /> <label for="markermaps_overlays_custom2"><?php echo $lmm_options['overlays_custom2_name']; ?></label><br />
				<input id="markermaps_overlays_custom3" type="checkbox" name="overlays_custom3" /> <label for="markermaps_overlays_custom3"><?php echo $lmm_options['overlays_custom3_name']; ?></label><br />
				<input id="markermaps_overlays_custom4" type="checkbox" name="overlays_custom4" /> <label for="markermaps_overlays_custom4"><?php echo $lmm_options['overlays_custom4_name']; ?></label>
			</td>
			<td style="vertical-align:middle;text-align:center;" class="lmm-border">
				<?php _e('Which markers should be updated?','lmm'); ?>
				<select id="marker-overlays-layer" name="marker-overlays-layer" style="width:230px;">
					<option value="all">
					<?php echo sprintf(__('all %1$s markers','lmm'), $markercount_all) ?>
					</option>
					<option value="0">
					<?php _e('markers not assigned to a layer','lmm') ?>
					</option>
					<?php
						foreach ($layerlist as $row) {
							$layername_abstract = (strlen($row['name']) >= 21) ? '...': '';
							echo '<option value="' . $row['id'] . '" title="' . sprintf(esc_attr__('Update markers from the following layer only: "%1$s"','lmm'), stripslashes(htmlspecialchars($row['name']))) . '">' . mb_substr(stripslashes(htmlspecialchars($row['name'])), 0, 28) . $layername_abstract . ' (ID ' . $row['id'] . ')</option>';
						}
					?>
				</select><br/><br/>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="overlays-submit" value="<?php _e('change overlay status','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the overlay status for the selected markers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="wms" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Active WMS layers','lmm') ?></strong>
				</td>
				<td class="lmm-border">
				<input type="checkbox" name="wms" /> <?php echo strip_tags($lmm_options['wms_wms_name']); ?> <a title="<?php esc_attr_e('WMS layer 1 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections2"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms2" /> <?php echo strip_tags($lmm_options['wms_wms2_name']); ?> <a title="<?php esc_attr_e('WMS layer 2 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections3"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms3" /> <?php echo strip_tags($lmm_options['wms_wms3_name']); ?> <a title="<?php esc_attr_e('WMS layer 3 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections4"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms4" /> <?php echo strip_tags($lmm_options['wms_wms4_name']); ?> <a title="<?php esc_attr_e('WMS layer 4 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections5"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms5" /> <?php echo strip_tags($lmm_options['wms_wms5_name']); ?> <a title="<?php esc_attr_e('WMS layer 5 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections6"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms6" /> <?php echo strip_tags($lmm_options['wms_wms6_name']); ?> <a title="<?php esc_attr_e('WMS layer 6 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections7"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms7" /> <?php echo strip_tags($lmm_options['wms_wms7_name']); ?> <a title="<?php esc_attr_e('WMS layer 7 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections8"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms8" /> <?php echo strip_tags($lmm_options['wms_wms8_name']); ?> <a title="<?php esc_attr_e('WMS layer 8 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections9"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms9" /> <?php echo strip_tags($lmm_options['wms_wms9_name']); ?> <a title="<?php esc_attr_e('WMS layer 9 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections10"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms10" /> <?php echo strip_tags($lmm_options['wms_wms10_name']); ?> <a title="<?php esc_attr_e('WMS layer 10 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections11"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>
			</td>
			<td style="vertical-align:middle;text-align:center;" class="lmm-border">
				<?php _e('Which markers should be updated?','lmm'); ?>
				<select id="marker-wms-layer" name="marker-wms-layer" style="width:230px;">
					<option value="all">
					<?php echo sprintf(__('all %1$s markers','lmm'), $markercount_all) ?>
					</option>
					<option value="0">
					<?php _e('markers not assigned to a layer','lmm') ?>
					</option>
					<?php
						foreach ($layerlist as $row) {
							$layername_abstract = (strlen($row['name']) >= 21) ? '...': '';
							echo '<option value="' . $row['id'] . '" title="' . sprintf(esc_attr__('Update markers from the following layer only: "%1$s"','lmm'), stripslashes(htmlspecialchars($row['name']))) . '">' . mb_substr(stripslashes(htmlspecialchars($row['name'])), 0, 28) . $layername_abstract . ' (ID ' . $row['id'] . ')</option>';
						}
					?>
				</select><br/><br/>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="wms-submit" value="<?php _e('change active WMS layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change active WMS layers for the selected markers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border" style="vertical-align:top;">
				<form method="post">
				<input type="hidden" name="action" value="mapsize" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Map size','lmm') ?></strong>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<?php _e('Width','lmm') ?>:
				<input size="2" maxlength="4" type="text" id="mapwidth" name="mapwidth" value="<?php echo intval($lmm_options[ 'defaults_marker_mapwidth' ]) ?>" style="margin-left:5px;" />
				<input id="markermaps_mapwidthunit_px" type="radio" name="mapwidthunit" value="px" checked /><label for="markermaps_mapwidthunit_px">px</label>&nbsp;&nbsp;&nbsp;
				<input id="markermaps_mapwidthunit_percent" type="radio" name="mapwidthunit" value="%" /><label for="markermaps_mapwidthunit_percent">%</label><br/>
				<?php _e('Height','lmm') ?>:
				<input size="2" maxlength="4" type="text" id="mapheight" name="mapheight" value="<?php echo intval($lmm_options[ 'defaults_marker_mapheight' ]) ?>" /> px
			</td>
			<td style="vertical-align:middle;text-align:center;" class="lmm-border">
				<?php _e('Which markers should be updated?','lmm'); ?>
				<select id="marker-mapsize-layer" name="marker-mapsize-layer" style="width:230px;">
					<option value="all">
					<?php echo sprintf(__('all %1$s markers','lmm'), $markercount_all) ?>
					</option>
					<option value="0">
					<?php _e('markers not assigned to a layer','lmm') ?>
					</option>
					<?php
						foreach ($layerlist as $row) {
							$layername_abstract = (strlen($row['name']) >= 21) ? '...': '';
							echo '<option value="' . $row['id'] . '" title="' . sprintf(esc_attr__('Update markers from the following layer only: "%1$s"','lmm'), stripslashes(htmlspecialchars($row['name']))) . '">' . mb_substr(stripslashes(htmlspecialchars($row['name'])), 0, 28) . $layername_abstract . ' (ID ' . $row['id'] . ')</option>';
						}
					?>
				</select><br/><br/>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mapsize-submit" value="<?php _e('change mapsize','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the map size for the selected markers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td style="vertical-align:top;" class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="zoom" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Zoom','lmm') ?></strong>
			</td>
			<td style="vertical-align:top;" class="lmm-border">
				<input style="width: 40px;" type="text" name="zoom" value="<?php echo intval($lmm_options[ 'defaults_marker_zoom' ]) ?>" />
			</td>
			<td style="vertical-align:middle;text-align:center;" class="lmm-border">
				<?php _e('Which markers should be updated?','lmm'); ?>
				<select id="marker-zoom-layer" name="marker-zoom-layer" style="width:230px;">
					<option value="all">
					<?php echo sprintf(__('all %1$s markers','lmm'), $markercount_all) ?>
					</option>
					<option value="0">
					<?php _e('markers not assigned to a layer','lmm') ?>
					</option>
					<?php
						foreach ($layerlist as $row) {
							$layername_abstract = (strlen($row['name']) >= 21) ? '...': '';
							echo '<option value="' . $row['id'] . '" title="' . sprintf(esc_attr__('Update markers from the following layer only: "%1$s"','lmm'), stripslashes(htmlspecialchars($row['name']))) . '">' . mb_substr(stripslashes(htmlspecialchars($row['name'])), 0, 28) . $layername_abstract . ' (ID ' . $row['id'] . ')</option>';
						}
					?>
				</select><br/><br/>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="zoom-submit" value="<?php _e('change zoom','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the zoom level for the selected markers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="controlbox" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Basemap/overlay controlbox on frontend','lmm') ?></strong>
			</td>
			<td style="vertical-align:top;" class="lmm-border">
				<input id="markermaps_controlbox_hidden" type="radio" name="controlbox" value="0" /><label for="markermaps_controlbox_hidden"><?php _e('hidden','lmm') ?></label><br/>
				<input id="markermaps_controlbox_collapsed" type="radio" name="controlbox" value="1" checked /><label for="markermaps_controlbox_collapsed"><?php _e('collapsed (except on mobiles)','lmm') ?></label><br/>
				<input id="markermaps_controlbox_expanded" type="radio" name="controlbox" value="2" /><label for="markermaps_controlbox_expanded"><?php _e('expanded','lmm') ?></label><br/>
			</td>
			<td style="vertical-align:middle;text-align:center;" class="lmm-border">
				<?php _e('Which markers should be updated?','lmm'); ?>
				<select id="marker-controlbox-layer" name="marker-controlbox-layer" style="width:230px;">
					<option value="all">
					<?php echo sprintf(__('all %1$s markers','lmm'), $markercount_all) ?>
					</option>
					<option value="0">
					<?php _e('markers not assigned to a layer','lmm') ?>
					</option>
					<?php
						foreach ($layerlist as $row) {
							$layername_abstract = (strlen($row['name']) >= 21) ? '...': '';
							echo '<option value="' . $row['id'] . '" title="' . sprintf(esc_attr__('Update markers from the following layer only: "%1$s"','lmm'), stripslashes(htmlspecialchars($row['name']))) . '">' . mb_substr(stripslashes(htmlspecialchars($row['name'])), 0, 28) . $layername_abstract . ' (ID ' . $row['id'] . ')</option>';
						}
					?>
				</select><br/><br/>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="controlbox-submit" value="<?php _e('change controlbox status','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the controlbox status for the selected markers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="panel" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Panel for displaying marker name and API URLs on top of map','lmm') ?></strong>
			</td>
			<td style="vertical-align:top;" class="lmm-border">
				<input id="markermaps_panel_show" type="radio" name="panel" value="1" checked />
				<label for="markermaps_panel_show"><?php _e('show','lmm') ?></label><br/>
				<input id="markermaps_panel_hide" type="radio" name="panel" value="0" />
				<label for="markermaps_panel_hide"><?php _e('hide','lmm') ?></label></p>
			</td>
			<td style="vertical-align:middle;text-align:center;" class="lmm-border">
				<?php _e('Which markers should be updated?','lmm'); ?>
				<select id="marker-panel-layer" name="marker-panel-layer" style="width:230px;">
					<option value="all">
					<?php echo sprintf(__('all %1$s markers','lmm'), $markercount_all) ?>
					</option>
					<option value="0">
					<?php _e('markers not assigned to a layer','lmm') ?>
					</option>
					<?php
						foreach ($layerlist as $row) {
							$layername_abstract = (strlen($row['name']) >= 21) ? '...': '';
							echo '<option value="' . $row['id'] . '" title="' . sprintf(esc_attr__('Update markers from the following layer only: "%1$s"','lmm'), stripslashes(htmlspecialchars($row['name']))) . '">' . mb_substr(stripslashes(htmlspecialchars($row['name'])), 0, 28) . $layername_abstract . ' (ID ' . $row['id'] . ')</option>';
						}
					?>
				</select><br/><br/>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="panel-submit" value="<?php _e('change panel status','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the panel status for the selected markers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="icon" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Icon','lmm') ?></strong>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<div style="text-align:center;float:left;line-height:0px;margin-bottom:3px;"><label for="default_icon"><img src="<?php echo LEAFLET_PLUGIN_URL . 'leaflet-dist/images/marker.png' ?>"/></label><br/>
				<input style="margin:1px 0 0 1px;" id="default_icon" type="radio" name="icon" value="" checked />
				</div>
				<?php
				  $iconlist = array();
				  $dir = opendir($defaults_marker_icon_dir);
				  while ($file = readdir($dir)) {
					if ($file === false)
					  break;
					if ($file != "." and $file != "..")
					  if (!is_dir($dir.$file) && ((substr($file, count($file)-5, 4) == '.png') || (substr($file, count($file)-5, 4) == '.jpg') || (substr($file, count($file)-5, 4) == '.gif')))
						$iconlist[] = $file;
				  }
				  closedir($dir);
				  sort($iconlist);
				  foreach ($iconlist as $row) {
				  	echo '<div style="text-align:center;float:left;line-height:0px;margin-bottom:3px;"><label for="' . $row . '"><img id="iconpreview" src="' . $defaults_marker_icon_url . '/' . $row . '" title="' . $row . '" alt="' . $row . '" width="32" height="37" /></label><br/><input style="margin:1px 0 0 1px;" id="' . $row . '" type="radio" name="icon" value="' . $row . '" /></div>';
				  }
				?>
			</td>
			<td style="vertical-align:middle;text-align:center;" class="lmm-border">
				<?php _e('Which markers should be updated?','lmm'); ?>
				<select id="marker-icon-layer" name="marker-icon-layer" style="width:230px;">
					<option value="all">
					<?php echo sprintf(__('all %1$s markers','lmm'), $markercount_all) ?>
					</option>
					<option value="0">
					<?php _e('markers not assigned to a layer','lmm') ?>
					</option>
					<?php
						foreach ($layerlist as $row) {
							$layername_abstract = (strlen($row['name']) >= 21) ? '...': '';
							echo '<option value="' . $row['id'] . '" title="' . sprintf(esc_attr__('Update markers from the following layer only: "%1$s"','lmm'), stripslashes(htmlspecialchars($row['name']))) . '">' . mb_substr(stripslashes(htmlspecialchars($row['name'])), 0, 28) . $layername_abstract . ' (ID ' . $row['id'] . ')</option>';
						}
					?>
				</select><br/><br/>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="icon-submit" value="<?php _e('update icon','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the icon for the selected markers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="openpopup" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Popup status','lmm') ?></strong></td>
			<td style="vertical-align:top;" class="lmm-border">
				<input id="markermaps_openpopup_closed" type="radio" name="openpopup" value="0" checked />
				<label for="markermaps_openpopup_closed"><?php _e('closed','lmm') ?></label>&nbsp;&nbsp;&nbsp;
				<input id="markermaps_openpopup_open" type="radio" name="openpopup" value="1" />
				<label for="markermaps_openpopup_open"><?php _e('open','lmm') ?></label>
			</td>
			<td style="vertical-align:middle;text-align:center;" class="lmm-border">
				<?php _e('Which markers should be updated?','lmm'); ?>
				<select id="marker-openpopup-layer" name="marker-openpopup-layer" style="width:230px;">
					<option value="all">
					<?php echo sprintf(__('all %1$s markers','lmm'), $markercount_all) ?>
					</option>
					<option value="0">
					<?php _e('markers not assigned to a layer','lmm') ?>
					</option>
					<?php
						foreach ($layerlist as $row) {
							$layername_abstract = (strlen($row['name']) >= 21) ? '...': '';
							echo '<option value="' . $row['id'] . '" title="' . sprintf(esc_attr__('Update markers from the following layer only: "%1$s"','lmm'), stripslashes(htmlspecialchars($row['name']))) . '">' . mb_substr(stripslashes(htmlspecialchars($row['name'])), 0, 28) . $layername_abstract . ' (ID ' . $row['id'] . ')</option>';
						}
					?>
				</select><br/><br/>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="openpopup-submit" value="<?php _e('change popup status','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the popup status for the selected markers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="popuptext" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Popup text','lmm') ?></strong>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<?php
					global $wp_version;
					if ( version_compare( $wp_version, '3.9-alpha', '>=' ) ) {
						function lmm_plugin_mce_css( $mce_css ) {
							global $wp_version;
							$lmm_options = get_option( 'leafletmapsmarker_options' );
							$defaults_marker_popups_maxwidth = intval($lmm_options['defaults_marker_popups_maxwidth'] + 1);
							$defaults_marker_popups_image_css = urlencode(htmlspecialchars($lmm_options['defaults_marker_popups_image_css']));						
							$custom_tinymce_css = LEAFLET_PLUGIN_URL . 'inc/css/leafletmapsmarker-admin-tinymce.php?defaults_marker_popups_maxwidth=' . $defaults_marker_popups_maxwidth . '&defaults_marker_popups_image_css=' . $defaults_marker_popups_image_css . '&wordpress_version='.$wp_version.'&timestamp='.time();
							if ( ! empty( $mce_css ) ) {
								$mce_css .= ',';
							}
							$mce_css .= $custom_tinymce_css;
							return $mce_css;
						}
						add_filter( 'mce_css', 'lmm_plugin_mce_css' );
						$settings = array(
							'wpautop' => true,
							'tinymce' => array(
								'height' => '250'
							 ),
						'quicktags' => array('buttons' => 'strong,em,link,block,del,ins,img,code,close'));
					} else {
						$defaults_marker_popups_maxwidth = intval($lmm_options['defaults_marker_popups_maxwidth'] + 1);
						$defaults_marker_popups_image_css = urlencode(htmlspecialchars($lmm_options['defaults_marker_popups_image_css']));
						$settings = array(
							'wpautop' => true,
							'tinymce' => array(
								'theme_advanced_buttons1' => 'bold,italic,underline,strikethrough,|,fontselect,fontsizeselect,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,blockquote,|,link,unlink,|,ltr,rtl',
								'theme' => 'advanced',
								'height' => '250',
								'content_css' => LEAFLET_PLUGIN_URL . 'inc/css/leafletmapsmarker-admin-tinymce.php?wordpress_version='.$wp_version.'&timestamp='.time().'&defaults_marker_popups_maxwidth=' . $defaults_marker_popups_maxwidth . '&defaults_marker_popups_image_css=' . $defaults_marker_popups_image_css . '',
								'theme_advanced_statusbar_location' => 'bottom'
							),
						'quicktags' => array('buttons' => 'strong,em,link,block,del,ins,img,code,close'));
					}
					wp_editor( '', 'popuptext', $settings);
				?>
			</td>
			<td style="vertical-align:middle;text-align:center;" class="lmm-border">
				<?php _e('Which markers should be updated?','lmm'); ?>
				<select id="marker-popuptext-layer" name="marker-popuptext-layer" style="width:230px;">
					<option value="all">
					<?php echo sprintf(__('all %1$s markers','lmm'), $markercount_all) ?>
					</option>
					<option value="0">
					<?php _e('markers not assigned to a layer','lmm') ?>
					</option>
					<?php
						foreach ($layerlist as $row) {
							$layername_abstract = (strlen($row['name']) >= 21) ? '...': '';
							echo '<option value="' . $row['id'] . '" title="' . sprintf(esc_attr__('Update markers from the following layer only: "%1$s"','lmm'), stripslashes(htmlspecialchars($row['name']))) . '">' . mb_substr(stripslashes(htmlspecialchars($row['name'])), 0, 28) . $layername_abstract . ' (ID ' . $row['id'] . ')</option>';
						}
					?>
				</select><br/><br/>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="popuptext-submit" value="<?php _e('change popup text','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the popup text for the selected markers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
	</table>
	<p><a href="#top" style="text-decoration:none;"><?php _e('back to top','lmm'); ?></a></p>

	<a name="bulk-update-layers"></a>
	<br/><br/>
	<?php $nonce= wp_create_nonce('tool-nonce'); ?>
	<table class="widefat fixed" style="width:auto;">
		<tr style="background-color:#d6d5d5;">
			<td colspan="3"><strong><?php echo sprintf( esc_attr__('Bulk updates for all %1$s existing layer maps','lmm'), $layercount_all) ?></strong></td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="basemap-layer" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Basemap','lmm') ?></strong>
			</td>
			<td class="lmm-border">
				<input id="layermaps_osm_mapnik" type="radio" name="basemap-layer" value="osm_mapnik" checked /> <label for="layermaps_osm_mapnik"><?php echo $lmm_options['default_basemap_name_osm_mapnik']; ?></label><br />
				<input id="layermaps_mapquest_osm" type="radio" name="basemap-layer" value="mapquest_osm" /> <label for="layermaps_mapquest_osm"><?php echo $lmm_options['default_basemap_name_mapquest_osm']; ?></label><br />
				<input id="layermaps_mapquest_aerial" type="radio" name="basemap-layer" value="mapquest_aerial" /> <label for="layermaps_mapquest_aerial"><?php echo $lmm_options['default_basemap_name_mapquest_aerial']; ?></label><br />
				<input id="layermaps_googleLayer_roadmap" type="radio" name="basemap-layer" value="googleLayer_roadmap" /> <label for="layermaps_googleLayer_roadmap"><?php echo $lmm_options['default_basemap_name_googleLayer_roadmap']; ?></label><br />
				<input id="layermaps_googleLayer_satellite" type="radio" name="basemap-layer" value="googleLayer_satellite" /> <label for="layermaps_googleLayer_satellite"><?php echo $lmm_options['default_basemap_name_googleLayer_satellite']; ?></label><br />
				<input id="layermaps_googleLayer_hybrid" type="radio" name="basemap-layer" value="googleLayer_hybrid" /> <label for="layermaps_googleLayer_hybrid"><?php echo $lmm_options['default_basemap_name_googleLayer_hybrid']; ?></label><br />
				<input id="layermaps_googleLayer_terrain" type="radio" name="basemap-layer" value="googleLayer_terrain" /> <label for="layermaps_googleLayer_terrain"><?php echo $lmm_options['default_basemap_name_googleLayer_terrain']; ?></label><br />
				<input id="layermaps_bingaerial" type="radio" name="basemap-layer" value="bingaerial" /> <label for="layermaps_bingaerial"><?php echo $lmm_options['default_basemap_name_bingaerial']; ?></label><br />
				<input id="layermaps_bingaerialwithlabels" type="radio" name="basemap-layer" value="bingaerialwithlabels" /> <label for="layermaps_bingaerialwithlabels"><?php echo $lmm_options['default_basemap_name_bingaerialwithlabels']; ?></label><br />
				<input id="layermaps_bingroad" type="radio" name="basemap-layer" value="bingroad" /> <label for="layermaps_bingroad"><?php echo $lmm_options['default_basemap_name_bingroad']; ?></label><br />
				<input id="layermaps_ogdwien_basemap" type="radio" name="basemap-layer" value="ogdwien_basemap" /> <label for="layermaps_ogdwien_basemap"><?php echo $lmm_options['default_basemap_name_ogdwien_basemap']; ?></label><br />
				<input id="layermaps_ogdwien_satellite" type="radio" name="basemap-layer" value="ogdwien_satellite" /> <label for="layermaps_ogdwien_satellite"><?php echo $lmm_options['default_basemap_name_ogdwien_satellite']; ?></label><br />
				<input id="layermaps_mapbox" type="radio" name="basemap-layer" value="mapbox" /> <label for="layermaps_mapbox"><?php echo $lmm_options['mapbox_name']; ?></label><br />
				<input id="layermaps_mapbox2" type="radio" name="basemap-layer" value="mapbox2" /> <label for="layermaps_mapbox2"><?php echo $lmm_options['mapbox2_name']; ?></label><br />
				<input id="layermaps_mapbox3" type="radio" name="basemap-layer" value="mapbox3" /> <label for="layermaps_mapbox3"><?php echo $lmm_options['mapbox3_name']; ?></label><br />
				<input id="layermaps_custom_basemap" type="radio" name="basemap-layer" value="custom_basemap" /> <label for="layermaps_custom_basemap"><?php echo $lmm_options['custom_basemap_name']; ?></label><br />
				<input id="layermaps_custom_basemap2" type="radio" name="basemap-layer" value="custom_basemap2" /> <label for="layermaps_custom_basemap2"><?php echo $lmm_options['custom_basemap2_name']; ?></label><br />
				<input id="layermaps_custom_basemap3" type="radio" name="basemap-layer" value="custom_basemap3" /> <label for="layermaps_custom_basemap3"><?php echo $lmm_options['custom_basemap3_name']; ?></label>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="basemap-layer-submit" value="<?php _e('change basemap for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the basemap for all layers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="overlays-layer" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Checked overlays in control box','lmm') ?></strong>
			</td>
			<td class="lmm-border">
				<input id="layermaps_overlays_custom-layer" type="checkbox" name="overlays_custom-layer" /> <label for="layermaps_overlays_custom-layer"><?php echo $lmm_options['overlays_custom_name']; ?></label><br />
				<input id="layermaps_overlays_custom-layer2" type="checkbox" name="overlays_custom2-layer" /> <label for="layermaps_overlays_custom-layer2"><?php echo $lmm_options['overlays_custom2_name']; ?></label><br />
				<input id="layermaps_overlays_custom-layer3" type="checkbox" name="overlays_custom3-layer" /> <label for="layermaps_overlays_custom-layer3"><?php echo $lmm_options['overlays_custom3_name']; ?></label><br />
				<input id="layermaps_overlays_custom-layer4" type="checkbox" name="overlays_custom4-layer" /> <label for="layermaps_overlays_custom-layer4"><?php echo $lmm_options['overlays_custom4_name']; ?></label>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="overlays-layer-submit" value="<?php _e('change overlay status for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the overlay status for all layers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="wms-layer" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Active WMS layers','lmm') ?></strong>
			</td>
			<td class="lmm-border">
				<input type="checkbox" name="wms-layer" /> <?php echo strip_tags($lmm_options['wms_wms_name']); ?> <a title="<?php esc_attr_e('WMS layer 1 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections2"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms2-layer" /> <?php echo strip_tags($lmm_options['wms_wms2_name']); ?> <a title="<?php esc_attr_e('WMS layer 2 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections3"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms3-layer" /> <?php echo strip_tags($lmm_options['wms_wms3_name']); ?> <a title="<?php esc_attr_e('WMS layer 3 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections4"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms4-layer" /> <?php echo strip_tags($lmm_options['wms_wms4_name']); ?> <a title="<?php esc_attr_e('WMS layer 4 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections5"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms5-layer" /> <?php echo strip_tags($lmm_options['wms_wms5_name']); ?> <a title="<?php esc_attr_e('WMS layer 5 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections6"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms6-layer" /> <?php echo strip_tags($lmm_options['wms_wms6_name']); ?> <a title="<?php esc_attr_e('WMS layer 6 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections7"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms7-layer" /> <?php echo strip_tags($lmm_options['wms_wms7_name']); ?> <a title="<?php esc_attr_e('WMS layer 7 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections8"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms8-layer" /> <?php echo strip_tags($lmm_options['wms_wms8_name']); ?> <a title="<?php esc_attr_e('WMS layer 8 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections9"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms9-layer" /> <?php echo strip_tags($lmm_options['wms_wms9_name']); ?> <a title="<?php esc_attr_e('WMS layer 9 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections10"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a><br />
				<input type="checkbox" name="wms10-layer" /> <?php echo strip_tags($lmm_options['wms_wms10_name']); ?> <a title="<?php esc_attr_e('WMS layer 10 settings','lmm'); ?>" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_settings#lmm-wms-sections11"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="wms-layer-submit" value="<?php _e('change active WMS layers for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change active WMS layers for all layers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="mapsize-layer" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Map size','lmm') ?></strong>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<?php _e('Width','lmm') ?>:
				<input size="2" maxlength="4" type="text" id="mapwidth-layer" name="mapwidth-layer" value="<?php echo intval($lmm_options[ 'defaults_layer_mapwidth' ]) ?>" style="margin-left:5px;" />
				<input id="layermaps_mapwidthunit_px" type="radio" name="mapwidthunit-layer" value="px" checked /><label for="layermaps_mapwidthunit_px">px</label>&nbsp;&nbsp;&nbsp;
				<input id="layermaps_mapwidthunit_percent" type="radio" name="mapwidthunit-layer" value="%" /><label for="layermaps_mapwidthunit_percent">%</label><br/>
				<?php _e('Height','lmm') ?>:
				<input size="2" maxlength="4" type="text" id="mapheight-layer" name="mapheight-layer" value="<?php echo intval($lmm_options[ 'defaults_layer_mapheight' ]) ?>" /> px
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mapsize-layer-submit" value="<?php _e('change mapsize for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the map size for all layers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td style="vertical-align:middle;" class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="zoom-layer" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Zoom','lmm') ?></strong>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input style="width: 40px;" type="text" id="zoom-layer" name="zoom-layer" value="<?php echo intval($lmm_options[ 'defaults_layer_zoom' ]) ?>" />
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="zoom-layer-submit" value="<?php _e('change zoom for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the zoom level for all layers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="controlbox-layer" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Basemap/overlay controlbox on frontend','lmm') ?></strong>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input id="layermaps_controlbox_hidden" type="radio" name="controlbox-layer" value="0" /><label for="layermaps_controlbox_hidden"><?php _e('hidden','lmm') ?></label><br/>
				<input id="layermaps_controlbox_collapsed" type="radio" name="controlbox-layer" value="1" checked /><label for="layermaps_controlbox_collapsed"><?php _e('collapsed (except on mobiles)','lmm') ?></label><br/>
				<input id="layermaps_controlbox_expanded" type="radio" name="controlbox-layer" value="2" /><label for="layermaps_controlbox_expanded"><?php _e('expanded','lmm') ?></label><br/>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="controlbox-layer-submit" value="<?php _e('change controlbox status for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the controlbox status for all layers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="panel-layer" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Panel for displaying layer name and API URLs on top of map','lmm') ?></strong>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input id="layermaps_panel_show" type="radio" name="panel-layer" value="1" checked />
				<label for="layermaps_panel_show"><?php _e('show','lmm') ?></label><br/>
				<input id="layermaps_panel_hide" type="radio" name="panel-layer" value="0" />
				<label for="layermaps_panel_hide"><?php _e('hide','lmm') ?></label></p>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="panel-layer-submit" value="<?php _e('change panel status for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the panel status for all layers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="listmarkers-layer" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Display a list of markers under the map','lmm') ?></strong>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input id="layermaps_listmarkers_yes" type="radio" name="listmarkers-layer" value="1" checked />
				<label for="layermaps_listmarkers_yes"><?php _e('yes','lmm') ?></label><br/>
				<input id="layermaps_listmarkers_no" type="radio" name="listmarkers-layer" value="0" />
				<label for="layermaps_listmarkers_no"><?php _e('no','lmm') ?></label></p>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="listmarkers-layer-submit" value="<?php _e('change list marker-status for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the list marker-status for all layers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="lmm-border">
				<form method="post">
				<input type="hidden" name="action" value="listmarkers-clustering" />
				<?php wp_nonce_field('tool-nonce'); ?>
				<strong><?php _e('Marker clustering','lmm') ?></strong>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input id="layermaps_clustering_enabled" type="radio" name="listmarkers-clustering" value="1" checked />
				<label for="layermaps_clustering_enabled"><?php _e('enabled','lmm') ?></label><br/>
				<input id="layermaps_clustering_disabled" type="radio" name="listmarkers-clustering" value="0" />
				<label for="layermaps_listmarkers_disabled"><?php _e('disabled','lmm') ?></label></p>
			</td>
			<td style="vertical-align:middle;" class="lmm-border">
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="listmarkers-clustering-submit" value="<?php _e('change clustering status for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the clustering-status for all layers? (cannot be undone)','lmm') ?>')" />
				</form>
			</td>
		</tr>
	</table>
	<p><a href="#top" style="text-decoration:none;"><?php _e('back to top','lmm'); ?></a></p>

	<a name="change-marker-id"></a>
	<br/><br/>
	<?php $nonce= wp_create_nonce('tool-nonce'); ?>
	<form method="post">
	<input type="hidden" name="action" value="change_marker_id" />
	<?php wp_nonce_field('tool-nonce'); ?>
	<table class="widefat fixed" style="width:auto;">
		<tr style="background-color:#d6d5d5;">
			<td colspan="2"><strong><?php _e('Change marker ID','lmm') ?> <img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/help-pro-feature.png" /></strong></td>
		</tr>
		<tr>
			<td style="vertical-align:middle;">
				<?php _e('Old marker ID','lmm') ?>:
				<input id="marker_id_old" name="marker_id_old" type="text" size="4" />
				<?php _e('New marker ID','lmm') ?>:
				<input id="marker_id_new" name="marker_id_new" type="text" size="4" />
			</td>
			<td>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mass_asign-submit" value="<?php _e('update ID','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the ID?','lmm') ?>')" />
			</td>
		</tr>
	</table>
	</form>
	<p><a href="#top" style="text-decoration:none;"><?php _e('back to top','lmm'); ?></a></p>

	<a name="change-layer-id"></a>
	<br/><br/>
	<?php $nonce= wp_create_nonce('tool-nonce'); ?>
	<form method="post">
	<input type="hidden" name="action" value="change_layer_id" />
	<?php wp_nonce_field('tool-nonce'); ?>
	<table class="widefat fixed" style="width:auto;">
		<tr style="background-color:#d6d5d5;">
			<td colspan="2"><strong><?php _e('Change layer ID','lmm') ?> <img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/help-pro-feature.png" /></strong></td>
		</tr>
		<tr>
			<td style="vertical-align:middle;">
				<p><?php _e('Markers assigned to the layer will also be updated. Please keep in mind that if the layer is assigned to a multi-layer-map, you also have to manually re-assign this layer to the multi-layer-map in question!','lmm'); ?></p>
				<?php _e('Old layer ID','lmm') ?>:
				<input id="layer_id_old" name="layer_id_old" type="text" size="4" />
				<?php _e('New layer ID','lmm') ?>:
				<input id="layer_id_new" name="layer_id_new" type="text" size="4" />
			</td>
			<td>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mass_asign-submit" value="<?php _e('update ID','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the ID?','lmm') ?>')" />
			</td>
		</tr>
	</table>
	</form>
	<p><a href="#top" style="text-decoration:none;"><?php _e('back to top','lmm'); ?></a></p>

	<a name="api-url-generator"></a>
	<br/><br/>
	<table class="widefat fixed" style="width:auto;">
		<tr style="background-color:#d6d5d5;">
			<td colspan="2"><strong><?php _e('API URL generator','lmm') ?></strong> <?php echo '<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_settings#lmm-misc-section9">(' . __('MapsMarker API settings','lmm') . ')</a>'; ?></td>
		</tr>
		<tr>
			<td style="vertical-align:middle;">
				<?php _e('This tool will generate a secure, expiring URL','lmm') ?>:
				<select id="api-url-generator-expiration" style="margin-left:80px;">
					<option value="60"><?php _e('expire in','lmm'); ?> 1 <?php _e('Minute','lmm'); ?></option>
					<option value="3600"><?php _e('expire in','lmm'); ?> 1 <?php _e('Hour','lmm'); ?></option>
					<option value="86400"><?php _e('expire in','lmm'); ?> 1 <?php _e('Day','lmm'); ?></option>
					<option value="604800"><?php _e('expire in','lmm'); ?> 1 <?php _e('Week','lmm'); ?></option>
					<option value="2628000"><?php _e('expire in','lmm'); ?> 1 <?php _e('Month','lmm'); ?></option>
					<option value="31449600"><?php _e('expire in','lmm'); ?> 1 <?php _e('Year','lmm'); ?></option>
					<option value="157248000"><?php _e('expire in','lmm'); ?> 5 <?php _e('Years','lmm'); ?></option>
					<option value="314496000"><?php _e('expire in','lmm'); ?> 10 <?php _e('Years','lmm'); ?></option>
					<option value="3144960000"><?php _e('expire in','lmm'); ?> 100 <?php _e('Years','lmm'); ?></option>
				</select><br/>
				<textarea type="text" id="api-url-generator-generated-url" style="width:550px;height:85px;"></textarea>
			</td>
			<td style="vertical-align:middle;">
				<?php 
				if ( ($lmm_options['api_key'] == NULL) || ($lmm_options['api_key_private'] == NULL) ) {
					echo '<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_settings#lmm-misc-section9">' . __('Error: you have to set a public and private API key first!','lmm') . '</a>';
				} else {
					echo '<input id="api_url_generator-submit" type="submit" class="submit button-primary" value="' . __('Generate URL','lmm') . '"/>';
				} 
				?>
			</td>
		</tr>
	</table>
	<script type="text/javascript">
	/*
	CryptoJS v3.1.2
	code.google.com/p/crypto-js
	(c) 2009-2013 by Jeff Mott. All rights reserved.
	code.google.com/p/crypto-js/wiki/License
	*/
	var CryptoJS=CryptoJS||function(g,l){var e={},d=e.lib={},m=function(){},k=d.Base={extend:function(a){m.prototype=this;var c=new m;a&&c.mixIn(a);c.hasOwnProperty("init")||(c.init=function(){c.$super.init.apply(this,arguments)});c.init.prototype=c;c.$super=this;return c},create:function(){var a=this.extend();a.init.apply(a,arguments);return a},init:function(){},mixIn:function(a){for(var c in a)a.hasOwnProperty(c)&&(this[c]=a[c]);a.hasOwnProperty("toString")&&(this.toString=a.toString)},clone:function(){return this.init.prototype.extend(this)}},
	p=d.WordArray=k.extend({init:function(a,c){a=this.words=a||[];this.sigBytes=c!=l?c:4*a.length},toString:function(a){return(a||n).stringify(this)},concat:function(a){var c=this.words,q=a.words,f=this.sigBytes;a=a.sigBytes;this.clamp();if(f%4)for(var b=0;b<a;b++)c[f+b>>>2]|=(q[b>>>2]>>>24-8*(b%4)&255)<<24-8*((f+b)%4);else if(65535<q.length)for(b=0;b<a;b+=4)c[f+b>>>2]=q[b>>>2];else c.push.apply(c,q);this.sigBytes+=a;return this},clamp:function(){var a=this.words,c=this.sigBytes;a[c>>>2]&=4294967295<<
	32-8*(c%4);a.length=g.ceil(c/4)},clone:function(){var a=k.clone.call(this);a.words=this.words.slice(0);return a},random:function(a){for(var c=[],b=0;b<a;b+=4)c.push(4294967296*g.random()|0);return new p.init(c,a)}}),b=e.enc={},n=b.Hex={stringify:function(a){var c=a.words;a=a.sigBytes;for(var b=[],f=0;f<a;f++){var d=c[f>>>2]>>>24-8*(f%4)&255;b.push((d>>>4).toString(16));b.push((d&15).toString(16))}return b.join("")},parse:function(a){for(var c=a.length,b=[],f=0;f<c;f+=2)b[f>>>3]|=parseInt(a.substr(f,
	2),16)<<24-4*(f%8);return new p.init(b,c/2)}},j=b.Latin1={stringify:function(a){var c=a.words;a=a.sigBytes;for(var b=[],f=0;f<a;f++)b.push(String.fromCharCode(c[f>>>2]>>>24-8*(f%4)&255));return b.join("")},parse:function(a){for(var c=a.length,b=[],f=0;f<c;f++)b[f>>>2]|=(a.charCodeAt(f)&255)<<24-8*(f%4);return new p.init(b,c)}},h=b.Utf8={stringify:function(a){try{return decodeURIComponent(escape(j.stringify(a)))}catch(c){throw Error("Malformed UTF-8 data");}},parse:function(a){return j.parse(unescape(encodeURIComponent(a)))}},
	r=d.BufferedBlockAlgorithm=k.extend({reset:function(){this._data=new p.init;this._nDataBytes=0},_append:function(a){"string"==typeof a&&(a=h.parse(a));this._data.concat(a);this._nDataBytes+=a.sigBytes},_process:function(a){var c=this._data,b=c.words,f=c.sigBytes,d=this.blockSize,e=f/(4*d),e=a?g.ceil(e):g.max((e|0)-this._minBufferSize,0);a=e*d;f=g.min(4*a,f);if(a){for(var k=0;k<a;k+=d)this._doProcessBlock(b,k);k=b.splice(0,a);c.sigBytes-=f}return new p.init(k,f)},clone:function(){var a=k.clone.call(this);
	a._data=this._data.clone();return a},_minBufferSize:0});d.Hasher=r.extend({cfg:k.extend(),init:function(a){this.cfg=this.cfg.extend(a);this.reset()},reset:function(){r.reset.call(this);this._doReset()},update:function(a){this._append(a);this._process();return this},finalize:function(a){a&&this._append(a);return this._doFinalize()},blockSize:16,_createHelper:function(a){return function(b,d){return(new a.init(d)).finalize(b)}},_createHmacHelper:function(a){return function(b,d){return(new s.HMAC.init(a,
	d)).finalize(b)}}});var s=e.algo={};return e}(Math);
	(function(){var g=CryptoJS,l=g.lib,e=l.WordArray,d=l.Hasher,m=[],l=g.algo.SHA1=d.extend({_doReset:function(){this._hash=new e.init([1732584193,4023233417,2562383102,271733878,3285377520])},_doProcessBlock:function(d,e){for(var b=this._hash.words,n=b[0],j=b[1],h=b[2],g=b[3],l=b[4],a=0;80>a;a++){if(16>a)m[a]=d[e+a]|0;else{var c=m[a-3]^m[a-8]^m[a-14]^m[a-16];m[a]=c<<1|c>>>31}c=(n<<5|n>>>27)+l+m[a];c=20>a?c+((j&h|~j&g)+1518500249):40>a?c+((j^h^g)+1859775393):60>a?c+((j&h|j&g|h&g)-1894007588):c+((j^h^
	g)-899497514);l=g;g=h;h=j<<30|j>>>2;j=n;n=c}b[0]=b[0]+n|0;b[1]=b[1]+j|0;b[2]=b[2]+h|0;b[3]=b[3]+g|0;b[4]=b[4]+l|0},_doFinalize:function(){var d=this._data,e=d.words,b=8*this._nDataBytes,g=8*d.sigBytes;e[g>>>5]|=128<<24-g%32;e[(g+64>>>9<<4)+14]=Math.floor(b/4294967296);e[(g+64>>>9<<4)+15]=b;d.sigBytes=4*e.length;this._process();return this._hash},clone:function(){var e=d.clone.call(this);e._hash=this._hash.clone();return e}});g.SHA1=d._createHelper(l);g.HmacSHA1=d._createHmacHelper(l)})();
	(function(){var g=CryptoJS,l=g.enc.Utf8;g.algo.HMAC=g.lib.Base.extend({init:function(e,d){e=this._hasher=new e.init;"string"==typeof d&&(d=l.parse(d));var g=e.blockSize,k=4*g;d.sigBytes>k&&(d=e.finalize(d));d.clamp();for(var p=this._oKey=d.clone(),b=this._iKey=d.clone(),n=p.words,j=b.words,h=0;h<g;h++)n[h]^=1549556828,j[h]^=909522486;p.sigBytes=b.sigBytes=k;this.reset()},reset:function(){var e=this._hasher;e.reset();e.update(this._iKey)},update:function(e){this._hasher.update(e);return this},finalize:function(e){var d=
	this._hasher;e=d.finalize(e);d.reset();return d.finalize(this._oKey.clone().concat(e))}})})();
	/*
	CryptoJS v3.1.2
	code.google.com/p/crypto-js
	(c) 2009-2013 by Jeff Mott. All rights reserved.
	code.google.com/p/crypto-js/wiki/License
	*/
	(function(){var h=CryptoJS,j=h.lib.WordArray;h.enc.Base64={stringify:function(b){var e=b.words,f=b.sigBytes,c=this._map;b.clamp();b=[];for(var a=0;a<f;a+=3)for(var d=(e[a>>>2]>>>24-8*(a%4)&255)<<16|(e[a+1>>>2]>>>24-8*((a+1)%4)&255)<<8|e[a+2>>>2]>>>24-8*((a+2)%4)&255,g=0;4>g&&a+0.75*g<f;g++)b.push(c.charAt(d>>>6*(3-g)&63));if(e=c.charAt(64))for(;b.length%4;)b.push(e);return b.join("")},parse:function(b){var e=b.length,f=this._map,c=f.charAt(64);c&&(c=b.indexOf(c),-1!=c&&(e=c));for(var c=[],a=0,d=0;d<
	e;d++)if(d%4){var g=f.indexOf(b.charAt(d-1))<<2*(d%4),h=f.indexOf(b.charAt(d))>>>6-2*(d%4);c[a>>>2]|=(g|h)<<24-8*(a%4);a++}return j.create(c,a)},_map:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="}})();

	function lmm_apiCalculateSig(stringToSign, privateKey) {
		var hash = CryptoJS.HmacSHA1(stringToSign, privateKey);
		var base64 = hash.toString(CryptoJS.enc.Base64);
		return encodeURIComponent(base64);
	}	
	jQuery(document).ready(function () {
		jQuery("#api_url_generator-submit").click(function (e) {
			e.preventDefault();
			var publicKey, privateKey, expiration, stringToSign, url, sig;
			publicKey = '<?php echo $lmm_options['api_key']; ?>';
			privateKey = '<?php echo $lmm_options['api_key_private']; ?>';
			expiration = parseInt(jQuery("#api-url-generator-expiration").val());
			var d = new Date;
			var unixtime = parseInt(d.getTime() / 1000);
			var future_unixtime =  unixtime + expiration;
			stringToSign = publicKey + ":" + future_unixtime;
			sig = lmm_apiCalculateSig(stringToSign, privateKey);
			url = "<?php echo LEAFLET_PLUGIN_URL; ?>leaflet-api.php?key=" + publicKey + "&signature=" + sig + "&expires=" + future_unixtime;
			jQuery('#api-url-generator-generated-url').val(url);
			return false;
		});
	});
	</script>
	<p><a href="#top" style="text-decoration:none;"><?php _e('back to top','lmm'); ?></a></p>

	<a name="api-url-tester"></a>
	<br/><br/>
	<table class="widefat fixed" style="width:auto;">
		<tr style="background-color:#d6d5d5;">
			<td colspan="2"><strong><?php _e('API URL tester','lmm') ?></strong> <?php echo '<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_settings#lmm-misc-section9">(' . __('MapsMarker API settings','lmm') . ')</a>'; ?></td>
		</tr>
		<tr>
			<td style="vertical-align:middle;">
				<?php _e('This tool tests the authentication/signature','lmm') ?>:		
				<select id="api-url-tester-method" style="margin-left:80px;">
					<option value="GET">GET</option>
					<option value="POST">POST</option>
				</select><br />
				<textarea type="text" id="api-url-tester-url" style="width:550px;height:85px;"></textarea>
				<div id="api-url-tester-loading" style="display:none">
					<?php _e('Loading...','lmm'); ?>
				</div>
				<div id="api-url-tester-results">
					<!-- placeholder for results -->
				</div>
			</td>
			<td style="vertical-align:middle;">
				<?php 
				if ( ($lmm_options['api_key'] == NULL) || ($lmm_options['api_key_private'] == NULL) ) {
					echo '<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_settings#lmm-misc-section9">' . __('Error: you have to set a public and private API key first!','lmm') . '</a>';
				} else {
					echo '<input id="api_url_tester-submit" type="submit" class="submit button-primary" value="' . __('Test','lmm') . '"/>';
				} 
				?>
			</td>
		</tr>
	</table>
	<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery("#api_url_tester-submit").click(function (e) {
			var $button = jQuery(this);
			var $loading = jQuery("#api-url-tester-loading");
			var $results = jQuery("#api-url-tester-results");
			var url = jQuery('#api-url-tester-url').val();
			var method = jQuery('#api-url-tester-method').val();
			apiTesterAjaxRequest = jQuery.ajax({
				url       : url + "&callback=",
				type      : method,
				dataType  : 'json',
				data      : {},
				beforeSend: function (xhr, opts) {
					$button.attr('disabled', 'disabled');
					$loading.show();
				}
			})
				.done(function (data, textStatus, xhr) {
					$button.removeAttr('disabled');
					$loading.hide();
					var result_parsed = JSON.parse(xhr.responseText);
					if (result_parsed.success == false) {
						if (result_parsed.message == '<?php esc_attr_e('API parameter action has to be set','lmm'); ?>') {
							$results.html('<?php esc_attr_e('Success','lmm'); ?>: <span style="background:green;color:white;padding:0 5px;"><?php esc_attr_e('true','lmm'); ?></span><br/><?php esc_attr_e('Message','lmm');?>: <?php esc_attr_e('authentication and signature are valid','lmm');?>');
						} else {
							$results.html('<?php esc_attr_e('Success','lmm'); ?>: <span style="background:red;color:white;padding:0 5px;"><?php esc_attr_e('false','lmm'); ?></span><br/><?php esc_attr_e('Message','lmm');?>: '+result_parsed.message);
						}
					} else {
						$results.html('<?php esc_attr_e('Success','lmm'); ?>: <span style="background:green;color:white;padding:0 5px;">'+result_parsed.success+'</span><br/><?php esc_attr_e('Message','lmm');?>: '+result_parsed.message);
					}
					$results.fadeTo("fast", 1);
				})
				.fail(function (jqXHR) {
					$button.removeAttr('disabled');
					$loading.hide();
					$results.fadeTo("fast", 1);
					var msg;
					$loading.hide();
					if (msg == "abort") {
						msg = "Request cancelled";
					} else {
						msg = '<?php esc_attr_e('Success','lmm'); ?>: <span style="background:red;color:white;padding:0 5px;"><?php esc_attr_e('false','lmm'); ?></span><br/><?php esc_attr_e('Message','lmm');?>: '+jqXHR.status + " - " + jqXHR.statusText;
					}
					$results.html(msg);
				});
			return false;
		});
	});
	</script>
	<p><a href="#top" style="text-decoration:none;"><?php _e('back to top','lmm'); ?></a></p>
	
	<a name="clear-qr-cache"></a>
	<br/><br/>
	<?php $nonce= wp_create_nonce('tool-nonce'); ?>
	<form method="post">
	<input type="hidden" name="action" value="clear_qr_cache" />
	<?php wp_nonce_field('tool-nonce'); ?>
	<table class="widefat fixed" style="width:auto;">
		<tr style="background-color:#d6d5d5;">
			<td colspan="2"><strong><?php echo sprintf(__('Clear QR code images cache at %1$s','lmm'), LEAFLET_PLUGIN_QR_URL) ?></strong></td>
		</tr>
		<tr>
			<td style="vertical-align:middle;">
				<?php _e('Clearing the full QR code images cache is only recommended when a new QR code background is configured or if the URLs to the fullscreen maps have changed.','lmm') ?>
			</td>
			<td>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mass_delete_from_layer-submit" value="<?php _e('clear cache','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to clear the QR code images cache? (cannot be undone)','lmm') ?>')" />
			</td>
		</tr>
	</table>
	</form>
	<p><a href="#top" style="text-decoration:none;"><?php _e('back to top','lmm'); ?></a></p>

	<a name="delete-selected-markers"></a>
	<br/><br/>
	<?php $nonce= wp_create_nonce('tool-nonce'); ?>
	<form method="post">
	<input type="hidden" name="action" value="mass_delete_from_layer" />
	<?php wp_nonce_field('tool-nonce'); ?>
	<table class="widefat fixed" style="width:auto;">
		<tr style="background-color:#d6d5d5;">
			<td colspan="2"><strong><?php _e('Delete all markers from a layer','lmm') ?></strong></td>
		</tr>
		<tr>
			<td style="vertical-align:middle;">
				<?php _e('Layer','lmm') ?>:
				<select id="delete_from_layer" name="delete_from_layer">
				<option value="0">ID 0 - <?php _e('unassigned','lmm') ?> (<?php echo $markercount_layer0; ?> <?php _e('marker','lmm'); ?>)</option>
				<?php
				foreach ($layerlist as $row) {
					$markercount = $wpdb->get_var('SELECT count(*) FROM `'.$table_name_layers.'` as l INNER JOIN `'.$table_name_markers.'` AS m ON l.id=m.layer WHERE l.id='.$row['id']);
					echo '<option value="' . $row['id'] . '">ID ' . $row['id'] . ' - ' . stripslashes(htmlspecialchars($row['name'])) . ' (' . $markercount .' ' . __('marker','lmm') . ')</option>';
				}
				?>
				</select>
			</td>
			<td>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mass_delete_from_layer-submit" value="<?php _e('delete all markers from selected layer','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to delete all markers from the selected layer? (cannot be undone)','lmm') ?>')" />
			</td>
		</tr>
	</table>
	</form>
	<p><a href="#top" style="text-decoration:none;"><?php _e('back to top','lmm'); ?></a></p>

	<a name="delete-all-markers"></a>
	<br/><br/>
	<?php $nonce= wp_create_nonce('tool-nonce'); ?>
	<form method="post">
	<input type="hidden" name="action" value="mass_delete_all_markers" />
	<?php wp_nonce_field('tool-nonce'); ?>
	<table class="widefat fixed" style="width:auto;">
		<tr style="background-color:#d6d5d5;">
			<td colspan="2"><strong><?php echo sprintf( esc_attr__('Delete all %1$s markers from all %2$s layers','lmm'), $markercount_all, $layercount_all); ?></strong></td>
		</tr>
		<tr>
			<td style="vertical-align:middle;">
				<input id="delete_all_markers_from_all_layers" type="checkbox" id="delete_confirm_checkbox" name="delete_confirm_checkbox" /> <label for="delete_all_markers_from_all_layers"><?php _e('Yes','lmm') ?></label>
			</td>
			<td>
				<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mass_delete_all_markers" value="<?php _e('delete all markers from all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to delete all markers from all layers? (cannot be undone)','lmm') ?>')" />
			</td>
		</tr>
	</table>
	</form>
	<p><a href="#top" style="text-decoration:none;"><?php _e('back to top','lmm'); ?></a></p>

	<div style="display:none;">
	<?php //info: translations strings for Glotpress
	//info: mixed translations
	__('Too bad you are using the free version again :-( <a href="%1s" target="_blank">Please tell us what we can do to win you as a happy pro user and receive a discount voucher!</a>','lmm');
	__('You downloaded <a href="%1s" target="_blank">Leaflet Maps Marker Pro</a> but did not register a free 30-day-trial license key. Please note that <a href="%2s" target="_blank">according to our privacy policy</a> we will not disclose, rent or sell your personal information!<br/>If you install Leaflet Maps Marker Pro on a localhost installation (<a href="%3s" target="_blank">see available packages on Wikipedia</a>) you can also test the pro plugin without registering a free 30-day-trial license key and without time limitation.','lmm');
	__('This message will disappear once the pro version has been activated or deleted from your server (via the WordPress Plugins page!)','lmm');
	__('Lite Edition','lmm');
	__('Widget to show the most recent Leaflet Maps Marker entries - please see www.mapsmarker.com for more info', 'lmm');
	__('Leaflet Maps Marker - recent markers', 'lmm');
	__('Upgrade to pro version for even more features - click here to find out how you can start a free 30-day-trial easily','lmm');
	//info: upgrade page
	__('Upgrade to Pro', 'lmm');
	__('Optional: start a free trial of Maps Marker Pro', 'lmm');
	__('If you like using Leaflet Maps Marker, you might also be interested in starting a free 30-day-trial of Leaflet Maps Marker Pro, which offers even more features, higher performance and more.','lmm');
	__('Please click on the button below - this will start the download of Leaflet Maps Marker Pro from <a style="text-decoration:none;" href="%1s">%2s</a> and installation as a separate plugin.','lmm');
	__('As next step please activate the pro plugin and you will be guided through the process to receive a free 30-day-trial license without any obligations.','lmm');
	__('Your trial will expire automatically unless you purchase an unexpiring license key at %1$s','lmm');
	__('Terms of Service','lmm');
	__('Privacy Policy','lmm');
	__('You can also switch back to the free version at any time without loosing any data.','lmm');
	__('start free 30-day-trial','lmm');
	__('or continue using the lite edition','lmm');
	__('Highlights of Maps Marker Pro','lmm');
	__('integration of the latest leaflet.js version','lmm');
	__('Leaflet Maps Marker Pro supports the latest leaflet.js version, which is the core library used for displaying maps.','lmm');
	__('Major highlights:','lmm');
	__('support for IE11 touch devices','lmm');
	__('support for Metro apps','lmm');
	__('a much better panning inertia implementation','lmm');
	__('improved zoom animation curve for a better feel overal','lmm');
	__('improved scroll wheel zoom to be more responsive','lmm');
	__('hand cursors for dragging','lmm');
	__('significantly improved controls design on mobile devices','lmm');
	__('But the real power of the leaflet.js version used in Leaflet Maps Marker pro comes with about a hundred of subtle improvements and bugfixes, improving usability, performance and overall "feel" of browsing the map even further.','lmm');
	__('Click here to get the full changelog for leaflet.js v%1s currently integrated in the pro version','lmm');
	__('v%1s is used in the free version','lmm');
	__('mobile optimized maps through use of native javascript instead of jQuery','lmm');
	__('Maps will be loaded much faster with Leaflet Maps Marker Pro – especially on mobile devices - as no jQuery is needed anymore for displaying maps on frontend. This reduces the download size of each map by about 90kb and also minimizes the browser resources needed for displaying maps.','lmm');
	__('Click here to get more information about this pro feature on mapsmarker.com','lmm');
	__('option to remove MapsMarker.com backlinks','lmm');
	__('Leaflet Maps Marker Pro allows you to hide MapsMarker.com-backlinks from maps, KML files and from the Wikitude app:','lmm');
	__('HTML5 fullscreen maps','lmm');
	__('Leaflet Maps Marker Pro allows you to add a fullscreen button to maps. Clicking on this button will open an HTML5 fullscreen map without leaving the page you are currently viewing.','lmm');
	__('Leaflet Maps Marker Pro allows you to add a small map in the corner which shows the same as the main map with a set zoom offset:','lmm');
	__('mobile web app support for fullscreen maps and optimized mobile viewport','lmm');
	__('Leaflet Maps Marker Pro enables you to save the link to the fullscreen map to the homescreen on iOS devices and reopen the map with an optional launch image as web app – meaning the display of the map in fullscreen mode with no address bar:','lmm');
	__('Furthermore the viewport of the device used is considered, which results in optimized display of fullscreen maps especially on mobile devices:','lmm');
	__('custom Google Maps styling','lmm');
	__('Leaflet Maps Marker Pro allow you to easily customize the presentation of the standard Google base maps, changing the visual display of such elements as roads, parks, and built-up areas:','lmm');
	__('QR codes with custom backgrounds','lmm');
	__('Leaflet Maps Marker Pro allows you to use custom backgrounds for QR codes.','lmm');
	__('custom visualead API key required!','lmm');
	__('Since pro v1.5 QR code images are also cached for a higher performance.','lmm');
	__('Additionally the pro version does not display the visualead logo on the QR code output pages.','lmm');
	__('Google Adsense for maps integration','lmm');
	__('Leaflet Maps Marker Pro supports Google Adsense for maps. This allows you to add different types of ads to your Google maps:','lmm');
	__('upload icon button & custom icon directory','lmm');
	__('Uploading new icons gets easier with Leaflet Maps Marker Pro - no more need to use a FTP client, just click on the new upload button and add new icons from WordPress admin area easily:','lmm');
	__('backup and restore of settings','lmm');
	__('Leaflet Maps Marker Pro allows you to backup and restore your settings which makes it possible to quickly switch between different plugin profiles. This is especially useful if you want to deploy the plugin with custom configuration on multiple sites:','lmm');
	__('For more details, showcases and reviews please also visit <a style="text-decoration:none;" href="http://www.mapsmarker.com">www.mapsmarker.com</a>','lmm');
	__('To start your free 30-day-trial of Leaflet Maps Marker Pro, please click on the button "start installation" below. This will start the download of Leaflet Maps Marker Pro from <a style="text-decoration:none;" href="%1s">%2s</a> and installation as a separate plugin.<br/>Afterwards please activate the pro plugin and you will be guided through the process to receive a free 30-day-trial license without any obligations. Your trial will expire automatically unless you purchase a valid pro license. You can also switch back to the free version at any time.','lmm');
	__('Warning: your user does not have the capability to install new plugins - please contact your administrator (%1s)','lmm');
	__('start installation','lmm');
	__('You already downloaded "Leaflet Maps Marker Pro" to your server but did not activate the plugin yet!','lmm');
	__('Please navigate to <a href="%1$s">Plugins / Installed Plugins</a> and activate the plugin "Leaflet Maps Marker Pro".','lmm');
	__('Please contact your administrator (%1s) to activate the plugin "Leaflet Maps Marker Pro".','lmm');
	__('Manage your markers and layers through a highly customizable REST API, which supports GET & POST requests, JSON & XML as formats and was developed with a focus on security.','lmm');
	__('For more details please visit the MapsMarker API docs.','lmm');
	__('whitelabel backend admin pages','lmm');
	__('Leaflet Maps Marker Pro allows you to remove all backlinks and logos on backend as well as making the pages and menu entries for Tools, Settings, Support, License visible to admins only.','lmm');
	__('advanced permission settings','lmm');
	__('Leaflet Maps Marker Pro allows you to set the user level needed for editing and deleting marker and layer maps from other users.','lmm');
	__('We are working hard on delivering the best mapping solution available for WordPress - helping you to share your favorite spots. Therefore we are commited to constantly improving Leaflet Maps Marker Pro. Below you find some highlights from our development roadmap - if an important one is missing for you, let us know and we will check if we can include it in a future release:','lmm');
	__('filtering markers on frontend','lmm');
	__('support for displaying KML files','lmm');
	__('adding markers from frontend','lmm');
	__('support for Google Street View','lmm');
	__('better integration into the publication workflow (adding markers from posts or as custom post type)','lmm');
	__('support for Wikitude AR launchlinks','lmm');
	__('search for markers on frontend','lmm');
	__('draw features like polylines, polygons, rectangles, circles and markers on maps','lmm');
	__('email notify on marker/layer actions','lmm');
	__('assign markers to multiple layers','lmm');
	__('support for permalinks','lmm');
	__('support for geocoding services other than Google Places','lmm');
	__('better integration with other plugins','lmm');
	__('Visit our contact form to submit your feature request or idea','lmm');
	__('Furthermore can also remove the attribution link from the recent marker widget:','lmm');
	__('Marker clustering','lmm');
	__('Leaflet Maps Marker Pro allows you to create beautifully animated marker clusters for layer maps:','lmm');
	__('GPX tracks','lmm');
	__('additional optimizations and improvements','lmm');
	__('improved performance for layer maps with a huge number of markers (parsing of GeoJSON is up to 3 times faster)','lmm');
	__('support for shortcodes in popup texts','lmm');
	__('features planned for future releases','lmm');
	__('Leaflet Maps Marker Pro allows you to also display GPX tracks with optional metadata on your maps:','lmm');
	__('Please activate the plugin by clicking the link above','lmm');
	__('The pro plugin package could not be downloaded automatically. Please download the plugin from <a href="%1s">%2s</a> and upload it to the directory /wp-content/plugins on your server manually','lmm');
	__('For demo maps please visit %1s which also allows you to test the admin area of the pro version.','lmm');
	__('If you want to compare the free and pro version side by side, please visit %1s.','lmm');
	__('(not now, hide message)','lmm');
	__('support for CSV/XLS/XLSX/ODS import and export for bulk additions and bulk updates','lmm');
	__('Leaflet Maps Marker Pro allows you to easily perform bulk updates on markers and layers by using the integrated import feature:','lmm');
	__('pro version only','lmm');
	__('support for setting global maximum zoom level to 21 (tiles from basemaps with lower native zoom levels will be upscaled automatically)','lmm');
	__('back to top to start free 30-day-trial','lmm');
	//info: recent marker widget
	__('No marker created yet','lmm');
	__('advanced recent marker widget','lmm');
	__('Leaflet Maps Marker Pro allows you to customize which markers and layers to include or exclude in the recent marker widget:','lmm');
	esc_attr__('Please activate the plugin "Maps Marker Pro"','lmm');
	esc_attr__('Please install the plugin "Leaflet MapsMarker Pro"','lmm');
	__('free community support','lmm');
	__('One personal request: before you post a new support ticket in the <a href="http://wordpress.org/support/plugin/leaflet-maps-marker" target="_blank">Wordpress Support Forum</a>, please follow the instructions from <a href="http://www.mapsmarker.com/readme-first" target="_blank">http://www.mapsmarker.com/readme-first</a> which give you a guideline on how to deal with the most common issues.','lmm');
	__('support for duplicating markers','lmm');
	//delete settings with pro 1.5.5
	__('Footer is recommended for better performance. If you are using WordPress lesser than v3.3, Javascript files automatically get inserted into the header of your site and the javascript needed for each maps inline within the content.','lmm');
	__('Support for conditional css loading','lmm');
	__('If enabled, css files will only be loaded if a shortcode is used.','lmm');
	__('Warning: you are using the plugin "Better WordPress Minify" which can cause Leaflet Maps Marker to break if the option "Minify JS files automatically?" is active. Please disable this option (Settings / BWP Minify) or add <strong>leafletmapsmarker</strong> to the form field "Scripts to be ignored (not minified)"','lmm');
	__('Warning: you are using the plugin "W3 Total Cache" with the feature "JS Minify" enabled which is causing maps to break.<br/>To fix this, please navigate to <a href="%1s">Performance / Minify / Advanced</a> and add <strong>%2s</strong> to "Never minify the following JS files:"','lmm');
	__('The plugin "Leaflet Maps Marker" is inactive on this site and therefore this API link is not working.<br/><br/>Please contact the site owner (%1s) who can activate this plugin again.','lmm');
	__('You successfully switched to the simplified editor.','lmm');
	__('You successfully switched to the advanced editor.','lmm');
	__('Please note that unsaved input will not be passed to the new editor! Please click "OK" to switch the editor anyway or "Cancel" to go back and save first.','lmm');
	__('support for dynamic switching between simplified and advanced editor (no more reloads needed)','lmm');
	__('support for filtering of marker icons on backend (based on filename)','lmm');
	__('support for changing marker IDs and layer IDs from the tools page','lmm');
	__('support for bulk updates of marker maps on the tools page for selected layers only','lmm');
	__('option to add markernames to popups automatically (default = false)','lmm');
	__('map moves back to initial position after popup is closed','lmm');
	esc_attr__('Leaflet Maps Marker plugin update to v%1s was successful','lmm');
	__('Leaflet Maps Marker has been successfully updated to version %1s!','lmm');
	__('Leaflet Maps Marker has been successfully updated from version %1s to %2s!','lmm');
	__('improved performance for layer maps by asynchronous loading of markers via GeoJSON','lmm');
	__('option to disable loading of Google Maps API for higher performance if alternative basemaps are used only','lmm');
	__('map parameters can be overwritten within shortcodes (e.g. %1s)','lmm');
	__( 'You can also add markers directly to posts or pages without having to save them to your database previously. You just have to use the shortcode with the attributes mlat and mlon (e.g. <strong>[mapsmarker mlat="48.216038" mlon="16.378984"]</strong>).', 'lmm');
	__('tool for monitoring "active shortcodes with invalid map IDs"','lmm');
	__('layer maps: center map on markers and open popups by clicking on list of markers entries','lmm');
	__('search function for layerlist on marker edit page','lmm');
	__('geolocation support: show and follow your location when viewing maps','lmm');
	__('Good news, this plugin is free for everyone! Since it is released under the GPL2, you can use it free of charge on your personal or commercial blog.<br/>Anyway if you enjoy using this plugin, please consider upgrading to the pro version.','lmm');
	__('Leaflet Maps Marker for WordPress - helping you to share your favorite spots and tracks','lmm');
	?>
	</div>
	<!--wrap-->
<?php } include('inc' . DIRECTORY_SEPARATOR . 'admin-footer.php'); ?>