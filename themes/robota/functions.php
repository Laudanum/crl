<?php

register_sidebar(
	array(
    'id'          => 'front-page',
    'name'        => __( 'Front page', $text_domain ),
    'description' => __( 'This sidebar is only used on the front page for the centre content area.', $text_domain ),
	'before_widget' => '<article id="%1$s" class="widget %2$s">',
	'after_widget'  => '</article>',
	'before_title'  => '<header class="entry-header"><h2 class="entry-title widgettitle">',
	'after_title'   => '</h2></header>',
	)
);