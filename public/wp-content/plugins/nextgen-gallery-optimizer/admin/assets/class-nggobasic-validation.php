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
 * NGGOBASIC_Validation
 *
 * Provides settings validation for our main settings page.
 *
 * Developers can work with this instance by calling
 * NGGOBASIC_Validation::get_instance().
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.0.0
 */
class NGGOBASIC_Validation {

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
		$this->prefix = $vars['prefix'];
		$this->db_name = $vars['db_name'];
		$this->nextgen_v1 = $vars['nextgen_v1'];
		$this->nextgen_v2 = $vars['nextgen_v2'];
		$this->settings_slug = $vars['settings_slug'];
		$this->plugin_dir_path = $vars['plugin_dir_path'];

	}

	/**
	 * Controller method for validating our settings.
	 *
	 * @wp-hook	void
	 * @see			register_setting()
	 * @param	$input Required array of new settings to validate.
	 * @since		2.0.0
	 * @return 	array $db|$input
	 */
	function validate_settings( $input ) {

		/**
		 * Get a fresh copy of our database settings array (to avoid working
		 * with stale data).
		 *
		 * We'll update this array with our new input data during validation.
		 *
		 * This approach has two advantages...
		 *
		 * 1. It ensures all existing settings not submitted with the form
		 * remain in our database settings array.
		 *
		 * 2. Our database settings array maintains its original structure at
		 * all times.
		 */
		$db = get_option( $this->db_name );

		/**
		 * Validate our main settings page if applicable.
		 */
		if ( isset( $input[$this->prefix . '-settings-form'] ) ) {

			$this->sync_fancybox_checkbox( $input );
			$db = $this->validate_settings_page( $input, $db );
			return $db; // Return our updated database settings array.

		}

		return $input; // Important. Return original $input on plugin activation and update_option() calls.

	}

	/**
	 * Validate and sanitize settings submitted from Optimizer's main settings
	 * page.
	 *
	 * @wp-hook	void
	 * @param	$input Required array of new settings to validate.
	 * @param	$db Required array of existing settings to modify.
	 * @since		2.0.0
	 * @return 	array $db
	 */
	function validate_settings_page( $input, $db ) {

		if ( $this->nextgen_v1 ) {

			$db = $this->validate_nextgen_v1_settings( $input, $db );

		}

		if ( $this->nextgen_v2 ) {

			$db = $this->validate_nextgen_v2_settings( $input, $db );

		}

		// Validate our checkbox (must be either 1 or 0). Key doesn't exist when checkbox is unchecked.
		$db['fancybox'] = ( $input['fancybox'] == 1 ? 1 : 0 );

		return $db;

	}

	/**
	 * Validate the settings fields exclusive to NextGEN Legacy and
	 * NextCellent Gallery.
	 *
	 * Most of this is for the custom css URL, which must be in the correct
	 * format to load the stylesheet correctly on the front end.
	 *
	 * We strip the path to the wp-content directory (as we add it later
	 * dynamically), validate the file extension (css or css.gzip), and finally,
	 * check to see if the file exists.
	 *
	 * @wp-hook	void
	 * @uses		wp_filter_nohtml_kses(), add_settings_error(), content_url()
	 * @uses		is_wp_error(), wp_remote_get(), wp_remote_retrieve_response_code()
	 * @param	$input Required array of new settings to validate.
	 * @param	$db Required array of existing settings to modify.
	 * @since		2.0.0
	 * @return 	array $db
	 */
	function validate_nextgen_v1_settings( $input, $db ) {

		// Validate theme drop-down menu (use "Default Styles" if an invalid value is present)
		$matches = array( 'None', 'Black Minimalism Theme', 'Default Styles', 'Dkret3 Theme', 'Hovereffect Styles', 'K2 Theme', 'Shadow Effect', 'Shadow Effect with Description Text' );

		if ( in_array( $input['theme'], $matches ) ) {

			$db['theme'] = $input['theme'];

		} else {

			$db['theme'] = 'Default Styles';

		}

		// Sanitize and validate css file path
		if ( null != $input['css'] ) {

			// Get content directory path and escape regex special characters
			$content_dir = str_replace( array( 'http://', 'https://', 'www.' ), '', content_url() );
			$content_dir = preg_quote( $content_dir, '/' );

			// Remove any href tags, quotes and version parameters that nohtml_kses ignores (handy for users copy/pasting their css link from the source)
			// Also remove the protocol, "www.", domain and path to the wp-content directory, as we're adding it dynamically for portability
			$pattern = '/(<\s*a\s*href\s*=\s*|href\s*=|"|\'|\s|https?:\/\/|www\.|' . $content_dir . '|\?\s*ver\s*=.*)/';

			$db['css'] = preg_replace( $pattern, '', $input['css'] );

			// Sanitize input and remove all remaining html tags
			$db['css'] = wp_filter_nohtml_kses( $db['css'] );

			// Trim any leading/trailing forward slashes
			$db['css'] = trim( $db['css'], '/' );

			// Basic validation to ensure the .css suffix is present (with optional .gzip compression)
			if ( ! preg_match( '/.*(css|css.gzip)$/', $db['css']) ) {

				$stylesheet_error = true;
				add_settings_error( $this->settings_slug, 'invalid-file-extension', '<font style="font-weight:bold !important;">Your custom stylesheet URL appears to have an invalid file extension</font><br /><font style="font-weight:normal !important;">Please check the following address and ensure the filename ends with either ".css" or ".css.gzip" (if compressed)...<br /><i><a href="' . content_url() . '/' . $db['css'] . '" target="_blank">' . content_url() . '/' . $db['css'] . '</a></i></font>' );

			} else {

				// Check to see if file exists using the WordPress HTTP API

				$check_headers_url = content_url() . '/' . $db['css'];

				$response = wp_remote_get( esc_url_raw( $check_headers_url ) );

				if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) == '404' ) {

					$stylesheet_error = true;
					add_settings_error( $this->settings_slug, 'file-not-found', '<font style="font-weight:bold !important;">A 404 Not Found error was returned for your custom stylesheet URL</font><br /><font style="font-weight:normal !important;">Please check the following address, or select a built-in stylesheet from the drop-down menu in the "Advanced options" section below...<br /><i><a href="' . content_url() . '/' . $db['css'] . '" target="_blank">' . content_url() . '/' . $db['css'] . '</a></i></font>' );

				}

			}

			if ( $stylesheet_error ) {

				$db['css'] = ''; // On error, empty the custom css field and select the default stylesheet if not set.

				if ( $db['theme'] == 'None' ) {

					$db['theme'] = 'Default Styles';

				}

			} else {

				$db['theme'] = 'None'; // On success, we're using the custom stylesheet, so set the theme to "None".

			}

		}

		// Validate jQuery radio buttons (must be either "google" or "wordpress").
		$db['jquery'] = ( $input['jquery'] == 'google' ? 'google' : 'wordpress' );

		return $db;

	}

	/**
	 * Validate the settings fields exclusive to NextGEN 2.0.
	 *
	 * @wp-hook	void
	 * @param	$input Required array of new settings to validate.
	 * @param	$db Required array of existing settings to modify.
	 * @since		2.0.0
	 * @return 	array $db
	 */
	function validate_nextgen_v2_settings( $input, $db ) {

		$db['optimize_front_end'] = ( $input['optimize_front_end'] == 1 ? 1 : 0 );
		$db['optimize_admin'] = ( $input['optimize_admin'] == 1 ? 1 : 0 );

		// Advanced options.
		$db['remove_lightbox_context_js'] = ( $input['remove_lightbox_context_js'] == 1 ? 1 : 0 );
		$db['remove_common_js_and_cdata'] = ( $input['remove_common_js_and_cdata'] == 1 ? 1 : 0 );
		$db['remove_photocrati_ajax_js_and_cdata'] = ( $input['remove_photocrati_ajax_js_and_cdata'] == 1 ? 1 : 0 );
		$db['remove_lazy_resources_js'] = ( $input['remove_lazy_resources_js'] == 1 ? 1 : 0 );
		$db['remove_frame_event_publisher_js'] = ( $input['remove_frame_event_publisher_js'] == 1 ? 1 : 0 );
		$db['remove_ngg_store_js'] = ( $input['remove_ngg_store_js'] == 1 ? 1 : 0 );
		$db['remove_font_awesome_css'] = ( $input['remove_font_awesome_css'] == 1 ? 1 : 0 );
		$db['remove_nextgen_basic_thumbnails_js'] = ( $input['remove_nextgen_basic_thumbnails_js'] == 1 ? 1 : 0 );
		$db['remove_ngg_trigger_buttons_css'] = ( $input['remove_ngg_trigger_buttons_css'] == 1 ? 1 : 0 );
		$db['remove_related_images_css'] = ( $input['remove_related_images_css'] == 1 ? 1 : 0 );
		$db['remove_nextgen_pagination_css'] = ( $input['remove_nextgen_pagination_css'] == 1 ? 1 : 0 );
		$db['remove_custom_stylesheet'] = ( $input['remove_custom_stylesheet'] == 1 ? 1 : 0 );

		// Update NextGEN's settings to deactivate the custom stylesheet.
		if ( class_exists( 'C_NextGen_Settings' ) ) { $this->ngg_settings = C_NextGen_Settings::get_instance(); }

		// Make sure "ngg_settings" is indeed an object first, as it's only set if the "C_NextGen_Settings" class is loaded (which as of NextGEN v2.0.7x, doesn't occur during activation of other plugins).
		if ( is_object( $this->ngg_settings ) ) {

			if ( $db['remove_custom_stylesheet'] ) {

				$this->ngg_settings->activateCSS = '0';
				$this->ngg_settings->save();

			} else {

				$this->ngg_settings->activateCSS = '1';
				$this->ngg_settings->save();

			}

		}

		/**
		 * Set our "optimize_gallery_pages" database setting.
		 *
		 * Used to detect if any of our *gallery* page optimization options
		 * are selected, so we can conditionally load the front-end class
		 * in Optimizer's main plugin file.
		 */
		if ( $db['remove_lightbox_context_js'] ||
		$db['remove_common_js_and_cdata'] ||
		$db['remove_photocrati_ajax_js_and_cdata'] ||
		$db['remove_lazy_resources_js'] ||
		$db['remove_frame_event_publisher_js'] ||
		$db['remove_ngg_store_js'] ||
		$db['remove_font_awesome_css'] ||
		$db['remove_nextgen_basic_thumbnails_js'] ||
		$db['remove_ngg_trigger_buttons_css'] ||
		$db['remove_related_images_css'] ||
		$db['remove_nextgen_pagination_css'] ) {

			$db['optimize_gallery_pages'] = 1;

		} else {

			$db['optimize_gallery_pages'] = 0;

		}

		return $db;

	}

	/**
	 * Synchronize our Fancybox status (on or off) with NextGEN's Lightbox
	 * Effects.
	 *
	 * @wp-hook	void
	 * @param	$input Required array of new settings.
	 * @since		2.0.0
	 * @return 	void
	 */
	function sync_fancybox_checkbox( $input ) {

		if ( $this->nextgen_v1 ) {

			// Sync Fancybox selection with lightbox selection at Gallery --> Options --> Effects.
			require_once( $this->plugin_dir_path . 'admin/assets/install/class-' . $this->prefix . '-fancybox-installer-nextgen-v1.php' );
			$install = NGGOBASIC_Fancybox_Installer_NextGEN_V1::get_instance();
			$install->init();
			$install->sync_fancybox_checkbox( $input );

		}

		if ( $this->nextgen_v2 ) {

			// Sync Fancybox selection with lightbox selection at Gallery --> Other Options --> Lightbox Effects.
			require_once( $this->plugin_dir_path . 'admin/assets/install/class-' . $this->prefix . '-fancybox-installer-nextgen-v2.php' );
			$install = NGGOBASIC_Fancybox_Installer_NextGEN_V2::get_instance();
			$install->init();
			$install->sync_fancybox_checkbox( $input );

		}

	}

}