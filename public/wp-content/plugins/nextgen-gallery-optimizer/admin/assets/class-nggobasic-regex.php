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
 * NGGOBASIC_Regex
 *
 * A collection of regex operations performed by Optimizer in the admin,
 * including the updating of Fancybox's CSS and JS files, as well as a theme
 * file search to detect if jQuery's being deregistered.
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.0.0
 */
 class NGGOBASIC_Regex {

 	/**
	 * Get the file path to Fancybox's stylesheet.
	 *
	 * @since	2.0.0
	 * @var		string
	 * @see		init()
	 */
	protected $fancybox_css_path;

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
		$this->version = $vars['version'];
		$this->fancybox_version = $vars['fancybox_version'];
		$this->plugin_dir_url = $vars['plugin_dir_url'];
		$this->plugin_dir_path = $vars['plugin_dir_path'];
		$this->settings_slug = $vars['settings_slug'];

		// Retrieve required properties from our main admin class.
		$this->admin = NGGOBASIC_Admin::get_instance();
		$vars = $this->admin->get_vars();

		// Get our settings instance.
		$this->settings = NGGOBASIC_Settings::get_instance();

		// Define our Fancybox CSS filepath.
		$this->fancybox_css_path = $this->plugin_dir_path . "fancybox/" . $this->prefix . ".jquery.fancybox-" . $this->fancybox_version . ".css";

		// Add our action hooks.
		add_action( 'admin_notices', array( $this, 'check_for_deregister_jquery_regex' ) );
		add_action( 'admin_notices', array( $this, 'fancybox_regex_error_message' ) );

	}

	/**
	 * Check to make sure jQuery isn't being deregistered.
	 *
	 * We'll run a regex on the parent and child theme's functions.php files
	 * for variants of "wp_deregister_script('jquery');".
	 * If detected (and not re-registered with a CDN version), we'll alert the
	 * user via an admin message.
	 *
	 * Note: If a user deregisters jQuery in functions.php, that will also
	 * deregister it for other scripts that call jQuery via dependency
	 * (even if they're called later in the file). Eg. the functions.js script in
	 * the default Twenty Thirteen theme. As a result, we only need to check
	 * for jQuery being explicitly enqueued (not loaded via script dependency)
	 * before showing a message.
	 *
	 * @wp-hook	admin_notices
	 * @uses		get_stylesheet_directory(), get_template_directory()
	 * @since		2.0.0
	 * @return		void
	 */
	function check_for_deregister_jquery_regex() {

		if ( $this->admin->is_settings_page() || $this->admin->is_plugins_page() ) {

			$child_functions_path = get_stylesheet_directory() . '/functions.php'; // Looks for a child theme first, and if not in use, returns path to parent theme.
			$parent_functions_path = get_template_directory() . '/functions.php'; // Gets file path to parent theme.
			$pattern = '/wp\_(deregister|register|enqueue)\_script\s*\(\s*(jquery|\'jquery\'|"jquery")(,|\s)*/';


			// Check the child theme's functions.php (if in use and if file exists.
			// If no child theme is in use, checks the parent theme's functions.php instead.

			if ( file_exists( $child_functions_path ) ) {

				$file = file_get_contents( $child_functions_path );

				if ( preg_match_all( $pattern, $file, $matches )
				&& array_key_exists( 1, $matches )
				&& ! in_array( 'register', $matches[1] )
				&& ! in_array( 'enqueue', $matches[1] ) ) {

					$child_error = true;

				}

			}

			// Check the parent theme's functions.php.
			// Only runs if get_stylesheet_directory() did not return the parent theme's path above.

			if ( file_exists( $parent_functions_path ) && ( $parent_functions_path != $child_functions_path ) ) {

				$file = file_get_contents( $parent_functions_path );

				if ( preg_match_all( $pattern, $file, $matches )
				&& array_key_exists( 1, $matches )
				&& ! in_array( 'register', $matches[1] )
				&& ! in_array( 'enqueue', $matches[1] ) ) {

					$parent_error = true;

				}

			}

			if ( isset( $child_error ) ) {

				$error_path = $child_functions_path;

			} else if ( isset( $parent_error ) ) {

				$error_path = $parent_functions_path;

			}

			if ( ( isset( $child_error ) ) || isset( $parent_error ) ) {

				echo '
				<div class="error"><p>
				<b>NextGEN Gallery Optimizer Basic Error Notification:</b><br />
				Your theme appears to be deregistering jQuery, which will likely prevent Optimizer\'s Fancybox lightbox from functioning.<br />
				To resolve this issue, please remove <b>wp_deregister_script(\'jquery\');</b> from <b><i>' . $error_path . '</i></b>.
				</p></div>
				';

			}

		}

	}

	/**
	 * Fix for Fancybox images on IE6 & IE8.
	 *
	 * Microsoft.AlphaImageLoader CSS requires absolute file paths,
	 * so we'll run a regex on first activation, after an update and on change
	 * of URL to write in the correct image URLs.
	 *
	 * @wp-hook	void
	 * @uses		plugins_url(), update_option()
	 * @since 		2.0.0 moved inside class and removed function name prefix
	 * @since 		2.0.0 added plugin_dir_url() check for devs moving from development servers to production
	 * @return		void|boolean
	 */
	function fancybox_stylesheet_regex() {

		// Run on first activation and update.
		if ( ! isset( $this->settings->version ) ||
		isset( $this->settings->version ) && ( $this->settings->version != $this->version ) ||

		// Run on change of url or plugin folder location.
		! isset( $this->settings->plugin_dir_url ) ||
		isset( $this->settings->plugin_dir_url ) && ( $this->settings->plugin_dir_url != $this->plugin_dir_url ) ) {

			// The file and rewrite paths.
			$new_image_path = $this->plugin_dir_url . "fancybox/";
			$file = file_get_contents( $this->fancybox_css_path );

			// The regex.
			$pattern = '/\(src=\'(.*?)fancybox\//';
			$replacement = '(src=\'' . $new_image_path;
			$update_css = preg_replace( $pattern, $replacement, $file );

			// Run our regex rewrite operation.
			if ( $this->fancybox_regex_rewrite_operation( $update_css, $this->fancybox_css_path ) ) {

				/**
				 * We only want to run this regex on first activation, after
				 * auto-update or on change of url, so we'll update the "version"
				 * and "plugin_dir_url" options we check against.
				 */
				$settings = NGGOBASIC_Settings::get_instance();
				$settings->version = $this->version;
				$settings->plugin_dir_url = $this->plugin_dir_url;
				$settings->save();

				return true;

			} else {

				/**
				 * Nullify our "version" and "plugin_dir_url" properties until
				 * the regex has run successfully.
				 */
				$settings = NGGOBASIC_Settings::get_instance();
				$settings->version = 'not_set';
				$settings->plugin_dir_url = 'not_set';
				$settings->save();

				return false;

			}

		}

	}

	/**
	 * File handling to write in absolute file paths in Fancybox's stylesheet
	 * for Microsoft.AlphaImageLoader filters.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @param	string $data
	 * @param	string $file
	 * @since 		2.0.0
	 * @return		boolean
	 */
	function fancybox_regex_rewrite_operation( $data, $file ) {

		if ( is_writable( $file ) ) {

			if ( ! $handle = fopen( $file, 'w+' ) ) {

				return false;
				exit;

			}

			if ( fwrite( $handle, $data ) === false ) {

				return false;
				exit;

			}

			fclose( $handle );

			return true;

		} else {

			return false;

		}

	}

	/**
	 * Detailed error message for our stylesheet regex operations.
	 *
	 * Displayed in the event of permissions issues where we can't write the
	 * files, to let users know of several ways they can correct the issue.
	 *
	 * @wp-hook	admin_notices
	 * @uses		get_admin_url()
	 * @since 		2.0.0
	 * @return		void
	 */
	function fancybox_regex_error_message() {

		if ( $this->admin->is_settings_page() || $this->admin->is_plugins_page() ) {

			// Run our Fancybox stylesheet regex.
			if ( $this->fancybox_stylesheet_regex() === false ) {
				$stylesheet_rewrite_error = true;
			}

		}

		// Show error message on our main settings page and the plugins page only.
		if ( $this->admin->is_settings_page() || $this->admin->is_plugins_page() ) {

			if ( isset( $stylesheet_rewrite_error ) ) {

				$out  = '<div class="error"><p>';
				$out .= '<b>NextGEN Gallery Optimizer Basic Error Notification:</b><br /><br />';
				$out .= 'Optimizer automatically customizes Fancybox\'s css to ensure the lightbox displays correctly across all browsers. However...<br />';
				$out .= '<b>Fancybox\'s stylesheet is not <i>writable!</i></b><br /><br />';
				$out .= 'To correct the above, please change permissions to <b>766</b> on the following file(s):<br>';
				$out .= '<b>' . $this->fancybox_css_path . '</b><br />';
				$out .= '<br />There are several ways to do this...<br />';
				$out .= '1. Right-click the file in your FTP client and select "Properties" or "Get Info".<br />';
				$out .= '2. If using shared-hosting, select the file in your web-based file manager and look for a "Change Permissions" link.<br />';
				$out .= '3. If you have SSH access, simply enter "sudo chmod 766" in your terminal, followed by the full file path above. Repeat for multiple files.<br />';
				$out .= '4. If you uploaded Optimizer via FTP, it often helps to simply delete & reinstall the plugin through your WordPress admin at ';
				$out .= '<a href="' . get_admin_url( '', 'plugin-install.php?tab=upload' ) . '">Plugins --> Add New --> Upload</a>.';
				$out .= '</p></div>';

				echo $out;

			}

		}

	}

}