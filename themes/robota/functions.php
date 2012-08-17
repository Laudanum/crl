<?php

// Include Custom Widgets
include_once ('portfolio-widget.php');
include_once ('featured-post-widget.php');

// Change to Google JQuery
add_action( 'wp_enqueue_scripts', 'script_managment', 99);
function script_managment() {
	  wp_deregister_script( 'jquery' );
	  wp_deregister_script( 'jquery-ui' );
	  wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' );
	  wp_register_script( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js' );
	  wp_enqueue_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', array( 'jquery' ), '4.0', false );
	  wp_enqueue_script( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js', array( 'jquery' ), '1.8.16' );
}

// Sidebars
register_sidebar(
	array(
    'id'          => 'front-page',
    'name'        => __( 'Front page', $text_domain ),
    'description' => __( 'This sidebar is only used on the front page for the centre content area.', $text_domain ),
	'before_widget' => '<article id="%1$s" class="post widget %2$s">',
	'after_widget'  => '</article>',
	'before_title'  => '<header class="entry-header"><h2 class="entry-title widgettitle">',
	'after_title'   => '</h2></header>',
	)
);

// Slider Image
add_image_size( 'slider-full', 960, 340, true );
add_image_size( 'portfolio-thumb', 200, 120, true );


// Call First Uploaded Image in Post
/**
* @default_main_image
* args
* 	 $post_id
* 	 $image_size = 'thumbnail'
* 	 $image_align = 'alignnone'
*/
function get_default_main_image($post_id, $image_size = 'thumbnail', $image_align = 'alignnone') {
	$return = '';
	$the_title = get_the_title();
	$files = get_children('post_parent='.get_the_ID().'&post_type=attachment&post_mime_type=image&order=desc');
	if(has_post_thumbnail()):
		$return = get_the_post_thumbnail($post_id, $image_size, 
											array('class' => $image_align, 
											      'title' => $the_title, 
												  'alt' => $the_title));
	
	elseif($files && !has_post_thumbnail()): // if no post image search for images inside the post
		$keys = array_reverse(array_keys($files)); 					$num = $keys[0];
		$image = wp_get_attachment_image_src($num, $image_size); 
		
		$return = '<img src="'.$image[0].'" width="'. $image[1] .'" height="'.$image[2].'" alt="'.$the_title.'" title="'.$the_title.'" class="'. $image_align .' wp-post-image"/>';

	endif;
	
	return $return;
}

function default_main_image($post_id, $image_size = 'thumbnail', $image_align = 'alignnone'){
	echo get_default_main_image($post_id, $image_size, $image_align);
}

// Limit Words for Excerpt

function string_limit_words($string, $word_limit){
	$words = explode(' ', $string, ($word_limit + 1));
	if(count($words) > $word_limit)
	array_pop($words);
	return implode(' ', $words);
}