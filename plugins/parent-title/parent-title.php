<?php
/*
Plugin Name: Parent Title
Plugin URI: http://houseoflaudanum.com/wordpress/
Description: Add parent page titles to page titles where they exist.
Version: 0.1
Author: Laudanum
Author URI: http://houseoflaudanum.com/identities/mr-snow/
License: GPL2
*/



//	the_title 
//	wp_title 

function parent_title_the_title($title, $id=null) {
//	don't touch the title if we're not in the loop (eg: list pages or menus)
	if ( ! in_the_loop() ) {
		return $title;
	}
	if ( $id ) {
//	if we've got an id but its not the current post then ignore.
		global $post;
		if ( $id != $post->ID ) {
			return $title;
		}

		$the_post = get_post($id);
		$title = _parent_title_get_title($title, $the_post, FALSE, array('separator' => '; '));
	}
	return $title;
}


function _parent_title_get_title($title, $post=null, $is_header, $args=array()) {
//	no post provided so use global post
	if ( ! $post )
		global $post;

//	no parent so return
	if ( intval($post->post_parent) < 1 ) {
		return $title;
	}
//	possibly when using page links to 
	if ( $post->ID == $post->post_parent ) {
		return $title;
	}

	$parent = get_post($post->post_parent);
	$parent_title = $parent->post_title;
//	don't duplicate titles
	if ( $parent_title == $title ) {
		return $title;
	}

	$tag = $is_header ? 'wp_title' : 'the_title';
//	$parent_title = apply_filters($tag, $parent->post_title);

	if ( $is_header ) {
		$title .= " " . $args['separator'] . " " . $parent_title;
	} else {
		$separator = $args['separator'] ? $args['separator'] : ", ";
		$title = "<span class='parent-title'>$parent_title</span><span class='separator'>$separator</span><span class='title'>$title</span>";
	}
	return $title;
}


function parent_title_wp_title($title, $sep="&raquo;", $seplocation="") {
	if ( ! trim($sep) )
		$sep = "&raquo;";
	$args = array('separator' => $sep);
	$title = _parent_title_get_title($title, null, TRUE, $args);
	return $title;
}


if ( ! is_admin() ) {
	add_filter( 'the_title', 'parent_title_the_title', 10, 2);
	add_filter( 'wp_title', 'parent_title_wp_title', 10, 3);
}