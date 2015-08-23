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
 * NGGOBASIC_Admin
 *
 * Our main admin class for interaction with NextGEN Legacy, NextGEN 2.0 and
 * NextCellent Gallery to perform all of our plugin installation and administrative
 * functionality.
 *
 * This is called via our main NextGEN_Gallery_Optimizer_Basic class, and only
 * loads in the WordPress admin.
 *
 * Developers can work with this instance by calling
 * NGGOBASIC_Admin::get_instance().
 *
 * @package	NextGEN_Gallery_Optimizer_Basic
 * @author	Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @since 		2.0.0
 */
class NGGOBASIC_Admin {

	/**
	 * Our main plugin slug, as used for the directory name at the repository.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $main_plugin_slug = 'nextgen-gallery-optimizer';

	/**
	 * Our plugin slug, as used throughout the plugin.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $plugin_slug = 'nextgen-gallery-optimizer-basic';

	/**
	 * Optimizer's Fancybox settings page slug.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $fancybox_interface_slug = 'nextgen-optimizer-basic-fancybox-interface';

	/**
	 * Full file path to our main plugin file.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $plugin_dir_file;

	/**
	 * Our plugin basename.
	 *
	 * Format: plugin directory name -> forward slash -> main plugin file name.
	 *
	 * Used for adding extra links to our plugin item on the plugins page,
	 * while outside of the main plugin file itself.
	 *
	 * @since	2.0.0
	 * @var		string
	 */
	protected $plugin_basename;

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
		$this->settings_slug = $vars['settings_slug'];
		$this->plugin_dir_path = $vars['plugin_dir_path'];
		$this->nextgen_v1 = $vars['nextgen_v1'];
		$this->nextgen_v2 = $vars['nextgen_v2'];

		// Define path to main plugin file.
		$this->plugin_dir_file = $this->plugin_dir_path . $this->main_plugin_slug . '.php';

		// Define plugin_basename outside of the main plugin file.
		$this->plugin_basename = $this->main_plugin_slug . '/' . $this->main_plugin_slug . '.php';

		// Add our action hooks.
		add_action( 'admin_init', array( $this, 'deactivate_premium_version' ) );
		add_action( 'admin_notices', array( $this, 'display_thanks_for_downloading_message' ), 1 ); // Priority 1 to top the notification queue.
		add_action( 'admin_notices', array( $this, 'display_thanks_for_updating_message' ) ); // Default priority to display below the WordPress update nag.
		add_action( 'admin_notices', array( $this, 'display_view_changelog_message' ) ); // Default priority to display below the WordPress update nag.
		add_action( 'admin_notices', array( $this, 'nextgen_installed_and_activated_check' ) );
		add_action( 'admin_head', array( $this, 'add_plugin_description_formatting' ) );
		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_links' ), 10, 2 );
		add_filter( 'plugin_action_links_' . $this->plugin_basename, array( $this, 'add_settings_link' ) );

		// Load our dependencies.
		$this->load_dependencies();

	}

	/**
	 * Load our dependencies as required.
	 *
	 * @wp-hook	void
	 * @since		2.0.0
	 * @uses		void
	 * @see			admin_setup()
	 * @return 	void
	 */
	 function load_dependencies() {

		// Include our NGGOBASIC_Settings class for installation and settings updates.
		require_once( $this->plugin_dir_path . 'admin/assets/class-' . $this->prefix . '-settings.php' );
		$this->settings = NGGOBASIC_Settings::get_instance();
		$this->settings->init();

		if ( $this->nextgen_v1 ) {

			// Load appropriate Fancybox installation if we're using NextGEN v1.9.13 and below.
			require_once( $this->plugin_dir_path . 'admin/assets/install/class-' . $this->prefix . '-fancybox-installer-nextgen-v1.php' );
			$this->fancybox_installer_nextgen_v1 = NGGOBASIC_Fancybox_Installer_NextGEN_V1::get_instance();
			$this->fancybox_installer_nextgen_v1->init();

		}

		if ( $this->nextgen_v2 ) {

			// Load appropriate Fancybox installation if we're using NextGEN v2.0 and above.
			require_once( $this->plugin_dir_path . 'admin/assets/install/class-' . $this->prefix . '-fancybox-installer-nextgen-v2.php' );
			$this->fancybox_installer_nextgen_v2 = NGGOBASIC_Fancybox_Installer_NextGEN_V2::get_instance();
			$this->fancybox_installer_nextgen_v2->init();

			if ( $this->settings->optimize_admin && ! $this->is_nextgen_admin() ) {

				// Remove all NextGEN 2.0 scripts and styles from the admin (except where required) to speed up page loads.
				require_once( $this->plugin_dir_path . 'admin/assets/class-' . $this->prefix . '-admin-cleanup.php' );
				$this->admin_cleanup = NGGOBASIC_Admin_Cleanup::get_instance();
				$this->admin_cleanup->init();

			}

		}

		// Include our settings page class.
		require_once( $this->plugin_dir_path . 'admin/assets/class-' . $this->prefix . '-settings-page.php' );
		$this->settings_page = NGGOBASIC_Settings_Page::get_instance();
		$this->settings_page->init();

		// Include our Dynamic Fancybox Settings Interface demo class.
		require_once( $this->plugin_dir_path . 'admin/assets/class-' . $this->prefix . '-dfsi-demo.php' );
		$this->DFSI = NGGOBASIC_DFSI_Demo::get_instance();
		$this->DFSI->init();

		// Include our regex operations.
		require_once( $this->plugin_dir_path . 'admin/assets/class-' . $this->prefix . '-regex.php' );
		$this->regex = NGGOBASIC_Regex::get_instance();
		$this->regex->init();

	}

	/**
	 * Allow public access to our class properties.
	 *
	 * These are required for the admin only.
	 *
	 * To access from another plugin, or in your theme's functions.php file,
	 *	you could either extend this class, or do something like the following...
	 *
	 *	function get_optimizer_dir_url() {
	 *		$admin = NGGOBASIC_Admin::get_instance();
	 *		$vars = $admin->get_vars();
	 *		$optimizer_plugin_dir_url = $vars['plugin_dir_url'];
	 *		echo $optimizer_plugin_dir_url;
	 *	}
	 *	add_action( 'init', 'get_optimizer_dir_url' );
	 *
	 *	Note: If calling this class instance from within a plugin, depending on
	 * execution order, you may need to use the plugins_loaded hook or later
	 * to ensure this plugin's files have been included. NextGEN for example,
	 * forces itself to load last after all other plugins, so interaction with the
	 * gallery must come after all plugins have loaded.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @since		2.0.0
	 * @return 	array $vars
	 */
	public function get_vars() {

		$vars = array(

			'main_plugin_slug' => $this->main_plugin_slug,
			'plugin_slug' => $this->plugin_slug,
			'fancybox_interface_slug' => $this->fancybox_interface_slug,
			'plugin_dir_file' => $this->plugin_dir_file,
			'plugin_basename' => $this->plugin_basename

		);

		return $vars;

	}

	/**
	 * Helper function to check if the current screen is our main settings page.
	 *
	 * The get_current_screen() function used here returns null if called on
	 * admin_init(). Use admin_notices() or later when calling this method.
	 *
	 * @wp-hook	void
	 * @uses		get_current_screen()
	 * @since		2.0.0
	 * @return		void|true
	 */
	function is_settings_page() {

		$screen = get_current_screen();
		$page_id = $screen->id;
		$settings_page = 'settings_page_' . $this->settings_slug;

		if ( $page_id == $settings_page ) {

			return true;

		}

	}

	/**
	 * Helper function to check if the current screen is our Fancybox settings
	 * page.
	 *
	 * The get_current_screen() function used here returns null if called on
	 * admin_init(). Use admin_notices() or later when calling this method.
	 *
	 * @wp-hook	void
	 * @uses		get_current_screen()
	 * @since		2.0.0
	 * @return		void|true
	 */
	function is_fancybox_page() {

		$screen = get_current_screen();
		$page_id = $screen->id;
		$settings_page = 'settings_page_' . $this->fancybox_interface_slug;

		if ( $page_id == $settings_page ) {

			return true;

		}

	}

	/**
	 * Helper function to check if the current screen is the plugins page.
	 *
	 * The get_current_screen() function used here returns null if called on
	 * admin_init(). Use admin_notices() or later when calling this method.
	 *
	 * @wp-hook	void
	 * @uses		get_current_screen()
	 * @since		2.0.0
	 * @return		void|true
	 */
	function is_plugins_page() {

		$screen = get_current_screen();
		$page_id = $screen->id;

		if ( 'plugins' == $page_id ) {

			return true;

		}

	}

	/**
	 * Detect if we're on a NextGEN settings page, or the post/page->new/edit
	 * screens (where the new Attach To Post interface is added in NextGEN v2).
	 *
	 * If we're not on any of these screens, and Optimizer's "Optimize the
	 * admin" setting is selected, we'll deregister all of NextGEN 2.0.xx's
	 * unused scripts and stylesheets from loading all over the rest of the
	 * WordPress admin.
	 *
	 * @wp-hook	void
	 * @uses		void
	 * @since		2.0.0
	 * @return 	void|true
	 */
	function is_nextgen_admin() {

		// Detect all NextGEN settings pages in as future-proof a way as possible in case new pages are added.
		// Note: Whilst it would be simpler, adding a loose match for the string "nextgen" would incorrectly match most 3rd-party NextGEN add-ons.
		if ( isset( $_GET['page'] ) && strpos( $_GET['page'], 'nextgen-gallery-optimizer' ) === false && preg_match( '/(nextgen-gallery|ngg)/', $_GET['page'] ) ) {

			return true;

		}

		// Added in v2.1, and required for some items on the NextGEN Pro Ecommerce tab, which use the "post_type" query-string parameter.
		if ( isset( $_GET['post_type'] ) && preg_match( '/(nextgen-gallery|ngg|nextgen_proof)/', $_GET['post_type'] ) ) {

			return true;

		}

		// Check if we're on a post/page->new/edit screen, as NextGEN 2.0.xx requires some scripts and stylesheets for its Attach to Post interface on these pages.
		if ( strpos( $_SERVER['REQUEST_URI'], 'wp-admin/post-new.php' ) || strpos( $_SERVER['REQUEST_URI'], 'wp-admin/post.php' ) ) {

			return true;

		}

	}

	/**
	 * Format our plugin description to make it more readable.
	 *
	 * Required as it's not currently possible to insert line breaks or
	 * vertical spacing in the plugin description.
	 *
	 * @wp-hook	admin_head
	 * @uses		admin_url()
	 * @since		2.0.0
	 * @return 	void
	 */
	function add_plugin_description_formatting() {

		if ( $this->is_plugins_page() ) {

			ob_start(); ?>

			<script type='text/javascript'>

				jQuery.noConflict();

				jQuery( document ).ready( function( $ ) {

					$( '[id=<?php echo $this->main_plugin_slug; ?>].active' ) // Look for ALL identical ID's, but only match the one with the"active" class. Duplicates occur in the DOM when you duplicate a plugin and only rename its directory.
						.find( '.plugin-description' )
						.html( '' +
						'<p style="padding-top: 4px;"><strong>The essential add-on for the NextGEN Gallery WordPress plugin.</strong></p>' +
						'<p>Optimizer improves your site\'s page load speed, by preventing NextGEN\'s scripts and stylesheets from loading on posts and pages <strong><em>without</em></strong> NextGEN Gallery content. <a href="http://www.nextgengalleryoptimizer.com/#script-and-stylesheet-optimization" target="_blank">Learn more...</a></p>' +
						'<p>Optimizer also includes an <strong><em>enhanced</em></strong> version of the fantastic Fancybox lightbox script, and only loads it on appropriate posts and pages <strong><em>with</em></strong> NextGEN Gallery content. The result? Gorgeous galleries <strong><em>and</em></strong> a speedy site! <a href="http://www.nextgengalleryoptimizer.com/#enhanced-fancybox-lightbox" target="_blank">Learn more...</a></p>' +
						'<p>Optimizer v2.1.1 currently supports&nbsp;(and is tested compatible with)&nbsp;NextGEN Gallery&nbsp;(v2.0.0 to v2.1.0), NextGEN Legacy&nbsp;(v1.6.2 to v1.9.13)&nbsp;and NextCellent Gallery&nbsp;(v1.9.14 to v1.9.26).</p>' +

						'<div style="padding-top: 15px; padding-bottom: 5px; margin-top: 15px; margin-bottom: 15px; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;">' +
							'<p><strong>New and exclusive to Optimizer Pro...</strong></p>' +
							'<p><strong>NextGEN Gallery Deactivator</strong><br />A whole new level of speed optimization.<br />Only load NextGEN’s PHP <strong><em>code</em></strong> on posts and pages <strong><em>with</em></strong> NextGEN Gallery content.<br /><a href="http://www.nextgengalleryoptimizer.com/#nextgen-gallery-deactivator" target="_blank">Learn more</a> | <a href="http://www.nextgengalleryoptimizer.com/docs/nextgen-gallery-deactivator/" target="_blank">Documentation</a></p>' +
							'<p><strong>Dynamic Fancybox Settings Interface</strong><br />The fastest and easiest way to customize Fancybox. Completely update-safe.<br />Change title position, background color and opacity, make Fancybox <strong><em>responsive</em></strong> and more.<br /><a href="<?php echo admin_url( 'options-general.php?page=' . $this->settings_slug );?>&autoload=true" target="_blank">Launch</a> | <a href="http://www.nextgengalleryoptimizer.com/docs/dynamic-fancybox-settings-interface/" target="_blank">Documentation</a></p>' +
						'</div>' )
						.find( 'p' )
						.css( 'padding-bottom', '4px' )
						.find( 'a' )
						.css({ 'font-weight': 'bold', 'text-decoration': 'underline', 'font-style': 'italic' });
				});

			</script>

			<?php

			// Get our output buffer contents and delete the buffer.
			$out = ob_get_clean();

			/* Minify our output */

			// Remove comments (while testing for a preceding space to ignore URLs).
			$out = preg_replace( '/\s\/\/(.*)/', '', $out );

			// Remove returns, new lines and tabs.
			$out = str_replace( array("\r\n", "\r", "\n", "\t"), '', $out);

			// Remove all unneeded spaces in the JavaScript, whilst preserving those in the text and HTML.
			$out = preg_replace( array( '/\s*\(\s*/', '/\s*\)\s*/', '/\s*\+\s*/', '/\s*=\s*/', '/\',\s*/', '/\':\s*/', '/{\s*/', '/\s*}/'), array( '(', ')', '+', '=', '\',', '\':', '{', '}' ), $out );

			$out = "\n<!-- Format NextGEN Gallery Optimizer Basic's plugin description -->\n" . $out . "\n\n";

			echo $out;

		}

	}

	/**
	 * Add related links to our plugin item on the WordPress admin plugins page.
	 *
	 * @wp-hook	void
	 * @wp-filter	plugin_row_meta
	 * @uses		void
	 * @since		2.0.0
	 * @param	string $file
	 * @param	array $links
	 * @return		array $links
	 */
	function add_plugin_links( $links, $file ) {

		if ( $file == $this->plugin_basename ) {

			$links[] = '<a href="' . admin_url( 'options-general.php?page=' . $this->settings_slug ) . '" target="_blank">Settings</a>';
			$links[] = '<a href="http://www.nextgengalleryoptimizer.com/nextgen-gallery-optimizer-pro/" target="_blank">Upgrade to Pro</a>';

		}

		return $links;

	}

	/**
	 * Add a link to our settings page next to the Activate/Deactivate and
	 * Edit links on the plugins page.
	 *
	 * @wp-hook	void
	 * @wp-filter	plugin_action_links_
	 * @uses		admin_url
	 * @since		2.0.0
	 * @param	array $links
	 * @return		array $links
	 */
	function add_settings_link( $links ) {

		$settings_link = array( 'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->settings_slug ) . '">Settings</a>' );
		$links = $settings_link + $links;
		return $links;

	}

	/**
	 * Deactivate the old NextGEN Gallery Optimizer Premium plugin on
	 * activation if in use.
	 *
	 * @wp-hook	admin_init
	 * @uses		is_plugin_active(), deactivate_plugins()
	 * @since		2.0.0
	 * @return		void
	 */
	function deactivate_premium_version() {

		if ( is_plugin_active('nextgen-gallery-optimizer-premium/nextgen-gallery-optimizer-premium.php' ) ) {

			deactivate_plugins( 'nextgen-gallery-optimizer-premium/nextgen-gallery-optimizer-premium.php' );

		}

	}

	/**
	 * Display a thank you message on first activation.
	 *
	 * @wp-hook	admin_notices
	 * @uses		void
	 * @since		2.1 Renamed from less descriptive "thanks_for_downloading" in v2.0.0.
	 * @return		void
	 */
	function display_thanks_for_downloading_message() {

		if ( $this->is_settings_page() ) {

			if ( $this->settings->show_message ) {

				echo '
				<div id="message">
					<div class="updated">
						<p>Thanks for downloading NextGEN Gallery Optimizer Basic.
					</div>
					<div class="updated">
						<p>Upgrade to <a style="text-decoration: underline;" href="http://www.nextgengalleryoptimizer.com/nextgen-gallery-optimizer-pro/" target="_blank">Optimizer Pro</a> to enable the all new <a style="text-decoration: underline;" href="http://www.nextgengalleryoptimizer.com/#nextgen-gallery-deactivator" target="_blank">NextGEN Gallery Deactivator</a> feature, and to activate your customizations in the new <a style="text-decoration: underline; cursor: pointer;" id="' . $this->prefix . '-launch-dfsi-from-welcome-message">Dynamic Fancybox Settings Interface.</a></p>
					</div>
				</div>
				';

				// We only want to show this message once on first activation,
				// so we'll update the value of "show_message" to false.
				$this->settings->show_message = false;
				$this->settings->save();

			}

		}

	}

	/**
	 * Detect if the plugin has been updated by comparing the version number
	 * set in the code, with the "plugin_version" value in the database.
	 *
	 * We're not comparing against our original "version" db value here, as
	 * it's updated only after a successful Fancybox stylesheet rewrite (which
	 * may not update immediately if there are file permissions/ownership
	 * issues).
	 *
	 * @wp-hook	void
	 * @see			display_thanks_for_updating_message()
	 * @see			display_view_changelog_message()
	 * @uses		void
	 * @since		2.1
	 * @return		boolean
	 */
	function is_recently_updated() {

		// Return true if our version set in the code doesn't match our "plugin_version" set in the db.
		if ( isset( $this->db['plugin_version'] ) && ( $this->db['plugin_version'] !== $this->version ) ) {

			return true;

		}

		return false;

	}

	/**
	 * Display a thank you message with a link to Optimizer's settings page,
	 * after a user performs a 1-click update of this plugin.
	 *
	 * This message is automatically removed once the settings page is visited,
	 * and is important so that users are aware of the major changes and extra
	 * options available in Optimizer v2.0.x.
	 *
	 * @wp-hook	admin_notices
	 * @uses		admin_url()
	 * @uses		$this->is_settings_page()
	 * @uses		$this->is_recently_updated()
	 * @since		2.1
	 * @return		void
	 */
	function display_thanks_for_updating_message() {

		if ( ! $this->is_settings_page() && $this->is_recently_updated() ) {

			echo '
				<div class="update-nag" style="display: block;">
					Thanks for updating NextGEN Gallery Optimizer Basic.<br />Please visit <a href="' . admin_url( 'options-general.php?page=' . $this->settings_slug ) . '">Optimizer\'s options page</a> to review your settings, and see what\'s new.&nbsp;&nbsp;<a href="' . admin_url( 'options-general.php?page=' . $this->settings_slug ) . '">View page and dismiss</a>.
				</div>
			';

		}

	}

	/**
	 * Display a thank you message with a link to Optimizer's development blog,
	 * so users can see what's new after performing a 1-click update of this
	 * plugin.
	 *
	 * This message is shown just once, and only on Optimizer's settings page,
	 * and is important so that users are aware of the major changes and extra
	 * options available in Optimizer v2.0.x.
	 *
	 * @wp-hook	admin_notices
	 * @uses		$this->is_settings_page()
	 * @uses		$this->is_recently_updated()
	 * @uses		$this->settings->save()
	 * @since		2.1
	 * @return		void
	 */
	function display_view_changelog_message() {

		if ( $this->is_settings_page() && $this->is_recently_updated() ) {

			echo '
				<div class="update-nag" style="display: block;">
					Thanks for updating NextGEN Gallery Optimizer Basic.<br />To see what\'s new in v' . $this->version . ', please visit <a href="http://www.nextgengalleryoptimizer.com/blog/" target="_blank">Optimizer\'s development blog</a> and review your settings below.
				</div>
			';

			// Dismiss recently updated messages.
			$this->settings->plugin_version = $this->version;
			$this->settings->save();

		}

	}

	/**
	 * Check to make sure either NextGEN Gallery or NextCellent Gallery is
	 * installed and activated on the plugins page. If not, show an admin
	 * notification to assist with the installation/activation process.
	 *
	 * @wp-hook	admin_notices
	 * @uses		get_plugins(), is_plugin_active(), get_admin_url()
	 * @since 		2.0.0
	 * @return		void
	 */
	function nextgen_installed_and_activated_check() {

		if ( $this->is_settings_page() || $this->is_plugins_page() ) {

			// Check if NextGEN Gallery or NextCellent Gallery is installed.

			if ( ! get_plugins( '/nextgen-gallery' ) && ! get_plugins( '/nextcellent-gallery-nextgen-legacy' ) ) {

				$out = '<div class="error"><p>';
				$out.= '<b>NextGEN Gallery Optimizer Basic Error Notification:</b><br />';
				$out.= 'Optimizer is an add-on for the NextGEN Gallery WordPress plugin, but it appears...<b>NextGEN Gallery is not <i>installed</i>.</b><br />';
				$out.= 'Please <a href="' . get_admin_url( '', 'plugin-install.php?tab=search&s=NextGEN+Gallery' ) . '">download it here automatically</a> ';
				$out.= 'or <a href="http://wordpress.org/extend/plugins/nextgen-gallery">manually from the WordPress repository</a>.';
				$out.= '</p></div>';

				echo $out;

			}

			// Check if NextGEN Gallery or NextCellent Gallery is installed and activated.

			if ( get_plugins( '/nextgen-gallery' ) || get_plugins( '/nextcellent-gallery-nextgen-legacy' ) ) {

				if ( ! is_plugin_active( 'nextgen-gallery/nggallery.php' ) && ( ! is_plugin_active( 'nextcellent-gallery-nextgen-legacy/nggallery.php' ) ) ) {

					$out = '<div class="error"><p>';
					$out.= '<b>NextGEN Gallery Optimizer Basic Error Notification:</b><br />';
					$out.= 'Optimizer is an add-on for the NextGEN Gallery WordPress plugin, but it appears...<b>NextGEN Gallery is not <i>activated</i>.</b><br />';
					$out.= 'Please click the "Activate" link under the "NextGEN Gallery" / "NextCellent Gallery" item on <a href="' . get_admin_url( '', 'plugins.php' ) . '">your plugins page</a>.';
					$out.= '</p></div>';

					echo $out;

				}

			}

		}

	}

}