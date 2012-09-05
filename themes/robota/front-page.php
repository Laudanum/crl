<!-- template front-page -->
<?php 

get_header();

	echo '<div id="container">';
		if( is_home() && ! is_paged() && esplanade_get_option( 'slider' ) ) :
			get_template_part( 'slider' );
		endif;
		
		if( 'sidebar-content-sidebar' == esplanade_get_option( 'layout' ) ) :
			echo '<div class="content-sidebar-wrap">';
		endif;
		
		echo '<section id="content">';
			
			if ( ! dynamic_sidebar('front-page') ) :
			endif;
			
			/* Front Page Blog
			if( have_posts() ) :
				while( have_posts() ) : the_post();
					get_template_part( 'content', get_post_format() );
				endwhile;
				
                echo '<div class="clear"></div>';
				
                esplanade_posts_nav();
			else :
				esplanade_404();
			endif;
			*/

		echo '</section><!-- #content -->';
		
		echo '<section id="sidebar">';
			if ( ! dynamic_sidebar('front-page-sidebar') ) : endif;
		echo '</section><!-- #sidebar -->';
	
	echo '</div><!-- #container -->';
get_footer();