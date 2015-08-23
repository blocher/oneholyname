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
 * Main settings page HTML Template
 *
 * Represents the view for our main admin settings page, including the HTML
 * markup and options form that makes up the core of the user interface.
 *
 * @package		NextGEN_Gallery_Optimizer_Basic
 * @author		Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @link				http://www.nextgengalleryoptimizer.com
 * @copyright	2012 - 2015 Mark Jeldi | Helpful Media
 */
?>

<div class="wrap">

	<h2><?php echo $this->display_name; ?></h2>

	<div id="nggobasic-upgrade-to-pro-button">
		<a class="button-primary" title="Upgrade to NextGEN Gallery Optimizer Pro" alt="Upgrade to NextGEN Gallery Optimizer Pro" href="http://www.nextgengalleryoptimizer.com/nextgen-gallery-optimizer-pro/" target="_blank">Unlock more speed. Upgrade to Optimizer Pro.</a>
	</div>

	<form id="nggobasic-settings-form" method="post" action="options.php">
	<?php settings_fields( $this->db_name . '_group' ); ?>

		<div class="nggobasic-box">
			<h2><?php _e( 'Script and Stylesheet Optimization', 'nggobasic_domain' ); ?></h2>

			<?php if ( $this->nextgen_v1 ) { // Begin optimization settings for NextGEN Legacy and NextCellent Gallery. ?>

			<span>
			NextGEN Gallery Optimizer improves your site's page load speed, by preventing NextGEN's scripts and stylesheets from loading on posts and pages <em>without</em> NextGEN Gallery content.<br /><br />
			Additionally, as you're using NextGEN Legacy (v1.6.2 to v1.9.13) or the NextCellent Gallery fork, Optimizer will intelligently fine-tune all your posts and pages <em>with</em> NextGEN Gallery content too... automatically detecting every shortcode and setting, to only load the scripts and stylesheets required on each page.
			&nbsp;<a href="http://www.nextgengalleryoptimizer.com/optimization-flowcharts/nextgen-legacy-nextcellent-gallery/" target="_blank">Learn more...</a><br /><br />

			<a id="nggobasic-nextgen-v1-advanced-settings-link" href="#">Advanced options...</a>
			</span>

			<div id="nggobasic-nextgen-v1-advanced-settings">

				<div class="nggobasic-inner">

					<div class="nggobasic-select-style">
						<b>Select your NextGEN stylesheet:</b>
						<p>
							<?php $styles = array( 'None', 'Black Minimalism Theme', 'Default Styles', 'Dkret3 Theme', 'Hovereffect Styles', 'K2 Theme', 'Shadow Effect', 'Shadow Effect with Description Text' ); ?>
							<select name="<?php echo $this->db_name ?>[theme]" id="<?php echo $this->db_name ?>[theme]">
								<?php foreach ( $styles as $style ) { ?>
									<?php if ( $this->settings->theme == $style ) { $selected = ' selected="selected"'; } else { $selected = ''; } ?>
									<option value="<?php echo $style; ?>"<?php echo $selected; ?>><?php echo $style; ?></option>
								<?php } ?>
							</select>
						</p>
					</div>

					<div class="nggobasic-custom-style">
						<b>Or enter the path to a custom file:</b>
						<p><?php echo content_url() ?>/ <input id="<?php echo $this->db_name ?>[css]" name="<?php echo $this->db_name ?>[css]" type="text" size="35" value="<?php echo $this->settings->css; ?>"/></p>
					</div>

				</div>

				<div class="clear"></div>

				<div class="nggobasic-inner">

					<label>
						<input name="<?php echo $this->db_name ?>[jquery]" type="radio" value="wordpress"<?php checked( "wordpress", $this->settings->jquery ); ?> />
						&nbsp;&nbsp;<b>Use WordPress jQuery [greater compatibility]</b>&nbsp;&nbsp;&nbsp;&nbsp;
					</label>
					<label>
						<input name="<?php echo $this->db_name ?>[jquery]" type="radio" value="google"<?php checked( "google", $this->settings->jquery ); ?> />
						&nbsp;&nbsp;<b>Use Google-hosted jQuery [faster page loads]</b>
					</label>
				</div>

			</div><!-- End #nggobasic-nextgen-v1-advanced-settings. -->

			<?php } else if ( $this->nextgen_v2 ) { // Begin optimization settings for NextGEN v2.0.xx. ?>

			<label>
				<input id="<?php echo $this->db_name ?>[optimize_front_end]" name="<?php echo $this->db_name ?>[optimize_front_end]" type="checkbox" value="1"<?php checked( 1, $this->settings->optimize_front_end ); ?> />
				&nbsp;&nbsp;<b>Optimize the front-end?</b>

				<span class="nggobasic-optimization-description">
				&nbsp;&nbsp;Removes NextGEN's scripts and stylesheets from posts and pages without NextGEN gallery content, to speed up your site.
				&nbsp;<a href="http://www.nextgengalleryoptimizer.com/optimization-flowcharts/nextgen-gallery-v2/" target="_blank">Learn more...</a>
				</span><br />
			</label>

			<label>
				<input id="<?php echo $this->db_name ?>[optimize_admin]" name="<?php echo $this->db_name ?>[optimize_admin]" type="checkbox" value="1"<?php checked( 1, $this->settings->optimize_admin ); ?> />
				&nbsp;&nbsp;<b>Optimize the admin?</b>

				<span class="nggobasic-optimization-description">
				&nbsp;&nbsp;Prevents NextGEN's scripts and stylesheets from loading on every page of the admin, for faster administration.
				&nbsp;<a href="http://www.nextgengalleryoptimizer.com/optimization-flowcharts/nextgen-gallery-v2/" target="_blank">Learn more...</a>
				</span>
			</label>

			<br /><br />
			<a id="nggobasic-nextgen-v2-advanced-settings-link" href="#">Advanced options...</a>

			<div id="nggobasic-nextgen-v2-advanced-settings">

				<div id="nggobasic-nextgen-v2-advanced-settings-inner">

					<div id="nggobasic-optimization-header-box">
						<b>Optimize posts and pages <em>with</em> NextGEN Gallery content:</b>
					</div>

					<div id="nggobasic-optimization-description-box">
						The following settings are intended for <strong><em>advanced users</em></strong> and may break undocumented functionality.<br /><br/>
						<strong><em>Please test all posts and pages with NextGEN Gallery content after saving.</em></strong><br /><br />
						For a live example of these settings in action, please view the source of <a href="http://www.nextgengalleryoptimizer.com/" target="_blank">Optimizer's homepage</a> in your browser.<br /><br />
						To learn more about these settings, please see <a href="http://www.nextgengalleryoptimizer.com/docs/configuration/nextgen-gallery-v2/" target="_blank">Optimizer's NextGEN 2.0 settings documentation</a> and the <a href="http://www.nextgengalleryoptimizer.com/optimization-flowcharts/nextgen-gallery-v2/" target="_blank">Optimization Flowchart</a>.
					</div>

					<div class="nggobasic-optimization-title-box">
						<b>Optimize scripts:</b>
					</div>

					<div class="nggobasic-optimization-options-box">

						<div class="nggobasic-optimization-option">
							<label>
								<input id="<?php echo $this->db_name ?>[remove_lightbox_context_js]" name="<?php echo $this->db_name ?>[remove_lightbox_context_js]" type="checkbox" value="1"<?php checked( 1, $this->settings->remove_lightbox_context_js ); ?> />
								&nbsp;&nbsp;<b>Remove "lightbox_context.js"?</b>

								<span class="nggobasic-optimization-description">
								&nbsp;&nbsp;Optimizer's "Enhanced Fancybox Lightbox" or "No Lightbox" must be selected.
								</span>
							</label>
						</div>

						<div class="nggobasic-optimization-option">
							<label>
								<input id="<?php echo $this->db_name ?>[remove_common_js_and_cdata]" name="<?php echo $this->db_name ?>[remove_common_js_and_cdata]" type="checkbox" value="1"<?php checked( 1, $this->settings->remove_common_js_and_cdata ); ?> />
								&nbsp;&nbsp;<b>Remove "common.js" and its 1.5KB inline CDATA block?</b>

								<span class="nggobasic-optimization-description">
								&nbsp;&nbsp;Optimizer's "Enhanced Fancybox Lightbox" or "No Lightbox" must be selected.
								</span>
							</label>
						</div>

						<div class="nggobasic-optimization-option">
							<label>
								<input id="<?php echo $this->db_name ?>[remove_photocrati_ajax_js_and_cdata]" name="<?php echo $this->db_name ?>[remove_photocrati_ajax_js_and_cdata]" type="checkbox" value="1"<?php checked( 1, $this->settings->remove_photocrati_ajax_js_and_cdata ); ?> />
								&nbsp;&nbsp;<b>Remove "ajax.js" and its "photocrati_ajax" CDATA block?</b>

								<span class="nggobasic-optimization-description">
									&nbsp;&nbsp;Not required for AJAX pagination. Empty file in NextGEN Gallery v2.0 to v2.0.66.37.
								</span>
							</label>
						</div>

						<div class="nggobasic-optimization-option">
							<label>
								<input id="<?php echo $this->db_name ?>[remove_lazy_resources_js]" name="<?php echo $this->db_name ?>[remove_lazy_resources_js]" type="checkbox" value="1"<?php checked( 1, $this->settings->remove_lazy_resources_js ); ?> />
								&nbsp;&nbsp;<b>Remove the resource minifier scripts "sidjs-0.1.js" and "lazy_resources.js"?</b>

								<span class="nggobasic-optimization-description">
								&nbsp;&nbsp;Present in early versions of NextGEN 2.0.
								</span>
							</label>
						</div>

						<div class="nggobasic-optimization-option">
							<label>
								<input id="<?php echo $this->db_name ?>[remove_frame_event_publisher_js]" name="<?php echo $this->db_name ?>[remove_frame_event_publisher_js]" type="checkbox" value="1"<?php checked( 1, $this->settings->remove_frame_event_publisher_js ); ?> />
								&nbsp;&nbsp;<b>Remove "frame_event_publisher.js"?</b>

								<span class="nggobasic-optimization-description">
								&nbsp;&nbsp;Present in early versions of NextGEN 2.0.
								</span>
							</label>
						</div>

						<div class="nggobasic-optimization-option">
							<label>
								<input id="<?php echo $this->db_name ?>[remove_ngg_store_js]" name="<?php echo $this->db_name ?>[remove_ngg_store_js]" type="checkbox" value="1"<?php checked( 1, $this->settings->remove_ngg_store_js ); ?> />
								&nbsp;&nbsp;<b>Remove persistent storage scripts "persist.js", "store.js", and "ngg_store.js"?</b>

								<span class="nggobasic-optimization-description">
								&nbsp;&nbsp;Helpful for subsequent views, but detrimental to the initial load.
								</span>
							</label>
						</div>

						<div class="nggobasic-optimization-option">
							<label>
								<input id="<?php echo $this->db_name ?>[remove_nextgen_basic_thumbnails_js]" name="<?php echo $this->db_name ?>[remove_nextgen_basic_thumbnails_js]" type="checkbox" value="1"<?php checked( 1, $this->settings->remove_nextgen_basic_thumbnails_js ); ?> />
								&nbsp;&nbsp;<b>Remove "nextgen_basic_thumbnails.js"?</b>

								<span class="nggobasic-optimization-description">
								&nbsp;&nbsp;Empty file in NextGEN Gallery v2.0 to v2.0.66.37.
								</span>
							</label>
						</div>

					</div><!-- Close .nggobasic-optimization-options-box -->

					<div class="nggobasic-optimization-title-box">
						<b>Optimize stylesheets:</b>
					</div>

					<div class="nggobasic-optimization-options-box">

						<div class="nggobasic-optimization-option">
							<label>
								<input id="<?php echo $this->db_name ?>[remove_font_awesome_css]" name="<?php echo $this->db_name ?>[remove_font_awesome_css]" type="checkbox" value="1"<?php checked( 1, $this->settings->remove_font_awesome_css ); ?> />
								&nbsp;&nbsp;<b>Remove "font-awesome.css"?</b>

								<span class="nggobasic-optimization-description">
								&nbsp;&nbsp;A seemingly unused 18KB file for scalable vector icons. Appears to be for NextGEN Pro.
								</span>
							</label>
						</div>

						<div class="nggobasic-optimization-option">
							<label>
								<input id="<?php echo $this->db_name ?>[remove_ngg_trigger_buttons_css]" name="<?php echo $this->db_name ?>[remove_ngg_trigger_buttons_css]" type="checkbox" value="1"<?php checked( 1, $this->settings->remove_ngg_trigger_buttons_css ); ?> />
								&nbsp;&nbsp;<b>Remove "trigger_buttons.css"?</b>

								<span class="nggobasic-optimization-description">
								&nbsp;&nbsp;A seemingly unused 1KB file to style trigger buttons. Again, appears to be for NextGEN Pro.
								</span>
							</label>
						</div>

						<div class="nggobasic-optimization-option">
							<label>
								<input id="<?php echo $this->db_name ?>[remove_related_images_css]" name="<?php echo $this->db_name ?>[remove_related_images_css]" type="checkbox" value="1"<?php checked( 1, $this->settings->remove_related_images_css ); ?> />
								&nbsp;&nbsp;<b>Remove "nextgen_gallery_related_images.css"?</b>

								<span class="nggobasic-optimization-description">
								&nbsp;&nbsp;Only if NextGEN's "Show Related Images on Posts?" setting is disabled, or enabled and we're not on a post.
								</span>
							</label>
						</div>

						<div class="nggobasic-optimization-option">
							<label>
								<input id="<?php echo $this->db_name ?>[remove_nextgen_pagination_css]" name="<?php echo $this->db_name ?>[remove_nextgen_pagination_css]" type="checkbox" value="1"<?php checked( 1, $this->settings->remove_nextgen_pagination_css ); ?> />
								&nbsp;&nbsp;<b>Remove "nextgen_pagination/static/style.css"?</b>

								<span class="nggobasic-optimization-description">
								&nbsp;&nbsp;For when you have pagination disabled at Gallery --> Gallery Settings --> NextGEN Basic Thumbnails.
								</span>
							</label>
						</div>

						<div class="nggobasic-optimization-option">
							<label>
								<input id="<?php echo $this->db_name ?>[remove_custom_stylesheet]" name="<?php echo $this->db_name ?>[remove_custom_stylesheet]" type="checkbox" value="1"<?php checked( 1, $this->settings->remove_custom_stylesheet ); ?> />
								&nbsp;&nbsp;<b>Remove the custom stylesheet?</b>

								<span class="nggobasic-optimization-description">
								&nbsp;&nbsp;Empty unless customized. Deactivates it at "Gallery --> Other Options --> Styles" (re-save if reset).
								</span>
							</label>
						</div>

					</div><!-- Close .nggobasic-optimization-options-box -->

				</div><!-- Close #nggobasic-nextgen-v2-advanced-settings-inner -->

			</div><!-- Close #nggobasic-nextgen-v2-advanced-settings. -->

			<?php } ?>

		</div>

		<div class="nggobasic-box">
			<h2><?php _e( 'Enhanced Fancybox Lightbox', 'nggobasic_domain' ); ?></h2>

			<label>
				<input id="<?php echo $this->db_name ?>[fancybox]" name="<?php echo $this->db_name ?>[fancybox]" type="checkbox" value="1"<?php checked( 1, $this->settings->fancybox ); ?> />
				&nbsp;&nbsp;<b>Use Optimizer's Enhanced <a href="http://fancybox.net" target="_blank">Fancybox</a> Lightbox effect?</b>
				&nbsp;<em><a href="http://www.nextgengalleryoptimizer.com/#enhanced-fancybox-lightbox" target="_blank">Learn more...</a></em>
			</label>

		</div>

		<div id="nggobasic-dfsi-box">
			<h2><?php _e( 'Dynamic Fancybox Settings Interface', 'nggobasic_domain' ); ?></h2>

			<span id="nggobasic-dfsi-description">
				Exclusive to <a href="http://www.nextgengalleryoptimizer.com/nextgen-gallery-optimizer-pro/" target="_blank">Optimizer Pro</a>, the Dynamic Fancybox Settings Interface feature is the fastest and easiest way to customize Fancybox.
				No coding. No front-end refreshes. And completely update-safe. See your changes as you make them.
				&nbsp;<a href="http://www.nextgengalleryoptimizer.com/docs/dynamic-fancybox-settings-interface/" target="_blank">Learn more...</a>
			</span>

			<a class="button-primary" title="Get NextGEN Gallery Optimizer Pro" alt="Get NextGEN Gallery Optimizer Pro" href="http://www.nextgengalleryoptimizer.com/nextgen-gallery-optimizer-pro/" target="_blank">Get Optimizer Pro</a>&nbsp;
			<a id="<?php echo $this->prefix; ?>-dynamic-fancybox-settings-interface" class="button-primary" title="Launch Optimizer Pro's Dynamic Fancybox Settings Interface" href="<?php echo admin_url( 'options-general.php?page=' . $this->fancybox_interface_slug ); ?>">Launch demo</a>

		</div>

		<div id="nggobasic-deactivator-box">
			<h2><?php _e( 'NextGEN Gallery Deactivator', 'nggobasic_domain' ); ?></h2>

			<span id="nggobasic-deactivator-description">
				Exclusive to <a href="http://www.nextgengalleryoptimizer.com/nextgen-gallery-optimizer-pro/" target="_blank">Optimizer Pro</a>, the NextGEN Gallery Deactivator feature substantially improves your site's page load speed on non-NextGEN pages,
				by excluding all of NextGEN's PHP code from being processed on the front-end of your site (except on pages you specify).
				&nbsp;<a href="http://www.nextgengalleryoptimizer.com/docs/nextgen-gallery-deactivator/" target="_blank">Learn more...</a>
			</span>

			<a class="button-primary" title="Get NextGEN Gallery Optimizer Pro" alt="Get NextGEN Gallery Optimizer Pro" href="http://www.nextgengalleryoptimizer.com/nextgen-gallery-optimizer-pro/" target="_blank">Get Optimizer Pro</a>

		</div>

		<div id="nggobasic-save-box">
				<input type="submit" id="nggobasic-save-button" class="button-primary" name="save" value="<?php _e( 'Save Options', 'nggobasic_domain' ); ?>" />
		</div>

		<div class="nggobasic-box">
			<h2><?php _e('Help', 'nggobasic_domain'); ?></h2>
			Need help getting started with Optimizer?<br />
			Please see the new <a href="http://www.nextgengalleryoptimizer.com/documentation/" target="_blank">documentation section</a> for a quick-start guide, customization overview and more.<br /><br />
			If you need further help or assistance, please visit Optimizer's <a href="http://wordpress.org/support/plugin/nextgen-gallery-optimizer/" target="_blank">Support Forum</a> at WordPress.org.<br />
			If you're an <a href="http://www.nextgengalleryoptimizer.com/nextgen-gallery-optimizer-pro/" target="_blank">Optimizer Pro</a> user, please see <a href="http://www.nextgengalleryoptimizer.com/support/" target="_blank">the support page</a> for one-on-one assistance from the developer.
		</div>

		<!-- Hidden input used to differentiate between our main settings page, and our Dynamic Fancybox Settings Interface during validation. -->
		<input type="hidden" id="<?php echo $this->db_name ?>[nggobasic-settings-form]" name="<?php echo $this->db_name ?>[nggobasic-settings-form]" value="true">

	</form>

</div><!-- End wrap -->