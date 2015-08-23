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
 * NextGEN Gallery Optimizer Basic's settings page JavaScript
 *
 * @package		NextGEN_Gallery_Optimizer_Basic
 * @author		Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @link				http://www.nextgengalleryoptimizer.com
 * @copyright	2012 - 2015 Mark Jeldi | Helpful Media
 * @since 			2.0.0
 */

/**
 * Self-executing, namespaced closure pattern.
 *
 * Provides an easy way to extend our namespace, while keeping the content
 * protected from the global namespace.
 *
 * It also enables the use of public and private properties and methods,
 * protects the $ from conflicting with other JavaScript libraries, and protects
 * undefined from being redefined.
 *
 * We're prefixing a semi-colon here to ensure this script will continue to
 * function if concatenated with another file.
 */
;( function( nggobasicSettings, $, undefined ) {

	var unsaved,

		/**
		 * Init
		 *
		 * Call all methods required to initialize our settings page.
		 *
		 * @param 	void
		 * @uses		_toggle_advanced_options()
		 * @uses		_store_saved_state()
		 * @uses		_confirm_close()
		 * @uses		_remove_fancybox_title()
		 * @uses		nggobasicSettings.attachFancybox()
		 * @uses		_trigger_fancybox()
		 * @since		2.0.0
		 * @return 	void
		 */
		_init = function() {

			_toggle_advanced_options();
			_store_saved_state();
			_confirm_close();
			_remove_fancybox_title();

			nggobasicSettings.attachFancybox();
			_trigger_fancybox(); // Only call after Fancybox is attached.

		},

		/**
		 * Toggle advanced options
		 *
		 * Slides up/down our Advanced Options fields for NextGEN Legacy and
		 * NextCellent Gallery.
		 *
		 * @param 	void
		 * @since		2.0.0
		 * @return 	void
		 */
		_toggle_advanced_options = function() {

			$( '#nggobasic-nextgen-v1-advanced-settings-link' ).click( function( e ) {

				e.preventDefault(); // Prevent our hash href moving page to top on-click.
				$( '#nggobasic-nextgen-v1-advanced-settings' ).slideToggle( 'slow' );

			});

			$( '#nggobasic-nextgen-v2-advanced-settings-link' ).click( function( e ) {

				e.preventDefault(); // Prevent our hash href moving page to top on-click.
				$( '#nggobasic-nextgen-v2-advanced-settings' ).slideToggle( 1000 );

			});

		},

		/**
		 * Store saved state
		 *
		 * Cache the saved selections in our input fields, so we can alert the
		 * user to unsaved changes if they try to navigate away.
		 *
		 * We're appending a "data-orig" attribute on all inputs to store our
		 * original form data on-load, which lets us detect if the actual data
		 * has changed before alerting the user (they may have undone/reverted
		 * their changes before navigating away).
		 *
		 * @param 	void
		 * @since		2.0.0
		 * @return 	void
		 */
		_store_saved_state = function() {

			var $input = $( 'input[type=text]' );
			var $select = $( 'select' );
			var $radio = $( 'input[type=radio]' );
			var $checkbox = $( 'input[type=checkbox]' );

			$input.add( $select ).each( function() {

				$( this ).attr( 'data-orig', $( this ).val() );

			});

			$radio.add( $checkbox ).each( function() {

				if ( this.checked ) {

					$( this ).attr( 'data-orig', 'checked' );

				} else {

					$( this ).attr( 'data-orig', null );

				}

			});

		},

		/**
		 * Check changes
		 *
		 * We're checking against our "data-orig" attributes added earlier on
		 * all inputs, which lets us detect if the actual data has changed
		 * before alerting the user (they may have undone/reverted
		 * their changes before navigating away).
		 *
		 * @param 	void
		 * @since		2.0.0
		 * @return 	false|null|void
		 */
		_check_changes = function() {

			var $input = $( 'input[type=text]' );
			var $select = $( 'select' );
			var $radio = $( 'input[type=radio]' );
			var $checkbox = $( 'input[type=checkbox]' );

			$input.add( $select ).each( function() {

				var originalValue = $( this ).attr( 'data-orig' );
				var currentValue = $( this ).val();

				if ( originalValue != currentValue ) {

					unsaved = true;
					return false; // Return to stop the loop, preventing another field from resetting unsaved back to false.

				} else {

					unsaved = false;

				}

			});

			/**
			 * Return if we've detected unsaved changes in any text or select
			 * inputs above.
			 *
			 * This prevents resetting the flag if no radio or checkbox inputs
			 * have changed in the test below.
			 */
			if ( unsaved ) {

				return;

			}

			$radio.add( $checkbox ).each( function() {

				var originalValue = $( this ).attr( 'data-orig' ); // Get our original value stored earlier.
				var currentValue = this.checked ? 'checked' : null; // Get the current value of the "checked" attribute.

				if ( originalValue != currentValue ) {

					unsaved = true;
					return false;

				} else {

					unsaved = false;

				}

			});

		},

		/**
		 * Confirm close
		 *
		 * If any unsaved changes were detected, we'll alert the user and ask
		 * if they really want to leave before closing the settings page.
		 *
		 * @param 	void
		 * @uses		_check_changes()
		 * @since		2.0.0
		 * @return 	alert|void
		 */
		_confirm_close = function() {

			$( window ).bind( 'beforeunload.nggobasic', function() {

				_check_changes();

				if ( unsaved ) {

					return "You appear to have unsaved changes on NextGEN Gallery Optimizer Basic's main settings page.";

				}

			});

			/**
			 * Release our save button from the unsaved changes prompt.
			 */
			$( '#nggobasic-settings-form' ).submit( function() {

				unsaved = false;
				$( window ).unbind( 'beforeunload.nggobasic' );

			});

		},

		/**
		 * Remove Fancybox title
		 *
		 * Removes Fancybox's title position placeholder, so we can perform
		 * custom title animations in our Dynamic Fancybox Settings Interface
		 * demo.
		 *
		 * Thanks to a small check added to the Fancybox script, the
		 * "_process_title" method will return if this placeholder is absent,
		 * and no titles will be displayed (however our spacing for single-line
		 * titles will be preserved in the lightbox height calculations).
		 *
		 * We're removing this on-click of the settings interface button, so as
		 * to not affect regular front-end functionality.
		 *
		 * @param 	void
		 * @since		2.0.0
		 * @return 	void
		 */
		 _remove_fancybox_title = function() {

			$( 'a#nggobasic-dynamic-fancybox-settings-interface' ).click( function() {

				$( '#nggobasic-fancybox-title' ).remove();

			});

		},

		/**
		 * Trigger Fancybox
		 *
		 * Auto-load our Dynamic Fancybox Settings Interface on page load,
		 * when clicking through from NextGEN's settings page at Gallery -->
		 * Options --> Effects.
		 *
		 * @param 	void
		 * @since		2.0.0
		 * @return 	void
		 */
		_trigger_fancybox = function() {

			if ( 'true' === _get_url_query_string_parameters( 'autoload' ) ) {

				$( 'a#nggobasic-dynamic-fancybox-settings-interface' ).trigger( 'click' );

			}

			$( 'a#nggobasic-launch-dfsi-from-welcome-message' ).click( function() {

				$( 'a#nggobasic-dynamic-fancybox-settings-interface' ).trigger( 'click' );

			});

			/**
			 * Get URL Query String Parameters
			 *
			 * Retrieves all URL query string parameters in our settings page
			 * request, and looks for our provided parameter, returning its
			 * value if it exists.
			 *
			 * We're using this to look for an "autoload=true" key/value pair,
			 * which is provided in the link to our Dynamic Fancybox Settings
			 * Interface at Gallery --> Options --> Effects.
			 *
			 * @param 	parameter
			 * @since		2.0.0
			 * @return 	boolean
			 */
			function _get_url_query_string_parameters( parameter ) {

				var queryString = window.location.search.substring( 1 );
				var params = queryString.split( '&' );

				for ( var i=0; i < params.length; i++ ) {

					var pair = params[i].split( '=' );

					if ( pair[0] == parameter ) {

						// Return the value of the provided parameter.
						return pair[1];

					}

				}

				return false;

			}

		};

	/**
	 * Public methods
	 */

	/**
	 * Attach Fancybox
	 *
	 * Attaches our Fancybox lightbox (and our custom settings) to our
	 * interface button ready for launch.
	 *
	 * @param 	void
	 * @uses		nggobasicDFSIDemoFancybox()
	 * @uses		nggobasicDFSIDemoFancybox.showActivity()
	 * @since		2.0.0
	 * @return 	void
	 */
	nggobasicSettings.attachFancybox = function() {

		$( 'a#nggobasic-dynamic-fancybox-settings-interface' ).nggobasicDFSIDemoFancybox({

			'overlayColor': '#666',
			'overlayOpacity': '0.3',
			'type': 'iframe',
			'width': '100%',
			'height': '100%',
			'autoScale': false,
			'titlePosition': 'float',
			'responsiveResize': false,
			'onStart': function() {

				/**
				 * Prevent direct horizontal and vertical scrolling of our
				 * background admin page (behind the interface overlay) on iOS
				 * and Android devices.
				 */
				$( 'html, body' ).bind( 'touchmove.nggobasicDFSI', function( event ) {

					event.preventDefault();

				});

				// Lock background page.
				$( 'html' ).css( 'overflow', 'hidden' );

			},

			'onComplete': function() {

				// Fade-in Fancybox loading animation on start up.
				$.nggobasicDFSIDemoFancybox.showActivity();
				$( '#nggobasic-fancybox-loading' ).find( 'div' ).hide().fadeIn();

				/**
				 * Assign focus to our iframe.
				 *
				 * This enables us to instantly use the spacebar and up/down
				 * keys to scroll through the options grid, without first
				 * having to click somewhere inside the iframe.
				 *
				 * Note 1: focus() by itself works in Chrome and Firefox, but
				 * not in Opera (v12.16 in testing), so we need to specifically
				 * target the content window.
				 *
				 * Note 2: We need to call this again in our main interface JS
				 * file in order to gain focus in IE9, but we still need to
				 * call it here for Firefox, which otherwise focuses the whole
				 * lightbox (the parent) instead of its content.
				 */
				$( '#nggobasic-fancybox-frame' ).get( 0 ).contentWindow.focus();

			},

			'onClosed': function() {

				/**
				 * Clean up backup in case our interface is closed before it
				 * can load.
				 */
				$( 'html, body, #wpwrap' ).removeAttr( 'style' );

				// Prevent flash of horizontal scrollbar.
				$( 'body' ).css( 'overflow-x', 'hidden' );

				/**
				 * Unbind our prevention of direct horizontal and vertical
				 * scrolling of our background admin page (behind the interface
				 * overlay) on iOS and Android devices.
				 */
				$( 'html, body' ).unbind( 'touchmove.nggobasicDFSI' );

			}

		});

	};

	/**
	 * Ready. Set. Go...
	 */
	$( document ).ready( function() {

		_init();

	});

}( window.nggobasicSettings = window.nggobasicSettings || {}, jQuery ) );