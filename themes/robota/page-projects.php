<?php

// Template Name: Projects Page

get_header(); 



?>
	<div id="container">
		<?php if( 'sidebar-content-sidebar' == esplanade_get_option( 'layout' ) ) : ?>
			<div class="content-sidebar-wrap">
		<?php endif; ?>
		<section id="content">
        
            <header class="entry-header">
                <<?php esplanade_title_tag( 'post' ); ?> class="entry-title">
					<?php the_title(); ?>
                </<?php esplanade_title_tag( 'post' ); ?>>
            </header>
			<?php 
			
			
			$args = 	array(
				'post_type' 	=> 'page',
				'post_parent'	=> '412',
				'posts_per_page'=> -1
			);
			$projects_query = new WP_Query ( $args );
			$counter = 0;
			
			while ( $projects_query->have_posts() ) : $projects_query->the_post();
				$counter++;
				if($counter % 3 == 0){
					$position = "last-entry";
				} else {
					$position = "";
				}
			
			?>
				<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
					<div class="entry portfolio-entry <?php echo $position; ?>">
						<header class="entry-header">
							<<?php esplanade_title_tag( 'post' ); ?> class="entry-title">
                            	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                            </<?php esplanade_title_tag( 'post' ); ?>>
						</header><!-- .entry-header -->
						<div class="entry-content">
							<?php the_post_thumbnail( 'portfolio-thumb' ); ?>
						</div><!-- .entry-content -->
					</div><!-- .entry -->
				</article><!-- .post -->
			<?php 
			endwhile;
			wp_reset_query(); 
			?>
		</section><!-- #content -->
        
		<?php if( 'sidebar-content-sidebar' == esplanade_get_option( 'layout' ) ) : ?>
				<?php get_sidebar( 'left' ); ?>
			</div><!-- #content-sidebar-wrap -->
			<?php get_sidebar( 'right' ); ?>
		<?php elseif( ( 'no-sidebars' != esplanade_get_option( 'layout' ) ) && ( 'full-width' != esplanade_get_option( 'layout' ) ) ) : ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>
	</div><!-- #container -->
<?php get_footer(); ?>