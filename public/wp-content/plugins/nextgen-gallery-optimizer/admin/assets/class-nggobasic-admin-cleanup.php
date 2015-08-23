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
 * NGGOBASIC_Admin_Cleanup
 *
 * Remove NextGEN's scripts and styles from the WordPress admin on pages
 * where they're not used, to speed up load times in our administration area.
 *
 * This is called via our NGGOBASIC_Admin class, and only loads in the
 * WordPress admin.
 *
 * Developers can work with this instance by calling
 * NGGOBASIC_Admin_Cleanup::get_instance().
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.0.0
 */
class NGGOBASIC_Admin_Cleanup {

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
	 * @wp-hook	void
	 * @uses		add_action()
	 * @since		2.0.0
	 * @return 	void
	 */
	public function init() {

		add_action( 'admin_init', array( $this, 'remove_actions' ) );
		add_action( 'admin_print_scripts', array( $this, 'remove_scripts' ) );
		add_action( 'admin_print_styles', array( $this, 'remove_styles' ) );

	}

	/**
	 * Remove NextGEN's unnecessary admin scripts.
	 *
	 * @wp-hook	admin_print_scripts
	 * @uses		wp_dequeue_script()
	 * @since		2.0.0
	 * @return 	void
	 */
	function remove_scripts() {

		// Remove inline photocrati_ajax CDATA.
		// Remove nextgen-gallery/products/photocrati_nextgen/modules/ajax/static/ajax.js.
		wp_dequeue_script( 'photocrati_ajax' );

		// Remove lazy loading scripts from early versions of NextGEN 2.0...
		// Remove nextgen-gallery/products/photocrati_nextgen/modules/resource_minifier/static/sidjs-0.1.js.
		// Remove nextgen-gallery/products/photocrati_nextgen/modules/resource_minifier/static/lazy_resources.js.
		wp_dequeue_script( 'lazy_resources' );

		// For early versions of NextGEN 2.0...
		// Remove nextgen-gallery/products/photocrati_nextgen/modules/frame_communication/static/frame_event_publisher.js.
		wp_dequeue_script( 'frame_event_publisher' );

		// Remove persistent storage scripts, which are unused on non-gallery pages.
		// Remove nextgen-gallery/products/photocrati_nextgen/modules/ajax/static/persist.js.
		// Remove nextgen-gallery/products/photocrati_nextgen/modules/ajax/static/store.js.
		// Remove nextgen-gallery/products/photocrati_nextgen/modules/ajax/static/ngg_store.js
		wp_dequeue_script( 'ngg-store-js' );

	}

	/**
	 * Remove NextGEN's unnecessary admin styles.
	 *
	 * @wp-hook	admin_print_styles
	 * @uses		wp_dequeue_style()
	 * @since		2.0.0
	 * @return 	void
	 */
	function remove_styles() {

		// Remove nextgen-gallery/products/photocrati_nextgen/modules/nextgen_gallery_display/static/nextgen_gallery_related_images.css.
		wp_dequeue_style( 'nextgen_gallery_related_images' );

		// Remove nextgen-gallery/products/photocrati_nextgen/modules/nextgen_gallery_display/static/fontawesome/font-awesome.css.
		wp_dequeue_style( 'fontawesome' );

	}

	/**
	 * Remove scripts and styles added in the nggAdminPanel class's constructor.
	 *
	 * For this to work, we must hook in after plugins_loaded() so as to fire
	 * only after NextGEN is fully initialized (as NextGEN's configured to load
	 * last in the plugin execution order). This ensures the $ngg handle/
	 * identifier for the nggLoader class instance is defined, so we can
	 * reference the nggAdminPanel class (instantiated inside nggLoader) in
	 * our remove_action() calls.
	 *
	 * Reference: "nextgen-gallery/products/photocrati_nextgen/modules/ngglegacy/nggallery.php" (nggLoader).
	 * Reference: "nextgen-gallery/products/photocrati_nextgen/modules/ngglegacy/admin/admin.php" (nggAdminPanel).
	 *
	 * Removes "nextgen-gallery/products/photocrati_nextgen/modules/nextgen_admin/static/gritter/gritter.min.js".
	 * Removes "nextgen-gallery/products/photocrati_nextgen/modules/nextgen_admin/static/ngg_progressbar.js".
	 * Removes "nextgen-gallery/products/photocrati_nextgen/modules/nextgen_admin/static/gritter/css/gritter.css".
	 * Removes "nextgen-gallery/products/photocrati_nextgen/modules/nextgen_admin/static/ngg_progressbar.css".
	 * Removes "nextgen-gallery/products/photocrati_nextgen/modules/ngglegacy/admin/css/menu.css".
	 *
	 * @wp-hook	admin_init
	 * @uses		remove_action()
	 * @global		$ngg
	 * @since		2.0.0
	 * @return 	void
	 */
	function remove_actions() {

		global $ngg;

		if ( isset( $ngg->nggAdminPanel ) ) {

			remove_action( 'admin_print_scripts', array( $ngg->nggAdminPanel, 'load_scripts' ) );
			remove_action( 'admin_print_styles', array( $ngg->nggAdminPanel, 'load_styles' ) );

		}

	}

}