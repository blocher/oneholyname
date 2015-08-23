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
 * NGGOBASIC_Register_Scripts
 *
 * Handles the registering and enqueuing of NextGEN's scripts and styles, which
 * we'll load only where required to speed up page loads.
 *
 * This is called via our NGGOBASIC_Optimize_NextGEN_V1 class, and only loads
 * on front-end requests (when NextGEN Legacy is installed).
 *
 * Developers can work with this instance by calling
 * NGGOBASIC_Register_Scripts::get_instance().
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.0.0
 */
class NGGOBASIC_Register_Scripts {

	/**
	 * Optimizer's Fancybox slug.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $fancybox_slug = 'nextgen-gallery-optimizer-basic-enhanced-fancybox-lightbox';

	/**
	 * The version of jQuery to pull from Google's CDN (if selected).
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $jquery_version = '1.11.1';

	/**
	 * The version of jQuery Migrate to use if Google CDN is selected and
	 * jQuery version is v1.9 or higher.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $jquery_migrate_version = '1.2.1';

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

		// Retrieve required properties from our main plugin class.
		$this->plugin = NextGEN_Gallery_Optimizer_Basic::get_instance();
		$vars = $this->plugin->get_vars();
		$this->db = $vars['db'];
		$this->prefix = $vars['prefix'];
		$this->version = $vars['version'];
		$this->plugin_dir_url = $vars['plugin_dir_url'];
		$this->fancybox_version = $vars['fancybox_version'];
		$this->nextgen_v1 = $vars['nextgen_v1'];
		$this->gallery_version = $vars['gallery_version'];

		// Get the directory URL for either NextGEN Gallery or the NextCellent Gallery fork.
		add_action( 'init', array( $this, 'get_nextgen_dir_url' ) );

	}

	/**
	 * Allow public access to our class properties.
	 *
	 * Used in our NGGOBASIC_NextGEN_Widgets class.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @since		2.0.0
	 * @return 	array $vars
	 */
	public function get_vars() {

		$vars = array(

			"jquery_version" => $this->jquery_version,
			"jquery_migrate_version" => $this->jquery_migrate_version

		);

		return $vars;

	}

	/**
	 * Helper method to detect which lightbox is activated at Gallery -->
	 * Options --> Effects.
	 *
	 * Options include Optimizer's Fancybox, as well as NextGEN's Shutter
	 * effect and WordPress' built-in Thickbox lightbox.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @global		$ngg
	 * @param	string $val Required value to check.
	 * @since		2.0.0
	 * @return 	boolean
	 */
	function is_lightbox( $val ) {

		global $ngg;

		// Convert to full slug.
		if ( 'fancybox' == $val ) {

			$val = $this->fancybox_slug;

		}

		if ( isset( $ngg->options['thumbEffect'] ) && $ngg->options['thumbEffect'] == $val ) {

			return true;

		}

		return false;

	}

	/**
	 * Helper method to detect which slideshow is activated at Gallery -->
	 * Options --> Slideshow.
	 *
	 * Options include the default jQuery slideshow, or the third-party,
	 * flash-based JR Image Rotator.
	 *
	 * In NextCellent Gallery 1.9.25+, both of these slideshows were replaced
	 * with the Owl Carousel, so we'll check for that too.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @global		$ngg
	 * @param	string $val Required value to check.
	 * @since		2.0.0
	 * @return 	boolean
	 */
	function is_slideshow( $val ) {

		global $ngg;

		// Target NextCellent Gallery 1.9.25+. Since v2.1.
		// This class only loads with NextGEN Legacy and NextCellent Gallery, but we'll explicitly check our "nextgen_v1" property in case other developers call it directly.
		// Both slideshows were replaced with the Owl Carousel.
		if ( $this->nextgen_v1 && $this->gallery_version >= '1.9.25' && 'owl_carousel' == $val ) {

			return true;

		}

		if ( isset( $ngg->options['enableIR'] ) ) {

			if ( $ngg->options['enableIR'] && 'jr_image_rotator' == $val ) {

				return true;

			}

			if ( ! $ngg->options['enableIR'] && 'default_slideshow' == $val ) {

				return true;

			}

		}

		return false;

	}

	/**
	 * Helper method to detect which version of jQuery we're using, as selected
	 * on Optimizer's main settings page.
	 *
	 * Options include the default WordPress version, or the CDN-based Google-
	 * hosted version.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @param	string $val Required value to check.
	 * @since		2.0.0
	 * @return 	void|true
	 */
	function is_jquery( $val ) {

		if ( isset( $this->db['jquery'] ) && $this->db['jquery'] == $val ) {

			return true;

		}

	}

	/**
	 * Get NextGEN's plugin directory URL, including support for forks of
	 * NextGEN v1 such as the "NextCellent Gallery - NextGEN Legacy" plugin.
	 *
	 * @wp-hook	init
	 * @uses		is_plugin_active()
	 * @since		2.0.0
	 * @return 	string $url
	 */
	function get_nextgen_dir_url() {

		$url = null;

		if ( is_plugin_active( 'nextgen-gallery/nggallery.php' ) ) {

			$url = plugins_url( 'nextgen-gallery/' );

		}

		if ( is_plugin_active( 'nextcellent-gallery-nextgen-legacy/nggallery.php' ) ) {

			$url = plugins_url( 'nextcellent-gallery-nextgen-legacy/' );

		}

		return $url;

	}

	/**
	 * Load Optimizer's Fancybox script and stylesheet.
	 *
	 * @wp-hook	wp_enqueue_scripts
	 * @uses		wp_register_style(), wp_enqueue_style()
	 * @uses		wp_deregister_script(), wp_register_script(), wp_enqueue_script()
	 * @since		2.0.0
	 * @return 	void
	 */
	function load_fancybox() {

		wp_register_style( $this->prefix . '-fancybox', $this->plugin_dir_url . 'fancybox/' . $this->prefix . '.jquery.fancybox-' . $this->fancybox_version . '.css', false, $this->version, 'screen' );
		wp_enqueue_style( $this->prefix . '-fancybox' );

		wp_deregister_script( 'fancybox' );
		wp_deregister_script( 'jquery.fancybox' );
		wp_deregister_script( 'jquery-fancybox' );
		wp_register_script( $this->prefix . '-fancybox', $this->plugin_dir_url . 'fancybox/' . $this->prefix . '.jquery.fancybox-' . $this->fancybox_version . '.pack.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( $this->prefix . '-fancybox' );

	}

	/**
	 * Load NextGEN's Shutter Reloaded script, stylesheet and CDATA block.
	 *
	 * @wp-hook	wp_enqueue_scripts
	 * @uses		wp_register_style(), wp_enqueue_style()
	 * @uses		wp_register_script(), wp_enqueue_script()
	 * @uses		wp_localize_script()
	 * @since		2.0.0
	 * @return 	void
	 */
	function load_shutter() {

		wp_register_style( $this->prefix . '-shutter-reloaded', $this->get_nextgen_dir_url() . 'shutter/shutter-reloaded.css', false, null, 'screen' );
		wp_enqueue_style( $this->prefix . '-shutter-reloaded' );

		wp_register_script( $this->prefix . '-shutter-reloaded', $this->get_nextgen_dir_url() . 'shutter/shutter-reloaded.js', false, null );
		wp_enqueue_script( $this->prefix . '-shutter-reloaded' );

		// Include Shutter's inline CDATA block.
		wp_localize_script( $this->prefix . '-shutter-reloaded', 'shutterSettings', array(
				'msgLoading' => 'L O A D I N G',
				'msgClose' => 'Click to Close',
				'imageCount' => '1'
			)
		);

	}

	/**
	 * Load WordPress' Thickbox script, stylesheet and CDATA block.
	 *
	 * @wp-hook	wp_enqueue_scripts
	 * @uses		wp_register_style(), wp_enqueue_style(), wp_enqueue_script()
	 * @since		2.0.0
	 * @return 	void
	 */
	function load_thickbox() {

		wp_register_style( $this->prefix . '-thickbox', includes_url( '/js/thickbox/thickbox.css' , dirname( __FILE__ ) ), false, null, 'screen' );
		wp_enqueue_style( $this->prefix . '-thickbox' );

		wp_enqueue_script( 'thickbox' );

	}

	/**
	 * Enqueue the jQuery JavaScript library.
	 *
	 * We'll use either the WordPress version, or the Google CDN version as
	 * selected on Optimizer's settings page. Both versions are now coupled
	 * with jQuery Migrate for backwards compatibility.
	 *
	 * @wp-hook	wp_enqueue_scripts
	 * @uses		wp_deregister_script(), wp_register_script(), wp_enqueue_script()
	 * @since		2.0.0
	 * @return 	void
	 */
	function load_jquery() {

		if ( $this->is_jquery( 'wordpress' ) ) {

			wp_enqueue_script( 'jquery' );

		}

		if ( $this->is_jquery( 'google' ) ) {

			wp_deregister_script( 'jquery' );
			wp_deregister_script( 'jquery-migrate' );

			wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/' . $this->jquery_version . '/jquery.min.js', false, $this->jquery_version );
			wp_enqueue_script( 'jquery' );

			wp_register_script( 'jquery-migrate', 'http://code.jquery.com/jquery-migrate-' . $this->jquery_migrate_version . '.min.js', array( 'jquery' ), $this->jquery_migrate_version );
			wp_enqueue_script( 'jquery-migrate' );

		}

	}

	/**
	 * Run the jQuery.noConflict() method.
	 *
	 * Inserts an inline call to jQuery's noConflict() method on the front-end
	 * when the Google CDN version of jQuery is being used. This prevents
	 * conflicts with other JavaScript libraries such as prototype.js, MooTools,
	 * or YUI. The built-in WordPress version calls this itself.
	 *
	 * @wp-hook	wp_head
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function load_jquery_no_conflict_inline_js() {

		if ( $this->is_jquery( 'google' ) ) {

			echo "\n<script type='text/javascript'>jQuery.noConflict();</script>\n";

		}

	}

	/**
	 * Load NextGEN Gallery's styles (as selected on Optimizer's settings page).
	 *
	 * @wp-hook	wp_print_styles
	 * @uses		wp_register_style(), wp_enqueue_style(), content_url()
	 * @since		2.0.0
	 * @return 	void
	 */
	function load_nextgen_styles() {

		$theme = $this->db['theme'];

		if ( $theme == "Black Minimalism Theme" ) { $filename = 'Black_Minimalism.css'; }
		if ( $theme == "Default Styles" ) { $filename = 'nggallery.css'; }
		if ( $theme == "Dkret3 Theme" ) { $filename = 'ngg_dkret3.css'; }
		if ( $theme == "Hovereffect Styles" ) { $filename = 'hovereffect.css'; }
		if ( $theme == "K2 Theme" ) { $filename = 'ngg_k2.css'; }
		if ( $theme == "Shadow Effect" ) { $filename = 'ngg_shadow.css'; }
		if ( $theme == "Shadow Effect with Description Text" ) { $filename = 'ngg_shadow2.css'; }

		if ( $this->db['css'] != "" ) {
			wp_register_style( $this->prefix . '-nextgen', content_url( $this->db['css'], dirname( __FILE__ ) ), false, null, 'screen' );
			wp_enqueue_style( $this->prefix . '-nextgen' );

		} else {

			if ( $this->db['theme'] != "None" ) {
				wp_register_style( $this->prefix . '-nextgen', $this->get_nextgen_dir_url() . 'css/' . $filename . '', false, null, 'screen' );
				wp_enqueue_style( $this->prefix . '-nextgen' );
			}

		}

		// Target NextCellent Gallery 1.9.25+. Since v2.1.
		// This class only loads with NextGEN Legacy and NextCellent Gallery, but we'll explicitly check our "nextgen_v1" property in case other developers call it directly.
		if ( $this->nextgen_v1 && $this->gallery_version >= '1.9.25' ) {

			// Also load the new NextCellent Framework stylesheet if we're running NextCellent Gallery v1.9.25+.
			// Note the dependency, so it always loads directly AFTER the main gallery stylesheet.
			wp_register_style( $this->prefix . '-NextCellent-Framework', $this->get_nextgen_dir_url() . 'css/framework-min.css', $this->prefix . '-nextgen', null, 'screen' );
			wp_enqueue_style( $this->prefix . '-NextCellent-Framework' );

		}

	}

	/**
	 * Enqueue NextGEN Gallery's default slideshow scripts.
	 *
	 * @wp-hook	wp_enqueue_scripts
	 * @uses		wp_deregister_script(), wp_register_script(), wp_enqueue_script()
	 * @since		2.0.0
	 * @return 	void
	 */
	function nextgen_slideshow_scripts() {

		wp_deregister_script( 'jquery.cycle.all.min.js' );
		wp_deregister_script( 'cycle.all.min.js' );
		wp_deregister_script( 'jquery.cycle.all.js' );
		wp_deregister_script( 'jquery.cycle.all' );
		wp_deregister_script( 'cycle.all' );
		wp_deregister_script( 'cycle.all.js' );
		wp_register_script( $this->prefix . '-jquery-cycle', $this->get_nextgen_dir_url() . 'js/jquery.cycle.all.min.js', array( 'jquery' ), null );
		wp_enqueue_script( $this->prefix . '-jquery-cycle' );

		wp_deregister_script( 'ngg.slideshow.min.js' );
		wp_deregister_script( 'slideshow.min.js' );
		wp_deregister_script( 'slideshow.js' );
		wp_deregister_script( 'ngg.slideshow.js' );
		wp_deregister_script( 'ngg.slideshow' );
		wp_deregister_script( 'ngg_slideshow' );
		wp_deregister_script( 'ngg-slideshow' );
		wp_deregister_script( 'nggslideshow' );
		wp_register_script( $this->prefix . '-ngg-slideshow', $this->get_nextgen_dir_url() . 'js/ngg.slideshow.min.js', array( 'jquery' ), null );
		wp_enqueue_script( $this->prefix . '-ngg-slideshow' );

	}

	/**
	 * Enqueue WordPress' built-in "swfobject" script for the JR Image Rotator.
	 *
	 * @wp-hook	wp_enqueue_scripts
	 * @uses		wp_enqueue_script
	 * @since		2.0.0
	 * @return 	void
	 */
	function load_jr_image_rotator() {

		wp_enqueue_script( 'swfobject' );

	}

	/**
	 * Load the new Owl Carousel, which replaced both slideshows in NextCellent
	 * Gallery 1.9.25+.
	 *
	 * @wp-hook	wp_enqueue_scripts
	 * @uses		wp_register_script, wp_enqueue_script
	 * @since		2.1
	 * @return 	void
	 */
	function load_owl_carousel_scripts() {

		wp_register_script( $this->prefix . '-ngg-owl-carousel', $this->get_nextgen_dir_url() . 'js/owl.carousel.min.js', array( 'jquery' ), null );
		wp_enqueue_script( $this->prefix . '-ngg-owl-carousel' );

	}

	/**
	 * Enqueue the AJAX pagination JS for the Shutter effect and our Fancybox
	 * lightbox.
	 *
	 * As of Optimizer v2.0 (thanks to many user requests), NextGEN's AJAX
	 * pagination now works with Optimizer's Fancybox! Previously, NextGEN
	 * required you use the Shutter effect for this to function properly.
	 *
	 * As of v2.1, Optimizer now references home_url() instead of site_url(),
	 * to support WordPress installations both stored in their own subdirectory
	 * (Eg. http://example.com/wordpress), and that are configured to serve
	 * pages from the website root (Eg. http://example.com).
	 *
	 * @wp-hook	wp_enqueue_scripts
	 * @uses		wp_register_script(), wp_enqueue_script()
	 * @uses 		wp_localize_script(), home_url()
	 * @since		2.0.0
	 * @return 	void
	 */
	function load_ajax_pagination() {

		if ( $this->is_lightbox( 'fancybox' ) ) {

			wp_register_script( $this->prefix . '-fancybox-ajax-pagination.js', $this->plugin_dir_url . 'public/nextgen-v1/js/' . $this->prefix . '-fancybox-ajax-pagination.js', array( 'jquery' ) );
			wp_enqueue_script( $this->prefix . '-fancybox-ajax-pagination.js' );

			// Insert inline AJAX pagination CDATA for [nggallery id=x], [album id=x] GALLERY pages and [imagebrowser id=x].
			wp_localize_script( $this->prefix . '-fancybox-ajax-pagination.js', 'ngg_ajax', array(
					'path' => $this->get_nextgen_dir_url(),
					'callback' => trailingslashit( home_url() ) . 'index.php?callback=ngg-ajax',
					'loading' => 'loading'
				)
			);

		}

		if ( $this->is_lightbox( 'shutter' ) ) {

			wp_register_script( $this->prefix . '-shutter', $this->get_nextgen_dir_url() . 'js/ngg.js', array( 'jquery' ), '2.1' );
			wp_enqueue_script( $this->prefix . '-shutter' );

			// Insert inline AJAX pagination CDATA for [nggallery id=x], [album id=x] GALLERY pages and [imagebrowser id=x].
			wp_localize_script( $this->prefix . '-shutter', 'ngg_ajax', array(
					'path' => $this->get_nextgen_dir_url(),
					'callback' => trailingslashit( home_url() ) . 'index.php?callback=ngg-ajax',
					'loading' => 'loading'
				)
			);

		}

	}

	/**
	 * Enqueue NextGEN's Cooliris Piclens script.
	 *
	 * @wp-hook	wp_enqueue_scripts
	 * @uses		wp_deregister_script(), wp_register_script(), wp_enqueue_script()
	 * @since		2.0.0
	 * @return 	void
	 */
	function load_piclens_js() {

		wp_deregister_script( 'piclens' );
		wp_deregister_script( 'piclens_optimized' );
		wp_deregister_script( 'piclens-optimized' );
		wp_register_script( $this->prefix . '-piclens', 'http://lite.piclens.com/current/piclens_optimized.js', false, $this->version, true /* In the footer */ );
		wp_enqueue_script( $this->prefix . '-piclens' );

	}

	/**
	 * Add NextGEN's Cooliris Piclens script to the footer.
	 *
	 * @wp-hook	void
	 * @uses		add_action()
	 * @since		2.0.0
	 * @return 	void
	 */
	function add_piclens_js() {

		add_action( 'wp_enqueue_scripts', array( $this, 'load_piclens_js' ), 1000 );

	}

	/**
	 * Conditionally hook all selected LIGHTBOX scripts and styles to WordPress.
	 *
	 * This method is called in "insert-scripts-and-styles.php" once we've
	 * detected a page that requires lightbox functionality (e.g. a page
	 * containing the [nggallery id=x] shortcode).
	 *
	 * @wp-hook	void
	 * @uses		add_action(), remove_action()
	 * @since		2.0.0
	 * @return 	void
	 */
	function add_lightbox_scripts_and_styles() {

		if ( $this->is_lightbox( 'fancybox' ) ) {

			add_action( 'wp_enqueue_scripts', array( $this, 'load_jquery' ), 1000 );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_fancybox' ), 1000 );

		}

		if ( $this->is_lightbox( 'shutter' ) ) {

			add_action( 'wp_enqueue_scripts', array( $this, 'load_shutter' ), 1000 );

		}

		if ( $this->is_lightbox( 'thickbox' ) ) {

			add_action( 'wp_enqueue_scripts', array( $this, 'load_jquery' ), 1000 );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_thickbox' ), 1000 );
			add_action( 'wp_head', array( $this, 'load_jquery_no_conflict_inline_js' ), 1000 );

		}

		remove_action( 'wp_print_styles', array( $this, 'load_nextgen_styles' ), 1000 ); // For stylesheet ordering consistency on album gallery pages.
		add_action( 'wp_print_styles', array( $this, 'load_nextgen_styles' ), 1000 );

	}

	/**
	 * Conditionally UN-hook all selected LIGHTBOX scripts and styles from
	 * WordPress on gallery and album sub-pages.
	 *
	 * This method is called in "insert-scripts-and-styles.php" once we've
	 * detected a gallery SUB-page that does NOT require lightbox
	 * functionality, but inherits the scripts from its parent (e.g. the
	 * [Show as slideshow] link page on the [nggallery id=x] shortcode).
	 *
	 * @wp-hook	void
	 * @uses		remove_action()
	 * @since		2.0.0
	 * @return 	void
	 */
	function remove_lightbox_scripts_and_styles() {

		if ( $this->is_lightbox( 'fancybox' ) ) {

			remove_action( 'wp_enqueue_scripts', array( $this, 'load_jquery' ), 1000 );
			remove_action( 'wp_enqueue_scripts', array( $this, 'load_fancybox' ), 1000 );

		}

		if ( $this->is_lightbox( 'shutter' ) ) {

			remove_action( 'wp_enqueue_scripts', array( $this, 'load_shutter' ), 1000 );

		}

		if ( $this->is_lightbox( 'thickbox' ) ) {

			remove_action( 'wp_enqueue_scripts', array( $this, 'load_jquery' ), 1000 );
			remove_action( 'wp_enqueue_scripts', array( $this, 'load_thickbox' ), 1000 );
			remove_action( 'wp_head', array( $this, 'load_jquery_no_conflict_inline_js' ), 1000 );

		}

	}

	/**
	 * Conditionally hook all selected SLIDESHOW scripts and styles to
	 * WordPress.
	 *
	 * Checks if the JR Image Rotator is selected at
	 * Gallery --> Options --> Slideshow.
	 *
	 * This method is called in "class-nggobasic-shortcode-regex.php" if we're
	 * on a page that requires slideshow functionality (Eg. Pages with the
	 * [slideshow id=x] shortcode, or on [Show as slideshow] link sub-pages.
	 *
	 * As of version 2.1, we're also checking for the Owl Carousel, which
	 * replaced both the jQuery slideshow and JR Image Rotator in NextCellent
	 * Gallery 1.9.25+.
	 *
	 * @wp-hook	void
	 * @uses		add_action()
	 * @since		2.0.0
	 * @return 	void
	 */
	function add_slideshow_scripts_and_styles() {

		// Target NextCellent Gallery 1.9.25+. Since v2.1.
		// Both slideshows were replaced with the Owl Carousel.
		if ( $this->is_slideshow( 'owl_carousel' ) ) {

			add_action( 'wp_enqueue_scripts', array( $this, 'load_jquery' ), 1000 );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_owl_carousel_scripts' ), 1001 );
			add_action( 'wp_head', array( $this, 'load_jquery_no_conflict_inline_js' ), 1000 );

		}

		if ( $this->is_slideshow( 'jr_image_rotator' ) ) {

			add_action( 'wp_enqueue_scripts', array( $this, 'load_jr_image_rotator' ), 1001 );

		}

		if ( $this->is_slideshow( 'default_slideshow' ) ) {

			add_action( 'wp_enqueue_scripts', array( $this, 'load_jquery' ), 1000 );
			add_action( 'wp_enqueue_scripts', array( $this, 'nextgen_slideshow_scripts' ), 1001 );
			add_action( 'wp_head', array( $this, 'load_jquery_no_conflict_inline_js' ), 1000 );

		}

		add_action( 'wp_print_styles', array( $this, 'load_nextgen_styles' ), 1000 );

	}

	/**
	 * Conditionally hook all AJAX pagination scripts to WordPress.
	 *
	 * As of Optimizer v2.0 (thanks to many user requests), NextGEN's AJAX
	 * pagination now works with Optimizer's Fancybox! Previously, NextGEN
	 * required you use the Shutter effect for this to function properly.
	 *
	 * This method is called in "class-nggobasic-shortcode-regex.php" if we're
	 * on a gallery page (e.g. a page containing the [nggallery id=x]
	 * shortcode), and if AJAX pagination is selected at Gallery -->
	 * Options --> Gallery -->"Enable AJAX pagination".
	 *
	 * @wp-hook	void
	 * @uses		add_action()
	 * @since		2.0.0
	 * @return 	void
	 */
	function add_ajax_pagination_scripts() {

		if ( $this->is_lightbox( 'fancybox' ) || $this->is_lightbox( 'shutter' ) ) {

			add_action( 'wp_enqueue_scripts', array( $this, 'load_jquery' ), 1000 );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_ajax_pagination' ), 1000 );

		}

		if ( $this->is_lightbox( 'shutter' ) ) {

			add_action( 'wp_head', array( $this, 'load_jquery_no_conflict_inline_js' ), 1000 );

		}

	}


	/**
	 * Conditionally UN-hook all AJAX pagination scripts from WordPress on
	 * gallery and album sub-pages.
	 *
	 * This method is called in "class-nggobasic-shortcode-regex.php" if we're
	 * on a gallery SUB-page that does NOT require AJAX pagination
	 * functionality, but has inherited the scripts from its parent (e.g. the
	 * [Show as slideshow] link sub-page on the [nggallery id=x] shortcode).
	 *
	 * @wp-hook	void
	 * @uses		remove_action()
	 * @since		2.0.0
	 * @return 	void
	 */
	function remove_ajax_pagination_scripts() {

		if ( $this->is_lightbox( 'fancybox' ) || $this->is_lightbox( 'shutter' ) ) {

			remove_action( 'wp_enqueue_scripts', array( $this, 'load_jquery' ), 1000 );
			remove_action( 'wp_enqueue_scripts', array( $this, 'load_ajax_pagination' ), 1000 );

		}

		if ( $this->is_lightbox( 'shutter' ) ) {

			remove_action( 'wp_head', array( $this, 'load_jquery_no_conflict_inline_js' ), 1000 );

		}

	}

}