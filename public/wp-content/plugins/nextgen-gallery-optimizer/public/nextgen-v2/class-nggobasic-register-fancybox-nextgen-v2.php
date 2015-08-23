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
 */

/**
 * Exit if this file is called directly.
 */
if ( ! defined( 'WPINC' ) ) {

	exit( 'Sorry. You are not allowed to call this page directly.' );

}

/**
 * NGGOBASIC_Register_Fancybox_NextGEN_V2
 *
 * Provides front-end, in-memory lightbox registration in NextGEN 2.0.7+.
 *
 * Removes NextGEN's unnecessary lightbox script dependencies for Fancybox
 * across all versions of NextGEN 2.0.xx.
 *
 * Adds cache-busting, query-string versioning for Fancybox's single script and
 * stylesheet, as NextGEN 2.0.xx's "C_Lightbox_Installer" class and NextGEN
 * 2.0.7+'s "C_Lightbox_Library_Manager" class both lack the ability to define
 * these.
 *
 * This is called via our main NextGEN_Gallery_Optimizer_Basic class, and only
 * loads on front-end requests (when NextGEN v2.0.xx is installed).
 *
 * Developers can work with this instance by calling
 * NGGOBASIC_Register_Fancybox_NextGEN_V2::get_instance().
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.1
 */
class NGGOBASIC_Register_Fancybox_NextGEN_V2 {

 	/**
	 * A single instance of this class.
	 *
	 * @since	2.1
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
	 * @since		2.1
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
	 * @since		2.1
	 * @return 	void
	 */
	public function __construct() {}

	/**
	 * Class initialization.
	 *
	 * Get and set required properties, and add our action hooks to initialize
	 * this class.
	 *
	 * @wp-hook	void
	 * @uses		add_action(), add_filter()
	 * @since		2.1
	 * @return 	void
	 */
	public function init() {

		// Retrieve required properties from our main plugin class.
		$this->plugin = NextGEN_Gallery_Optimizer_Basic::get_instance();
		$vars = $this->plugin->get_vars();
		$this->db = $vars['db'];
		$this->prefix = $vars['prefix'];
		$this->version = $vars['version'];
		$this->plugin_dir_url = $vars['plugin_dir_url'];
		$this->plugin_dir_path = $vars['plugin_dir_path'];
		$this->fancybox_handle = $vars['fancybox_handle'];
		$this->fancybox_version = $vars['fancybox_version'];
		$this->nextgen_v207 = $vars['nextgen_v207'];

		// Hook into the do_action in C_Lightbox_Library_Manager::register_defaults() for in-memory, front-end lightbox registration in NextGEN 2.0.7+.
		if ( $this->nextgen_v207 ) { add_action( 'ngg_registered_default_lightboxes', array( $this, 'register_fancybox' ) ); }

		// Update our script dependencies set by NextGEN.
		add_action( 'wp_footer', array( $this, 'update_fancybox_script_dependencies' ) );

		// Ensure a fresh version of our Fancybox script is loaded by the browser (but only if it's been updated).
		add_filter( 'script_loader_src', array( $this, 'update_fancybox_script_versioning' ) );

		// Ensure a fresh version of our Fancybox stylesheet is loaded by the browser (but only after an Optimizer update).
		add_filter( 'style_loader_src', array( $this, 'update_fancybox_stylesheet_versioning' ) );

	}

	/**
	 * Register Fancybox.
	 *
	 * Provides front-end, in-memory lightbox registration in NextGEN 2.0.7+.
	 *
	 * This is required on the front-end in order to dynamically load the
	 * required lightbox CSS and JS files without touching the database, but is
	 * also required in the admin, in order to populate the lightbox select
	 * menu at Gallery -> Other Options -> Lightbox Effects.
	 *
	 * @wp-hook		void
	 * @ngg-hook	ngg_registered_default_lightboxes
	 * @uses			void
	 * @since			2.1
	 * @return 		void
	 */
	function register_fancybox() {

		// First check if the "C_Lightbox_Library_Manager" class exists.
		// This avoids creating a potential fatal error (which would cause us to not be able to deactivate the plugin) if this class is ever removed in a future NextGEN version.
		if ( ! class_exists( 'C_Lightbox_Library_Manager' ) ) {
			return;
		}

		// Also check the "register()" method exists, should any changes occur in a future version.
		if ( ! method_exists( 'C_Lightbox_Library_Manager', 'register' ) ) {
			return;
		}

		// Get instance of C_Lightbox_Library_Manager for lightbox integration in NextGEN 2.0.71+.
		$lightbox_library_manager = C_Lightbox_Library_Manager::get_instance();

		// Register our Enhanced Fancybox Lightbox in NextGEN 2.0.71+.
        $enhanced_fancybox_lightbox = new stdClass();
        $enhanced_fancybox_lightbox->title = __( 'Enhanced Fancybox Lightbox [NextGEN Gallery Optimizer Basic]', 'nggallery' );
        $enhanced_fancybox_lightbox->code = 'class=\'nggobasic-fancybox\' rel=\'%GALLERY_NAME%\'';
        $enhanced_fancybox_lightbox->styles = array( $this->plugin_dir_url . 'fancybox/' . $this->prefix . '.jquery.fancybox-' . $this->fancybox_version . '.css' );
        $enhanced_fancybox_lightbox->scripts = array( $this->plugin_dir_url . 'fancybox/' . $this->prefix . '.jquery.fancybox-' . $this->fancybox_version . '.pack.js' );
        $lightbox_library_manager->register( $this->fancybox_handle, $enhanced_fancybox_lightbox );

	}

	/**
	 * Update our Enhanced Fancybox Lightbox's script dependencies.
	 *
	 * NextGEN v2.0 to v2.0.66.37 imposes an unused dependency of "common.js"
	 * (and its 15KB inline CDATA block) on our lightbox, whilst NextGEN
	 * v2.0.71+ does the same with the "lightbox_context.min.js" script, even
	 * though we don't require either.
	 *
	 * In order to dequeue these scripts, we must first remove their listing as
	 * (unnecessary) dependencies.
	 *
	 * We'll also take this opportunity to add jQuery as a dependency, as both
	 * the "C_Lightbox_Installer" class in NextGEN v2.0 to v2.0.66.37, and the
	 * "C_Lightbox_Library_Manager" class in NextGEN v2.0.7+ lack the ability
	 * to define dependencies (as well as version query args).
	 *
	 * @wp-hook	wp_footer
	 * @global		$wp_scripts
	 * @uses		$wp_scripts->query()
	 * @since		2.0.0 (moved from "NGGOBASIC_Optimize_NextGEN_V2" class as of v2.1)
	 * @return 	void
	 */
	function update_fancybox_script_dependencies() {

		global $wp_scripts;

		$i = 0;

		do {

			$script = $wp_scripts->query( $this->fancybox_handle . '-' . $i, 'registered' );

			if ( $script && $this->db['fancybox'] ) {

				// NextGEN v2.0 to v2.0.66.37.
				if ( ! $this->nextgen_v207 && $this->db['remove_common_js_and_cdata'] ) {

					unset ( $script->deps ); // Remove the "ngg_common" dependency so we can deregister it.

				}

				// NextGEN v2.0.71+
				if ( $this->nextgen_v207 && $this->db['remove_lightbox_context_js'] ) {

					unset ( $script->deps ); // Remove the "ngg_lightbox_context" dependency so we can deregister it.

				}

				// All versions of NextGEN v2.0.xx
				$script->deps[] = 'jquery'; // Insert our jQuery dependency to ensure it loads with the script.

			}

			$i++;

		} while ( ! $script && $i < 100 );

	}

	/**
	 * Update Fancybox script versioning
	 *
	 * Appends a last-modified timestamp to our call to our Enhanced Fancybox
	 * Lightbox script on the front-end.
	 *
	 * This ensures a fresh version of the script is loaded by the browser if
	 * it's been updated via our Dynamic Fancybox Settings Interface.
	 *
	 * The script remains cached in the browser until the timestamp no longer
	 * matches (which only occurs when the file is modified).
	 *
	 * We're intentionally omitting the "ver=" key here, as it appears to work
	 * more consistently cross-browser.
	 *
	 * We're having to do this via the "script_loader_src" filter, as both the
	 * "C_Lightbox_Installer" class in NextGEN v2.0 to v2.0.66.37, and the
	 * "C_Lightbox_Library_Manager" class in NextGEN v2.0.7+ lack the ability
	 * to define a version.
	 *
	 * @wp-filter	script_loader_src
	 * @uses		remove_query_arg
	 * @param	string $src
	 * @since		2.0.0 (moved from "NGGOBASIC_Optimize_NextGEN_V2" class as of v2.1)
	 * @return 	string $src
	 */
	function update_fancybox_script_versioning( $src ) {

		$file = 'fancybox/' . $this->prefix . '.jquery.fancybox-' . $this->fancybox_version . '.pack.js';

		if ( strpos( $src, $file ) ) {

			$src = remove_query_arg( 'ver', $src );
			$src = $src . '?' . filemtime( $this->plugin_dir_path . $file );

		}

		return $src;

	}

	/**
	 * Update Fancybox stylesheet versioning
	 *
	 * As both the "C_Lightbox_Installer" class in NextGEN v2.0 to v2.0.66.37,
	 * and the "C_Lightbox_Library_Manager" class in NextGEN v2.0.7+ lack the
	 * ability to define a version, we need to update the "ver" query-string
	 * parameter on Fancybox's stylesheet separately.
	 *
	 * We'll update it to reflect Optimizer's own version number (instead of
	 * the WordPress version as set by default).
	 *
	 * This is required for cache-busting the stylesheet after an Optimizer
	 * update.
	 *
	 * @wp-filter	style_loader_src
	 * @uses		add_query_arg
	 * @param	string $src
	 * @since		2.0.0 (moved from "NGGOBASIC_Optimize_NextGEN_V2" class as of v2.1)
	 * @return 	string $src
	 */
	function update_fancybox_stylesheet_versioning( $src ) {

		if ( strpos( $src, $this->prefix . '.jquery.fancybox-' . $this->fancybox_version . '.css' ) ) {

			$src = add_query_arg( 'ver', $this->version, $src );

		}

		return $src;

	}

}