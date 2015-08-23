<?php
/**
	Plugin Name: Google Picasa Viewer
	Plugin URI: http://nakunakifi.com
    Description: Provides simple drag & drop image gallery functionality to enable you to display Google Picasa Web albums in your WordPress installation.

	Author: Ian Kennerley - <a href='http://twitter.com/nakunakifi'>@nakunakifi</a> on twitter
	Version: 1.3.2
	Author URI: http://nakunakifi.com
	License: GPLv2
	
	Copyright (c) 2011, nakunakifi.com, Ian Kennerley.
	
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

	if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	// Turn this off on a live site
	// error_reporting( E_ALL ); 
	// error_reporting(E_Error);
	// error_reporting(0);
	
	// Include required files
	include 'classes/class-PicasaAPI.php';

	// define( 'PICASA_PLUGIN_URL', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) );
	define( 'PICASA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );	

if ( !class_exists( 'WPPicasa' ) ) {


/**
 * Main Google Picasa Plugin Class
 *
 * Contains the main functions for WPPicasa, stores variables, displays messages 
 *
 */
class WPPicasa {

	/** Version ***************************************************************/

    var $plugin_version = '1.3.2';
		
	/** Variables ***************************************************************/
	var $page;
	
	/**
	 * WPPicasa Constructor
	 *
	 * Let's get this party started!
	 */ 
	function __construct() {
				
		// Include required files
		$this->includes();
				
		// Set up some location variables
		// TODO: Check https fix?...
		$this->pluginPath = dirname( __FILE__ );
		$this->pluginUrl  = WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ),"",plugin_basename( __FILE__ ) );
				
		$this->frontend_includes();
					
		// Add shortcode support for widgets
		if ( ! is_admin() ){ add_filter( 'widget_text', 'do_shortcode', 11 ); }
		
		if( isset( $_GET['nak_page'] ) ? $this->page = $_GET['nak_page'] :  $this->page = 1 );
		
		// Register front-end scripts and styles
		add_action( 'wp_print_scripts', array($this,'add_header_scripts') );
		add_action( 'wp_print_styles', array($this,'add_header_styles') );

		update_option( 'nak_gp_installed', 1 );
		
		// Register back-end styles, used for Admin Upgrade Notice
		add_action( 'admin_print_styles', 'nak_gp_admin_notices_styles' );		
		
		// Run this on deactivation
		register_deactivation_hook( __FILE__, array( $this, 'nak_gp_deactivate' ) );
		
		// Run this on activation
		register_activation_hook( __FILE__, array(  &$this, 'nak_gp_install' ) );
		
	}


	/**
	 * Create Default Settings
	 */
	static function nak_gp_install() {
		$nak_gp_options = array(
					'max_results' => 7,
					'max_album_results' => 3,
					'album_thumb_size' => 150,
					'thumb_size' => 150,
					'max_image_size' => 600
				);
        						
		update_option( 'nak_gp_options', $nak_gp_options );            						
    	}
	
	
	/**
	 * Include required core files
	 */
	function includes() {			
		
		if ( is_admin() ) $this->admin_includes();
		include_once( 'widgets/widget-init.php' );		// Widget classes
		include_once( 'nak-gp-functions.php' );
	}
	
	function admin_includes() {
		include_once( 'admin/nak-gp-admin-init.php' );		// Widget classes
		include_once( 'admin/nak-gp-functions.php' );
	}
		
	
	/**
	 * Deactivate
	 *
	 * Clean up usermeta data on deactivate, forces the upgrade message to be displayed
	 * when the plugin is reactivated.
	 */		
	public function nak_gp_deactivate() {
		global $wpdb;			
		$wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'nak_gp_%';");		// Delete usermeta data
	}
		
		
	/**
	* Enqueue scripts for front-end,
	*
	* Arranges to include javascript libraries / plugins, considers any dependencies
	* place in footer by setting final parameter to 1 
	*     
	* wp_register_script( $handle, $src, $deps, $ver, $in_footer );
	*
	*/	
	public function add_header_scripts() {
		
		if ( ! is_admin() ) {
		
			if( function_exists( 'wp_register_script' ) ) {
				
				// http://codex.wordpress.org/Function_Reference/wp_enqueue_script
				wp_register_script( 'nak_google_picasa_albums_fb', PICASA_PLUGIN_URL . 
									'fancybox/jquery.fancybox-1.3.4.js', array('jquery'),1 );
				wp_register_script( 'nak_google_picasa_albums_js', PICASA_PLUGIN_URL . 
									'js/base.js', array('jquery'),1 );
			
				if( function_exists( 'wp_enqueue_script' ) ) {
					wp_enqueue_script( 'nak_google_picasa_albums_fb' );
					wp_enqueue_script( 'nak_google_picasa_albums_js' );
				}
			}
		}
				
	}
		
		
	/**
	* Enqueue styles for front-end
	*
	* wp_register_style( $handle, $src, $deps, $ver, $media );
	*
	*/	
	public function add_header_styles() {

		if ( ! is_admin() ) {
			
			if( function_exists( 'wp_register_style' ) ) {
				
				// http://codex.wordpress.org/Function_Reference/wp_enqueue_style
				wp_register_style( 'nak_google_picasa_albums_fbcss', 
							plugins_url('fancybox/jquery.fancybox-1.3.4.css',__FILE__), '', $this->plugin_version );
				wp_register_style( 'nak_google_picasa_albums_stylecss', 
							plugins_url('css/style.css',__FILE__) , '', $this->plugin_version );
				
				/* wp_register_style( 'nak_google_picasa_albums_activation', 
							plugins_url(  'css/activation.css',__FILE__) , '', $this->plugin_version ); */

				if ( function_exists( 'wp_enqueue_style' ) ) {
					wp_enqueue_style( 'nak_google_picasa_albums_fbcss' );
					wp_enqueue_style( 'nak_google_picasa_albums_stylecss' );						
					wp_enqueue_style( 'nak_google_picasa_albums_activation' );
				}
			}
		}
	}
		
		
	/**
	 * Include required frontend files
	 **/
	function frontend_includes() {
		include( 'shortcodes/shortcode-init.php' );				// Init the shortcodes
	}		
		
		
		
					
	}
} // class_exists check


/**
 * Init WPPicasa class
 */	
$GLOBALS['wpPicasa'] = new WPPicasa();

?>