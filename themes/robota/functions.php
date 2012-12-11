<?php

// Include Custom Widgets
include_once ('portfolio-widget.php');
include_once ('featured-post-widget.php');
include_once ('feature-widget.php');
include_once ('news-widget.php');

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
    'name'        => __( 'Front Page', $text_domain ),
    'description' => __( 'This sidebar is only used on the front page for the centre content area.', $text_domain ),
	'before_widget' => '<article id="%1$s" class="post widget %2$s">',
	'after_widget'  => '</article>',
	'before_title'  => '<header class="entry-header"><h2 class="entry-title widgettitle">',
	'after_title'   => '</h2></header>',
	)
);
register_sidebar(
	array(
    'id'          => 'front-page-sidebar',
    'name'        => __( 'Front Page Sidebar', $text_domain ),
    'description' => __( 'This sidebar is only used on the front page for the sidebar content area.', $text_domain ),
	'before_widget' => '<article id="%1$s" class="post widget %2$s">',
	'after_widget'  => '</article>',
	'before_title'  => '<header class="entry-header"><h2 class="entry-title widgettitle">',
	'after_title'   => '</h2></header>',
	)
);

// Slider Image
add_image_size( 'slider-full', 960, 360, true );
add_image_size( 'portfolio-thumb', 220, 118, true );
add_image_size( 'front-page-thumb', 180, 180, true );


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

function crl_scripts()  
{  
    wp_register_script( 'custom-script', get_template_directory_uri() . '/scripts/main.js', array('jquery'));  
    wp_enqueue_script( 'custom-script' );  
}  
add_action( 'wp_enqueue_scripts', 'crl_scripts');  

function the_person_meta() {
	if ( $keys = get_post_custom_keys() ) {
		echo "<ul class='post-meta'>\n";
		foreach ( (array) $keys as $key ) {
			$keyt = trim($key);
			if ( is_protected_meta( $keyt, 'post' ) )
				continue;
			$values = array_map('trim', get_post_custom_values($key));
			$value = implode($values,', ');
			echo apply_filters('the_meta_key', "<li><span class='post-meta-key'>$key</span> $value</li>\n", $key, $value);
		}
		echo "</ul>\n";
	}
}

function the_publication_meta( $specific = null ){
	if ( $keys = get_post_custom_keys() ) {
		foreach ( (array) $keys as $key ) {
			$keyt = trim($key);
			if( $specific && $specific != $keyt ){
				continue;
			}
			if ( is_protected_meta( $keyt, 'post' ) )
				continue;
			$values = array_map('trim', get_post_custom_values($key));
			$value = implode($values,', ');
			
			// Links should be made into to anchors and listed //
			if( $keyt == 'Links' ){
				$links = explode(',', $value );
				$value = '<ul class="post-meta publication-meta-list"><li>Links:</li>';
				foreach( $links as $lnk ){
					$value .= '<li class=""><a href="' . $lnk . '">' . $lnk . '</a></li>';
				}
				$value .= '</ul>';
			}
			echo apply_filters('the_meta_key', "$value\n", $key, $value);
		}
	}
}

function has_attachments(){
	$args = array(
		'post_type' => 'attachment',
		'numberposts' => null,
		'post_status' => null,
		'post_parent' => get_the_ID()
	); 
	
	$attachments = get_posts($args);
	
	return count( $attachments ) > 0;
}

function the_publication_attachments(){
	
	$args = array(
		'post_type' => 'attachment',
		'numberposts' => null,
		'post_status' => null,
		'post_parent' => get_the_ID()
	); 
	
	$attachments = get_posts($args);
	
	if ($attachments) {
		$value = '<ul class="post-meta publication-meta-list">';
		foreach ($attachments as $attachment) {
			$value .= '<li><a href="'.$attachment->guid.'" target="_blank">' . apply_filters('the_title', $attachment->post_title) . '</a></li>';
		}
		$value .= '</ul>';
	}
	
	echo $value;
	
}

// Xreferences Shortcode - gets the publication posts //
function xref_shortcode_publications( $list = true ){
	$postId = get_the_ID();
	$posts = explode(",", is_xref_get_list( $postId ));
	
	if( !$list ){
		if( is_array($posts)){
			$str = '';
			$i = 0;
			foreach( $posts as $post ){
				if( $i > 0 ){
					$str .= ', ';
				}
				$thePost = get_post( $post );
				$str .= '<a href="'. get_permalink( $post ) . '">' . $thePost->post_title . '</a>';
				$i++;
			}
			return $str;
		}
	}
	
	if( is_array($posts)){
		$str = '<ul class="xref-publications">';

		foreach( $posts as $post ){
			$thePost = get_post( $post );
			$str .= '<li><a href="'. get_permalink( $post ) . '">
				' . $thePost->post_title . '
			</a></li>';
		}
		$str .= '</ul>';
		return $str;
	}
	return $str;
}

add_shortcode( 'xref_publications', 'xref_shortcode_publications' );