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
 * Optimizer Basic's JavaScript for NextGEN's lightbox settings page.
 *
 * Requires jQuery v1.4.4+ as included in WordPress v3.1.
 *
 * Adds our select menu item in NextGEN v1 at Gallery --> Options --> Effects.
 *
 * Also inserts a message at Gallery --> Options --> Effects (when Optimizer's
 * Enhanced Fancybox Lightbox is selected), to help inform users about the
 * benefits of Optimizer's Enhanced Fancybox Lightbox, and introduce them to
 * the new Dynamic Fancybox Settings Interface available in Optimizer Pro.
 *
 * This script loads on NextGEN's "Options" page only...
 * "http://www.yoursite.com/wp-admin/admin.php?page=nggallery-options".
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
;( function( nggobasicFancyboxInstall, $, undefined ) {

	var $thumbEffect, $boxText, enhancedFancyboxDisplayName, currentSelection,

		/**
		 * Init
		 *
		 * Cache our multi-use objects, and call all methods required to
		 * initialize our script.
		 *
		 * @param 	void
		 * @uses		_add_menu_item()
		 * @uses		_add_message_html()
		 * @uses		_animate_message()
		 * @since		2.0.0
		 * @return 	void
		 */
		_init = function() {

			$thumbEffect = $( '#thumbEffect' );
			enhancedFancyboxDisplayName = nggobasicNextGENOptionsPage.enhancedFancyboxDisplayName;

			_add_menu_item();
			_add_message_html();
			_animate_message();

		},

		/**
		 * Add menu item
		 *
		 * Adds our select menu item in NextGEN v1 at Gallery --> Options -->
		 * Effects for NextGEN Gallery Optimizer Basic's Enhanced Fancybox
		 * Lightbox.
		 *
		 * @param 	void
		 * @since		2.0.0
		 * @return		void
		 */
		_add_menu_item = function() {

			// Selection returned by NextGEN's jQuery on submit.
			var returnedSelection = $( '#thumbEffect' ).find( ':selected' ).val();

			// Our lightbox class/rel code.
			var enhancedFancyboxCode = nggobasicNextGENOptionsPage.enhancedFancyboxCode;

			// Cache our objects.
			var $thumbCode = $( '#thumbCode' );

			$thumbEffect.prepend( '<option value=\"' + enhancedFancyboxDisplayName + '\">' + enhancedFancyboxDisplayName + '</option>' );

			$thumbEffect.change( function() {

				// Current selection in the select menu itself.
				currentSelection = $thumbEffect.find( ':selected' ).val();

				if ( enhancedFancyboxDisplayName === currentSelection ) {

					$thumbCode.val( enhancedFancyboxCode );

				}

			});

			/**
			 * Fix for NextGEN v1.
			 *
			 * Menu displays 'None' after save if an add-on lightbox is selected.
			 */
			if ( 'none' === returnedSelection ) {

				if ( enhancedFancyboxCode === $thumbCode.val() ) {

					$thumbEffect.val( enhancedFancyboxDisplayName );

				}

			}

		},

		/**
		 * Show message
		 *
		 * Inserts a message at Gallery --> Options --> Effects when Optimizer's
		 * Enhanced Fancybox Lightbox is selected.
		 *
		 * This is intended to help inform users about the benefits of Optimizer's
		 * Enhanced Fancybox Lightbox, and introduce them to the new Dynamic
		 * Fancybox Settings Interface available in Optimizer Pro.
		 *
		 * @param 	void
		 * @since		2.0.0
		 * @return 	void
		 */
		_add_message_html = function() {

			var html = '' +

				'<tr>' +

				'<th></th>' +

				'<td style="padding-top: 0; padding-right: 0;">' +

					'<div id="nggobasic-message-box" style="color: #333;">' +

						'<div class="nggobasic-message-box-text" style="background-color: #ddd; padding: 15px;">' +

							"Why use Optimizer's <em>Enhanced</em> Fancybox Lightbox? " + '<a href="http://www.nextgengalleryoptimizer.com/#enhanced-fancybox-lightbox" target="_blank">Learn more...</a>' +

						'</div>' +

						'<div class="nggobasic-message-box-text" style="background-color: #222; color: #fff; margin: 10px 0 2px; padding: 15px;">' +

							"NEW! <em>Customize</em> Fancybox with Optimizer Pro's " + '<a style="color: #fff;" href="' + nggobasicNextGENOptionsPage.optimizerSettingsURL + '&autoload=true" target="_blank">' + "<b>Dynamic Fancybox Settings Interface</b></a>." +

						'</div>' +

					'</div>' +

				'</td>' +

			'</tr>';

			/**
			 * 1. Insert our table row
			 *
			 * 2. Give the lightbox select menu's table header a minimum width
			 * to prevent the title text running onto three lines (which causes
			 * a big gap below the select menu).
			 *
			 * 3. Adjust padding on the lightbox select menu's table header
			 * for tighter spacing below the select menu.
			 */
			$thumbEffect.parent().parent()
				.after( html )
				.find( 'th' )
				.css({ 'min-width': '120px', 'padding-bottom': '0', 'padding-top': '15px' });

			// Adjust select menu's padding.
			$thumbEffect.parent().css({ 'padding-top': '0', 'padding-bottom': '0', 'padding-right': '0' });

			// Remove 1px left margin for pixel-perfect alignment.
			$thumbEffect.css( 'margin-left', '0' );

			// Cache our message object ahead of further use.
			$boxText = $( '.nggobasic-message-box-text' );

			// Get our current lightbox selection.
			currentSelection = $thumbEffect.find( ':selected' ).val();

			// Hide our message on initial load if another lightbox is saved.
			if ( enhancedFancyboxDisplayName !== currentSelection ) { $boxText.css({ 'opacity': '0', 'display': 'none' }); }

		},

		/**
		 * Animate message
		 *
		 * Controls all animation of our message at Gallery --> Options -->
		 * Effects.
		 *
		 * @param 	void
		 * @since		2.0.0
		 * @return 	void
		 */
		_animate_message = function() {

			// Avoid animation collisions.
			var animating = '';
			var animationQueue = '';

			$thumbEffect.change( function() {

				// Our queue is reset to true upon every change to the lightbox select menu.
				// This way, we know to run again once the current animation has completed.
				animationQueue = true;
				animate();

				/**
				 * Animate
				 *
				 * Animate the display of our learn more/DFSI message depending
				 * on whether Optimizer's Enhanced Fancybox Lightbox is
				 * selected.
				 *
				 * Scroll-down and fade-in if it is, fade-out and scroll-up if
				 * it's not.
				 *
				 * @param 	void
				 * @since		2.0.0
				 * @return 	null|void
				 */
				function animate() {

					currentSelection = $thumbEffect.find( ':selected' ).val();

					if ( ! animating ) { // Avoid collisions.

						// Return when animation isn't required to end our loop.
						if ( enhancedFancyboxDisplayName === currentSelection && $boxText.css( 'opacity' ) > 0 || enhancedFancyboxDisplayName !== currentSelection && $boxText.css( 'opacity' ) < 1 ) {

							return;

						}

						animating = true;
						animationQueue = false;

						if ( enhancedFancyboxDisplayName === currentSelection ) {

							$boxText.slideDown( 600, function() {

								$boxText.fadeTo( 600, 1, function() {

									animating = false;
									loop_animation();

								});

							});

						} else if ( $boxText.css( 'opacity' ) === '1' ) {

							$boxText.fadeTo( 600, 0.00, function() {

								$boxText.slideUp( 600, function() {

									animating = false;
									loop_animation();

								});

							});

						}

					}

				}

				/**
				 * Loop animation
				 *
				 * If another selection was made during our message animation,
				 * "animationQueue" will be true at this point.
				 *
				 * If so, we'll call our animation method again to
				 * appropriately show/hide Optimizer's message.
				 *
				 * @param 	void
				 * @since		2.0.0
				 * @return 	void
				 */
				function loop_animation() {

					if ( true === animationQueue ) {

						animate();

					}

				}

			});

		};

	$( document ).ready( function() {

		_init();

	});

}( window.nggobasicFancyboxInstall = window.nggobasicFancyboxInstall || {}, jQuery ) );

/**
 * jQuery noConflict
 *
 * Ensure we're running in noConflict mode for compatibility with other
 * JavaScript libraries.
 *
 * Required when using Google-hosted jQuery, which does not include this (the
 * version built-into WordPress calls it right at the end of the library).
 *
 * This will be unnecessary in most cases, as this script is called inside the
 * admin, but best to be safe.
 *
 * @since	2.0.0
 */
jQuery.noConflict();