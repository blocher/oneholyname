
=== Plugin Name ===
Contributors: christopherross
Plugin URI: http://thisismyurl.com/plugins/our-progress/
Donate link:  http://thisismyurl.com/
Tags: progress, counter, thermometer, fund, raising, fundraising
Requires at least: 2.0.0
Tested up to: 3.5.1
Stable tag: 1.0.1

Fundraising Thermometer Plugin for WordPress allows WordPress to display a thermometer to measure progress such as fundraising.

== Description ==

Fundraising Thermometer Plugin for WordPress is designed to be a simple method for fund raising activities to be tracked and reported on the live website.

[WordPress Plugins by Christopher Ross] (http://thisismyurl.com/plugins/)

This plugin is maintained by Christopher Ross, http://thisismyurl.com or you can find him on Twitter at http://twitter.com/thisismyurl/


== Installation ==

To install Fundraising Thermometer Plugin for WordPress, please upload the ourprogress folder to your plugins folder and active the plugin.

== Displaying the results ==

If you would like to display the current money raised, include the following code in your WordPress theme:

<?php echo show_ourprogress() ;?>

To display the graphic results, include the following code in your theme:

<?php echo show_ourprogress_graphic();?>


== Screenshots ==

1. screenshot-1.jpg
2. screenshot-2.jpg

== Updates ==
Updates to the plugin will be posted here, to [Christopher Ross](http://thisismyurl.com)

== Frequently Asked Questions ==

= How do I display the results? =

If you would like to display the current money raised, include the following code in your WordPress theme:

<?php echo show_ourprogress() ;?>

To display the graphic results, include the following code in your theme:

<?php echo show_ourprogress_graphic();?>

= Can I include it in a Widget? =

As of 0.6.8, you will see a new widget (Appearance > Widgets) for the Fundraising Thermometer Plugin for WordPress graphic.

= Why can't I see any graphics? =

There are two common reasons graphics are not appearing.

Check that your wp-content/plugins/fundraising-thermometer-plugin-for-wordpress/ folder (and all subfolders) is readable (chmod 755).

Ensure the plugin folder is named fundraising-thermometer-plugin-for-wordpress, not ourprogress as in earlier versions.

= Short Codes =

In a post, you can add [show_ourprogress_graphic] or [show_ourprogress] to return the graphic file or amount donated. If you want to show the target amount in a post, use [show_ourprogress_target]

= My local currency isn't displaying correctly! =

In some cases, local currency needs to be set in the code by adding the line setlocale(LC_MONETARY, 'en_US'); immediately under the first */ for example:

Version: 0.2.5
*/
setlocale(LC_MONETARY, Ôen_USÕ);

= How do I format the numbers? =

The plugin uses standard php money_format(); formating.

= Is this plugin stable? =

Until I upgrade the version number to 1.x, I still consider this plugin to be under development but it has been tested and works well.

== Donations ==
If you would like to donate to help support future development of this tool, please visit [Christopher Ross](http://thisismyurl.com/)


== Change Log ==

= 1.0.0 =

* updated functions to avoid naming conflicts
* implemented code from Pete Haddow for 1% increments
* removed need for external CSS (thanks Pete!)
* general code cleanup


0.5.1 (2009-05-07)
- Altered the file path
- documentation modifications

0.6.1 (2010-04-01)
- fixed method for saving data
- added padding feature to help make CSS better


0.6.4 (2010-04-24)
- fixed padding error


0.6.5 (2010-06-01)
- made the counter more sensitive and added control to allow users to set how large or small each increment appears as.

0.6.6
- added instructions link

== Upgrade Notice ==


0.6.5 (2010-06-01)
- made the counter more sensitive and added control to allow users to set how large or small each increment appears as.


0.6.7

- added short codes [show_ourprogress_graphic], [show_ourprogress] and [show_ourprogress_target]

0.6.8

- added Fundraising Thermometer Plugin for WordPress Widget

0.6.9

* removed update routines

0.7.0

* broke file lookup

0.7.1

* fixed file lookup