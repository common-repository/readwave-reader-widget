<?php
/*
Plugin Name: ReadWave Story Embed
Plugin URI: http://readwave.com/wordpress-plugin/
Description: Embed Readwave widgets in your blog posts or pages
Version: 0.1
Author: Ben Gillbanks
Author URI: http://www.binarymoon.co.uk
License: GPL2

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * ReadWave Shortcode
 * Example Usage:
 * [readwave]2447[/readwave]
 * [readwave width="500" height="500"]2447[/readwave]
 *
 * had to remove the link back to readwave as I don't know the path to the post.
 * I'd set up a short url that uses the post id and then that can be added in easily
 * 
 * $atts = attributes (width and height)
 * $content = post id
 */
function readwave_shortcode( $atts, $content = null ) {
	
	if ( $content == null ) {
		return false;
	}
	
	extract( shortcode_atts( array (
		'width' => 600,
		'height' => 975,
	), $atts ) );
	
	$width = (int) $width;
	$height = (int) $height;
	$id = (int) $content;

	$embed_code = '';
	
	$embed_code .= '<script type="text/javascript">ReadWaveStoryWidget.load({id : ' . $id . ', "width": "' . $width . 'px", "height": "' . $height . 'px" })</script>';
	$embed_code .= '<div id="readwave-story-widget-' . $id . '"><iframe src="http://www.readwave.com/widget/story/' . $id . '/" scrolling="auto" frameborder="no" class="readwave-widget readwave-widget-story" width="' . $width . 'px" height="' . $height . 'px"></iframe></div>';
	
	return $embed_code;
	
}

add_shortcode( 'readwave', 'readwave_shortcode' );


/**
 * A helper to include the javascript on the page once, and not repeat it with every embed
 */
function readwave_post_users_shortcode( $posts ) {

	if ( empty( $posts ) ) {
		return $posts;	
	}

	$found = false;

	foreach ( $posts as $post ) {		
		if ( stripos( $post->post_content, '[readwave' ) !== false ) {
			$found = true;
			break;
		}
	}
		
	if ( $found ) {
		wp_register_script( 'readwave', 'http://www.readwave.com/js/readwave/widgets/story.js' );
		wp_print_scripts( 'readwave' );
	}

	return $posts;
	
}

add_action( 'the_posts', 'readwave_post_users_shortcode' );