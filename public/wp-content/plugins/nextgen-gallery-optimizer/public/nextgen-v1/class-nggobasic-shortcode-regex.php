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
 * NGGOBASIC_Shortcode_Regex
 *
 * Detects the presence of NextGEN Gallery shortcodes in posts, pages and
 * multi-post pages, and conditionally loads the appropriate scripts and styles
 * as required (instead of sitewide).
 *
 * This is called via our NGGOBASIC_Optimize_NextGEN_V1 class, and only loads
 * on front-end requests (when NextGEN Legacy is installed).
 *
 * Developers can work with this instance by calling
 * NGGOBASIC_Shortcode_Regex::get_instance().
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.0.0
 */
class NGGOBASIC_Shortcode_Regex {

 	/**
	 * Array of all detected NextGEN shortcodes.
	 *
	 * @since	2.0.0
	 * @var		array
	 */
	public $shortcodes;

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

		// Access our methods in NGGOBASIC_Register_Scripts.
		$this->register_scripts = NGGOBASIC_Register_Scripts::get_instance();

		$this->shortcodes = array(

			"nggallery" => false,
			"album" => false,
			"slideshow" => false,
			"nggtags_album" => false,
			"nggtags_gallery" => false,
			"imagebrowser" => false,
			"singlepic" => false,
			"thumb" => false,
			"random" => false,
			"recent" => false,
			"tagcloud" => false,
			"singlepic_link_attribute" => false

		);

		add_action( 'wp', array( $this, 'get_shortcode_regex' ) );
		add_action( 'wp', array( $this, 'check_nggallery_shortcode' ) );
		add_action( 'wp', array( $this, 'check_nextgen_shortcodes' ), 1000);
		add_action( 'get_header', array( $this, 'check_album_gallery_page' ) );
		add_action( 'get_header', array( $this, 'is_show_first_slideshow_setting' ) );
		add_action( 'get_header', array( $this, 'is_show_as_slideshow_page' ) );

	}

	/**
	 * Main shortcode regex to detect all eleven NextGEN shortcodes, including
	 * [nggalbum id=x] for NextGEN v.1.9.12.
	 *
	 * We're storing all matches in an array, so we can load all required scripts
	 * and styles on multi-post pages that may contain more than one shortcode.
	 *
	 * Now also searches for the direct URL "link" attribute in the [singlepic id=x]
	 * shortcode, and if found, we'll omit any selected lightbox scripts so the
	 * URL can be opened in the current browser window. Note: This must be used
	 * exclusively on a given page as other gallery shortcodes may override
	 * lightbox exclusion.
	 *
	 * @wp-hook	wp
	 * @uses		get_shortcode_regex()
	 * @global		$wp_query
	 * @since		2.0.0
	 * @return		void
	 */
	function get_shortcode_regex() {

		global $wp_query;

		$posts = $wp_query->posts;
		$pattern = get_shortcode_regex();

		foreach ( $posts as $post ) {

			/**
			 * Get all shortcodes in posts and pages using the built-in
			 * WordPress shortcode regex.
			 */
			preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches );

			if ( array_key_exists( 2, $matches ) ) {

				if ( in_array( 'nggallery', $matches[2] ) ) {

					$this->shortcodes['nggallery'] = true;

				}

				if ( in_array( 'album', $matches[2] ) || in_array( 'nggalbum', $matches[2] ) ) {

					$this->shortcodes['album'] = true;

				}

				if ( in_array( 'slideshow', $matches[2] ) ) {

					$this->shortcodes['slideshow'] = true;

				}

				if ( in_array( 'imagebrowser', $matches[2] ) ) {

					$this->shortcodes['imagebrowser'] = true;

				}

				if ( in_array( 'singlepic', $matches[2] ) ) {

					$this->shortcodes['singlepic'] = true;

				}

				if ( in_array( 'thumb', $matches[2] ) ) {

					$this->shortcodes['thumb'] = true;

				}

				if ( in_array( 'random', $matches[2] ) ) {

					$this->shortcodes['random'] = true;

				}

				if ( in_array( 'recent', $matches[2] ) ) {

					$this->shortcodes['recent'] = true;

				}

				if ( in_array( 'tagcloud', $matches[2] ) ) {

					$this->shortcodes['tagcloud'] = true;

				}

				/**
				 * Check if the posts loop contains the [nggtags album=tag] or
				 * [nggtags gallery=tag] shortcodes.
				 */
				if ( ( in_array( 'nggtags', $matches[2] ) ) && isset( $matches[0] ) ) {

					foreach ( $matches[0] as $match ) {

						if ( strpos( $match, 'nggtags album' ) ) {

							$this->shortcodes['nggtags_album'] = true;

						}

						if ( strpos( $match, 'nggtags gallery' ) ) {

							$this->shortcodes['nggtags_gallery'] = true;

						}

					}

				}

				/**
				 * Search for the "link" attribute in the singlepic shortcode.
				 * If present we don't want to load any lightbox scripts or styles.
				 * We're using preg_grep here as it's able to accept multidimensional arrays (returned from the WordPress shortcode regex)
				 * and match a pattern (rather than an exact needle).
				 */
				if ( in_array( 'singlepic', $matches[2] ) ) {

					if ( preg_grep( '/(?=.*\[singlepic)(?=.*link\s*=).*/', $matches[0] ) ) {

						$this->shortcodes['singlepic_link_attribute'] = true;

					}

				}

			}

		}

	}

	/**
	 * Check if NextGEN's URL query string parameter "show" is set, and whether
	 * it has a value of "gallery" or "slide" (if this optional parameter is
	 * provided in the function call).
	 *
	 * Also detects the corresponding permalink suffixes of "/images/ or
	 * "/slideshow/" if "Activate permalinks" is enabled at Gallery --> Options -->
	 * General Options.
	 *
	 * @wp-hook	void
	 * @uses		wp_query
	 * @global		wp_query
	 * @param	string|null $val Optional value to check.
	 * @since		2.0.0
	 * @return 	boolean
	 */
	function is_show_parameter( $val = null ) {

		global $wp_query;

		if ( isset( $wp_query->query_vars['show'] ) && $wp_query->query_vars['show'] == $val ||
		isset( $wp_query->query_vars['show'] ) && null == $val ) {

			return true;

		} else {

			return false;

		}

	}

	/**
	 * Check if NextGEN's URL query string parameter "album", "gallerytags" or
	 * "pid" are set.
	 *
	 * This lets us know if we're on an [album id=x], [nggtags album=x] or
	 * [tagcloud] *gallery* page, or in the case of "pid", a gallery sub-page
	 * created by the "Show ImageBrowser" option at Gallery -> Options ->
	 * Gallery.
	 *
	 * Also detects the corresponding permalink conversions, if "Activate permalinks"
	 * is enabled at Gallery --> Options --> General Options.
	 *
	 * @wp-hook	void
	 * @uses		query_vars
	 * @global		wp_query
	 * @param	string $val Required value to check.
	 * @since		2.0.0
	 * @return 	boolean
	 */
	function is_gallery_page( $val ) {

		global $wp_query;

		if ( isset( $wp_query->query_vars[ $val ] ) ) {

			return true;

		} else {

			return false;

		}

	}

	/**
	 * Check if NextGEN is set to show either slideshows or galleries first at
	 * Gallery --> Options --> Gallery --> "Show first".
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @global		$ngg
	 * @param	string $val Required value to check.
	 * @since		2.0.0
	 * @return 	void|true
	 */
	function is_show_first( $val ) {

		global $ngg;

		if ( isset( $ngg->options['galShowOrder'] ) && ( $ngg->options['galShowOrder'] == $val ) ) {

			return true;

		}

	}

	/**
	 * Check if NextGEN's "Enable AJAX pagination" is selected at Gallery -->
	 * Options --> Gallery, and whether "Number of images per page" is set to
	 * a value higher than 0 (as galImages = 0 disables pagination).
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @global		$ngg
	 * @since		2.0.0
	 * @return 	void|true
	 */
	function is_ajax_pagination() {

		global $ngg;

		if ( $ngg->options['galAjaxNav'] && isset( $ngg->options['galImages'] ) && $ngg->options['galImages'] > '0' ) {

			return true;

		}

	}

	/**
	 * Check if NextGEN's "Activate PicLens/CoolIris support" is selected at
	 * Gallery --> Options --> General Options.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @global		$ngg
	 * @since		2.0.0
	 * @return 	void|true
	 */
	function is_piclens() {

		global $ngg;

		if ( isset( $ngg->options['usePicLens'] ) && ( true == $ngg->options['usePicLens'] ) ) {

			return true;

		}

	}

	/**
	 * Check if the page contains the [nggallery id=x] shortcode, and whether
	 * "Show first --> Slideshow" is activated at Gallery --> Options --> Gallery.
	 *
	 * @wp-hook	wp
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function check_nggallery_shortcode() {

		if ( $this->shortcodes['nggallery'] ) {

			if ( $this->is_show_first( 'gallery' ) || $this->is_show_first( 'slide' ) && $this->is_show_parameter( 'gallery' ) ) {

				$this->register_scripts->add_lightbox_scripts_and_styles();

				if ( $this->is_ajax_pagination() ) {

					$this->register_scripts->add_ajax_pagination_scripts();

				}

				/**
				 * Check if Piclens is activated, and that we're not on a
				 * gallery sub-page created by the "Show ImageBrowser" option.
				 */
				if ( $this->is_piclens() && ! $this->is_gallery_page( 'pid' ) ) {

					$this->register_scripts->add_piclens_js();

				}

			}

		}

	}

	/**
	 * Check for [album id=x] or [nggalbum id=x] shortcode GALLERY page.
	 * Check if show first --> slideshow is activated at gallery --> options --> gallery.
	 *
	 * @wp-hook	get_header
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function check_album_gallery_page() {

		if ( $this->shortcodes['album'] ) {

			if ( $this->is_gallery_page( 'album' ) ) {

				if ( $this->is_show_first( 'gallery' ) || $this->is_show_first( 'slide' ) && $this->is_show_parameter( 'gallery' ) ) {

					if ( ! $this->is_show_parameter() || $this->is_show_parameter( 'gallery' ) ) {

						$this->register_scripts->add_lightbox_scripts_and_styles();

						if ( $this->is_ajax_pagination() ) {

							$this->register_scripts->add_ajax_pagination_scripts();

						}

						/**
						 * Check if Piclens is activated, and that we're not on a
						 * gallery sub-page created by the "Show ImageBrowser" option.
						 */
						if ( $this->is_piclens() && ! $this->is_gallery_page( 'pid' ) ) {

							$this->register_scripts->add_piclens_js();

						}

					}

				}

			}

		}

	}

	/**
	 * Add scripts and styles for NextGEN's other shortcodes and sub-pages.
	 *
	 * @wp-hook	wp
	 * @uses		add_action()
	 * @since		2.0.0
	 * @return 	void
	 */
	function check_nextgen_shortcodes() {

		if ( $this->shortcodes['slideshow'] ) {

			$this->register_scripts->add_slideshow_scripts_and_styles();

		}

		if ( $this->shortcodes['imagebrowser'] ) {

			$this->register_scripts->add_lightbox_scripts_and_styles();

			if ( $this->is_ajax_pagination() ) {

				$this->register_scripts->add_ajax_pagination_scripts();

			}

		}

		/**
		 * Check for singlepic (WITHOUT link attribute), tagcloud (GALLERY page),
		 * nggtags album (GALLERY page), nggtags gallery, thumb, random or recent
		 * shortcodes.
		 */
		if ( $this->shortcodes['singlepic'] && ! $this->shortcodes['singlepic_link_attribute'] ||
		$this->shortcodes['tagcloud'] && $this->is_gallery_page( 'gallerytag' ) ||
		$this->shortcodes['nggtags_album'] && $this->is_gallery_page( 'gallerytag' ) ||
		$this->shortcodes['nggtags_gallery'] ||
		$this->shortcodes['thumb'] ||
		$this->shortcodes['random'] ||
		$this->shortcodes['recent'] ) {

			$this->register_scripts->add_lightbox_scripts_and_styles();

		}

		/**
		 * Check for singlepic (WITH link attribute), nggtags album (MAIN page),
		 * tagcloud (MAIN page) or album (MAIN page).
		 */
		if ( $this->shortcodes['singlepic'] && $this->shortcodes['singlepic_link_attribute'] ||
		$this->shortcodes['nggtags_album'] && ! $this->is_gallery_page( 'gallerytag' ) ||
		$this->shortcodes['tagcloud'] ||
		$this->shortcodes['album'] ) {

			add_action( 'wp_print_styles', array( $this->register_scripts, 'load_nextgen_styles' ), 1000 );

		}

	}

	/**
	 * Add support for NextGEN's "Show first --> Slideshow" option
	 *
	 * Adds slideshow scripts on [nggallery id=x] MAIN page and [album id=x]
	 * GALLERY page...but only if "Show first --> Slideshow" is activated at
	 * Gallery --> Options --> Gallery.
	 *
	 * @wp-hook	get_header
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function is_show_first_slideshow_setting() {

		if (

		/**
		 * If we're on an [nggallery id=x] *main* page with "Show first:
		 * Slideshow" selected.
		 */
		$this->shortcodes['nggallery'] &&
		$this->is_show_first( 'slide' ) &&
		! $this->is_show_parameter() ||

		/**
		 * If we're on an [album|nggalbum id=x] *gallery* page with "Show first:
		 * Slideshow" selected.
		 */
		$this->shortcodes['album'] &&
		$this->is_show_first( 'slide' ) &&
		$this->is_gallery_page( 'album' ) &&
		! $this->is_show_parameter()

		) {

			$this->register_scripts->remove_lightbox_scripts_and_styles();
			$this->register_scripts->remove_ajax_pagination_scripts();
			$this->register_scripts->add_slideshow_scripts_and_styles();

		}

	}

	/**
	 * Add slideshow scripts, and remove lightbox scripts any time we have a
	 * query string parameter of "show=slide" or a permalink suffix of
	 * "/slideshow/".
	 *
	 * @wp-hook	get_header
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function is_show_as_slideshow_page() {

		if ( $this->is_show_parameter( 'slide' ) ) {

			$this->register_scripts->remove_lightbox_scripts_and_styles();
			$this->register_scripts->remove_ajax_pagination_scripts();
			$this->register_scripts->add_slideshow_scripts_and_styles();

		}

	}

}