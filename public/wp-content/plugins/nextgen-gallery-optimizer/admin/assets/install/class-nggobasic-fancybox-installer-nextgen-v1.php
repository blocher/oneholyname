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
 * NGGOBASIC_Fancybox_Installer_NextGEN_V1
 *
 * Automatic Fancybox installation and integration with NextGEN Gallery Legacy
 * (v1.6.2 to 1.9.13).
 *
 * Completely reworked for v2.0.0. Now includes a lightbox menu item at
 * Gallery --> Options --> Effects, which is synced with our Fancybox checkbox
 * on Optimizer's main settings page.
 *
 * This is called via our NGGOBASIC_Admin class, and only loads in the WordPress
 * admin.
 *
 * Developers can work with this instance by calling
 * NGGOBASIC_Fancybox_Installer_NextGEN_V1::get_instance().
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.0.0
 */
class NGGOBASIC_Fancybox_Installer_NextGEN_V1 {

	/**
	 * NextGEN Gallery's options name in the WordPress wp_options database
	 * table.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $ngg_db_name = 'ngg_options';

	/**
	 * The name of our lightbox.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $lightbox_name = 'nextgen-gallery-optimizer-basic-enhanced-fancybox-lightbox';

	/**
	 * The display name of our lightbox.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $lightbox_display_name = 'Enhanced Fancybox Lightbox [NextGEN Gallery Optimizer Basic]';

	/**
	 * Our lightbox class/rel code used in each image's anchor tag on the front
	 * end. The class name hooks Fancybox to the image, and the rel tag enables
	 * Fancybox to group images by gallery.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $lightbox_code  = 'class="nggobasic-fancybox" rel="%GALLERY_NAME%"';

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
		$this->settings_slug = $vars['settings_slug'];
		$this->plugin_dir_url = $vars['plugin_dir_url'];
		$this->prefix = $vars['prefix'];
		$this->version = $vars['version'];

		// Retrieve required properties from our main admin class.
		$this->admin = NGGOBASIC_Admin::get_instance();
		$vars = $this->admin->get_vars();
		$this->plugin_dir_file = $vars['plugin_dir_file'];

		// Get our settings instance.
		$this->settings = NGGOBASIC_Settings::get_instance();

		// Reset our class/rel code if accidentally changed.
		add_action( 'admin_init', array( $this, 'update_modified_fancybox_code' ) );

		// Register and enqueue Optimizer's JavaScript file for NextGEN's lightbox settings.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_nextgen_options_page_js' ) );

		// Install our lightbox on initial activation
		add_action( 'admin_footer', array( $this, 'install_controller' ) );

		// Uninstall Fancybox if Optimizer is deactivated.
		register_deactivation_hook( $this->plugin_dir_file, array( $this, 'uninstall_controller' ) );

	}

	/**
	 * Check if we're on NextGEN's "Options" page.
	 *
	 * We're doing this the PHP way, as some of our hooks (inc. admin_head)
	 * fire too early to use get_current_screen();
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void|true
	 */
	function is_nextgen_options_page() {

		if ( isset ( $_GET['page'] ) && 'nggallery-options' == $_GET['page'] ) {

			return true;

		}

	}

	/**
	 * Installation controller for initial Fancybox installation.
	 *
	 * Captures previous lightbox settings before installing our menu option
	 * at Gallery --> Options --> Effects upon Optimizer's activation.
	 *
	 * We're using the admin_footer hook here, so we can capture the current
	 * selection on the same page load as the lightbox form submission (for
	 * when a new selection is being made).
	 *
	 * @wp-hook	admin_footer
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function install_controller() {

		$this->reset_installation();

		$this->capture_previous_lightbox_settings();

		// Run our initial installation upon activation or reactivation.
		if ( ! $this->settings->fancybox_installed ) {

			if ( $this->settings->fancybox ) {

				$this->save_fancybox_selection();

			}

			// Set our install flag as true so we only run on activation.
			$this->settings->fancybox_installed = true;
			$this->settings->save();

		}

		$this->set_fancybox_checkbox();

	}

	/**
	 * Reset our Fancybox installation flag if a user has rolled-back to
	 * NextGEN Legacy after using NextGEN 2.0.
	 *
	 * This is required as the two codebases require different methods of
	 * lightbox installation.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function reset_installation() {

		if ( ! $this->settings->nextgen_legacy ) {

			$this->settings->fancybox_installed = false;
			$this->settings->nextgen_legacy = true;

		}

	}

	/**
	 * Capture the previous lightbox selection in Optimizer's settings in order
	 * to restore NextGEN's settings on deactivation, or when our Fancybox
	 * checkbox is deselected on Optimizer's settings page.
	 *
	 * We'll automatically restore users' previous settings if they only want
	 * to use the Optimization features of Optimizer.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @global		$ngg
	 * @since		2.0.0
	 * @return 	void
	 */
	function capture_previous_lightbox_settings() {

		global $ngg;

		// Run if our thumbEffect setting doesn't match the current selection in NextGEN's settings.
		if ( $ngg->options['thumbEffect'] != $this->settings->original_thumbEffect ) {

			// Only run if the selected lightbox is not Optimizer's Fancybox.
			if ( $ngg->options['thumbEffect'] != $this->lightbox_name ) {

				// Pass the previous lightbox settings to our NGGOBASIC_Settings instance for saving.
				$this->settings->original_thumbEffect = stripslashes( $ngg->options['thumbEffect'] );
				$this->settings->original_thumbCode = stripslashes( $ngg->options['thumbCode'] );
				$this->settings->save();

			}

		}

	}

	/**
	 * Toggle the Fancybox checkbox on/off on our settings page depending on
	 * whether our lightbox is selected at Gallery --> Options --> Effects.
	 *
	 * Only runs when NextGEN's "Options" page is saved.
	 *
	 * @wp-hook	admin_footer
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function set_fancybox_checkbox() {

		global $ngg;
		//echo $ngg->options['thumbEffect'];

		if ( $this->is_nextgen_options_page() && isset( $_POST[ 'thumbEffect' ] ) ) {

			if ( $_POST[ 'thumbEffect' ] == $this->lightbox_name ) {

				$this->settings->fancybox = true;
				$this->settings->save();

			} else {

				$this->settings->fancybox = false;
				$this->settings->save();

			}

		}

	}

	/**
	 * Save our Fancybox lightbox as the selected option at
	 * Gallery --> Options --> Effects.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @global		$ngg
	 * @since		2.0.0
	 * @return 	void
	 */
	function save_fancybox_selection() {

		global $ngg;

		// Save our lightbox as the current selection.
		$ngg->options['thumbEffect'] = $this->lightbox_name;
		$ngg->options['thumbCode'] = $this->lightbox_code;
		update_option( $this->ngg_db_name, $ngg->options );

	}

	/**
	 * Sync Fancybox checkbox with Gallery --> Options --> Effects.
	 *
	 * Check if the user has just deactivated Fancybox via our main settings
	 * page checkbox, and if so, restore NextGEN's lightbox selection to its
	 * previous value and show an admin message to notify the user.
	 *
	 * Also, check if the user has just reactivated Fancybox via our main
	 * settings page checkbox, and if so, change NextGEN's lightbox selection
	 * to Optimizer's Fancybox.
	 *
	 * This method is called in our NGGOBASIC_Validation class.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @global		$ngg
	 * @see			validate_optimizer_settings()
	 * @since		2.0.0
	 * @return 	void
	 */
	function sync_fancybox_checkbox( $input ) {

		global $ngg;

		$new_input = $input;

		// Check for "fancybox_title_position" so we don't run on Fancybox settings.
		if ( ! $new_input['fancybox'] && ! $new_input['fancybox_title_position'] ) {

			// Check if the current thumbEffect is set to Optimizer's Fancybox.
			if ( $ngg->options['thumbEffect'] == $this->lightbox_name ) {

				// Restore lightbox to previous selection.
				$this->restore_previous_lightbox_settings();

				// Display a notification that the previous selection has been restored.
				$this->display_lightbox_restored_notice();

			}

		}

		// The following "fancybox_installed" check is required so as not to run on initial database setup.
		if ( $new_input['fancybox'] && ! $this->settings->fancybox && $this->settings->fancybox_installed ) {

			// Reselect Optimizer's Fancybox at Gallery --> Options --> Effects.
			$this->save_fancybox_selection();

		}

	}

	/**
	 * Update modified class/rel code.
	 *
	 * As it's trivially easy for a user to accidentally edit Fancybox's
	 * class/rel code at Gallery --> Options --> Effects, and since there's
	 * no way for a user to automatically restore defaults, we'll ensure the
	 * correct paths are submitted on save.
	 *
	 * @wp-hook	admin_init
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function update_modified_fancybox_code() {

		if ( $this->is_nextgen_options_page() && isset( $_POST[ 'thumbEffect' ] ) ) {

			if ( $_POST[ 'thumbEffect' ] == $this->lightbox_display_name ) {

				// Replace our display name with a hyphenated handle for the database.
				$_POST[ 'thumbEffect' ] = $this->lightbox_name;

				// Ensure our class/rel code hasn't been accidentally changed.
				$_POST[ 'thumbCode' ] = $this->lightbox_code;

			}

		}

	}

	/**
	 * Restore our previously captured lightbox settings on plugin deactivation
	 * or Fancybox checkbox deselection.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @global		$ngg
	 * @since		2.0.0
	 * @return 	void
	 */
	function restore_previous_lightbox_settings() {

		global $ngg;

		// Restore the previous lightbox as the current selection.
		$ngg->options['thumbEffect'] = $this->settings->original_thumbEffect;
		$ngg->options['thumbCode'] = $this->settings->original_thumbCode;
		update_option( $this->ngg_db_name, $ngg->options );

	}

	/**
	 * Uninstall controller.
	 *
	 * Restores previous lightbox settings and updates our installation flag
	 * to false.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void
	 */
	function uninstall_controller() {

		$this->restore_previous_lightbox_settings();

		// Update our Fancybox installation options so we can run again on reactivation.
		$this->settings->original_thumbEffect = 'none';
		$this->settings->original_thumbCode = '';
		$this->settings->fancybox_installed = false;
		$this->settings->save_settings();

	}

	/**
	 * Display a message on Optimizer's main settings page if Fancybox's
	 * checkbox is unchecked, notifying users that their previous lightbox
	 * selection has been restored.
	 *
	 * @wp-hook	void
	 * @uses		add_settings_error()
	 * @global		$ngg
	 * @since		2.0.0
	 * @return 	void
	 */
	function display_lightbox_restored_notice() {

		global $ngg;

		$lightbox = $ngg->options['thumbEffect']; // Get the current lightbox name.
		$lightbox = ucfirst( $lightbox ); // Capitalize the first letter of current thumbEffect for display purposes.

		$out  = '<font style="font-weight:bold !important;">Settings updated.</font><br />';
		$out .= '<font style="font-weight:normal !important;">';
		$out .= 'As Fancybox has been deselected, Optimizer has just restored your previous NextGEN lightbox selection of "<b><i>' . $lightbox . '</i></b>" at ';
		$out .= '<a href="' . admin_url( 'admin.php?page=nggallery-options#effects', __FILE__ ) . '">Gallery --> Options --> Effects</a>.<br />';
		$out .= 'To resume using Optimizer\'s Enhanced Fancybox Lightbox, simply select it below, click Save Options, and NextGEN\'s Lightbox Effects will be automatically updated.';

		$out .= '</font>';

		add_settings_error( $this->settings_slug, 'previous-lightbox-selection-restored', $out, 'updated' );

	}

	/**
	 * Enqueue NextGEN options page JS.
	 *
	 * Register and enqueue Optimizer's JavaScript file for NextGEN's lightbox
	 * settings.
	 *
	 * This script loads on NextGEN's "Options" page only.
	 *
	 * Adds our select menu item in NextGEN v1 at Gallery --> Options -->
	 * Effects.
	 *
	 * Also inserts a message at Gallery --> Options --> Effects (when
	 * Optimizer's Enhanced Fancybox Lightbox is selected), to help inform
	 * users about the benefits of Optimizer's Enhanced Fancybox Lightbox, and
	 * introduce them to the new Dynamic Fancybox Settings Interface available
	 * in Optimizer Pro.
	 *
	 * @wp-hook	admin_enqueue_scripts
	 * @uses		wp_register_script(), wp_enqueue_script()
	 * @uses		wp_localize_script()
	 * @since		2.0.0
	 * @return 	void
	 */
	function enqueue_nextgen_options_page_js() {

		if ( $this->is_nextgen_options_page() ) {

			wp_register_script( $this->prefix . '-nextgen-options-page', $this->plugin_dir_url . 'admin/assets/install/js/' . $this->prefix . '-nextgen-v1-fancybox-install.min.js', array( 'jquery' ), $this->version );
			wp_enqueue_script( $this->prefix . '-nextgen-options-page' );

			// Include a small CDATA block to pass localized data to our script.
			wp_localize_script( $this->prefix . '-nextgen-options-page', $this->prefix . 'NextGENOptionsPage', array(
					'optimizerSettingsURL' => admin_url( 'options-general.php?page=' . $this->settings_slug ),
					'enhancedFancyboxDisplayName' => $this->lightbox_display_name,
					'enhancedFancyboxCode' => $this->lightbox_code
				)
			);

		}

	}

}