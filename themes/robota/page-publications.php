<?php

// Template Name: Publications Page

get_header(); 

?>
	<div id="container">
		<?php if( 'sidebar-content-sidebar' == esplanade_get_option( 'layout' ) ) : ?>
			<div class="content-sidebar-wrap">
		<?php endif; ?>
		<section id="content" class="publications-page fullwidth">
        
            <header class="entry-header">
                <<?php esplanade_title_tag( 'post' ); ?> class="entry-title entry-title-publications">
					<?php the_title(); ?>
                </<?php esplanade_title_tag( 'post' ); ?>>
            </header>
			<?php 
			
			
			$args = 	array(
				'category_name'		=> 'publications'
			);
			
			$publications_query = new WP_Query ( $args );
			
			while ( $publications_query->have_posts() ) : $publications_query->the_post();
				get_template_part( 'content', get_post_format() );
				echo '<div class="clear"></div>';
				esplanade_posts_nav();
			endwhile;
			
			?>
			<?php 
			wp_reset_query(); 
			?>
		</section><!-- #content -->
        <?php /*
		<?php if( 'sidebar-content-sidebar' == esplanade_get_option( 'layout' ) ) : ?>
				<?php get_sidebar( 'left' );?>
			</div><!-- #content-sidebar-wrap -->
			<?php get_sidebar( 'right' ); ?>
		<?php elseif( ( 'no-sidebars' != esplanade_get_option( 'layout' ) ) && ( 'full-width' != esplanade_get_option( 'layout' ) ) ) : ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>
		 * 
		 */ ?>
	</div><!-- #container -->
<?php get_footer(); ?>