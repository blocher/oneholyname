<?php
while(!is_file('wp-load.php')){
  if(is_dir('..' . DIRECTORY_SEPARATOR)) chdir('..' . DIRECTORY_SEPARATOR);
  else die('Error: Could not construct path to wp-load.php - please check <a href="http://mapsmarker.com/path-error">http://mapsmarker.com/path-error</a> for more details');
}
include( 'wp-load.php' );
if (get_option('leafletmapsmarker_update_info') == 'show') {
$lmm_version_old = get_option( 'leafletmapsmarker_version_pro_before_update' );
$lmm_version_new = get_option( 'leafletmapsmarker_version_pro' );

$text_a = __('Changelog for version %s','lmm');
$text_b = __('released on','lmm');
$text_c = __('blog post with more details about this release','lmm');
$text_d = __('Translation updates','lmm');
$text_e = __('In case you want to help with translations, please visit the <a href="%1s" target="_blank">web-based translation plattform</a>','lmm');
$text_f = __('Known issues','lmm');
$new = '<img src="' . LEAFLET_PLUGIN_URL .'inc/img/icon-changelog-new.png">';
$changed = '<img src="' . LEAFLET_PLUGIN_URL .'inc/img/icon-changelog-changed.png">';
$fixed = '<img src="' . LEAFLET_PLUGIN_URL .'inc/img/icon-changelog-fixed.png">';
$transl = '<img src="' . LEAFLET_PLUGIN_URL .'inc/img/icon-changelog-translations.png">';
$issue = '<img src="' . LEAFLET_PLUGIN_URL .'inc/img/icon-changelog-know-issues.png">';
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
<title>Changelog for Maps Marker Pro</title>
<style type="text/css">
<?php 
if ( function_exists( 'is_rtl' ) && is_rtl() ) {
	echo 'body{font-family:sans-serif;font-size:12px;line-height:1.4em;margin:0;padding:0 0 0 5px;direction: rtl;unicode-bidi: embed;}'.PHP_EOL;
} else {
	echo 'body{font-family:sans-serif;font-size:12px;line-height:1.4em;margin:0;padding:0 0 0 5px;}'.PHP_EOL;
} ?>
table{line-height:.7em;font-size:12px;font-family:sans-serif}
td{line-height:1.1em}
.updated{background-color:#FFFFE0;padding:10px}
a{color:#21759B;text-decoration:none}
a:hover,a:active,a:focus{color:#D54E21}
hr{color:#E6DB55}
</style></head><body>
<?php
/*****************************************************************************************/
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.9.1') . '</strong> - ' . $text_b . ' 11.10.2014 (<a href="https://www.mapsmarker.com/v1.9.1p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
support for accent folding for API and importer geocoding calls (to better support special chars)
</td></tr>
<tr><td>' . $new . '</td><td>
compatibility check for Sucuri Security plugin which breaks maps if option "Restrict wp-content access" is active
</td></tr>
<tr><td>' . $changed . '</td><td>
MapsMarker API: use "MapsMarker API" as createdby & updatedby attribute if not set
</td></tr>
<tr><td>' . $fixed . '</td><td>
leaflet-min.css was not properly loaded on RTL themes (thx Nic!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
potential CSS conflict resulting in geolocate icon not being shown (thx Christopher!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
custom default marker icon was not saved when creating a new marker map (thx Oleg!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
custom panel background for marker maps was taken from layer map settings (thx Bernd!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
API delete action for markers was broken (thx Jason!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
"Delete all markers from all layers" function on tools page did not delete cached QR code images
</td></tr>
<tr><td>' . $fixed . '</td><td>
Google+Bing language localizations could be broken since WordPress 4.0 as constant WPLANG has been depreciated
</td></tr>
<tr><td>' . $fixed . '</td><td>
Bing culture parameter was ignored and fallback set to en-US when constant WPLANG with hypen was used 
</td></tr>
<tr><td>' . $fixed . '</td><td>
MapsMarker API search action did not work as designed if popuptext or address was empty (thx Jason!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
RSS & Atom feeds for marker and layer maps did not validate with http://validator.w3.org
</td></tr>
<tr><td>' . $fixed . '</td><td>
remove slashes before single apostrophes (Arc d\\\'airain) in addresses for new maps / on map updates (thx Guffroy!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
sort order on "list all markers" page was broken on page 2+ if custom sort order was selected (thx kluong!)
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Danish translation thanks to Mads Dyrmann Larsen and Peter Erfurt, <a href="http://24-7news.dk" target="_blank">http://24-7news.dk</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated French translation thanks to Vincèn Pujol, <a href="http://www.skivr.com" target="_blank">http://www.skivr.com</a> and Rodolphe Quiedeville, <a href="http://rodolphe.quiedeville.org" target="_blank">http://rodolphe.quiedeville.org</a>, Fx Benard, <a href="http://wp-translator.com" target="_blank">http://wp-translator.com</a>, cazal cédric, <a href="http://www.cedric-cazal.com" target="_blank">http://www.cedric-cazal.com</a> and Fabian Hurelle, <a href="http://hurelle.fr" target="_blank">http://hurelle.fr</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Polish translation thanks to Pawel Wyszy&#324;ski, <a href="http://injit.pl" target="_blank">http://injit.pl</a>, Tomasz Rudnicki, <a href="http://www.kochambieszczady.pl" target="_blank"></a> and Robert Pawlak
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Spanish translation thanks to Alvaro Lara, <a href="http://www.alvarolara.com" target="_blank">http://www.alvarolara.com</a>, Victor Guevara, <a href="http://1sistemas.net" target="_blank">http://1sistemas.net</a>, Ricardo Viteri, <a href="http://www.labviteri.com" target="_blank">http://www.labviteri.com</a> and Juan Valdes
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Swedish translation thanks to Olof Odier <a href="http://www.historiskastadsvandringar.se" target="_blank">http://www.historiskastadsvandringar.se</a>, Tedy Warsitha <a href="http://codeorig.in/" target="_blank">http://codeorig.in/</a>, Dan Paulsson <a href="http://www.paulsson.eu" target="_blank">http://www.paulsson.eu</a>, Elger Lindgren, <a href="http://20x.se" target="_blank">http://20x.se</a> and Anton Andreasson, <a href="http://andreasson.org/" target="_blank">http://andreasson.org/</a>
</td></tr>
</table>'.PHP_EOL;

if ( (version_compare($lmm_version_old,"1.9","<")) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.9') . '</strong> - ' . $text_b . ' 30.08.2014 (<a href="https://www.mapsmarker.com/v1.9p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
geolocation support: show and follow your location when viewing maps
</td></tr>
<tr><td>' . $new . '</td><td>
added IE11 native fullscreen support
</td></tr>
<tr><td>' . $new . '</td><td>
search function for layerlist on marker edit page
</td></tr>
<tr><td>' . $new . '</td><td>
support for using WMTS servers as custom overlays (thx dimizu!)
</td></tr>
<tr><td>' . $new . '</td><td>
compatibility check for plugin "WP External Links" which can cause maps to break
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized RTL (right-to-left) language support
</td></tr>
<tr><td>' . $changed . '</td><td>
updated jQuery select2 addon to v3.5.1
</td></tr>
<tr><td>' . $changed . '</td><td>
added backticks (`) around column and table names in all SQL statements to prevent collisions with reserved words
</td></tr>
<tr><td>' . $fixed . '</td><td>
some settings were not selectable when RTL (right-to-left) language support was active
</td></tr>
<tr><td>' . $fixed . '</td><td>
custom overlays and custom basemaps with & and {} chars in URLs were broken
</td></tr>
<tr><td>' . $fixed . '</td><td>
fullscreen mode for multiple maps on one page
</td></tr>
<tr><td>' . $fixed . '</td><td>
cancel fullscreen mode did not work with Firefox 31
</td></tr>
<tr><td>' . $fixed . '</td><td>
additional output (0) before maps created with shortcodes directly (thx Bernd!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
default marker icon was not used for maps created with shortcodes directly (thx Bernd!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
broken layer maps/plugin installations on mySQL instances using <i>clustering</i> as reserved word (thx Tim!)
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Bosnian translation thanks to Kenan Dervišević, <a href="http://dkenan.com" target="_blank">http://dkenan.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Polish translation thanks to Pawel Wyszy&#324;ski, <a href="http://injit.pl" target="_blank">http://injit.pl</a>, Tomasz Rudnicki, <a href="http://www.kochambieszczady.pl" target="_blank"></a> and Robert Pawlak
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Russian translation thanks to Ekaterina Golubina (supported by Teplitsa of Social Technologies - <a href="http://te-st.ru" target="_blank">http://te-st.ru</a>) and Vyacheslav Strenadko, <a href="http://poi-gorod.ru" target="_blank">http://poi-gorod.ru</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a>, Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a> and Flo Bejgu, <a href="http://www.inboxtranslation.com" target="_blank">http://www.inboxtranslation.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( (version_compare($lmm_version_old,"1.8.1","<")) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.8.1') . '</strong> - ' . $text_b . ' 22.07.2014 (<a href="https://www.mapsmarker.com/v1.8.1p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
<a href="https://www.mapsmarker.com/2014/07/22/10-discount-code-to-celebrate-the-1st-anniversary-of-maps-marker-pro/" target="_blank">10% discount code to celebrate the 1st anniversary of Maps Marker Pro</a>
</td></tr>
<tr><td>' . $new . '</td><td>
<a href="https://www.mapsmarker.com" target="_blank">enabled SSL by default for MapsMarker.com website & installed EV SSL certificate (=verified identity)</a>
</td></tr>
<tr><td>' . $new . '</td><td>
compatibility check for "Page Builder by SiteOrigin" plugin (thx porga!)
</td></tr>
<tr><td>' . $new . '</td><td>
tested against WordPress 4.0
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized version compare functions by using PHP version_compare();
</td></tr>
<tr><td>' . $fixed . '</td><td>
not all sections within settings could be selected on smaller screens (thx Francesco!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
display of popuptext in GeoRSS feed was broken (thx Indrajit!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
fixed broken incompatibility check with Better WordPress Minify plugin v1.3.0
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese translation thanks to John Shen, <a href="http://www.synyan.net" target="_blank">http://www.synyan.net</a> and ck
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Latvian translation thanks to Juris Orlovs, <a href="http://lbpa.lv" target="_blank">http://lbpa.lv</a> and Eriks Remess <a href="http://geekli.st/Eriks" target="_blank">http://geekli.st/Eriks</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Russian translation thanks to Ekaterina Golubina (supported by Teplitsa of Social Technologies - <a href="http://te-st.ru" target="_blank">http://te-st.ru</a>) and Vyacheslav Strenadko, <a href="http://poi-gorod.ru" target="_blank">http://poi-gorod.ru</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( (version_compare($lmm_version_old,"1.8","<")) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.8') . '</strong> - ' . $text_b . ' 27.06.2014 (<a href="https://www.mapsmarker.com/v1.8p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
layer maps: center map on markers and open popups by clicking on list of markers entries
</td></tr>
<tr><td>' . $new . '</td><td>
new tool for monitoring "active shortcodes for already deleted maps"
</td></tr>
<tr><td>' . $new . '</td><td>
option to disable Google Places Autocomplete API on backend (for <a href="http://travel.synyan.net" target="_blank">John</a> & other users in countries, where access to Google APIs is blocked)
</td></tr>
<tr><td>' . $changed . '</td><td>
replaced discontinued predefined MapBox tiles "MapBox Streets" with "Natural Earth I"
</td></tr>
<tr><td>' . $fixed . '</td><td>
input field for marker and layer zoom on backend was too small on mobile devices
</td></tr>
<tr><td>' . $fixed . '</td><td>
undefined index PHP warnings on maps created with shortcodes only
</td></tr>
<tr><td>' . $fixed . '</td><td>
backslashes in popuptexts resulted in broken layer maps - now replaced with slashes (thx Dmitry!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
option to hide new mapsmarker.com blogposts and link section in dashboard widget was broken
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Turkish translation thanks to Emre Erkan, <a href="http://www.karalamalar.net" target="_blank">http://www.karalamalar.net</a> and Mahir Tosun, <a href="http://www.bozukpusula.com" target="_blank">http://www.bozukpusula.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( (version_compare($lmm_version_old,"1.7","<")) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.7') . '</strong> - ' . $text_b . ' 07.06.2014 (<a href="https://www.mapsmarker.com/v1.7p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $changed . '</td><td>
upgrade to leaflet.js v0.7.3 (maintenance release with 8 bugfixes, <a href="https://github.com/Leaflet/Leaflet/blob/master/CHANGELOG.md#073-may-23-2014" target="_blank">changelog</a>)
</td></tr>
<tr><td>' . $changed . '</td><td>
update marker cluster codebase (using build 28/05/14 instead of 14/03/14)
</td></tr>
<tr><td>' . $changed . '</td><td>
show more detailed error messages on issues with mapsmarker.com license API calls
</td></tr>
<tr><td>' . $fixed . '</td><td>
image edit+remove overlay buttons in TinyMCE editor for popuptexts on marker edit pages were missing since WordPress 3.9 (thx <a href="http://dorf.vsgtaegerwilen.ch" target="_blank">Bruno</a>)
</td></tr>
<tr><td>' . $fixed . '</td><td>
tiles for Google Maps disappeared during zoom when pinch zooming on mobile phones
</td></tr>
<tr><td>' . $fixed . '</td><td>
broken license API calls on servers with outdated SSL libraries
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Italian translation thanks to Luca Barbetti, <a href="http://twitter.com/okibone" target="_blank">http://twitter.com/okibone</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a>, Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a> and Flo Bejgu, <a href="http://www.inboxtranslation.com" target="_blank">http://www.inboxtranslation.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.6' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.6') . '</strong> - ' . $text_b . ' 18.05.2014 (<a href="https://www.mapsmarker.com/v1.6p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
improved performance for layer maps by asynchronous loading of markers via GeoJSON
</td></tr>
<tr><td>' . $new . '</td><td>
added support for loading maps within jQuery Mobile frameworks (thanks Håkan!)
</td></tr>
<tr><td>' . $new . '</td><td>
option to disable loading of Google Maps API for higher performance if alternative basemaps are used only
</td></tr>
<tr><td>' . $new . '</td><td>
map parameters can be overwritten within shortcodes (e.g. [mapsmarker marker="1" height="100"]) - <a href="https://www.mapsmarker.com/shortcodes" target="_blank">see available shortcode parameters</a>
</td></tr>
<tr><td>' . $new . '</td><td>
added support for GeoJSON-API-links for multi-layer-maps in map panels 
</td></tr>
<tr><td>' . $new . '</td><td>
added new sort order options for "list of markers" below layer maps (popuptext, icon, created by, updated by, kml_timestamp)
</td></tr>
<tr><td>' . $changed . '</td><td>
significantly improve loading time for huge layer maps by limiting (hidden) geo microformat tags
</td></tr>
<tr><td>' . $changed . '</td><td>
update import-export library PHPExcel to v1.8.0 (<a href="https://github.com/PHPOffice/PHPExcel/blob/develop/changelog.txt" target="_blank">changelog</a>)
</td></tr>
<tr><td>' . $changed . '</td><td>
increase timeout for loading gpx files from 10 to 30 seconds to better support larger files
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized CSS classes and removed inline-styles for list of markers-table for better custom styling
</td></tr>
<tr><td>' . $changed . '</td><td>
updated jQuery timepicker addon to v1.4.4
</td></tr>
<tr><td>' . $changed . '</td><td>
updated jQuery select2 addon for settings to v3.4.8
</td></tr>
<tr><td>' . $changed . '</td><td>
hardened icon upload function to better prevent potential directory traversal attacks
</td></tr>
<tr><td>' . $changed . '</td><td>
renamed transient for proxy access to avoid plugin conflicts (thanks <a href="https://twitter.com/pippinsplugins" target="_blank">@pippinsplugins</a>!)
</td></tr>
<tr><td>' . $changed . '</td><td>
hardened SQL queries for multi-layer-maps
</td></tr>
<tr><td>' . $fixed . '</td><td>
&lt;ol&gt; and &lt;ul&gt; lists were not shown correctly in popuptexts (thanks <a href="http://storyv.com/world/" target="_blank">Dan</a>!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
wrong line-height applied to panel api images could break map layout on certain themes (thx K.W.!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
if number of markers within a cluster was 5 digits or more, a linebreak was added
</td></tr>
<tr><td>' . $fixed . '</td><td>
potential low-critical PHP object injection vulnerabilities with PHPExcel, discovered by <a href="https://security.dxw.com/" target="_blank">https://security.dxw.com/</a>
</td></tr>
<tr><td>' . $fixed . '</td><td>
issues on plugin updates on servers with PHP 5.5 and ioncube support
</td></tr>
<tr><td>' . $fixed . '</td><td>
license key propagation to subsites on multisite installations was broken
</td></tr>
<tr><td>' . $fixed . '</td><td>
uploaded icons were not saved in the marker icon directory on multisite installations
</td></tr>
<tr><td>' . $fixed . '</td><td>
GPX tracks were not shown on layer maps if Google Adsense was active
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese (zh_TW) translation thanks to jamesho Ho, <a href="http://outdooraccident.org" target="_blank">http://outdooraccident.org</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated French translation thanks to Vincèn Pujol, <a href="http://www.skivr.com" target="_blank">http://www.skivr.com</a> and Rodolphe Quiedeville, <a href="http://rodolphe.quiedeville.org" target="_blank">http://rodolphe.quiedeville.org</a>, Fx Benard, <a href="http://wp-translator.com" target="_blank">http://wp-translator.com</a>, cazal cédric, <a href="http://www.cedric-cazal.com" target="_blank">http://www.cedric-cazal.com</a> and Fabian Hurelle, <a href="http://hurelle.fr" target="_blank">http://hurelle.fr</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Italian translation thanks to Luca Barbetti, <a href="http://twitter.com/okibone" target="_blank">http://twitter.com/okibone</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Latvian translation thanks to Juris Orlovs, <a href="http://lbpa.lv" target="_blank">http://lbpa.lv</a> and Eriks Remess <a href="http://geekli.st/Eriks" target="_blank">http://geekli.st/Eriks</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Polish translation thanks to Pawel Wyszy&#324;ski, <a href="http://injit.pl" target="_blank">http://injit.pl</a>, Tomasz Rudnicki, <a href="http://www.kochambieszczady.pl" target="_blank"></a> and Robert Pawlak
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a> and Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.5.9' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.5.9') . '</strong> - ' . $text_b . ' 13.04.2014 (<a href="https://www.mapsmarker.com/v1.5.9p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
Maps Marker Pro reseller program launched - see <a href="https://www.mapsmarker.com/reseller" target="_blank">https://www.mapsmarker.com/reseller</a> for more details
</td></tr>
<tr><td>' . $new . '</td><td>
show warning message if incompatible plugin "Root Relative URLs" is active (thx Brad!)
</td></tr>
<tr><td>' . $changed . '</td><td>
plugin updates are now delivered via SSL to prevent man-in-the-middle-attacks (supporting <a href="https://www.resetthenet.org/" target="_blank">resetthenet.org</a> - <a href="http://mapsmarker.com/helpdesk" target="_blank">please report any issues</a>!)
</td></tr>
<tr><td>' . $changed . '</td><td>
remove plugin version used from source code on frontend to prevent information disclosure
</td></tr>
<tr><td>' . $changed . '</td><td>
remove source code comment about Maps Marker Pro when "remove backlink" option is enabled
</td></tr>
<tr><td>' . $fixed . '</td><td>
update plugin-update-checker to v1.5 (as it may conflict with other plugins using this library, resulting in no info about new updates - thx Shepherd!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
fixed potential XSS issues (exploitable by admins only)
</td></tr>
<tr><td>' . $fixed . '</td><td>
attribution for mapbox 2 basemap was wrong on marker and layer edit pages
</td></tr>
<tr><td>' . $fixed . '</td><td>
WMS demo layer "Vienna public toilets" was not shown on KML view (fixed on new installations only to not overwrite existing custom settings)
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese translation thanks to John Shen, <a href="http://www.synyan.net" target="_blank">http://www.synyan.net</a> and ck
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Russian translation thanks to Ekaterina Golubina (supported by Teplitsa of Social Technologies - <a href="http://te-st.ru" target="_blank">http://te-st.ru</a>) and Vyacheslav Strenadko, <a href="http://poi-gorod.ru" target="_blank">http://poi-gorod.ru</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Turkish translation thanks to Emre Erkan, <a href="http://www.karalamalar.net" target="_blank">http://www.karalamalar.net</a> and Mahir Tosun, <a href="http://www.bozukpusula.com" target="_blank">http://www.bozukpusula.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.5.8' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.5.8') . '</strong> - ' . $text_b . ' 27.03.2014 (<a href="https://www.mapsmarker.com/v1.5.8p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
add css classes markermap/layermap and marker-ID/layer-ID to each map div for better custom styling
</td></tr>
<tr><td>' . $new . '</td><td>
option to add markernames to popups automatically (default = false)
</td></tr>
<tr><td>' . $new . '</td><td>
allow admins to change createdby and createdon information for marker and layer maps
</td></tr>
<tr><td>' . $new . '</td><td>
display an alert for unsaved changes before leaving marker/layer edit or settings pages
</td></tr>
<tr><td>' . $new . '</td><td>
new tool to clear QR code images cache
</td></tr>
<tr><td>' . $new . '</td><td>
map moves back to initial position on marker maps after popup is closed
</td></tr>
<tr><td>' . $new . '</td><td>
added support for gif and jpg marker icons
</td></tr>
<tr><td>' . $changed . '</td><td>
replaced option "maximum width for images in popups" with option "CSS for images in popups" (<strong>action is needed if you changed maximum width for images in popups!</strong>)
</td></tr>
<tr><td>' . $changed . '</td><td>
switch to persistent javascript variable names instead of random numbers on frontend (thx Sascha!)
</td></tr>
<tr><td>' . $changed . '</td><td>
remove support for Cloudmade basemaps as free tile service is discontinued (->changing basemap to OSM for maps using Cloudmade)
</td></tr>
<tr><td>' . $changed . '</td><td>
layer center pin on backend now always stays on top of markers and is now a bit transparent (thx Sascha!)
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized live preview of popup content on marker edit page (now also showing current address for directions link)
</td></tr>
<tr><td>' . $changed . '</td><td>
removed option "extra CSS for table cells" for list of markers
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized backend loadtimes on marker+layer updates (not loading plugin header twice anymore; next: AJAX ;-)
</td></tr>
<tr><td>' . $changed . '</td><td>
improved plugin security by implementing recommendations resulting from second security audit by the City of Vienna
</td></tr>
<tr><td>' . $changed . '</td><td>
license verification calls are now done via WordPress HTTP API, supporting proxies configured in wp-config.php
</td></tr>
<tr><td>' . $changed . '</td><td>
use WordPress HTTP API instead of cURL() for custom marker icons and shadow check
</td></tr>
<tr><td>' . $changed . '</td><td>
use wp_handle_upload() for icon upload instead of WP_Filesystem() for better security
</td></tr>
<tr><td>' . $changed . '</td><td>
update marker cluster codebase (using build 14/03/14 instead of 21/01/14)
</td></tr>
<tr><td>' . $changed . '</td><td>
set appropriate title for HTML5 fullscreen button (view fullscreen/exit fullscreen)
</td></tr>
<tr><td>' . $fixed . '</td><td>
marker icon selection on backend was broken on Internet Explorer 11 (use of other browsers is recommended generally)
</td></tr>
<tr><td>' . $fixed . '</td><td>
Maps Marker API: validity check for post requests for createdon/updatedon parameter failed (thx Sascha!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
added clear:both; to directions link in popup text to fix display of floating images (thx Sascha!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
custom css for marker clusters was not used if shortcode is used within a template file or widget
</td></tr>
<tr><td>' . $fixed . '</td><td>
link to directions settings in marker popup texts on marker edit pages was broken (visible on advanced editor only)
</td></tr>
<tr><td>' . $fixed . '</td><td>
dynamic preview of WMS layers was broken on backend since v1.5.7
</td></tr>
<tr><td>' . $fixed . '</td><td>
potential cross site scripting issues (mostly exploitable by admin users only)
</td></tr>
<tr><td>' . $fixed . '</td><td>
wpdb::prepare() warning message on Wikitude API output for layer maps
</td></tr>
<tr><td>' . $fixed . '</td><td>
visual tinyMCE editor was broken on marker edit and tools pages since WordPress 3.9-alpha
</td></tr>
<tr><td>' . $fixed . '</td><td>
icon upload button was broken since WordPress 3.9-alpha
</td></tr>
<tr><td>' . $fixed . '</td><td>
escaping of input values with mysql_real_escape_string() was broken since WordPress 3.9-alpha (now replaced with esc_sql())
</td></tr>
<tr><td>' . $fixed . '</td><td>
resetting the settings was broken since WordPress 3.9-alpha (now replaced with esc_sql())
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Latvian translation thanks to Juris Orlovs, <a href="http://lbpa.lv" target="_blank">http://lbpa.lv</a> and Eriks Remess <a href="http://geekli.st/Eriks" target="_blank">http://geekli.st/Eriks</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a> and Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Spanish translation thanks to Alvaro Lara, <a href="http://www.alvarolara.com" target="_blank">http://www.alvarolara.com</a>, Victor Guevara, <a href="http://1sistemas.net" target="_blank">http://1sistemas.net</a>, Ricardo Viteri, <a href="http://www.labviteri.com" target="_blank">http://www.labviteri.com</a> and Juan Valdes
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Swedish translation thanks to Olof Odier <a href="http://www.historiskastadsvandringar.se" target="_blank">http://www.historiskastadsvandringar.se</a>, Tedy Warsitha <a href="http://codeorig.in/" target="_blank">http://codeorig.in/</a>, Dan Paulsson <a href="http://www.paulsson.eu" target="_blank">http://www.paulsson.eu</a>, Elger Lindgren, <a href="http://20x.se" target="_blank">http://20x.se</a> and Anton Andreasson, <a href="http://andreasson.org/" target="_blank">http://andreasson.org/</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.5.7' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.5.7') . '</strong> - ' . $text_b . ' 01.03.2014 (<a href="https://www.mapsmarker.com/v1.5.7p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
support for dynamic switching between simplified and advanced editor (no more reloads needed)
</td></tr>
<tr><td>' . $new . '</td><td>
more secure authentication method for <a href="https://www.mapsmarker.com/mapsmarker-api">MapsMarker API</a> (<strong>old method with public key only is not supported anymore!</strong>)
</td></tr>
<tr><td>' . $new . '</td><td>
new <a href="https://www.mapsmarker.com/mapsmarker-api">MapsMarker API</a> search action with support for bounding box searches and more
</td></tr>
<tr><td>' . $new . '</td><td>
support for filtering of marker icons on backend (based on filename)
</td></tr>
<tr><td>' . $new . '</td><td>
support for changing marker IDs and layer IDs from the tools page
</td></tr>
<tr><td>' . $new . '</td><td>
support for bulk updates of marker maps on the tools page for selected layers only
</td></tr>
<tr><td>' . $new . '</td><td>
<a href="https://www.mapsmarker.com/order" target="_blank">store on mapsmarker.com</a> now also accepts Diners Club credit cards
</td></tr>
<tr><td>' . $changed . '</td><td>
updated marker edit page (optimized marker icons display, less whitespace for better workflow, added "Advanced settings" row)
</td></tr>
<tr><td>' . $changed . '</td><td>
checkbox for multi layer maps is now also visible by default on layer edit pages
</td></tr>
<tr><td>' . $changed . '</td><td>
WMS legend link is not added to WMS attribution if legend link is empty
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized input on backend by adding labels to all form elements
</td></tr>
<tr><td>' . $fixed . '</td><td>
single quotes in marker map names were escaped (thx Eric!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
double quotes in marker map names would break maps if marker was updated/created via import
</td></tr>
<tr><td>' . $fixed . '</td><td>
double quotes in marker map names would break maps if marker was updated via API
</td></tr>
<tr><td>' . $fixed . '</td><td>
parameter clustering on layer view action in Maps Marker API did not return any results
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese translation thanks to John Shen, <a href="http://www.synyan.net" target="_blank">http://www.synyan.net</a> and ck
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Czech translation thanks to Viktor Kleiner and Vlad Kuzba, <a href="http://kuzbici.eu" target="_blank">http://kuzbici.eu</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated French translation thanks to Vincèn Pujol, <a href="http://www.skivr.com" target="_blank">http://www.skivr.com</a> and Rodolphe Quiedeville, <a href="http://rodolphe.quiedeville.org" target="_blank">http://rodolphe.quiedeville.org</a>, Fx Benard, <a href="http://wp-translator.com" target="_blank">http://wp-translator.com</a>, cazal cédric, <a href="http://www.cedric-cazal.com" target="_blank">http://www.cedric-cazal.com</a> and Fabian Hurelle, <a href="http://hurelle.fr" target="_blank">http://hurelle.fr</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a> and Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Slovak translation thanks to Zdenko Podobny
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_f . '</a></p></strong>
</td></tr>
<tr><td>' . $issue . '</td><td>
custom marker cluster colors do not show up on backend layer maps if WordPress <3.7 is used - upgrade is advised!
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.5.6' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.5.6') . '</strong> - ' . $text_b . ' 10.02.2014 (<a href="https://www.mapsmarker.com/v1.5.6p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
import and export of layer maps as CSV/XLS/XLSX/ODS file
</td></tr>
<tr><td>' . $new . '</td><td>
support for conditional SSL loading of Javascript for Google Maps to increase performance (thx John!)
</td></tr>
<tr><td>' . $new . '</td><td>
re-added option to load javascript in header (for conflicts with certain themes and plugins, default: footer)
</td></tr>
<tr><td>' . $new . '</td><td>
added check if browser support window.console for displaying gpx track status info on backend
</td></tr>
<tr><td>' . $changed . '</td><td>
icons on marker maps and layer maps center icon on backend are now also draggable (thx Sascha for the hint!)
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized mysql queries for list all marker admin page and georss-feeds (by removing concat()-function)
</td></tr>
<tr><td>' . $changed . '</td><td>
use plugin name "Maps Marker Pro" instead of "Leaflet Maps Marker" for texts on plugin-inactive-checks and for wp_nonce-messages
</td></tr>
<tr><td>' . $changed . '</td><td>
renamed plugin from "Leaflet Maps Marker Pro" to "Maps Marker Pro" on WordPress plugins page for better consistency
</td></tr>
<tr><td>' . $fixed . '</td><td>
marker import verification could fail under certain circumstances
</td></tr>
<tr><td>' . $fixed . '</td><td>
removed display of custom css on backend map pages on WordPress <3.7 (=bug solved with WordPress 3.7)
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese translation thanks to John Shen, <a href="http://www.synyan.net" target="_blank">http://www.synyan.net</a> and ck
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Danish translation thanks to Mads Dyrmann Larsen and Peter Erfurt, <a href="http://24-7news.dk" target="_blank">http://24-7news.dk</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a> and Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Spanish translation thanks to Alvaro Lara, <a href="http://www.alvarolara.com" target="_blank">http://www.alvarolara.com</a>, Victor Guevara, <a href="http://1sistemas.net" target="_blank">http://1sistemas.net</a> and Ricardo Viteri, <a href="http://www.labviteri.com" target="_blank">http://www.labviteri.com</a>
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_f . '</a></p></strong>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.5.5' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.5.5') . '</strong> - ' . $text_b . ' 31.01.2014 (<a href="https://www.mapsmarker.com/v1.5.5p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
loading progress bar for markerclusters when loading of markers takes longer than 1 second
</td></tr>
<tr><td>' . $changed . '</td><td>
updated Google Maps codebase (removed boolean that will always execute)
</td></tr>
<tr><td>' . $changed . '</td><td>
split leaflet.js in leaflet-core.js and leaflet-addons.js to utilize parallel loading
</td></tr>
<tr><td>' . $changed . '</td><td>
minimized leaflet.css into leaflet.min.css to save a few kb
</td></tr>
<tr><td>' . $changed . '</td><td>
removed option to add javascript to header (as popuptext got broken; default was footer)
</td></tr>
<tr><td>' . $changed . '</td><td>
removed option to disabled conditional css loading (=only load leaflet.css when shortcode used)
</td></tr>
<tr><td>' . $changed . '</td><td>
removed workarounds for WordPress <3.3 for better performance
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated French translation thanks to Vincèn Pujol, <a href="http://www.skivr.com" target="_blank">http://www.skivr.com</a> and Rodolphe Quiedeville, <a href="http://rodolphe.quiedeville.org" target="_blank">http://rodolphe.quiedeville.org</a>, Fx Benard, <a href="http://wp-translator.com" target="_blank">http://wp-translator.com</a>, cazal cédric, <a href="http://www.cedric-cazal.com" target="_blank">http://www.cedric-cazal.com</a> and Fabian Hurelle, <a href="http://hurelle.fr" target="_blank">http://hurelle.fr</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Turkish translation thanks to Emre Erkan, <a href="http://www.karalamalar.net" target="_blank">http://www.karalamalar.net</a> and Mahir Tosun, <a href="http://www.bozukpusula.com" target="_blank">http://www.bozukpusula.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.5.4' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.5.4') . '</strong> - ' . $text_b . ' 24.01.2014 (<a href="https://www.mapsmarker.com/v1.5.4p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $changed . '</td><td>
optimized TinyMCE media button integration for posts/pages (showing button just once & design update)
</td></tr>
<tr><td>' . $changed . '</td><td>
improved performance for marker edit pages and posts/pages (by removing TinyMCE scripts and additional WordPress initialization)
</td></tr>
<tr><td>' . $changed . '</td><td>
improved performance for dynamic changelog (by removing additional WordPress initialization)
</td></tr>
<tr><td>' . $changed . '</td><td>
improved performance for gpx loading on backend (by recuding database queries needed)
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized css loading on backend (load leaflet.css only on marker and layer edit pages)
</td></tr>
<tr><td>' . $changed . '</td><td>
removed backend compatibility check for flickr-gallery plugin
</td></tr>
<tr><td>' . $changed . '</td><td>
GeoJSON API: add marker=all parameter & only allow all/* to list all markers
</td></tr>
<tr><td>' . $changed . '</td><td>
KML API: add marker=all parameter & only allow all/* to list all markers
</td></tr>
<tr><td>' . $changed . '</td><td>
add minimap css styles for Internet Explorer < 9 (thx kermit-the-frog!)
</td></tr>
<tr><td>' . $changed . '</td><td>
update ioncube loader wizard to v2.40
</td></tr>
<tr><td>' . $changed . '</td><td>
update jQuery timepicker addon to v1.43
</td></tr>
<tr><td>' . $changed . '</td><td>
reduced http requests for jquery time picker addon css on marker edit page
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized backend performance by reducing SQL queries and http requests on new layer edit page
</td></tr>
<tr><td>' . $changed . '</td><td>
only show first 25 characters for layernames in select box on marker edit page in order not to break page layout
</td></tr>
<tr><td>' . $changed . '</td><td>
reduced mysql queries on layer edit page by showing marker count for multi-layer-maps only on demand
</td></tr>
<tr><td>' . $fixed . '</td><td>
fit bounds on GPX additions and click on "fit bounds"-link were broken
</td></tr>
<tr><td>' . $fixed . '</td><td>
bing maps were broken if https was used due to changes in the bing url templates
</td></tr>
<tr><td>' . $fixed . '</td><td>
PHP error log entries when Wikitude API was called with specific parameters
</td></tr>
<tr><td>' . $fixed . '</td><td>
GeoRSS API for marker parameter displayed incorrect titles
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
<a href="https://translate.mapsmarker.com/projects/lmm" target="_blank">new design template on translation.mapsmarker.com & support for SSL-login</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Italian translation thanks to Luca Barbetti, <a href="http://twitter.com/okibone" target="_blank">http://twitter.com/okibone</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.5.3' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.5.3') . '</strong> - ' . $text_b . ' 17.01.2014 (<a href="https://www.mapsmarker.com/v1.5.3p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
upgrade to <a href="https://github.com/Leaflet/Leaflet/blob/master/CHANGELOG.md#072-january-17-2014" target="_blank">leaflet.js v0.7.2</a> (fixing a zooming bug with Chrome 32)
</td></tr>
<tr><td>' . $new . '</td><td>
Vietnamese (vi) translation thanks to Hoai Thu, <a href="http://bizover.net" target="_blank">http://bizover.net</a>
</td></tr>
<tr><td>' . $new . '</td><td>
increased security by loading basemaps for OSM, Mapbox and OGD Vienna via SSL if WordPress also loads via SSL
</td></tr>
<tr><td>' . $new . '</td><td>
increased security by hardening search input field for markers on backend
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized performance by moving version checks for PHP and WordPress to register_activation_hook()
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized performance by running pro active check only on admin pages
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese translation thanks to John Shen, <a href="http://www.synyan.net" target="_blank">http://www.synyan.net</a> and ck
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Norwegian (Bokmål) translation thanks to Inge Tang, <a href="http://drommemila.no" target="_blank">http://drommemila.no</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Polish translation thanks to Tomasz Rudnicki, <a href="http://www.kochambieszczady.pl" target="_blank">http://www.kochambieszczady.pl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Russian translation thanks to Ekaterina Golubina (supported by Teplitsa of Social Technologies - <a href="http://te-st.ru" target="_blank">http://te-st.ru</a>) and Vyacheslav Strenadko, <a href="http://poi-gorod.ru" target="_blank">http://poi-gorod.ru</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Spanish translation thanks to Alvaro Lara, <a href="http://www.alvarolara.com" target="_blank">http://www.alvarolara.com</a>, Victor Guevara, <a href="http://1sistemas.net" target="_blank">http://1sistemas.net</a> and Ricardo Viteri, <a href="http://www.labviteri.com" target="_blank">http://www.labviteri.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Turkish translation thanks to Emre Erkan, <a href="http://www.karalamalar.net" target="_blank">http://www.karalamalar.net</a> and Mahir Tosun, <a href="http://www.bozukpusula.com" target="_blank">http://www.bozukpusula.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.5.2' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.5.2') . '</strong> - ' . $text_b . ' 21.12.2013 (<a href="https://www.mapsmarker.com/v1.5.2p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
<a href="https://www.mapsmarker.com/bitcoin"  target="_top">MapsMarker.com now also supports bitcoin payments</a>
</td></tr>
<tr><td>' . $new . '</td><td>
warning message on importer if . instead of , is used as coma separater for lat/lon values (thx Yannick!)
</td></tr>
<tr><td>' . $new . '</td><td>
additional check if loaded GPX file is valid
</td></tr>
<tr><td>' . $new . '</td><td>
added marker cluster fallback colors for IE6-8 (via markercluster codebase update to v0.4)
</td></tr>
<tr><td>' . $changed . '</td><td>
updated markercluster codebase to v0.4 (<a href="https://github.com/Leaflet/Leaflet.markercluster/blob/master/CHANGELOG.md" target="_blank">changelog</a>)
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized minimap control box to better fit leaflet design (thx robpvn!)
</td></tr>
<tr><td>' . $changed . '</td><td>
use WordPress wp_remove_get() function instead of proprietary proxy for fetching GPX files
</td></tr>
<tr><td>' . $changed . '</td><td>
switched from wp_remote_post() to wp_remove_get() to avoid occasional IIS7.0 issues (thx Chas!)
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized detailed import log messages to better indicate if test mode is on
</td></tr>
<tr><td>' . $fixed . '</td><td>
import log showed wrong row number on marker updates
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese translation thanks to John Shen, <a href="http://www.synyan.net" target="_blank">http://www.synyan.net</a> and ck
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Indonesian translation thanks to Andy Aditya Sastrawikarta and Emir Hartato, <a href="http://whateverisaid.wordpress.com" target="_blank">http://whateverisaid.wordpress.com</a> and Phibu Reza, <a href="http://www.dedoho.pw/" target="_blank">http://www.dedoho.pw/</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Italian translation thanks to Luca Barbetti, <a href="http://twitter.com/okibone" target="_blank">http://twitter.com/okibone</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Korean translation thanks to Andy Park, <a href="http://wcpadventure.com" target="_blank">http://wcpadventure.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Latvian translation thanks to Juris Orlovs, <a href="http://lbpa.lv" target="_blank">http://lbpa.lv</a> and Eriks Remess <a href="http://geekli.st/Eriks" target="_blank">http://geekli.st/Eriks</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Norwegian (Bokmål) translation thanks to Inge Tang, <a href="http://drommemila.no" target="_blank">http://drommemila.no</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Polish translation thanks to Tomasz Rudnicki, <a href="http://www.kochambieszczady.pl" target="_blank">http://www.kochambieszczady.pl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a> and Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.5.1' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.5.1') . '</strong> - ' . $text_b . ' 07.12.2013 (<a href="https://www.mapsmarker.com/v1.5.1p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
upgrade to leaflet.js v0.7.1 with 7 bugfixes (<a href="https://github.com/Leaflet/Leaflet/blob/master/CHANGELOG.md#071-december-6-2013" target="_blank">detailed changelog</a>)
</td></tr>
<tr><td>' . $new . '</td><td>
duplicate markers feature
</td></tr>
<tr><td>' . $new . '</td><td>
option to use Google Maps API for Business for csv/xls/xlsx/ods import geocoding (which allows up to 100.000 instead of 2.500 requests per day)
</td></tr>
<tr><td>' . $changed . '</td><td>
geocoding for csv/xls/xlsx/ods import: if Google Maps API returns error OVER_QUERY_LIMIT, wait 1.5sec and try again once
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized backend pages for WordPress 3.8/MP6 theme (re-added separator lines, reduce white space usage)
</td></tr>
<tr><td>' . $changed . '</td><td>
geocoding for MapsMarker API requests: if Google Maps API returns error OVER_QUERY_LIMIT, wait 1.5sec and try again once
</td></tr>
<tr><td>' . $changed . '</td><td>
hardened SQL statements needed for fullscreen maps by additionally using prepared-statements
</td></tr>
<tr><td>' . $changed . '</td><td>
change main menu and admin bar entry from "Maps Marker" to "Maps Marker Pro" again to avoid confusion with lite version
</td></tr>
<tr><td>' . $changed . '</td><td>
removed link from main admin bar menu entry ("Maps Marker Pro") for better usability on mobile devices
</td></tr>
<tr><td>' . $fixed . '</td><td>
broken terms of service and feedback links on Google marker maps
</td></tr>
<tr><td>' . $fixed . '</td><td>
broken Google Adsense ad links on layer maps
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese translation thanks to John Shen, <a href="http://www.synyan.net" target="_blank">http://www.synyan.net</a> and ck
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Indonesian translation thanks to Andy Aditya Sastrawikarta and Emir Hartato, <a href="http://whateverisaid.wordpress.com" target="_blank">http://whateverisaid.wordpress.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Italian translation thanks to Luca Barbetti, <a href="http://twitter.com/okibone" target="_blank">http://twitter.com/okibone</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.5' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.5') . '</strong> - ' . $text_b . ' 01.12.2013 (<a href="https://www.mapsmarker.com/v1.5p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
upgrade to leaflet.js v0.7 with lots of improvements and bugfixes (more infos: <a href="http://leafletjs.com/2013/11/18/leaflet-0-7-released-plans-for-future.html" target="_blank">release notes</a> and <a href="https://github.com/Leaflet/Leaflet/blob/master/CHANGELOG.md#07-november-18-2013" target="_blank">detailed changelog</a>)
</td></tr>
<tr><td>' . $new . '</td><td>
global maximum zoom level (21) for all basemaps with automatic upscaling if native maximum zoom level is lower
</td></tr>
<tr><td>' . $new . '</td><td>
improved accessibility by adding marker name as alt attribute for marker icon
</td></tr>
<tr><td>' . $new . '</td><td>
compatibility with WordPress 3.8/MP6 (responsive admin template)
</td></tr>
<tr><td>' . $changed . '</td><td>
HTML5 fullscreen updates: support for retina icon + different icon for on/off
</td></tr>
<tr><td>' . $changed . '</td><td>
cleaned up admin dashboard widget (showing blog post titles only)
</td></tr>
<tr><td>' . $changed . '</td><td>
visualead QR code generation: API key needed for custom image url, added support for caching - see blog post for more details
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized license settings page for registering free 30-day-trials
</td></tr>
<tr><td>' . $fixed . '</td><td>
maps break if the option worldCopyJump is set to true
</td></tr>
<tr><td>' . $fixed . '</td><td>
toogle layers control image was not shown on mobile devices with retina display
</td></tr>
<tr><td>' . $fixed . '</td><td>
undefined index message on pro plugin activation
</td></tr>
<tr><td>' . $fixed . '</td><td>
fullscreen layer maps with no panel showed wrong layer center (thx Massimo!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
PHP warning message with debug enabled on license page when no license key was entered
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese translation thanks to John Shen, <a href="http://www.synyan.net" target="_blank">http://www.synyan.net</a> and ck
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Czech translation thanks to Viktor Kleiner and Vlad Kuzba, <a href="http://kuzbici.eu" target="_blank">http://kuzbici.eu</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Latvian translation thanks to Juris Orlovs, <a href="http://lbpa.lv" target="_blank">http://lbpa.lv</a> and Eriks Remess <a href="http://geekli.st/Eriks" target="_blank">http://geekli.st/Eriks</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Norwegian (Bokmål) translation thanks to Inge Tang, <a href="http://drommemila.no" target="_blank">http://drommemila.no</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Polish translation thanks to Tomasz Rudnicki, <a href="http://www.kochambieszczady.pl" target="_blank">http://www.kochambieszczady.pl</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.4' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.4') . '</strong> - ' . $text_b . ' 16.11.2013 (<a href="https://www.mapsmarker.com/v1.4p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_import_export" target="_top">support for CSV/XLS/XLSX/ODS import and export for bulk additions and bulk updates of markers</a>
</td></tr>
<tr><td>' . $new . '</td><td>
Norwegian (Bokmål) translation thanks to Inge Tang, <a href="http://drommemila.no" target="_blank">http://drommemila.no</a>
</td></tr>
<tr><td>' . $new . '</td><td>
added a check if marker icon directory is writeable before trying to upload new icons
</td></tr>
<tr><td>' . $changed . '</td><td>
switched from curl() to wp_remote_post() on API geocoding calls for higher compatibility
</td></tr>
<tr><td>' . $changed . '</td><td>
updated markercluster codebase (<a href="https://github.com/Leaflet/Leaflet.markercluster/commits/master" target="_blank">using build from 13/11/2013</a>)
</td></tr>
<tr><td>' . $changed . '</td><td>
Improved error handling on metadata errors on bing maps - use console.log() instead of alert()
</td></tr>
<tr><td>' . $changed . '</td><td>
ensure zoom levels of google maps and leaflet maps stay in sync
</td></tr>
<tr><td>' . $changed . '</td><td>
remove zoomanim event handler in onRemove on google maps
</td></tr>
<tr><td>' . $fixed . '</td><td>
alignment of panel and list marker icon images could be broken on certain themes
</td></tr>
<tr><td>' . $fixed . '</td><td>
added fix for loading maps in woocommerce tabs (thx Glenn!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
default error tile image and map deleted image showed wrong www.mapsmarker.com url (ups)
</td></tr>
<tr><td>' . $fixed . '</td><td>
backslashes in map name and address broke GeoJSON output (and thus layer maps) - now replaced with /
</td></tr>
<tr><td>' . $fixed . '</td><td>
tabs in popuptext (character literals) broke GeoJSON output (and thus layer maps) - now replaced with space
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese translation thanks to John Shen, <a href="http://www.synyan.net" target="_blank">http://www.synyan.net</a> and ck
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese (zh_TW) translation thanks to jamesho Ho, <a href="http://outdooraccident.org" target="_blank">http://outdooraccident.org</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Czech translation thanks to Viktor Kleiner and Vlad Kuzba, <a href="http://kuzbici.eu" target="_blank">http://kuzbici.eu</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated French translation thanks to Vincèn Pujol, <a href="http://www.skivr.com" target="_blank">http://www.skivr.com</a> and Rodolphe Quiedeville, <a href="http://rodolphe.quiedeville.org" target="_blank">http://rodolphe.quiedeville.org</a>, Fx Benard, <a href="http://wp-translator.com" target="_blank">http://wp-translator.com</a>, cazal cédric, <a href="http://www.cedric-cazal.com" target="_blank">http://www.cedric-cazal.com</a> and Fabian Hurelle, <a href="http://hurelle.fr" target="_blank">http://hurelle.fr</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Indonesian translation thanks to Andy Aditya Sastrawikarta and Emir Hartato, <a href="http://whateverisaid.wordpress.com" target="_blank">http://whateverisaid.wordpress.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Italian translation thanks to Luca Barbetti, <a href="http://twitter.com/okibone" target="_blank">http://twitter.com/okibone</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Latvian translation thanks to Juris Orlovs, <a href="http://lbpa.lv" target="_blank">http://lbpa.lv</a> and Eriks Remess <a href="http://geekli.st/Eriks" target="_blank">http://geekli.st/Eriks</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Polish translation thanks to Tomasz Rudnicki, <a href="http://www.kochambieszczady.pl" target="_blank">http://www.kochambieszczady.pl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a> and Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.3.1' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.3.1') . '</strong> - ' . $text_b . ' 09.10.2013 (<a href="https://www.mapsmarker.com/v1.3.1p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
new options to set text color in marker cluster circles (thanks Simon!)
</td></tr>
<tr><td>' . $changed . '</td><td>
removed shortcode parsing in popup texts from layer maps completely
</td></tr>
<tr><td>' . $fixed . '</td><td>
GeoJSON output for markers did not display marker name if parameter full was set to no
</td></tr>
<tr><td>' . $fixed . '</td><td>
GeoJSON output could break if special characters were used in markername
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese (zh_TW) translation thanks to jamesho Ho, <a href="http://outdooraccident.org" target="_blank">http://outdooraccident.org</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Latvian translation thanks to Juris Orlovs, <a href="http://lbpa.lv" target="_blank">http://lbpa.lv</a> and Eriks Remess <a href="http://geekli.st/Eriks" target="_blank">http://geekli.st/Eriks</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Polish translation thanks to Tomasz Rudnicki, <a href="http://www.kochambieszczady.pl" target="_blank">http://www.kochambieszczady.pl</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.3' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.3') . '</strong> - ' . $text_b . ' 08.10.2013 (<a href="https://www.mapsmarker.com/v1.3p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
support for shortcodes in popup texts (with some limitations - <a href="https://www.mapsmarker.com/v1.3p" target="_blank">see release notes</a>)
</td></tr>
<tr><td>' . $new . '</td><td>
set marker cluster colors in settings / map defaults / marker clustering settings
</td></tr>
<tr><td>' . $new . '</td><td>
optimized marker and layer admin pages for mobile devices
</td></tr>
<tr><td>' . $new . '</td><td>
notification about new pro versions now also works if access to plugin updates has expired
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized GeoJSON-mySQL-statement (less memory needed now on each execution)
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized GeoJSON-output of directions link (using separate parameter dlink now)
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized minimap toogle icon (with transition effect, thank robpvn!)
</td></tr>
<tr><td>' . $changed . '</td><td>
removed workaround for former incompatibility with jetpack plugin (has been fixed with jetpack 2.2)
</td></tr>
<tr><td>' . $changed . '</td><td>
make custom update checker more consistent with how WP handles plugin updates (<a href="https://github.com/YahnisElsts/plugin-update-checker/commit/c3a8325c2d81be96c795aaf955aed44e1873f251" target="_blank">details</a>)
</td></tr>
<tr><td>' . $changed . '</td><td>
updated markercluster codebase (<a href="https://github.com/Leaflet/Leaflet.markercluster/commits/master" target="_blank">using build from 25/08/2013</a>)
</td></tr>
<tr><td>' . $fixed . '</td><td>
tabs from address now get removed on edits as this breakes GeoJSON/layer maps (thx Chris!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
save button in settings was not accessible with certain languages active (thx Herbert!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
htmlspecialchars in marker name (< > &) were not shown correctly on hover text (thx fredel+devEdge!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
update class conflict with WordPress "quick edit" feature when debug bar plugin is active (<a href="https://github.com/YahnisElsts/plugin-update-checker/commit/2edd17e" target="_blank">details</a>)
</td></tr>
<tr><td>' . $fixed . '</td><td>
deleting layers when using custom capability settings was broken on layer edit page
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese (zh_TW) translation thanks to jamesho Ho, <a href="http://outdooraccident.org" target="_blank">http://outdooraccident.org</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Czech translation thanks to Viktor Kleiner and Vlad Kuzba, <a href="http://kuzbici.eu" target="_blank">http://kuzbici.eu</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated French translation thanks to Vincèn Pujol, <a href="http://www.skivr.com" target="_blank">http://www.skivr.com</a> and Rodolphe Quiedeville, <a href="http://rodolphe.quiedeville.org" target="_blank">http://rodolphe.quiedeville.org</a>, Fx Benard, <a href="http://wp-translator.com" target="_blank">http://wp-translator.com</a>, cazal cédric, <a href="http://www.cedric-cazal.com" target="_blank">http://www.cedric-cazal.com</a> and Fabian Hurelle, <a href="http://hurelle.fr" target="_blank">http://hurelle.fr</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Indonesian translation thanks to Andy Aditya Sastrawikarta and Emir Hartato, <a href="http://whateverisaid.wordpress.com" target="_blank">http://whateverisaid.wordpress.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Latvian translation thanks to Juris Orlovs, <a href="http://lbpa.lv" target="_blank">http://lbpa.lv</a> and Eriks Remess <a href="http://geekli.st/Eriks" target="_blank">http://geekli.st/Eriks</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Polish translation thanks to Tomasz Rudnicki, <a href="http://www.kochambieszczady.pl" target="_blank">http://www.kochambieszczady.pl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a> and Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.2.1' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.2.1') . '</strong> - ' . $text_b . ' 14.09.2013 (<a href="https://www.mapsmarker.com/v1.2.1p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
<a title="click here for more information" href="https://www.mapsmarker.com/affiliateid" target="_blank">support for MapsMarker affiliate links instead of default backlinks - sign up as an affiliate and receive commissions up to 50% !</a>
</td></tr>
<tr><td>' . $changed . '</td><td>
parsing of GeoJSON for layer maps is now up to 3 times faster by using JSON.parse instead of eval()
</td></tr>
<tr><td>' . $changed . '</td><td>
improved gpx backend proxy security by adding transients
</td></tr>
<tr><td>' . $changed . '</td><td>
using WordPress function antispambot() instead of own function hide_email() for API links
</td></tr>
<tr><td>' . $changed . '</td><td>
display gpx fitbounds-link already on focusing gpx url field (when pasting gpx URL manually)
</td></tr>
<tr><td>' . $fixed . '</td><td>
MapsMarker API - icon-parameter could not be set (always returned null) - thx Hovhannes!
</td></tr>
<tr><td>' . $fixed . '</td><td>
fixed broken settings page when plugin wp photo album plus was active (thx Martin!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
Wikitude API was not accepted on registration if ar:name was empty (now using map type + id as fallback)
</td></tr>
<tr><td>' . $fixed . '</td><td>
plugin uninstall did not remove all database entries completely on multisite installations
</td></tr>
<tr><td>' . $fixed . '</td><td>
incorrect warning on multisite installations to upgrade to latest free version before uninstalling
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Bosnian translation thanks to Kenan Dervišević, <a href="http://dkenan.com" target="_blank">http://dkenan.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese translation thanks to John Shen, <a href="http://www.synyan.net" target="_blank">http://www.synyan.net</a> and ck
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese (zh_TW) translation thanks to jamesho Ho, <a href="http://outdooraccident.org" target="_blank">http://outdooraccident.org</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Czech translation thanks to Viktor Kleiner and Vlad Kuzba, <a href="http://kuzbici.eu" target="_blank">http://kuzbici.eu</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated French translation thanks to Vincèn Pujol, <a href="http://www.skivr.com" target="_blank">http://www.skivr.com</a> and Rodolphe Quiedeville, <a href="http://rodolphe.quiedeville.org" target="_blank">http://rodolphe.quiedeville.org</a>, Fx Benard, <a href="http://wp-translator.com" target="_blank">http://wp-translator.com</a>, cazal cédric, <a href="http://www.cedric-cazal.com" target="_blank">http://www.cedric-cazal.com</a> and Fabian Hurelle, <a href="http://hurelle.fr" target="_blank">http://hurelle.fr</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Indonesian translation thanks to Andy Aditya Sastrawikarta and Emir Hartato, <a href="http://whateverisaid.wordpress.com" target="_blank">http://whateverisaid.wordpress.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Latvian translation thanks to Juris Orlovs, <a href="http://lbpa.lv" target="_blank">http://lbpa.lv</a> and Eriks Remess <a href="http://geekli.st/Eriks" target="_blank">http://geekli.st/Eriks</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Polish translation thanks to Tomasz Rudnicki, <a href="http://www.kochambieszczady.pl" target="_blank">http://www.kochambieszczady.pl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a> and Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.2' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.2') . '</strong> - ' . $text_b . ' 31.08.2013 (<a href="https://www.mapsmarker.com/v1.2p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
support for displaying GPX tracks on marker and layer maps
</td></tr>
<tr><td>' . $new . '</td><td>
option to whitelabel backend admin pages
</td></tr>
<tr><td>' . $new . '</td><td>
advanced permission settings
</td></tr>
<tr><td>' . $new . '</td><td>
optimized settings page (added direct links, return to last seen page after saving and full-text-search)
</td></tr>
<tr><td>' . $changed . '</td><td>
removed visualead logo and backlink from QR code output pages
</td></tr>
<tr><td>' . $changed . '</td><td>
changed minimum required WordPress version from v3.0 to v3.3 (needed for tracks)
</td></tr>
<tr><td>' . $changed . '</td><td>
increased database field for multi layer maps from 255 to 4000 (allowing you to add more layers to a multi layer map)
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized marker and layer edit page (widened first column to better fit different browsers)
</td></tr>
<tr><td>' . $changed . '</td><td>
allow custom icon upload only if user has the capability upload_files
</td></tr>
<tr><td>' . $changed . '</td><td>
optimized default backlinks and added QR-link to visualead
</td></tr>
<tr><td>' . $changed . '</td><td>
reduced maximum zoom level for bing maps to 19 as 21 is not supported worldwide
</td></tr>
<tr><td>' . $fixed . '</td><td>
API does not break anymore if parameter type is not set to json or xml
</td></tr>
<tr><td>' . $fixed . '</td><td>
marker icons in widgets were not aligned correctly on IE<9 on some themes
</td></tr>
<tr><td>' . $fixed . '</td><td>
javascript errors on backend pages when clicking "show more" links
</td></tr>
<tr><td>' . $fixed . '</td><td>
Using W3 Total Cache >=v0.9.3 with active CDN no longer requires custom config
</td></tr>
<tr><td>' . $fixed . '</td><td>
wrong image url on on backend edit pages resulting in 404 http request
</td></tr>
<tr><td>' . $fixed . '</td><td>
wrong css url on on tools page resulting in 404 http request
</td></tr>
<tr><td>' . $fixed . '</td><td>
plugin install failed if php_uname() had been disabled for security reasons (thx Stefan!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
Wikitude API was broken when multiple multi-layer-maps were selected
</td></tr>
<tr><td>' . $fixed . '</td><td>
broken settings page when other plugins enqueued jQueryUI on all admin pages
</td></tr>
<tr><td>' . $fixed . '</td><td>
undefined index error messages on recent marker widget with debug enabled
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $new . '</td><td>
Spanish/Mexico translation thanks to Victor Guevera, <a href="http://1sistemas.net" target="_blank">http://1sistemas.net</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Catalan translation thanks to Efraim Bayarri, <a href="http://replicantsfactory.com" target="_blank">http://replicantsfactory.com</a> and  Vicent Cubells, <a href="http://vcubells.net" target="_blank">http://vcubells.net</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese translation thanks to John Shen, <a href="http://www.synyan.net" target="_blank">http://www.synyan.net</a> and ck
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Croatian translation thanks to Neven Pausic, <a href="http://www.airsoft-hrvatska.com" target="_blank">http://www.airsoft-hrvatska.com</a>, Alan Benic and Marijan Rajic, <a href="http://www.proprint.hr" target="_blank">http://www.proprint.hr</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Czech translation thanks to Viktor Kleiner and Vlad Kuzba, <a href="http://kuzbici.eu" target="_blank">http://kuzbici.eu</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated French translation thanks to Vincèn Pujol, <a href="http://www.skivr.com" target="_blank">http://www.skivr.com</a> and Rodolphe Quiedeville, <a href="http://rodolphe.quiedeville.org" target="_blank">http://rodolphe.quiedeville.org</a>, Fx Benard, <a href="http://wp-translator.com" target="_blank">http://wp-translator.com</a> and cazal cédric, <a href="http://www.cedric-cazal.com" target="_blank">http://www.cedric-cazal.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Italian translation thanks to Luca Barbetti, <a href="http://twitter.com/okibone" target="_blank">http://twitter.com/okibone</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Latvian translation thanks to Juris Orlovs, <a href="http://lbpa.lv" target="_blank">http://lbpa.lv</a> and Eriks Remess <a href="http://geekli.st/Eriks" target="_blank">http://geekli.st/Eriks</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a> and Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Spanish translation thanks to Alvaro Lara, <a href="http://www.alvarolara.com" target="_blank">http://www.alvarolara.com</a> and <a href="http://www.labviteri.com" target="_blank">http://www.labviteri.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Ukrainian translation thanks to Andrexj, <a href="http://all3d.com.ua" target="_blank">http://all3d.com.ua</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.1.2' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.1.2') . '</strong> - ' . $text_b . ' 10.08.2013 (<a href="https://www.mapsmarker.com/v1.1.2p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $changed . '</td><td>
tweaked transparency for minimap toogle display (thx <a href="http://twitter.com/robpvn" target="_blank">@robpvn</a>!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
maps did not load correctly in (jquery ui) tabs (thx <a href="http://twitter.com/leafletjs" target="_blank">@leafletjs</a>!)
</td></tr>
<tr><td>' . $fixed . '</td><td>
icon upload button got broken with WordPress 3.6
</td></tr>
<tr><td>' . $fixed . '</td><td>
undefined index messages on license activation if debug is enabled
</td></tr>
<tr><td>' . $fixed . '</td><td>
console warning message "Resource interpreted as script but transferred with MIME type text/plain."
</td></tr>
<tr><td>' . $fixed . '</td><td>
preview of qr code image in settings was broken
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Catalan translation thanks to Efraim Bayarri, <a href="http://replicantsfactory.com" target="_blank">http://replicantsfactory.com</a> and  Vicent Cubells, <a href="http://vcubells.net" target="_blank">http://vcubells.net</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Czech translation thanks to Viktor Kleiner and Vlad Kuzba, <a href="http://kuzbici.eu" target="_blank">http://kuzbici.eu</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Latvian translation thanks to Juris Orlovs, <a href="http://lbpa.lv" target="_blank">http://lbpa.lv</a> and Eriks Remess <a href="http://geekli.st/Eriks" target="_blank">http://geekli.st/Eriks</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a> and Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.1.1' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.1.1') . '</strong> - ' . $text_b . ' 06.08.2013 (<a href="https://www.mapsmarker.com/v1.1.1p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
added option to start an anonymous free 30-day-trial period
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Catalan translation thanks to Efraim Bayarri, <a href="http://replicantsfactory.com" target="_blank">http://replicantsfactory.com</a> and  Vicent Cubells, <a href="http://vcubells.net" target="_blank">http://vcubells.net</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Chinese translation thanks to John Shen, <a href="http://www.synyan.net" target="_blank">http://www.synyan.net</a> and ck
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Czech translation thanks to Viktor Kleiner and Vlad Kuzba, <a href="http://kuzbici.eu" target="_blank">http://kuzbici.eu</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated French translation thanks to Vincèn Pujol, <a href="http://www.skivr.com" target="_blank">http://www.skivr.com</a> and Rodolphe Quiedeville, <a href="http://rodolphe.quiedeville.org" target="_blank">http://rodolphe.quiedeville.org</a>, Fx Benard, <a href="http://wp-translator.com" target="_blank">http://wp-translator.com</a> and cazal cédric, <a href="http://www.cedric-cazal.com" target="_blank">http://www.cedric-cazal.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a> and Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Spanish translation thanks to Alvaro Lara, <a href="http://www.alvarolara.com" target="_blank">http://www.alvarolara.com</a>, Victor Guevara, <a href="http://1sistemas.net" target="_blank">http://1sistemas.net</a> and Ricardo Viteri, <a href="http://www.labviteri.com" target="_blank">http://www.labviteri.com</a>
</td></tr>
</table>'.PHP_EOL;
}

if ( ( $lmm_version_old < '1.1' ) && ( $lmm_version_old > '0' ) ) {
echo '<p><hr noshade size="1"/></p>';
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.1') . '</strong> - ' . $text_b . ' 02.08.2013 (<a href="https://www.mapsmarker.com/v1.1p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>
upgraded leaflet.js ("the engine of this plugin") from v0.5.1 to v0.6.4 - please see <a href="http://leafletjs.com/2013/06/26/leaflet-0-6-released-dc-code-sprint-mapbox.html" target="_blank">blog post on leafletjs.com</a> and <a href="https://github.com/Leaflet/Leaflet/blob/master/CHANGELOG.md" target="_blank">full changelog</a> for more details
</td></tr>
<tr><td>' . $new . '</td><td>
Leaflet Maps Marker Pro can now be tested on localhost installations without time limitation and on up to 25 domains on live installations
</td></tr>
<tr><td>' . $new . '</td><td>
added option to switch update channel and download new beta releases (not advised on production sites!)
</td></tr>
<tr><td>' . $new . '</td><td>
minimap now also supports bing maps
</td></tr>
<tr><td>' . $new . '</td><td>
show compatibility warning if plugin "Dreamgrow Scrolled Triggered Box" is active (which is causing settings page to break)
</td></tr>
<tr><td>' . $changed . '</td><td>
move scale control up when using Google basemaps in order not to hide the Google logo (thx Kendall!)
</td></tr>
<tr><td>' . $changed . '</td><td>
reset option worldCopyJump to new default false instead of true (as advised by leaflet API docs)
</td></tr>
<tr><td>' . $changed . '</td><td>
using uglify v2 instead of v1 for javascript minification
</td></tr>
<tr><td>' . $fixed . '</td><td>
minimaps caused main map to zoom change on move with low zoom
</td></tr>
<tr><td>' . $fixed . '</td><td>
do not load Google Adsense ads on minimaps
</td></tr>
<tr><td>' . $fixed . '</td><td>
fixed warning message "constant SUHOSIN_PATCH not found"
</td></tr>
<tr><td>' . $fixed . '</td><td>
fixed warning message "Cannot modify header information" when plugin woocommerce is active
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Bosnian translation thanks to Kenan Dervišević, <a href="http://dkenan.com" target="_blank">http://dkenan.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Croatian translation thanks to Neven Pausic, <a href="http://www.airsoft-hrvatska.com" target="_blank">http://www.airsoft-hrvatska.com</a>, Alan Benic and Marijan Rajic, <a href="http://www.proprint.hr" target="_blank">http://www.proprint.hr</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Dutch translation thanks to Patrick Ruers, <a href="http://www.stationskwartiersittard.nl" target="_blank">http://www.stationskwartiersittard.nl</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Korean translation thanks to Andy Park, <a href="http://wcpadventure.com" target="_blank">http://wcpadventure.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Latvian translation thanks to Juris Orlovs, <a href="http://lbpa.lv" target="_blank">http://lbpa.lv</a> and Eriks Remess <a href="http://geekli.st/Eriks" target="_blank">http://geekli.st/Eriks</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Romanian translation thanks to Arian, <a href="http://administrare-cantine.ro" target="_blank">http://administrare-cantine.ro</a> and Daniel Codrea, <a href="http://www.inadcod.com" target="_blank">http://www.inadcod.com</a>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Slovak translation thanks to Zdenko Podobny
</td></tr>
<tr><td>' . $transl . '</td><td>
updated Spanish translation thanks to Alvaro Lara, <a href="http://www.alvarolara.com" target="_blank">http://www.alvarolara.com</a>, Victor Guevara, <a href="http://1sistemas.net" target="_blank">http://1sistemas.net</a> and Ricardo Viteri, <a href="http://www.labviteri.com" target="_blank">http://www.labviteri.com</a>
</td></tr>
</table>'.PHP_EOL;
}
echo '</div>';

/*******************************************************************************************************************************/
/* 2do: change version numbers and date in first line on each update and add if ( ($lmm_version_old < 'x.x' ) ){ to old changelog
********************************************************************************************************************************
echo '<p style="margin:0.5em 0 0 0;"><strong>' . sprintf($text_a, '1.6') . '</strong> - ' . $text_b . ' xx.11.2014 (<a href="https://www.mapsmarker.com/v1.6p" target="_blank">' . $text_c . '</a>):</p>
<table>
<tr><td>' . $new . '</td><td>

</td></tr>
<tr><td>' . $changed . '</td><td>

</td></tr>
<tr><td>' . $fixed . '</td><td>

</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_d . '</a></p></strong>
<p>' . sprintf($text_e, 'https://translate.mapsmarker.com/projects/lmm') . '</p>
</td></tr>
<tr><td>' . $transl . '</td><td>
updated German translation
</td></tr>
<tr><td colspan="2">
<p><strong>' . $text_f . '</a></p></strong>
</td></tr>
</table>'.PHP_EOL;

echo '<p><hr noshade size="1"/></p>';
*******************************************************************************************************************************/
?>
</body>
</html>
<?php } ?>