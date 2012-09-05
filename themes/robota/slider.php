<<<<<<< HEAD
<?php 
	$querydetails = "
		SELECT wposts.*
		FROM $wpdb->posts wposts
		WHERE wposts.post_status = 'publish'
		AND wposts.post_type = 'page'
		AND wposts.post_parent = 412
		ORDER BY wposts.post_date DESC
	";

	$pageposts = $wpdb->get_results($querydetails, OBJECT);

	if ($pageposts):
	?>
		<section id="slider">
			<ul class="slides">
				<?php foreach ($pageposts as $post):
       setup_postdata($post); ?>
					<li>
						<article <?php post_class(); ?>>
							<?php if( has_post_format( 'video' ) ) : ?>
								<?php esplanade_post_video(); ?>
							<?php elseif( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="slider-img-link">
									<?php the_post_thumbnail( 'slider-full' ); ?>
								</a>
							<?php endif; ?>
							<div class="entry-container">
								<header class="entry-header">
									<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
								</header><!-- .entry-header -->
							</div><!-- .entry-container -->
						</article><!-- .post -->
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="clear"></div>
		</section><!-- #slider -->
=======
<?php 
	$querydetails = "
		SELECT wposts.*
		FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
		WHERE wposts.ID = wpostmeta.post_id
		AND wpostmeta.meta_key = 'in_slider'
		AND wpostmeta.meta_value = 'yes'
		AND wposts.post_status = 'publish'
		AND wposts.post_type = 'post'
		ORDER BY wposts.post_date DESC
	";

	$pageposts = $wpdb->get_results($querydetails, OBJECT);

	if ($pageposts):
	?>
		<section id="slider">
			<ul class="slides">
				<?php foreach ($pageposts as $post):
       setup_postdata($post); ?>
					<li>
						<article <?php post_class(); ?>>
							<?php if( has_post_format( 'video' ) ) : ?>
								<?php esplanade_post_video(); ?>
							<?php elseif( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
									<?php the_post_thumbnail( 'slider-full' ); ?>
								</a>
							<?php endif; ?>
							<div class="entry-container">
								<header class="entry-header">
									<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
								</header><!-- .entry-header -->
							</div><!-- .entry-container -->
						</article><!-- .post -->
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="clear"></div>
		</section><!-- #slider -->
>>>>>>> 8f0ec37444fdd5d736c0ea14f5f7af594014484a
	<?php endif; ?>