=== Plugin Name ===
Contributors: piyushmishra
Tags: opengraph, open graph, facebook, fb, open-graph, protocol, social graph
Requires at least: 3.1
Tested up to: 3.1
Stable tag: 1.1

Implements the Open Graph Protocol on a WordPress installation. Can be used by other plugins as a dependency.

== Description ==

The Open Graph protocol enables any web page to become a rich object in a social graph. For instance, this is used on Facebook to enable any web page to have the same functionality as a Facebook Page.

While many different technologies and schemas exist and could be combined together, there isn't a single technology which provides enough information to richly represent any web page within the social graph. The Open Graph protocol builds on these existing technologies and gives developers one thing to implement. Developer simplicity is a key goal of the Open Graph protocol which has informed many of the technical design decisions.

= For Developers =
Use `add_action('open_graph_head_pre','your_function');` and `add_action('open_graph_head_post','your_function');` to edit the meta data. For an instance of the class.

Always use `open_graph_instance()`
Read more on the Plugin URI > [Open Graph WP implementation](http://www.piyushmishra.com/plugins/open-graph.html "Plugin URI") or check out the FAQs

== Installation ==

1. Upload `open-graph.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set the default image for your domain (not available via admin panel in version 1.0, replace the image `open_graph_protocol_logo.png` for now)

== Frequently Asked Questions ==

= How to override opengraph metadata? =

You can add/edit meta data by using 2 actions 'open_graph_head_pre' and 'open_graph_head_post'.
'open_graph_head_pre' executes before the setup takes place.
'open_graph_head_post' executes after the setup but before echoing meta data.
I'll add more functions for editing in later versions.

= How to override default image of the plugin? =

* Use the `add_action('open_graph_head_pre','my_function')` .
* In `my_function` grab an instance  `$og = open_graph_instance();` and call `$og->set_default_img( $url );`
* Also you can use `'open_graph_head_post'` action and `$og->set_og_data( 'og:image' => $url )` but this will over ride for all pages/ posts etc

= How to override default type of homepage? =

* Use the `add_action('open_graph_head_pre','my_function')` .
* In `my_function` grab an instance  `$og = open_graph_instance();` and call `$og->set_home_type( $type );` 
* Also you can use `'open_graph_head_post'` action and ` if(is_home()) { $og->set_og_data( 'og:type' => $type );}`

== Upgrade Notice ==

= 1.1 =

Fixes a major bug. New featues added. Absolutely necessary.

= 1.0.1 =
Minor, not necessary from 1.0 versions.
