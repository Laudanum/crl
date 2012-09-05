<?php

add_action('widgets_init', 'news_load_widgets');

function news_load_widgets()
{
	register_widget('News_Widget');
}

class News_Widget extends WP_Widget {
	
	function News_Widget()
	{
		$widget_ops = array('classname' => 'news', 'description' => '');

		$control_ops = array('id_base' => 'news-widget');

		$this->WP_Widget('news-widget', 'CRL: News Widget', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
        extract( $args );
		
        $title = $instance['title'];
		$categories = $instance['categories'];
		$post_count = $instance['post_count'];
		$excerpt_length = $instance['excerpt_length'];
		$category_text = $instance['category_text'];
		
		$post_title = isset($instance['post_title']) ? 'true' : 'false';
		$post_date = isset($instance['post_date']) ? 'true' : 'false';
		$post_excerpt = isset($instance['post_excerpt']) ? 'true' : 'false';
		$category_link = isset($instance['category_link']) ? 'true' : 'false';
		
				
		echo $before_widget;
		
        
        echo '<div id="news-post-widget-wrap">';
		
            if ($title != '') :
				echo '<h4><span>' . $title . '</span></h4>';
			endif;
			
        echo '<div class="news-post-block">';
						
			$cat_id = get_cat_ID( $categories );
			$cat_name = get_cat_name( $categories );
			$cat_link = get_category_link( $categories ); 
			
			$recent_posts = new WP_Query(array(
				'showposts' => $post_count,
				'cat' => $categories,
			));
			
			$count = 1;
			while($recent_posts->have_posts()): $recent_posts->the_post();
                
                echo '<div class="news-post-item'; if($count == $post_count) { echo ' last'; }; echo '">';
                    echo '<div class="news-post-date-wrap">';
						if($post_date == 'true'):
							echo '<div class="news-post-date"><span>';
								the_time('j');
							echo '</span></div>';
							
							echo '<a href="';
							the_permalink();
							echo '" title="';
							the_title();
							echo '">';
								echo 'Read More';
							echo '</a>';
							
						endif;
					echo '</div>';
					
                    echo '<div class="news-post-text-wrap">';
						if($post_title == 'true'):
							echo '<h3>';
								echo '<a href="';
								the_permalink();
								echo '" title="';
								the_title();
								echo '">';
									the_title();
								echo '</a>';
							echo '</h3>';
						endif;  
						
						if($post_excerpt == 'true'):
							echo '<span class="news-post-excerpt">';
								echo '<a href="'; echo the_permalink(); echo '">';
									echo string_limit_words(get_the_excerpt(), $excerpt_length);
									echo ' &hellip;';
								echo '</a>';
							 echo '</span>';
						endif;
					echo '</div>';
					
                echo '</div>';
				
			$count++;
			endwhile;
                    
			if($category_link == 'true'):
                echo '<p>';
					echo '<a href="' . esc_url( $cat_link ) . '" title="' . $cat_name . '">';
						echo $category_text;
					echo '</a>';
				echo '</p>';
            endif;
			
		echo '</div>';
		
		echo '</div>';
        
        
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
	
		$instance['title'] = $new_instance['title'];
		$instance['categories'] = $new_instance['categories'];
		$instance['post_count'] = $new_instance['post_count'];
		$instance['post_title'] = $new_instance['post_title'];
		$instance['post_date'] = $new_instance['post_date'];
		$instance['post_excerpt'] = $new_instance['post_excerpt'];
		$instance['excerpt_length'] = $new_instance['excerpt_length'];
		$instance['category_link'] = $new_instance['category_link'];
		$instance['category_text'] = $new_instance['category_text'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => '', 'categories' => 'all', 'post_count' => 3, 'post_title' => 'on', 'post_date' => null, 'post_excerpt' => null, 'excerpt_length' => 12, 'category_link' => null, 'category_text' => 'Read More');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
        
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('categories'); ?>">Filter by Category:</label> 
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('categories'), 'selected' => $instance['categories'], 'orderby' => 'Name' , 'hierarchical' => 1, 'show_option_all' => 'All Categories', 'hide_empty' => '0')); ?>
		</p>
        				
		<p>
			<label for="<?php echo $this->get_field_id('post_count'); ?>">Post Count:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" value="<?php echo $instance['post_count']; ?>" />
		</p>
		
		<p>
            <input class="checkbox" type="checkbox" <?php checked($instance['post_title'], 'on'); ?> id="<?php echo $this->get_field_id('post_title'); ?>" name="<?php echo $this->get_field_name('post_title'); ?>" /> 
			<label for="<?php echo $this->get_field_id('post_title'); ?>">Show Post Title?</label>
		</p>
		
		<p>
            <input class="checkbox" type="checkbox" <?php checked($instance['post_date'], 'on'); ?> id="<?php echo $this->get_field_id('post_date'); ?>" name="<?php echo $this->get_field_name('post_date'); ?>" />
			<label for="<?php echo $this->get_field_id('post_date'); ?>">Show Post Date?</label> 
		</p>
		
		<p>
            <input class="checkbox" type="checkbox" <?php checked($instance['post_excerpt'], 'on'); ?> id="<?php echo $this->get_field_id('post_excerpt'); ?>" name="<?php echo $this->get_field_name('post_excerpt'); ?>" /> 
			<label for="<?php echo $this->get_field_id('post_excerpt'); ?>">Show Post Excerpt? &nbsp;&nbsp;&nbsp; Length:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('excerpt_length'); ?>" name="<?php echo $this->get_field_name('excerpt_length'); ?>" value="<?php echo $instance['excerpt_length']; ?>" />
		</p>
		
		<p>
            <input class="checkbox" type="checkbox" <?php checked($instance['category_link'], 'on'); ?> id="<?php echo $this->get_field_id('category_link'); ?>" name="<?php echo $this->get_field_name('category_link'); ?>" /> 
			<label for="<?php echo $this->get_field_id('category_link'); ?>">Show Category Link?</label>
		</p>
        
		<p>
			<label for="<?php echo $this->get_field_id('category_text'); ?>">Category Text:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('category_text'); ?>" name="<?php echo $this->get_field_name('category_text'); ?>" value="<?php echo $instance['category_text']; ?>" />
		</p>
	<?php }
}