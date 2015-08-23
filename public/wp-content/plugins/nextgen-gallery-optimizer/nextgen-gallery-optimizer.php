<?php

/**
 * NextGEN Gallery Optimizer Basic
 *
 * NextGEN Gallery Optimizer improves your site's page load speed, by
 * preventing NextGEN's scripts and stylesheets from loading on posts and pages
 * *without* NextGEN Gallery content.
 *
 * Additionally...
 *
 * If you're using NextGEN v2.x.xx, Optimizer also provides you with complete
 * granular control of the scripts and stylesheets that load on posts and pages
 * *with* NextGEN Gallery content. For a live example of these settings in action,
 * please view the source of Optimizer's homepage (a NextGEN v2.x.xx gallery
 * page with AJAX pagination) in your browser.
 *
 * If you're using NextGEN Legacy (v1.6.2 to v1.9.13) or the NextCellent
 * Gallery fork, Optimizer will intelligently fine-tune all your posts and pages
 * *with* NextGEN Gallery content automatically... detecting every shortcode
 * and setting, to only load the scripts and stylesheets required on each page.
 *
 * For a full overview of the optimization process, please see...
 * http://www.nextgengalleryoptimizer.com/optimization-flowcharts/
 *
 * Optimizer also includes an *enhanced* version of the fantastic Fancybox
 * lightbox script, and only loads it on appropriate posts and pages *with*
 * NextGEN Gallery content. The result? Gorgeous galleries *and* a speedy site.
 *
 * Optimizer v2.1.1 currently supports (and is tested compatible with) NextGEN
 * Gallery (v2.0.0 to v2.1.0), NextGEN Legacy (v1.6.2 to v1.9.13) and
 * NextCellent Gallery (v1.9.14 to v1.9.26).
 *
 * NextGEN Gallery Optimizer Pro
 *
 * Upgrade to Optimizer Pro for the new "NextGEN Gallery Deactivator" feature.
 * A whole new level of speed optimization.
 * Only load NextGEN’s PHP *code* on posts/pages *with* NextGEN Gallery content.
 * http://www.nextgengalleryoptimizer.com/#nextgen-gallery-deactivator
 *
 * Upgrade to Optimizer Pro for the new "Dynamic Fancybox Settings Interface".
 * The fastest and easiest way to customize Fancybox.
 * Set title styles, background color & opacity, make Fancybox responsive & more.
 * http://www.nextgengalleryoptimizer.com/#dynamic-fancybox-settings-interface
 *
 * For more information, please see the following...
 * http://www.nextgengalleryoptimizer.com
 * http://www.nextgengalleryoptimizer.com/documentation/
 * http://www.nextgengalleryoptimizer.com/nextgen-gallery-optimizer-pro/
 * http://www.nextgengalleryoptimizer.com/purchase/
 *
 * @package		NextGEN_Gallery_Optimizer_Basic
 * @author		Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @link				http://www.nextgengalleryoptimizer.com
 * @copyright	2012 - 2015 Mark Jeldi | Helpful Media
 *
 * @wordpress-plugin
 * Plugin Name:	NextGEN Gallery Optimizer
 * Plugin URI:	http://www.nextgengalleryoptimizer.com
 * Description:	<strong>The essential add-on for the NextGEN Gallery WordPress plugin.</strong> Optimizer improves your site's page load speed, by preventing NextGEN's scripts and stylesheets from loading on posts and pages <strong><em>without</em></strong> NextGEN Gallery content. <strong><a href="http://www.nextgengalleryoptimizer.com/#script-and-stylesheet-optimization">Learn more</a></strong>. Optimizer also includes an <strong><em>enhanced</em></strong> version of the fantastic Fancybox lightbox script, and only loads it on appropriate posts and pages <strong><em>with</em></strong> NextGEN Gallery content. The result? Gorgeous galleries <strong><em>and</em></strong> a speedy site! <strong><a href="http://www.nextgengalleryoptimizer.com/#enhanced-fancybox-lightbox">Learn more</a></strong>. Optimizer v2.1.1 currently supports (and is tested compatible with) NextGEN Gallery (v2.0.0 to v2.1.0), NextGEN Legacy (v1.6.2 to v1.9.13) and NextCellent Gallery (v1.9.14 to v1.9.26). <strong><a href="http://www.nextgengalleryoptimizer.com/nextgen-gallery-optimizer-pro/">***Upgrade to Optimizer Pro***</a></strong> for the new <strong><a href="http://www.nextgengalleryoptimizer.com/#nextgen-gallery-deactivator">NextGEN Gallery Deactivator</a></strong> feature. A whole new level of speed optimization, that only loads NextGEN’s PHP <strong><em>code</em></strong> on posts and pages <strong><em>with</em></strong> NextGEN Gallery content. Also included in Optimizer Pro is the new <strong><a href="http://www.nextgengalleryoptimizer.com/#dynamic-fancybox-settings-interface">Dynamic Fancybox Settings Interface</a></strong>. The fastest and easiest way to customize Fancybox. Set title styles, background color and opacity, make Fancybox <strong><em>responsive</em></strong> and more. See it in action on Optimizer's settings page, or check out the website for a <strong><a href="http://www.nextgengalleryoptimizer.com/#dynamic-fancybox-settings-interface">live demo</a></strong>.
 * Author:			Helpful Media
 * Version:		2.1.1
 *
 * Copyright (c) 2012-2015 by Mark Jeldi | Helpful Media | http://www.nextgengalleryoptimizer.com/contact/
 */

/**
 * Exit if this file is called directly.
 */
if ( ! defined( 'WPINC' ) ) {

	exit( 'Sorry. You are not allowed to call this page directly.' );

}

/**
 * Check for required PHP version.
 *
 * As of Optimizer 2.0, we're dropping support for PHP 4.
 *
 * This shouldn't be an issue as WordPress itself has required PHP 5.2.4 since v3.2 released in July 2011.
 * Read more: http://wordpress.org/news/2010/07/eol-for-php4-and-mysql4/.
 * Additionally, it appears no current WordPress installs (as of Oct 2013) are using anything less than PHP 5.2.
 * See: http://wordpress.org/about/stats/.
 */
if ( version_compare( PHP_VERSION, '5.0', '<' ) ) {

	exit( sprintf( 'Sorry. NextGEN Gallery Optimizer requires PHP 5 or greater, <a href="http://wordpress.org/about/requirements">just like WordPress itself since v3.2</a>. You’re still on ' . PHP_VERSION . '. Please contact your host to arrange an upgrade.' ) );

}

/**
 * Check if WordPress version is at least v3.1 before we proceed with installation.
 *
 * Required for use of the show_admin_bar(), wp_dequeue_script() and
 * get_current_screen() functions introduced in WordPress v3.1.
 */
global $wp_version;

if ( $wp_version < 3.7 ) {

	wp_die( __( 'Sorry. NextGEN Gallery Optimizer Basic requires WordPress v3.7 or greater.<br />To proceed, please first <a href="http://codex.wordpress.org/Updating_WordPress" target="_blank">update your installation</a>.' ) );

}

/**
 * NextGEN_Gallery_Optimizer_Basic
 *
 * Our main class to initialize the plugin.
 *
 * We'll route all requests to the appropriate includes depending on whether
 * we're in the admin, or on a public, front-facing screen. This optimizes
 * performance by not loading unnecessary code into memory.
 *
 * Developers can work with this main plugin instance by calling
 * NextGEN_Gallery_Optimizer_Basic::get_instance().
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.0.0
 */
 class NextGEN_Gallery_Optimizer_Basic {

 	/**
	 * Optimizer's prefix used as an identifier in plugin file names.
	 * We're defining it here for greater consistency between Basic
	 * and Pro versions.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $prefix = 'nggobasic';

	/**
	 * Optimizer's version number, used for cache-busting scripts and styles on
	 * plugin updates.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $version = '2.1.1';

	/**
	 * Our plugin display name.
	 *
	 * @since	2.1 (formerly $plugin_display_name in NGGOBASIC_Admin).
	 * @var		string
	 */
	protected $display_name = 'NextGEN Gallery Optimizer Basic';

	/**
	 * Fancybox's version number, used for cache-busting scripts and styles on
	 * plugin updates.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $fancybox_version = '1.3.4';

	/**
	 * Our Fancybox handle required for registering scripts and styles.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $fancybox_handle = 'nextgen-gallery-optimizer-basic-enhanced-fancybox-lightbox';

	/**
	 * Optimizer's main settings page slug.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $settings_slug = 'nextgen-gallery-optimizer-basic-settings';

	/**
	 * Our options name in the WordPress wp_options database table.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $db_name = 'nextgen_gallery_optimizer_basic_settings';

	/**
	 * Get Optimizer's settings from the WordPress wp_options database table.
	 *
	 * @since	2.0.0
	 * @var		array
	 */
	protected $db;

	/**
	 * Full URL path to our plugin folder.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $plugin_dir_url;

	/**
	 * Full system file path to our plugin folder.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $plugin_dir_path;

	/**
	 * Are we using NextGEN Legacy?
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	protected $nextgen_v1;

	/**
	 * Are we using NextGEN 2.0?
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	protected $nextgen_v2;

	/**
	 * Are we using the major update of NextGEN v2.0.71+?
	 *
	 * @since	2.1
	 * @var		boolean
	 */
	protected $nextgen_v207;

	/**
	 * The display name of our currently active gallery plugin.
	 *
	 * @since	2.1
	 * @var		string
	 */
	protected $gallery_display_name;

	/**
	 * The exact version of NextGEN or NextCellent Gallery in use.
	 *
	 * This is taken directly from NextGEN's main plugin file header, as we
	 * can't count on Photocrati to not remove/rename the "NGG_PLUGIN_VERSION"
	 * constant (which had already replaced Legacy's "$ngg->version" in NextGEN
	 * v2.0+), nor provide any graceful deprecation or backwards compatibility
	 * to prevent errors after doing so (re: the NextGEN v2.0.7x update).
	 *
	 * @since	2.1
	 * @var		integer
	 */
	protected $gallery_version;

 	/**
	 * A single instance of this class.
	 *
	 * @since	2.0.0
	 * @var		object
	 * @see		get_instance()
	 */
	protected static $instance = null;

	/**
	 * Access this class's working instance.
	 * If the instance hasn't been set, set it now.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @since		2.0.0
	 * @return		object of this class
	 */
	public static function get_instance() {

		if ( null == self::$instance ) { self::$instance = new self; }

		return self::$instance;

	}

	/**
	 * Class constructor.
	 *
	 * Intentionally left empty and public for unit testing.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @see			init()
	 * @since		2.0.0
	 * @return 	void
	 */
	public function __construct() {}

	/**
	 * Class initialization.
	 *
	 * Get and set required properties, and add our action hooks, function
	 * calls and file includes to initialize this class.
	 *
	 * @wp-hook	plugins_loaded
	 * @uses		get_option()
	 * @since		2.0.0
	 * @return 	void
	 */
	public function init() {

		// Get our main database options.
		$this->db = get_option( $this->db_name );

		// Define extra plugin paths.
		$this->plugin_dir_url = plugin_dir_url( __FILE__ );
		$this->plugin_dir_path = plugin_dir_path( __FILE__ );

		// Detect which NextGEN codebase we're using.
		$this->get_nextgen_version();

		// Route to either admin classes or front-end classes.
		$this->route_request();

	}

	/**
	 * Our class router.
	 *
	 * Only loads the code required for a given page view.
	 *
	 * Fully separates admin requests from the front-end, and also NextGEN
	 * Legacy code from NextGEN v2.0.
	 *
	 * @wp-hook	void
	 * @uses		is_admin()
	 * @since		2.0.0
	 * @return 	null|void
	 */
	function route_request() {

		if ( is_admin() ) {

			if ( ( basename( $_SERVER['PHP_SELF'] ) == 'plugins.php' ) && ( ! is_array( $this->db ) ) ) {

				// Redirect to settings page on first activation.
				add_action( 'init', array( $this, 'redirect_to_settings_page' ) );

			}

			// Include and instantiate our main admin class.
			require_once( $this->plugin_dir_path . 'admin/class-' . $this->prefix . '-admin.php' );
			$instance = NGGOBASIC_Admin::get_instance();
			$instance->init();

		}

		if ( ! is_admin() ) {

			if ( $this->nextgen_v1 ) {

				require_once( $this->plugin_dir_path . 'public/nextgen-v1/class-' . $this->prefix . '-optimize-nextgen-v1.php' );
				$instance = NGGOBASIC_Optimize_NextGEN_V1::get_instance();
				$instance->init();

				add_action( 'init', array( $this, 'remove_nextgen_v1_version' ) );
				add_action( 'wp_head', array( $this, 'add_nextgen_v1_version' ) );

			}

			if ( $this->nextgen_v2 ) {

				if ( $this->db['fancybox'] ) {

					require_once( $this->plugin_dir_path . 'public/nextgen-v2/class-' . $this->prefix . '-register-fancybox-nextgen-v2.php' );
					$instance = NGGOBASIC_Register_Fancybox_NextGEN_V2::get_instance();
					$instance->init();

				}

				if ( $this->db['optimize_front_end'] || $this->db['optimize_gallery_pages'] ) { // Optional settings for NextGEN 2.0.

					require_once( $this->plugin_dir_path . 'public/nextgen-v2/class-' . $this->prefix . '-optimize-nextgen-v2.php' );
					$instance = NGGOBASIC_Optimize_NextGEN_V2::get_instance();
					$instance->init();

				}

				// Add our version to NextGEN's inline version comment, for easier debugging for both developers.
				add_filter( 'show_nextgen_version', array( $this, 'filter_nextgen_v2_version' ) );

			}

		}

	}

	/**
	 * Allow public access to our main class properties.
	 *
	 * These are required both on the front-end and in the admin.
	 *
	 * To access from another plugin, or in your theme's functions.php file,
	 *	you could either extend this class, or do something like the following...
	 *
	 *	function get_optimizer_dir_url() {
	 *		$plugin = NextGEN_Gallery_Optimizer_Basic::get_instance();
	 *		$vars = $plugin->get_vars();
	 *		$optimizer_dir_url = $vars['plugin_dir_url'];
	 *		echo $optimizer_dir_url;
	 *	}
	 *	add_action( 'init', 'get_optimizer_dir_url' );
	 *
	 *	Note: If calling this class instance from within a plugin, depending on
	 * execution order, you may need to use the plugins_loaded hook or later
	 * to ensure this plugin's files have been included. NextGEN for example,
	 * forces itself to load last after all other plugins, so interaction with the
	 * gallery must come after all plugins have loaded.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @since		2.0.0
	 * @return 	array $vars
	 */
	public function get_vars() {

		$vars = array(

			"db" => $this->db,
			"db_name" => $this->db_name,
			"prefix" => $this->prefix,
			"version" => $this->version,
			"display_name" => $this->display_name, // Added in v2.1. Formerly $plugin_display_name in NGGOBASIC_Admin.
			"fancybox_version" => $this->fancybox_version,
			"fancybox_handle" => $this->fancybox_handle,
			"settings_slug" => $this->settings_slug,
			"plugin_dir_path" => $this->plugin_dir_path,
			"plugin_dir_url" => $this->plugin_dir_url,
			"nextgen_v1" => $this->nextgen_v1,
			"nextgen_v2" => $this->nextgen_v2,
			"nextgen_v207" => $this->nextgen_v207, // Added in v2.1.
			"gallery_display_name" => $this->gallery_display_name, // Added in v2.1.
			"gallery_version" => $this->gallery_version // Added in v2.1.

		);

		return $vars;

	}

	/**
	 * Check which NextGEN codebase and version we're running, using WordPress'
	 * plugin meta-data parser.
	 *
	 * Options include NextGEN Legacy (up to and including v1.9.13), version
	 * 2.0 or the NextGEN Legacy fork called "NextCellent Gallery".
	 *
	 * @wp-hook	void
	 * @uses		get_plugins(), is_plugin_active()
	 * @since		2.0.0
	 * @return		void
	 */
	function get_nextgen_version() {

		if ( ! function_exists( 'get_plugins' ) ) {

			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		}

		$nextgen_dir_name = 'nextgen-gallery';
		$nextcellent_dir_name = 'nextcellent-gallery-nextgen-legacy';
		$plugin_file_name = 'nggallery.php';

		$nextgen_data = get_plugins( '/' . $nextgen_dir_name );
		$nextcellent_data = get_plugins( '/' . $nextcellent_dir_name );

		if ( $nextgen_data && is_plugin_active( $nextgen_dir_name . '/' . $plugin_file_name  ) ) {

			$this->gallery_display_name = 'NextGEN Gallery';
			$this->gallery_version = $nextgen_data[$plugin_file_name]['Version'];

			if ( $this->gallery_version < '2.0.0' ) {

				$this->nextgen_v1 = true; // Cache the result for use throughout the plugin.

			} else {

				$this->nextgen_v2 = true; // Cache the result for use throughout the plugin.

			}

			if ( $this->gallery_version >= '2.0.71' ) {

				$this->nextgen_v207 = true; // Cache the result for use throughout the plugin.

			}

		}

		if ( $nextcellent_data && is_plugin_active( $nextcellent_dir_name . '/' . $plugin_file_name ) ) {

			$this->gallery_display_name = 'NextCellent Gallery';
			$this->gallery_version = $nextcellent_data[$plugin_file_name]['Version'];
			$this->nextgen_v1 = true;

		}

	}

	/**
	 * Remove NextGEN Gallery's version number comment from source code.
	 *
	 * @wp-hook	init
	 * @uses		remove_action()
	 * @since		2.0.0
	 * @return 	void
	 */
	function remove_nextgen_v1_version() {

		remove_action( 'wp_head', array( 'nggGallery', 'nextgen_version' ) );

	}

	/**
	 * Add our combined version tags for NextGEN Legacy.
	 *
	 * We're returning this in meta tag format instead of HTML comments, for
	 * greater consistency with the way WordPress adds its own tag.
	 *
	 * @wp-hook	wp_head()
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function add_nextgen_v1_version() {

		echo '<meta name="generator" content="' . $this->gallery_display_name . ' v' . $this->gallery_version . '" />' . "\n";
		echo '<meta name="generator" content="' . $this->display_name . ' v' . $this->version . '" />' . "\n";

	}

	/**
	 * Add our version to NextGEN's inline version tag, for easier debugging
	 * for both developers.
	 *
	 * We're returning this in meta tag format instead of HTML comments, for
	 * greater consistency with the way WordPress adds its own tag.
	 *
	 * @filter		show_nextgen_version
	 * @since		2.0.0
	 * @return 	string $out
	 */
	function filter_nextgen_v2_version() {

		$out  = '<meta name="generator" content="' . $this->gallery_display_name . ' v' . $this->gallery_version . '" />' . "\n";
		$out .= '<meta name="generator" content="' . $this->display_name . ' v' . $this->version . '" />' . "\n";

		return $out;

	}

	/**
	 * Redirect users to settings page on first activation.
	 *
	 * @wp-hook	init
	 * @uses		wp_redirect()
	 * @since		2.0.0
	 * @return		void
	 */
	function redirect_to_settings_page() {

		if ( ! is_array( $this->db ) ) {

			wp_redirect( admin_url( 'options-general.php?page=' . $this->settings_slug . '', __FILE__ ) );

		}

	}

}

/**
 * Instantiate our class.
 */
add_action( 'plugins_loaded', array( NextGEN_Gallery_Optimizer_Basic::get_instance(), 'init' ) );