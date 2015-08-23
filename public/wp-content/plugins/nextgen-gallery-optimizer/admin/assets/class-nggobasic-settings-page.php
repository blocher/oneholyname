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
 * NGGOBASIC_Settings_Page
 *
 * Builds and processes our main plugin settings page, and handles data
 * validation on form submission.
 *
 * This is called via our NGGOBASIC_Admin class, and only loads in the
 * WordPress admin.
 *
 * Developers can work with this instance by calling
 * NGGOBASIC_Settings_Page::get_instance().
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.0
 */
class NGGOBASIC_Settings_Page {

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
		$this->display_name = $vars['display_name'];
		$this->settings_slug = $vars['settings_slug'];
		$this->plugin_dir_url = $vars['plugin_dir_url'];
		$this->plugin_dir_path = $vars['plugin_dir_path'];
		$this->fancybox_version = $vars['fancybox_version'];
		$this->nextgen_v1 = $vars['nextgen_v1'];
		$this->nextgen_v2 = $vars['nextgen_v2'];

		// Retrieve required properties from our main admin class.
		$this->admin = NGGOBASIC_Admin::get_instance();
		$vars = $this->admin->get_vars();
		$this->fancybox_interface_slug = $vars['fancybox_interface_slug'];

		// Get our settings instance.
		$this->settings = NGGOBASIC_Settings::get_instance();

		// Add our action hooks.
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );

	}

	/**
	 * Add our options page for Optimizer's main settings interface.
	 *
	 * @wp-hook	admin_menu
	 * @uses		add_options_page()
	 * @since		2.0.0
	 * @return 	void
	 */
	function add_options_page() {

		add_options_page(

			$this->display_name, // $page_title
			$this->display_name, // $menu_title
			'manage_options', // $capability
			$this->settings_slug, // $menu_slug
			array( $this, 'get_view' ) // $function

		);

	}

	/**
	 * Register and enqueue Optimizer's settings page js and css files.
	 * These scripts and styles load on the Optimizer's main settings page only.
	 *
	 * @wp-hook	admin_enqueue_scripts
	 * @uses		wp_register_script(), wp_enqueue_script()
	 * @uses		wp_register_style(), wp_enqueue_style()
	 * @since		2.0.0
	 * @return 	void
	 */
	function enqueue_scripts_and_styles() {

		if ( $this->admin->is_settings_page() ) {

			wp_register_style( $this->prefix . '-fancybox', $this->plugin_dir_url . 'fancybox/' . $this->prefix . '.jquery.fancybox-' . $this->fancybox_version . '.css', false, $this->version, 'screen' );
			wp_enqueue_style( $this->prefix . '-fancybox' );

			wp_register_script( $this->prefix . '-fancybox', $this->plugin_dir_url . 'admin/assets/js/' . $this->prefix . '-dfsi-demo-fancybox.min.js', array( 'jquery' ), $this->version );
			wp_enqueue_script( $this->prefix . '-fancybox' );

			wp_register_style( $this->prefix . '-optimizer-settings', $this->plugin_dir_url . 'admin/assets/css/' . $this->prefix . '-settings.css', false, $this->version, 'screen' );
			wp_enqueue_style( $this->prefix . '-optimizer-settings' );

			wp_register_script( $this->prefix . '-optimizer-settings', $this->plugin_dir_url . 'admin/assets/js/' . $this->prefix . '-settings.js', array( 'jquery' ), $this->version );
			wp_enqueue_script( $this->prefix . '-optimizer-settings' );

			// Include a small CDATA block to make our database settings available to our Dynamic Fancybox Settings Interface initialization script.
			wp_localize_script( $this->prefix . '-optimizer-settings', $this->prefix . 'DFSIDemoVars', array(
					'pluginDirUrl' => $this->plugin_dir_url,
					'adminUrl' => admin_url(),
					'titlePosition' => 'float',
					'overlayColor' => '#666',
					'overlayOpacity' => '0.3',
					'responsiveResize' => '',
					'imageCounter' => '',
					'titleFromAlt' => ''
				)
			);

		}

	}

	/**
	 * Include the view for Optimizer's main settings page.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @see			add_options_page()
	 * @since		2.0.0
	 * @return 	void
	 */
	function get_view() {

		if ( $this->admin->is_settings_page() ) {

			require_once( $this->plugin_dir_path . 'admin/assets/view/' . $this->prefix . '-settings-view.php' );

		}

	}

}