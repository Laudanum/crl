<?php
/*
Plugin Name: Parent Title
Plugin URI: http://jameslao.com/2011/03/24/category-posts-widget-3-2/
Description: Adds a widget that can display posts from a single category.
Author: James Lao	
Version: 3.3
Author URI: http://jameslao.com/
*/



//	the_title 
//	wp_title 

function parent_title_the_title($title, $id=null) {
	$title = _parent_title_get_title($title, FALSE, array('separator' => '; '));
	return $title;
}

function _parent_title_get_title($title, $is_header, $args=array()) {
	global $post;
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


function parent_title_wp_title($title, $sep="»", $seplocation="") {
	$args = array('separator' => $sep);
	$title = _parent_title_get_title($title, TRUE, $args);
	return $title;
}


if ( ! is_admin() ) {
	add_filter( 'the_title', 'parent_title_the_title');
	add_filter( 'wp_title', 'parent_title_wp_title');
}