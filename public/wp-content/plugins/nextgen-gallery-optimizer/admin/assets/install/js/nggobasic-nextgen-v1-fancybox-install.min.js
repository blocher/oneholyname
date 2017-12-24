/**
 * Optimizer Basic's JavaScript for the lightbox settings page in NextGEN
 * Legacy and NextCellent Gallery.
 *
 * Requires jQuery v1.4.4+ as included in WordPress v3.1.
 *
 * Adds our select menu item in NextGEN v1 at Gallery --> Options -->
 * Effects.
 *
 * Also inserts a message at Gallery --> Options --> Effects, to help inform
 * users about the benefits of Optimizer's Enhanced Fancybox Lightbox, and
 * introduce them to the new Dynamic Fancybox Settings Interface available
 * in Optimizer Pro.
 *
 * This script loads on NextGEN's "Options" page only...
 * "http://www.yoursite.com/wp-admin/admin.php?page=nggallery-options".
 *
 * @package		NextGEN_Gallery_Optimizer_Basic
 * @author		Mark Jeldi | Helpful Media <http://www.nextgengalleryoptimizer.com/contact/>
 * @link				http://www.nextgengalleryoptimizer.com
 * @copyright	2012 - 2016 Mark Jeldi | Helpful Media
 * @since 			2.0.0
 */
;(function(m,a,n){var b,c,d,e,h=function(){var c=a("#thumbEffect").find(":selected").val(),f=nggobasicNextGENOptionsPage.enhancedFancyboxCode,g=a("#thumbCode"),h=g.val();b.prepend('<option value="'+d+'">'+d+"</option>");b.change(function(){e=b.find(":selected").val();d===e&&g.val(f)});c&&"none"!==c||f===a.trim(h)&&b.val(d)},l=function(){var a="",f="";b.change(function(){function g(){e=b.find(":selected").val();a||d===e&&0<c.css("opacity")||d!==e&&1>c.css("opacity")||(a=!0,f=!1,d===e?c.slideDown(600,
function(){c.fadeTo(600,1,function(){a=!1;!0===f&&g()})}):"1"===c.css("opacity")&&c.fadeTo(600,0,function(){c.slideUp(600,function(){a=!1;!0===f&&g()})}))}f=!0;g()})};a(document).ready(function(){b=a("#thumbEffect");d=nggobasicNextGENOptionsPage.enhancedFancyboxDisplayName;h();var k='<tr><th></th><td style="padding-top: 0; padding-right: 0;"><div id="nggobasic-message-box" style="color: #333;"><div class="nggobasic-message-box-text" style="background-color: #ddd; padding: 15px;">Why use Optimizer\'s <em>Enhanced</em> Fancybox Lightbox? <a href="http://www.nextgengalleryoptimizer.com/#enhanced-fancybox-lightbox" target="_blank">Learn more...</a></div><div class="nggobasic-message-box-text" style="background-color: #222; color: #fff; margin: 10px 0 2px; padding: 15px;">NEW! <em>Customize</em> Fancybox with Optimizer Pro\'s <a style="color: #fff;" href="'+
nggobasicNextGENOptionsPage.optimizerSettingsURL+'&autoload=true" target="_blank"><b>Dynamic Fancybox Settings Interface</b></a>.</div></div></td></tr>';b.parent().parent().after(k).find("th").css({"min-width":"120px","padding-bottom":"0","padding-top":"15px"});b.parent().css({"padding-top":"0","padding-bottom":"0","padding-right":"0"});b.css("margin-left","0");c=a(".nggobasic-message-box-text");e=b.find(":selected").val();d!==e&&c.css({opacity:"0",display:"none"});0<a("#slider").length&&"0px"==a("#slider").css("margin-left")&&
b.parent().css({"padding-top":"0","padding-bottom":"9px","padding-right":"0"});l()})})(window.nggobasicFancyboxInstall=window.nggobasicFancyboxInstall||{},jQuery);jQuery.noConflict();