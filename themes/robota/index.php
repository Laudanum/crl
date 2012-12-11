<?php 
get_header();

	echo '<div id="container">';
		if( is_home() && ! is_paged() && esplanade_get_option( 'slider' ) ) :
			get_template_part( 'slider' );
		endif;
		
		echo '<section id="content">';
		if( in_category('publications') ){
			$cats = get_the_category();
			$theCats = 'Publications';
			foreach( $cats as $cat ){
				if( $cat->slug == 'publications' ){
					continue;
				}
				$theCats .= ': ' . $cat->name;
				break;
			}
			echo '<header class="entry-header">
				<h1 class="entry-title">'. $theCats . '</h1>
			</header>';
		}
			if( have_posts() ) :
				while( have_posts() ) : the_post();
					get_template_part( 'content', get_post_format() );
				endwhile;
				echo '<div class="clear"></div>';
				esplanade_posts_nav();
			else :
				esplanade_404();
			endif;
		echo '</section><!-- #content -->';
		
		if( ( 'no-sidebars' != esplanade_get_option( 'layout' ) ) && ( 'full-width' != esplanade_get_option( 'layout' ) ) ) :
			get_sidebar();
		endif;
	echo '</div><!-- #container -->';
	
get_footer();