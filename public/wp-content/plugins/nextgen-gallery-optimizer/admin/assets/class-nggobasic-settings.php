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
 * NGGOBASIC_Settings
 *
 * A controller that combines all of our save operations (post db installation)
 * into one database query, providing improved performance and finer control
 * over execution timing.
 *
 * Developers can work with this instance by calling
 * NGGOBASIC_Settings::get_instance().
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.0.0
 */
 class NGGOBASIC_Settings {

 	/**
	 * Store the new value of our "plugin_version" database setting.
	 *
	 * Used to detect plugin updates.
	 *
	 * @since	2.1
	 * @var		integer
	 */
	public $plugin_version;

	/**
	 * Store the new value of our "theme" database setting.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	public $theme;

	/**
	 * Store the new value of our "show_message" database setting.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	public $css;

	/**
	 * Store the new value of our "jquery" database setting.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	public $jquery;

	/**
	 * Store the new value of our "optimize_front_end" database setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $optimize_front_end;

	/**
	 * Store the new value of our "optimize_admin" database setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $optimize_admin;

	/**
	 * Store the new value of our "optimize_gallery_pages" database setting.
	 *
	 * Used to detect if any of our gallery page optimization options are
	 * selected, so we can conditionally load the front-end class in
	 * Optimizer's main plugin file.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $optimize_gallery_pages;

	/**
	 * Store the new value of our "remove_lightbox_context_js" database
	 * setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $remove_lightbox_context_js;

	/**
	 * Store the new value of our "remove_common_js_and_cdata" database
	 * setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $remove_common_js_and_cdata;

	/**
	 * Store the new value of our "remove_photocrati_ajax_js_and_cdata"
	 * database setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $remove_photocrati_ajax_js_and_cdata;

	/**
	 * Store the new value of our "remove_lazy_resources_js" database setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $remove_lazy_resources_js;

	/**
	 * Store the new value of our "remove_frame_event_publisher_js" database
	 * setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $remove_frame_event_publisher_js;

	/**
	 * Store the new value of our "remove_ngg_store_js" database setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $remove_ngg_store_js;

	/**
	 * Store the new value of our "remove_nextgen_basic_thumbnails_js" database
	 * setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $remove_nextgen_basic_thumbnails_js;

	/**
	 * Store the new value of our "remove_font_awesome_css" database setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $remove_font_awesome_css;

	/**
	 * Store the new value of our "remove_ngg_trigger_buttons_css" database
	 *setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $remove_ngg_trigger_buttons_css;

	/**
	 * Store the new value of our "remove_related_images_css" database setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $remove_related_images_css;

	/**
	 * Store the new value of our "remove_nextgen_pagination_css" database
	 * setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $remove_nextgen_pagination_css;

	/**
	 * Store the new value of our "remove_custom_stylesheet" database setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $remove_custom_stylesheet;

	/**
	 * Store the new value of our "fancybox" database setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $fancybox;

	/**
	 * Store the new value of our "show_message" database setting.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $show_message;

	/**
	 * Store the new value of our "version" database setting.
	 *
	 * @since	2.0.0
	 * @var		integer
	 */
	public $version;

	/**
	 * Store the new value of our "plugin_dir_url" database setting.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	public $plugin_dir_url;

	/**
	 * Store the new value of our "lightbox_plugin_dir_url" database setting.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	public $lightbox_plugin_dir_url;

	/**
	 * Store the new value of our "original_thumbEffect" database
	 * setting.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	public $original_thumbEffect;

	/**
	 * Store the new value of our "original_thumbCode" database
	 * setting.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	public $original_thumbCode;

	/**
	 * Store the new value of our "fancybox_installed" database
	 * flag.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $fancybox_installed;

	/**
	 * Store the new value of our "nextgen_legacy" database
	 * flag.
	 *
	 * @since	2.0.0
	 * @var		boolean
	 */
	public $nextgen_legacy;

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
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	public function init() {

		// Retrieve required properties from our main plugin class.
		$this->plugin = NextGEN_Gallery_Optimizer_Basic::get_instance();
		$vars = $this->plugin->get_vars();
		$this->db = $vars['db'];
		$this->db_name = $vars['db_name'];
		$this->prefix = $vars['prefix'];
		$this->version = $vars['version'];
		$this->plugin_dir_url = $vars['plugin_dir_url'];
		$this->plugin_dir_path = $vars['plugin_dir_path'];
		$this->nextgen_v1 = $vars['nextgen_v1'];
		$this->nextgen_v2 = $vars['nextgen_v2'];

		// Retrieve required properties from our main admin class.
		$this->admin = NGGOBASIC_Admin::get_instance();
		$vars = $this->admin->get_vars();
		$this->plugin_dir_file = $vars['plugin_dir_file'];

		// Run our settings methods.
		$this->get_validation();
		$this->register_setting();
		$this->install();
		$this->set_settings();

	}

	/**
	 * Register our database setting in the wp-options table, and include a
	 * pointer to our validation callback in the NGGOBASIC_Validation class.
	 *
	 * @wp-hook	admin_init
	 * @uses		register_setting()
	 * @since		2.0.0
	 * @return		void
	 */
	function register_setting() {

		register_setting( $this->db_name . '_group', $this->db_name, array( NGGOBASIC_Validation::get_instance(), 'validate_settings') );

	}

	/**
	 * Get our settings validation class.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function get_validation() {

		// Include our settings validation class.
		require_once( $this->plugin_dir_path . 'admin/assets/class-' . $this->prefix . '-validation.php' );
		$this->validation = NGGOBASIC_Validation::get_instance();
		$this->validation->init();

	}

	/**
	 * Set up default Optimizer settings array.
	 * We're checking and adding each option individually, to avoid overwriting
	 * current users' settings on update.
	 *
	 * We're calling this via our NGGOBASIC_Admin class on plugins_loaded, as
	 * register_activation_hook() doesn't fire on automatic updates as of
	 * WordPress v3.1.
	 * See: http://make.wordpress.org/core/2010/10/27/plugin-activation-hooks-no-longer-fire-for-updates/
	 *
	 * @wp-hook	void
	 * @uses		update_option()
	 * @see			NGGOBASIC_Admin class
	 * @since		2.0.0
	 * @return		void
	 */
	function install() {

		// Only fire on initial activation and update.
		if ( ! is_array( $this->db ) || ! isset( $this->db['version'] ) || $this->db['version'] != $this->version ) {

			$old_db = get_option( 'nextgen_optimizer_settings' ); // Returns false if it doesn't exist.

			// Restore some existing data on update to Optimizer 2.0.
			if ( $old_db ) {

				$this->db['theme'] = $old_db['theme'];
				$this->db['css'] = $old_db['css'];
				$this->db['jquery'] = $old_db['jquery'];
				$this->db['show_message'] = false;
				$this->db['original_thumbEffect'] == $old_db['original_nextgen_thumbEffect'];
				$this->db['original_thumbCode'] == $old_db['original_nextgen_thumbCode'];
				if ( 'uninstalled' == $old_db['auto_fancybox_install'] ) { $this->db['fancybox'] = false; } // Fancybox used to be unset from the array when not selected.

				// Remove our old settings array.
				delete_option( 'nextgen_optimizer_settings' );

			}

			// Used to detect new installations. Since v2.1.
			if ( ! is_array( $this->db ) ) 															{ $this->db['plugin_version'] = $this->version; }

			// Used to detect updates. Since v2.1.
			if ( ! isset( $this->db['plugin_version'] ) ) 										{ $this->db['plugin_version'] = 'not_set'; } // Updated on Optimizer's settings page (re: post-update admin notification).

			// NextGEN Legacy / NextCellent Gallery.
			if ( ! isset( $this->db['theme'] ) )													{ $this->db['theme'] = 'Default Styles'; }
			if ( ! isset( $this->db['css'] ) )														{ $this->db['css'] = ''; }
			if ( ! isset( $this->db['jquery'] ) )													{ $this->db['jquery'] = 'wordpress'; }

			// NextGEN 2.0.xx.
			if ( ! isset( $this->db['optimize_front_end'] ) )									{ $this->db['optimize_front_end'] = true; }
			if ( ! isset( $this->db['optimize_admin'] ) )										{ $this->db['optimize_admin'] = true; }

			// NextGEN 2.0.xx advanced options.
			if ( ! isset( $this->db['optimize_gallery_pages'] ) )							{ $this->db['optimize_gallery_pages'] = false; }
			if ( ! isset( $this->db['remove_lightbox_context_js'] ) )					{ $this->db['remove_lightbox_context_js'] = false; }
			if ( ! isset( $this->db['remove_common_js_and_cdata'] ) )				{ $this->db['remove_common_js_and_cdata'] = false; }
			if ( ! isset( $this->db['remove_photocrati_ajax_js_and_cdata'] ) )	{ $this->db['remove_photocrati_ajax_js_and_cdata'] = false; }
			if ( ! isset( $this->db['remove_lazy_resources_js'] ) )						{ $this->db['remove_lazy_resources_js'] = false; }
			if ( ! isset( $this->db['remove_frame_event_publisher_js'] ) )			{ $this->db['remove_frame_event_publisher_js'] = false; }
			if ( ! isset( $this->db['remove_ngg_store_js'] ) )								{ $this->db['remove_ngg_store_js'] = false; }
			if ( ! isset( $this->db['remove_nextgen_basic_thumbnails_js'] ) )		{ $this->db['remove_nextgen_basic_thumbnails_js'] = false; }
			if ( ! isset( $this->db['remove_font_awesome_css'] ) )					{ $this->db['remove_font_awesome_css'] = false; }
			if ( ! isset( $this->db['remove_ngg_trigger_buttons_css'] ) )			{ $this->db['remove_ngg_trigger_buttons_css'] = false; }
			if ( ! isset( $this->db['remove_related_images_css'] ) )					{ $this->db['remove_related_images_css'] = false; }
			if ( ! isset( $this->db['remove_nextgen_pagination_css'] ) )				{ $this->db['remove_nextgen_pagination_css'] = false; }
			if ( ! isset( $this->db['remove_custom_stylesheet'] ) )						{ $this->db['remove_custom_stylesheet'] = false; }

			// Shared.
			if ( ! isset( $this->db['fancybox'] ) )												{ $this->db['fancybox'] = true; }
			if ( ! isset( $this->db['show_message'] ) )										{ $this->db['show_message'] = true; }
			if ( ! isset( $this->db['version'] ) )													{ $this->db['version'] = 'not_set'; }
			if ( ! isset( $this->db['plugin_dir_url'] ) )											{ $this->db['plugin_dir_url'] = 'not_set'; }
			if ( ! isset( $this->db['lightbox_plugin_dir_url'] ) )							{ $this->db['lightbox_plugin_dir_url'] = $this->plugin_dir_url; }
			if ( ! isset( $this->db['original_thumbEffect'] ) )								{ $this->db['original_thumbEffect'] = 'none'; }
			if ( ! isset( $this->db['original_thumbCode'] ) )								{ $this->db['original_thumbCode'] = ''; }
			if ( ! isset( $this->db['fancybox_installed'] ) )									{ $this->db['fancybox_installed'] = false; }
			if ( ! isset( $this->db['nextgen_legacy'] ) )										{ $this->db['nextgen_legacy'] = null; }

			update_option( $this->db_name, $this->db );

		}

	}

	/**
	 * Save settings.
	 *
	 * This method can be called to save updated settings on the admin_footer
	 * hook, at the end of an admin page load.
	 *
	 * @wp-hook	void
	 * @uses		add_action()
	 * @since		2.0.0
	 * @return 	void
	 */
	public function save() {

		// Save our settings. Priority is important here to save last after all processing.
		add_action( 'admin_footer', array( $this, 'save_settings' ), 1000 );

	}

	/**
	 * Set settings.
	 *
	 * This method sets our class settings properties with their corresponding
	 * values in the database array.
	 *
	 * We can then check and update these values at any point during execution,
	 * before finally persisting the data to the database once we reach the
	 * admin_footer hook.
	 *
	 * This ensures we're always working with up-to-date settings and not
	 * stale data.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function set_settings() {

		$a = $this->db;

		foreach ( $a as $k => $v ) {

			$this->$k = $v;

		}

	}

	/**
	 * Update our database array.
	 *
	 * We're running this method on the admin_footer() hook to ensure our
	 * update_option calls save the most up-to-date data available for any
	 * given page load.
	 *
	 * This is important when working with freshly submitted data from admin
	 * forms such as NextGEN's settings pages, as it allows us to set the
	 * latest options immediately after submission.
	 *
	 * @wp-hook	admin_footer
	 * @uses		update_option()
	 * @since		2.0.0
	 * @return 	void
	 */
	public function save_settings() {

		$a = array_keys( $this->db );

		foreach( $a as $k ) {

			$this->db[ $k ] = $this->$k;

		}

		update_option( $this->db_name, $this->db );

	}

}