<?php
/*
Plugin Name:  Add image_src Meta Tag
Description:  Adds an image_src meta tag to your header using post thumbnnail - helps sites like FaceBook use a relevant image as thumbnail when sharing content.
Version:      1.1
Plugin URI:   http://frankprendergast.ie/resources/add-image-src-wordpress-plugin/
Author:       <a href="http://johnblackbourn.com/">John Blackbourn</a> and <a href="http://frankprendergast.ie/">Frank Prendergast</a>

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

*/

function image_src_rel() {

	global $post;

	if ( !function_exists( 'has_post_thumbnail' ) )
		return;

	if ( !is_singular() or !has_post_thumbnail( $post->ID ) )
		return;

	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
	echo '<link rel="image_src" href="' . esc_attr( $thumb[0] ) . '" />';

}

add_action( 'wp_head', 'image_src_rel' );

?>