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
 * NGGOBASIC_NextGEN_Widgets
 *
 * Our NextGEN Gallery widget optimization class for NextGEN Legacy (v1.6.2 to
 * 1.9.13) and NextCellent Gallery.
 *
 * We'll detect any instances of NextGEN widgets, determine their type (Eg.
 * Gallery or Slideshow), and load the appropriate scripts, stylesheets and
 * CDATA blocks as required.
 *
 * This is called via our NGGOBASIC_Optimize_NextGEN_V1 class, and only loads
 * on front-end requests (when NextGEN Legacy / NextCellent Gallery is
 * installed).
 *
 * Developers can work with this instance by calling
 * NGGOBASIC_NextGEN_Widgets::get_instance().
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.0.0
 */
 class NGGOBASIC_NextGEN_Widgets {

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
		$this->plugin_dir_path = $vars['plugin_dir_path'];
		$this->fancybox_version = $vars['fancybox_version'];

		// Access our class methods and properties in NGGOBASIC_Register_Scripts.
		$this->register_scripts = NGGOBASIC_Register_Scripts::get_instance();
		$vars = $this->register_scripts->get_vars();
		$this->jquery_version = $vars['jquery_version'];
		$this->jquery_migrate_version = $vars['jquery_migrate_version'];

		// Check if we have an active NextGEN widget.
		add_action( 'init', array( $this, 'is_nextgen_widget_active' ) );

	}

	/**
	 * Check if one of NextGEN's widgets are actually activated on the
	 * WordPress admin plugins page.
	 *
	 * As there is currently no WordPress function to detect if a widget is
	 * actually present on any given page, we'll go on to buffer the HTML
	 * output if this check passes, and search for their unique div names
	 * in the markup. As this can be an expensive process, we'll only run it
	 * if a NextGEN widget is activated in the admin.
	 *
	 * If a widget is detected in the output, we'll write in the appropriate
	 * scripts and styles.
	 *
	 * @wp-hook	init
	 * @uses		is_active_widget(), add_action()
	 * @since		2.0.0
	 * @return 	void
	 */
	function is_nextgen_widget_active() {

		if ( is_active_widget( '', '', 'ngg-images' ) ||  is_active_widget( '', '', 'slideshow' ) ) {

			add_action( 'wp_head', array( $this, 'buffer_output' ) );
			add_action( 'wp_footer', array( $this, 'add_widget_scripts' ) );

		}

	}

	/**
	 * Begin output buffering if a NextGEN widget is activated in the
	 * WordPress admin.
	 *
	 * @wp-hook	wp_head
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function buffer_output() {

		ob_start();

	}

	/**
	 * Get complete HTML output and write in the appropriate scripts and styles
	 * for NextGEN's widgets.
	 *
	 * All scripts and stylesheets are first checked in the callback functions
	 * to make sure they're not already enqueued. This avoids conflicts from
	 * duplication (especially important with jQuery).
	 *
	 * @wp-hook	wp_footer
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function add_widget_scripts() {

		$this->html = ob_get_contents();
		$scripts = '';

		/**
		 * Check if our Gallery widget or Slideshow widget is present on the
		 * current page by searching for the "ngg_images" and
		 * "ngg-widget-slideshow"div classes.
		 */
		$gallery_widget = strpos( $this->html, 'ngg_images' );
		$slideshow_widget = strpos( $this->html, 'ngg-widget-slideshow' );

		// Strpos can return 0 when there is a match at the beginning of the string, so requires strict comparison.
		// We'll reverse the comparison to simplify our widget logic below.
		if ( false !== $gallery_widget ) { $gallery_widget = true; }
		if ( false !== $slideshow_widget ) { $slideshow_widget = true; }

		/**
		 * Load our gallery stylesheet if either the Gallery widget or the
		 * Slideshow widget (or both) are active on the current page.
		 */
		if ( $gallery_widget || $slideshow_widget ) {

			$scripts .= $this->load_stylesheet();

		}

		/**
		 * Load jQuery for NextGEN widgets, except when it's a gallery widget
		 * using the Shutter effect (pure javascript), or a slideshow widget
		 * using the JR Image Rotator (flash-based).
		 */
		if ( $gallery_widget && ! $this->register_scripts->is_lightbox( 'shutter' ) ||
		$slideshow_widget && ! $this->register_scripts->is_slideshow( 'jr_image_rotator' ) ) {

			$scripts .= $this->load_jquery();

		}

		/**
		 * If the *gallery* widget is present, load the appropriate lightbox
		 * scripts and styles.
		 */
		if ( $gallery_widget ) {

			$scripts .= $this->load_lightbox_scripts();

		}

		/**
		 * If the *slideshow* widget is present, load the appropriate slideshow
		 * scripts and styles.
		 */
		if ( $slideshow_widget ) {

			$scripts .= $this->load_slideshow_scripts();

		}

		$scripts .= "</head>";

		// Write in our required scripts and styles.
		$this->html = str_replace( '</head>', $scripts, $this->html );

		ob_end_clean();

		echo $this->html;

	}

	/**
	 * Get NextGEN Gallery's styles (as selected on Optimizer's settings page)
	 * for NextGEN's widgets.
	 *
	 * Only run if the stylesheets are not already enqueued.
	 *
	 * @wp-hook	void
	 * @uses		content_url(), wp_style_is()
	 * @since		2.0.0
	 * @return 	string $out
	 */
	function load_stylesheet() {

		if ( ! wp_style_is( $this->prefix . '-custom' ) && ! wp_style_is( $this->prefix . '-nextgen' ) ) {

			$theme = $this->db['theme'];

			if ( $theme == 'Black Minimalism Theme' ) { $filename = 'Black_Minimalism.css'; }
			if ( $theme == 'Default Styles' ) { $filename = 'nggallery.css'; }
			if ( $theme == 'Dkret3 Theme' ) { $filename = 'ngg_dkret3.css'; }
			if ( $theme == 'Hovereffect Styles' ) { $filename = 'hovereffect.css'; }
			if ( $theme == 'K2 Theme' ) { $filename = 'ngg_k2.css'; }
			if ( $theme == 'Shadow Effect' ) { $filename = 'ngg_shadow.css'; }
			if ( $theme == 'Shadow Effect with Description Text' ) { $filename = 'ngg_shadow2.css'; }

			if ( $this->db['css'] ) {

				$out = "\n<link rel='stylesheet' id='" . $this->prefix . "-custom-css'  href='" . content_url( $this->db['css'], dirname( __FILE__ ) ) . "' type='text/css' media='screen' />\n";

			} else {

				if ( $this->db['theme'] ) {

					$out = "\n<link rel='stylesheet' id='" . $this->prefix . "-nextgen-css'  href='" . $this->register_scripts->get_nextgen_dir_url() . 'css/' . $filename . "' type='text/css' media='screen' />\n";

				}

			}

		}

		return $out;

	}

	/**
	 * Get the jQuery JavaScript library for NextGEN widgets.
	 *
	 * We'll use either the WordPress version, or the Google CDN version as
	 * selected on Optimizer's settings page. Both versions are now coupled
	 * with jQuery Migrate for backwards compatibility.
	 *
	 * Firstly, we'll check if jQuery is already enqueued to avoid conflicts
	 * from duplication.
	 *
	 * @wp-hook	void
	 * @uses		includes_url(), wp_script_is()
	 * @since		2.0.0
	 * @return 	string $out
	 */
	function load_jquery() {

		if ( ! wp_script_is( 'jquery' ) ) {

			if ( $this->register_scripts->is_jquery( 'wordpress' ) ) {

				$out  = "<script type='text/javascript' src='" . includes_url() . "js/jquery/jquery.js'></script>\n";
				$out .= "<script type='text/javascript' src='" . includes_url() . "js/jquery/jquery-migrate.min.js'></script>\n";

			}

			if ( $this->register_scripts->is_jquery( 'google' ) ) {

				$out  = "<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/" . $this->jquery_version . "/jquery.min.js?ver=" . $this->jquery_version . "'></script>\n";
				$out .= "<script type='text/javascript' src='http://code.jquery.com/jquery-migrate-" . $this->jquery_migrate_version . ".min.js?ver=" . $this->jquery_migrate_version . "'></script>\n";

				/**
				 * Run the jQuery.noConflict() method.
				 *
				 * Inserts an inline call to jQuery's noConflict() method on the
				 * front-end when the Google CDN version of jQuery is being used.
				 * This prevents conflicts with other JavaScript libraries such as
				 * prototype.js, MooTools, or YUI. The built-in WordPress version
				 * calls this itself at the bottom of the file.
				 */
				$out .= "<script type='text/javascript'>jQuery.noConflict();</script>\n";

			}

		}

		return $out;

	}

	/**
	 * Load our lightbox scripts for NextGEN's gallery widget.
	 *
	 * Only run if the lightbox scripts are not already enqueued.
	 *
	 * @wp-hook	void
	 * @uses		includes_url(), wp_script_is(), wp_style_is()
	 * @since		2.0.0
	 * @return 	string $out
	 */
	function load_lightbox_scripts() {

		if ( $this->register_scripts->is_lightbox( 'fancybox' ) ) {

			if ( ! wp_script_is( $this->prefix . '-fancybox' ) && ! wp_style_is( $this->prefix . '-fancybox' ) ) {

				$out  = "<link rel='stylesheet' id='" . $this->prefix . "-fancybox-css'  href='" . $this->plugin_dir_url . "fancybox/" . $this->prefix . ".jquery.fancybox-" . $this->fancybox_version . ".css?ver=" . $this->version . "' type='text/css' media='screen' />\n";
				$out .= "<script type='text/javascript' src='" . $this->plugin_dir_url . "fancybox/" . $this->prefix . ".jquery.fancybox-" . $this->fancybox_version . ".pack.js?ver=" . $this->version . "'></script>\n";

			}

		}

		if ( $this->register_scripts->is_lightbox( 'shutter' ) ) {

			if ( ! wp_script_is( $this->prefix . '-shutter-reloaded' ) && ! wp_style_is( $this->prefix . '-shutter-reloaded' ) ) {

				$out  = "<script type='text/javascript'>\n";
				$out .= "/* <![CDATA[ */\n";
				$out .= 'var shutterSettings = { "msgLoading": "L O A D I N G", "msgClose": "Click to Close", "imageCount": "1" };' . "\n";
				$out .= "/* ]]> */\n";
				$out .= "</script>";
				$out .= "\n";

				$out .= "<link rel='stylesheet' id='" . $this->prefix . "-shutter-reloaded-css'  href='" . $this->register_scripts->get_nextgen_dir_url() . "shutter/shutter-reloaded.css' type='text/css' media='screen' />\n";
				$out .= "<script type='text/javascript' src='" . $this->register_scripts->get_nextgen_dir_url() . "shutter/shutter-reloaded.js'></script>\n";

			}

		}

		if ( $this->register_scripts->is_lightbox( 'thickbox' ) ) {

			if ( ! wp_script_is( 'thickbox' ) && ! wp_style_is( $this->prefix . '-thickbox' )) {

				$url = includes_url( "/js/thickbox/loadingAnimation.gif" , dirname( __FILE__ ) );
				$url = str_replace( '/', '\/', $url );

				$out  = "<script type='text/javascript'>\n";
				$out .= "/* <![CDATA[ */\n";
				$out .= 'var thickboxL10n = {"next":"Next >","prev":"< Prev","image":"Image","of":"of","close":"Close","noiframes":"This feature requires inline frames. You have iframes disabled or your browser does not support them.",';
				$out .= '"loadingAnimation":"' . $url . '"};' . "\n";
				$out .= "/* ]]> */\n";
				$out .= "</script>\n";

				$out .= "<link rel='stylesheet' id='thickbox-css' href='" . includes_url( "/js/thickbox/thickbox.css" , dirname( __FILE__ ) ) . "' type='text/css' media='screen' />\n";
				$out .= "<script type='text/javascript' src='" . includes_url( "/js/thickbox/thickbox.js" , dirname( __FILE__ ) ) . "'></script>\n";

			}

		}

		return $out;

	}

	/**
	 * Load our slideshow scripts for NextGEN's slideshow widget.
	 *
	 * Only run if the slideshow scripts are not already enqueued.
	 *
	 * @wp-hook	void
	 * @uses		includes_url(), wp_script_is()
	 * @since		2.0.0
	 * @return 	string $out
	 */
	function load_slideshow_scripts() {

		if ( $this->register_scripts->is_slideshow( 'default_slideshow' ) ) {

			if ( ! wp_script_is( $this->prefix . '-jquery-cycle' ) && ! wp_script_is( $this->prefix . '-ngg-slideshow' ) ) {

				$out  = "<script type='text/javascript' src='" . $this->register_scripts->get_nextgen_dir_url() . "js/jquery.cycle.all.min.js'></script>" . "\n";
				$out .= "<script type='text/javascript' src='" . $this->register_scripts->get_nextgen_dir_url() . "js/ngg.slideshow.min.js'></script>" . "\n";

			}

		}


		if ( $this->register_scripts->is_slideshow( 'jr_image_rotator' ) ) {

			if ( ! wp_script_is( 'swfobject' ) ) {

				$out = "<script type='text/javascript' src='" . includes_url( "/js/swfobject.js" , dirname( __FILE__ ) ) . "'></script>\n";

			}

		}

		return $out;

	}

}