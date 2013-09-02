<?php

// Template Name: People Page

get_header(); 



?>
<!-- template page people -->
	<div id="container">
		<?php if( 'sidebar-content-sidebar' == esplanade_get_option( 'layout' ) ) : ?>
			<div class="content-sidebar-wrap">
		<?php endif; ?>
		<section id="content" class="fullwidth">
        
            <header class="entry-header">
                <<?php esplanade_title_tag( 'post' ); ?> class="entry-title">
					<?php the_title(); ?>
                </<?php esplanade_title_tag( 'post' ); ?>>
            </header>
			<?php 
			
			
			$args = 	array(
				'post_type' 	=> 'page',
				'post_parent'	=> '418',
				'posts_per_page'=> -1
			);
			
			$projects_query = new WP_Query ( $args );
			$counter = 0;
			$org = array();
			while ( $projects_query->have_posts() ) : $projects_query->the_post();
				$counter++;
				if($counter % 3 == 0){
					$position = "last-entry";
				} else {
					$position = "";
				}
				$meta = get_post_meta( get_the_ID() );
				$thePosition = $meta['Position'][0] ? $meta['Position'][0] : '';
				
				$terms = get_the_terms( get_the_ID(), 'people' );
				
				if( is_array($terms)){
					$term = current( $terms );
					$post = get_post( get_the_ID());
					$post->position = $position;
					
					if( isset($org[ $term->slug ])){
						$org[ $term->slug ]['posts'][] = $post;
					} else {
						$org[ $term->slug ] = array(
							'name' => $term->name,
							'description' => $term->description,
							'posts' => array( $post )
						);
					}
				}
			endwhile;
			wp_reset_query(); 
			global $post;
			// sort categories alphabetically on their machine name
			ksort($org);
			foreach( $org as $settings ) :
				echo '<div class="clear"></div><h3>' . $settings['name'] . '</h3>';
				foreach( $settings['posts'] as $post ) :
				setup_postdata( $post );
				$meta = get_post_meta( get_the_ID() );
				$thePosition = $meta['Position'][0] ? $meta['Position'][0] : '';
				?>
					<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
						<div class="entry portfolio-entry <?php echo $post->position; ?> entry-person">
							<div class="entry-content">
								<?php the_post_thumbnail( 'portfolio-thumb' ); ?>
							</div><!-- .entry-content -->
							<header class="entry-header">
								<<?php esplanade_title_tag( 'post' ); ?> class="entry-title">
									<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
									<!-- <span class="entry-position"><?php echo $thePosition ?></span> -->
								</<?php esplanade_title_tag( 'post' ); ?>>
							</header><!-- .entry-header -->
						</div><!-- .entry -->
					</article><!-- .post -->
				<?php 
					endforeach;
				?>
			<?php
				endforeach;
				wp_reset_postdata(); 
			?>
		</section><!-- #content -->
		<section>
			<article class="entry-description">
				<?php echo the_content(); ?>
			</article>
		</section>
        <?php /*
		<?php if( 'sidebar-content-sidebar' == esplanade_get_option( 'layout' ) ) : ?>
				<?php get_sidebar( 'left' ); ?>
			</div><!-- #content-sidebar-wrap -->
			<?php get_sidebar( 'right' ); ?>
		<?php elseif( ( 'no-sidebars' != esplanade_get_option( 'layout' ) ) && ( 'full-width' != esplanade_get_option( 'layout' ) ) ) : ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>
		 * 
		 */ ?>
			
	</div><!-- #container -->
<?php get_footer(); ?>