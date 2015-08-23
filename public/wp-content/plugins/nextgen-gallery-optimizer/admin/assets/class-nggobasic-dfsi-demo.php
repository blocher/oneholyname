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
 * NGGOBASIC_DFSI_Demo
 *
 * Optimizer Basic's Dynamic Fancybox Settings Interface Demo
 *
 * Provides a unique, dynamic interface for customizing Optimizer's Fancybox
 * lightbox, without needing to edit code or refresh the front-end.
 *
 * This is called via our NGGOBASIC_Admin class, and only loads in the WordPress
 * admin.
 *
 * Developers can work with this instance by calling
 * NGGOBASIC_DFSI_Demo::get_instance().
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.0.0
 */
class NGGOBASIC_DFSI_Demo {

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
		$this->db_name = $vars['db_name'];
		$this->prefix = $vars['prefix'];
		$this->version = $vars['version'];
		$this->plugin_dir_url = $vars['plugin_dir_url'];
		$this->plugin_dir_path = $vars['plugin_dir_path'];

		// Retrieve required properties from our main admin class.
		$this->admin = NGGOBASIC_Admin::get_instance();
		$vars = $this->admin->get_vars();
		$this->fancybox_interface_slug = $vars['fancybox_interface_slug'];

		// Get our settings instance.
		$this->settings = NGGOBASIC_Settings::get_instance();

		// Add our action hooks.
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );

		/**
		 * Remove all admin toolbars, notifications and navigation elements from
		 * the Fancybox settings interface.
		 *
		 * We need to detect the page the PHP way here as get_current_screen()
		 * isn't available in time to access the admin_bar_init hook.
		 */
		if ( isset ( $_GET['page'] ) && $_GET['page'] == $this->fancybox_interface_slug ) {

			// Remove top admin bar.
			add_filter( 'init', array( $this, 'remove_top_admin_bar' ) ); // Doesn't seem to work on admin_init.

			// Remove the update nag.
			add_action( 'admin_notices', array( $this, 'remove_update_nag' ), 1 ); // Priority 1 is important here.

			// Remove NextGEN's "Gallery" menu item.
			add_action( 'admin_init', array( $this, 'remove_nextgen_menu_item' ) ); // Doesn't seem to work on admin_menu.

			// Remove all WordPress menu items.
			add_action( 'admin_menu', array( $this, 'remove_admin_menu_items' ) );

			// Remove the WordPress colophon and version number.
			add_action( 'admin_init', array( $this, 'remove_footer' ) );

		}

	}

	/**
	 * Add our options page for the Fancybox settings interface.
	 * $menu_title is left empty as we don't need a direct access menu item
	 * for this page.
	 *
	 * @wp-hook	admin_menu
	 * @uses		add_options_page()
	 * @since		2.0.0
	 * @return 	void
	 */
	function add_options_page() {

		add_options_page(

			$this->fancybox_interface_slug, // $page_title
			'', // $menu_title
			'manage_options', // $capability
			$this->fancybox_interface_slug, // $menu_slug
			array( $this, 'get_view' ) // $function

		);

	}

	/**
	 * Register and enqueue our Fancybox settings interface js and css files,
	 * and also our Farbtastic color-picker for the "overlay color" setting.
	 *
	 * These scripts and styles load on the Fancybox settings page only.
	 *
	 * @wp-hook	admin_enqueue_scripts
	 * @uses		get_current_screen(), wp_register_script(), wp_enqueue_script()
	 * @uses		wp_register_style(), wp_enqueue_style()
	 * @since		2.0.0
	 * @return 	void
	 */
	function enqueue_scripts_and_styles() {

		if ( $this->admin->is_fancybox_page() ) {

			wp_register_script( $this->prefix . '-fancybox-interface', $this->plugin_dir_url . 'admin/assets/js/' . $this->prefix . '-dfsi-demo.min.js', array( 'jquery' ), $this->version );
			wp_enqueue_script( $this->prefix . '-fancybox-interface' );

			wp_register_style( $this->prefix . '-fancybox-interface', $this->plugin_dir_url . 'admin/assets/css/' . $this->prefix . '-dfsi-demo.min.css', false, $this->version, 'screen' );
			wp_enqueue_style( $this->prefix . '-fancybox-interface' );

			wp_enqueue_style( 'farbtastic' );
			wp_enqueue_script( 'farbtastic' );

		}

	}

	/**
	 * Include the view for our Fancybox settings page.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function get_view() {

		if ( $this->admin->is_fancybox_page() ) {

			require_once( $this->plugin_dir_path . 'admin/assets/view/' . $this->prefix . '-dfsi-demo-view.php' );

		}

	}

	/**
	 * Remove the top horizontal admin toolbar.
	 *
	 * The 25px space left behind is handled with css in the file
	 * "fancybox-settings.css".
	 *
	 * Hooked to init as admin_init appears to fire too late for this filter.
	 *
	 * @wp-hook	init
	 * @uses		add_filter()
	 * @since		2.0.0
	 * @return		void
	 */
	function remove_top_admin_bar() {

		add_filter( 'wp_admin_bar_class', '__return_false' );

	}

	/**
	 * Remove the default WordPress admin menu items from the left sidebar.
	 *
	 * @wp-hook	admin_menu
	 * @uses		remove_menu_page()
	 * @since		2.0.0
	 * @return		void
	 */
	function remove_admin_menu_items() {

		remove_menu_page( 'index.php' );
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'link-manager.php' );
		remove_menu_page( 'tools.php' );
		remove_menu_page( 'plugins.php' );
		remove_menu_page( 'users.php' );
		remove_menu_page( 'options-general.php' );
		remove_menu_page( 'upload.php' );
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit.php?post_type=page' );
		remove_menu_page( 'themes.php' );

		// NextGEN's menu item cannot currently be removed on the admin_menu hook (as it should).
		// In case this is fixed in a future release (> v2.0), we'll leave the following in place...

		remove_menu_page( 'nextgen-gallery' );

	}

	/**
	 * Remove NextGEN Gallery's admin menu item from the left navbar.
	 * Currently (v2.0) this must fire separately on the admin_init hook.
	 *
	 * @wp-hook	admin_init
	 * @uses		remove_menu_page()
	 * @since		2.0.0
	 * @return		void
	 */
	function remove_nextgen_menu_item() {

		remove_menu_page( 'nextgen-gallery' );

	}

	/**
	 * Remove footer colophon and WordPress version number.
	 * __return_false function available from WP 3.0.
	 *
	 * @wp-hook	admin_init
	 * @uses		add_filter(), __return_false
	 * @since		2.0.0
	 * @return		void
	 */
	function remove_footer() {

		add_filter( 'admin_footer_text', '__return_false' ); // Remove colophon.
		add_filter( 'update_footer', '__return_false' ); // Remove WP version number.

	}

	/**
	 * Remove the WordPress update notification.
	 *
	 * @wp-hook	admin_notices
	 * @uses		remove_action()
	 * @since		2.0.0
	 * @return		void
	 */
	function remove_update_nag() {

		remove_action( 'admin_notices', 'update_nag', 3 ); // Priority 3 is important here.

	}

}