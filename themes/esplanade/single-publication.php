<?php get_header(); ?>

	<div id="container">
		<?php if( 'sidebar-content-sidebar' == esplanade_get_option( 'layout' ) ) : ?>
			<div class="content-sidebar-wrap">
		<?php endif; ?>
		<section id="content">
			<?php if( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
					<div class="entry">
						<?php if( esplanade_get_option( 'breadcrumbs' ) ) : ?>
							<div id="location">
								<?php esplanade_breadcrumbs(); ?>
							</div><!-- #location -->
						<?php endif; ?>
						<?php esplanade_post_nav(); ?>
						<header class="entry-header">
							<<?php esplanade_title_tag( 'post' ); ?> class="entry-title"><?php the_title(); ?></<?php esplanade_title_tag( 'post' ); ?>>
							<aside>
								<ul class="publication-meta-list post-meta">
									<li><?php echo xref_shortcode_publications( 'list' ) ?></li>
									<li><?php the_time( get_option( 'date_format' ) ); ?></li>
									<li><?php the_publication_meta('Platform') ?></li>
								</ul>
								<?php edit_post_link( __( 'Edit', 'esplanade' ), '', '' ); ?>		
							</aside><!-- .entry-meta -->
						</header><!-- .entry-header -->
						<div class="entry-content-wrapper">
							<?php if( has_post_format( 'audio' ) ) : ?>
								<p><?php esplanade_post_audio(); ?></p>
							<?php elseif( has_post_format( 'video' ) ) : ?>
								<p><?php esplanade_post_video(); ?></p>
							<?php endif; ?>
							<?php the_content(); ?>
							<p><?php the_field('name'); ?></p>
							<div class="clear"></div>
							<ul class="publication-meta-list post-meta">
								<li><?php the_category( ', ' )?></li>
								<?php if( has_attachments()) : ?>
									<li><?php the_publication_attachments(); ?></li>
								<?php endif; ?>
								<?php the_publication_meta('Links') ?>
							</ul>
						</div><!-- .entry-content -->
						<footer class="entry-utility">
							<?php wp_link_pages( array( 'before' => '<p class="post-pagination">' . __( 'Pages:', 'esplanade' ), 'after' => '</p>' ) ); ?>
						</footer><!-- .entry-utility -->
					</div><!-- .entry -->
					<?php comments_template(); ?>
				</article><!-- .post -->
			<?php else : ?>
				<?php esplanade_404(); ?>
			<?php endif; ?>
		</section><!-- #content -->
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