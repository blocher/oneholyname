<?php
/*
Plugin Name: Fundraising Thermometer Plugin for WordPress
Plugin URI: http://thisismyurl.com/plugins/our-progress/
Description: Allows WordPress to display a thermometer to measure progress such as fundraising.
Author: Christopher Ross
Author URI: http://thisismyurl.com/
Version: 1.0.1
*/

/**
 * Fundraising Thermometer Plugin for WordPress core file
 *
 * This file contains all the logic required for the plugin
 *
 * @link		http://wordpress.org/extend/plugins/our-progress/
 *
 * @package 		Fundraising Thermometer Plugin for WordPress
 * @copyright		Copyright (c) 2008, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		Fundraising Thermometer Plugin for WordPress 1.0
 */


add_action( 'admin_menu', 'thisismyurl_ourprogress_add_pages' );
add_action( 'wp_head','thisismyurl_add_header_code' );
add_filter( 'plugin_action_links', 'thisismyurl_ourprogress_action', -10, 2 );

add_shortcode( 'show_ourprogress','show_ourprogress' );
add_shortcode( 'show_ourprogress_graphic','show_ourprogress_graphic' );
add_shortcode( 'show_ourprogress_target','show_ourprogress_target' );

if ( $_REQUEST['ourprogresssubmit'] && isset( $_REQUEST['ourprogressmax'] ) ) {

	$uploadpath = wp_upload_dir( );
	$myFile = $uploadpath['basedir']."/ourprogresssettings.txt";
	$fh = fopen( $myFile, 'w' ) or die( "can't open file" );

	$ourprogressprogress = ereg_replace( "[^0-9]", "", floor( $_REQUEST['ourprogressprogress'] ) );
	$ourprogressmax = ereg_replace( "[^0-9]", "", floor( $_REQUEST['ourprogressmax'] ) );


	$stringData = $ourprogressprogress."\n";
	fwrite( $fh, $stringData );
	$stringData = $ourprogressmax."\n";
	fwrite( $fh, $stringData );
	$stringData = $_REQUEST['ourprogressformat']."\n";
	fwrite( $fh, $stringData );
	$stringData = $_REQUEST['ourprogresstheme']."\n";
	fwrite( $fh, $stringData );
	$stringData = $_REQUEST['ourprogresspadding']."\n";
	fwrite( $fh, $stringData );

	fclose( $fh );

	update_option( "ourprogressprogress", $ourprogressprogress );
	update_option( "ourprogressmax", $ourprogressmax );
	update_option( "ourprogressformat", $_REQUEST['ourprogressformat'] );
	update_option( "ourprogresstheme", $_REQUEST['ourprogresstheme'] );
	update_option( "ourprogresspadding", $_REQUEST['ourprogresspadding'] );

}



function thisismyurl_ourprogress_action( $links, $file ) {
	// adds the link to the settings page in the plugin list page
	if ( $file == plugin_basename( dirname( __FILE__ ).'/ourprogress.php' ) )
	$links[] = "<a href='edit.php?page=ourprogressmanage'>" . __( 'Settings', 'Fundraising Thermometer Plugin for WordPress' ) . "</a>";
	return $links;
}



function thisismyurl_ourprogress_add_pages( ) {
    add_management_page( 'Fundraising Thermometer Plugin for WordPress', 'Our Progess', 8, 'ourprogressmanage', 'thisismyurl_ourprogress_manage_page' );
}

function thisismyurl_ourprogress_manage_page( ) {
    echo '<div class="wrap">';
	echo '<h2>Fundraising Thermometer Plugin for WordPress</h2>';
	echo '<form method="post">';

	?>
	<table class="form-table">
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="name">Current Amount</label></th>
			<td><input name="ourprogressprogress" id="ourprogressprogress" type="text" value="<?php
				if( get_option( "ourprogressprogress" ) ) {echo get_option( "ourprogressprogress" );} else {echo "0";}

			?>" size="40" aria-required="true" />
            <p>How much money have you raised to date?</p></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="name">Target Amount</label></th>
			<td><input name="ourprogressmax" id="ourprogressmax" type="text" value="<?php
				if( get_option( "ourprogressmax" ) ) {echo get_option( "ourprogressmax" );} else {echo "100";}
			?>" size="40" aria-required="true" />
            <p>Input the total amount of money you would like to raise.</p></td>
		</tr>
        <?php if ( function_exists( 'money_format' ) ) {?>
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="name">Currency Format</label></th>
			<td><input name="ourprogressformat" id="ourprogressmax" type="text" value="<?php
				if( get_option( "ourprogressformat" ) ) {echo get_option( "ourprogressformat" );} else {echo "$%( #10n";}
			?>" size="40" aria-required="true" />
            <p>Number formating is based on the standard <a href='http://ca.php.net/manual/en/function.money-format.php'>PHP money format</a>.</p></td>
		</tr>
        <?php }?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="slug">Theme</label></th>
			<td><select name="ourprogresstheme" id="ourprogresstheme">
            	<?php
					$path = "../wp-content/plugins/fundraising-thermometer-plugin-for-wordpress/images/";
					$myDirectory = opendir( $path );

			// get each entry
			while( $entryName = readdir( $myDirectory ) ) {
				if ( substr_count( $entryName,"." ) == 0 ) {
					echo "<option value='$entryName'";
					if ( get_option( "ourprogressformat" ) == $entryName ) { echo " selected ";}
					echo ">$entryName</option>\n";
				}
			}


				?>
                </select>
            <p>Which theme would you like to use?</p></td>
		</tr>

        <tr class="form-field form-required">
			<th scope="row" valign="top"><label for="name">Padding Amount</label></th>
			<td><input name="ourprogresspadding" id="ourprogresspadding" type="text" value="<?php
				if( get_option( "ourprogresspadding" ) ) {echo get_option( "ourprogresspadding" );} else {echo "20";}
			?>" size="40" aria-required="true" />
            <p>How many pixels would you like Fundraising Thermometer Plugin for WordPress to place between values on the bar?</p></td>
		</tr>


	</table>
	<p class="submit"><input type="submit" class="button" name="ourprogresssubmit" value="Update" /></p>
	<?php
	echo '<input id="old" type="hidden" value="'.get_option( "progress" ).'">';
	echo '</form>';

	echo "	<small><p><strong>Installation</strong></p>
			<p>You can display the current value of your fund raising efforts by placing the code <em>&lt;?php echo show_ourprogress( );?&gt;</em> anywhere in your theme. You can display a graphic of your fund raising efforts by placing the code <em>&lt;?php echo show_ourprogress_graphic( );?&gt;</em> anywhere in your theme.</p>

			<p><strong>Want to say thank you?</strong></p>
		  	<p>Using this plug-in is free, but if you'd like to say thanks you can <a href='http://thisismyurl.com/?2098421'>send me a small donation</a>.<br/>Even better, a simple link from your web site to mine ( <em><a href='http://thisismyurl.com'>http://thisismyurl.com</a></em> ).</p>";

	echo '</small></div>';
}

function show_ourprogress( ) {

	if( get_option( "ourprogressformat" ) ) {$format = get_option( "ourprogressformat" );} else {$format = "$%( #10n";}
	if( get_option( "ourprogressprogress" ) ) {$progress = get_option( "ourprogressprogress" );} else {$progress = 0;}

	return thisismyurl_my_money_format ( $format,$progress );
}

function show_ourprogress_target( ) {

	if( get_option( "ourprogressformat" ) ) {$format = get_option( "ourprogressformat" );} else {$format = "$%( #10n";}
	if( get_option( "ourprogressmax" ) ) {$progress = get_option( "ourprogressmax" );} else {$progress = 0;}

	return thisismyurl_my_money_format ( $format,$progress );
}

function show_ourprogress_graphic( ) {
	echo "<div class='ourprogresswrapper'>\n";
	if( strlen( get_option( "ourprogressmax" ) )>1 ) {$max = get_option( "ourprogressmax" );} else {$max = "100";}
	if( strlen( get_option( "ourprogressprogress" ) )>1 ) {$current = get_option( "ourprogressprogress" );} else {$current = 0;}
	if( strlen( get_option( "ourprogressformat" ) )>1 ) {$format = get_option( "ourprogressformat" );} else {$format = "$%( #10n";}
	if( strlen( get_option( "ourprogresstheme" ) )>1 ) {$theme = get_option( "ourprogresstheme" );} else {$theme = "default";}
	if( strlen( get_option( "ourprogresspadding" ) )>1 ) {$padding = get_option( "ourprogresspadding" );} else {$padding = "20";}

	if ( $current >= $max ) {
        echo "<div class='ourprogress-burst'>\n";
    } else {
     	echo "<div class='ourprogress'>\n";
    }

	echo "<div class='ourprogressgraphics'>\n";
	$percent = round( ( $current/$max )*100 );

	if( $percent >= 100 )  {$percent = 100;}
	$percent = str_pad( thisismyurl_roundnum( $percent, 1 ), 2, "0", STR_PAD_LEFT );
	$height = $percent * 4;
	$margin_top = 400 - $height;
	if( $margin_top > 398 ) $margin_top = 398;

	echo "	<div class='ourprogressmercury' style='margin-top: ".$margin_top."px; height: ".$height."px'>\n"; /* PHZ */
	echo "		<div class='ourprogressmercurytop'></div>\n";
	echo "	</div>\n";
	echo "</div>\n";
	echo "<div class='ourprogressnumbers'>\n";
	for ( $counter = $max; $counter >= 0;$counter=$counter-( $max/10 )	 ) {
		echo "<div class='progressvalue' style='margin-top:";
        if( get_option( "ourprogresspadding" ) ) {echo get_option( "ourprogresspadding" );} else {echo "20";}
        echo "px;'>".thisismyurl_my_money_format( $format,$counter )."</div>\n";
	}
	echo "</div>\n";
	echo "<!-- Fundraising Thermometer Plugin for WordPress plug-in by Christopher Ross, http://thisismyurl.com -->\n";
	echo "</div>\n";
	echo "<p><a style='font-size: 5px;' href='http://thisismyurl.com' title='WordPress Plugin by Christopher Ross'>Fundraising Thermometer Plugin for WordPress by Christopher Ross</a></p>";
	echo "</div>\n";
}


function thisismyurl_add_header_code( ) {
	if( get_option( "ourprogresstheme" ) ) 		{$theme = get_option( "ourprogresstheme" );} else {$theme = "default";}
	echo '<link type="text/css" rel="stylesheet" href="'. get_bloginfo( 'wpurl' ).'/wp-content/plugins/fundraising-thermometer-plugin-for-wordpress/images/'.$theme.'/style.css" />' . "\n";
}

function thisismyurl_roundnum ( $num, $nearest )
{
   $ret = 0;
   $mod = $num % $nearest;
   if ( $mod >= 0 )
     $ret = ( $mod > ( $nearest / 2 ) ) ? $num + ( $nearest - $mod ) : $num - $mod;
    else
     $ret = ( $mod > ( -$nearest / 2 ) ) ? $num - $mod : $num + ( -$nearest - $mod );
    return $ret;
}


function thisismyurl_my_money_format( $format = '%i', $num = 0 ) {
		if ( function_exists( 'money_format' ) ) {
			 return ( money_format( $format,$num ) );
		} else {
			return "$" . number_format( $num, 2 );
		}

    }

function widget_thisismyurl_our_progress( ) {
?>
  <h2 class="widgettitle">Fundraising Thermometer Plugin for WordPress</h2>
  <?php show_ourprogress_graphic( ); ?>
<?php
}
function thisismyurl_our_progress_init( )
{
  register_sidebar_widget( __( 'Fundraising Thermometer Plugin for WordPress' ), 'widget_thisismyurl_our_progress' );
}
add_action( "plugins_loaded", "thisismyurl_our_progress_init" );


?>
