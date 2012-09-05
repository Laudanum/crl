<<<<<<< HEAD
<?php

add_action('widgets_init', 'featured_posts_load_widgets');

function featured_posts_load_widgets()
{
	register_widget('Featured_Posts_Widget');
}

class Featured_Posts_Widget extends WP_Widget {
	
	function Featured_Posts_Widget()
	{
		$widget_ops = array('classname' => 'featured_posts', 'description' => '');

		$control_ops = array('id_base' => 'featured_posts-widget');

		$this->WP_Widget('featured_posts-widget', 'CRL: Featured Posts', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
        extract( $args );
		
		$post_type = 'all';
		
        $title = $instance['title'];
		$categories = $instance['categories'];
		$post_type = $instance['post_type'];
		$image_size = $instance['image_size'];
		$post_count = $instance['post_count'];
		$image_size = $instance['image_size'];
		$image_align = $instance['image_align'];
		$excerpt_length = $instance['excerpt_length'];
		$category_text = $instance['category_text'];
		
		$show_images = isset($instance['show_images']) ? 'true' : 'false';
		$use_sticky = isset($instance['use_sticky']) ? 'true' : 'false';
		$post_title = isset($instance['post_title']) ? 'true' : 'false';
		$post_meta = isset($instance['post_meta']) ? 'true' : 'false';
		$post_excerpt = isset($instance['post_excerpt']) ? 'true' : 'false';
		$category_link = isset($instance['category_link']) ? 'true' : 'false';
		
		$dimensions = '';
		if( isset( $_wp_additional_image_sizes[$size] ) ) {
			$width = $_wp_additional_image_sizes[$size]['width'];
			$height = $_wp_additional_image_sizes[$size]['height'];
			$dimensions = 'width="'.$width.'" height="'.$height.'" ';
		}
				
		echo $before_widget;
		
		
		if($post_type == 'all') {
			$post_type_array = $post_types;
		} else {
			$post_type_array = $post_type;
		}	        
        
        echo '<div id="featured-post-widget-wrap">';
		
            if ($title != '') :
				echo '<h4>' . $title . '</h4>';
			endif;
			
        echo '<div class="featured-post-block">';
						
			$cat_id = get_cat_ID( $categories );
			$cat_name = get_cat_name( $categories );
			$cat_link = get_category_link( $categories );
			
			if($use_sticky == 'true'):
				$sticky = get_option( 'sticky_posts' );
				$ignore_sticky = false;
			else:
				$ignore_sticky = true;
			endif;
			
			$recent_posts = new WP_Query(array(
				'showposts' => $post_count,
				'post_type' => $post_type_array,
				'ignore_sticky_posts' => $ignore_sticky,
				'cat' => $categories,
				'post__in'  => $sticky,
			));
			
			$count = 1;
			while($recent_posts->have_posts()): $recent_posts->the_post();
                
                echo '<div class="featured-post-item'; if($count == $post_count) { echo ' last'; }; echo '">';
                     
					
					if($show_images == 'true'):
                        echo '<a href="';
						the_permalink();
						echo '" title="';
						the_title();
						echo '">';
                            default_main_image($post->ID, $image_size, $image_align);
                        echo '</a>';
                    endif; 
					 
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
                    
					if($post_meta == 'true'):
                    	echo '<span class="featured-post-meta">';
							the_time('F j, Y');
							echo ', ';
							comments_popup_link();
						echo '</span>';
                    endif;   
                    
					if($post_excerpt == 'true'):
                    	echo '<span class="featured-post-excerpt">';
							echo '<a href="'; echo the_permalink(); echo '">';
								echo string_limit_words(get_the_excerpt(), $excerpt_length);
								echo ' &hellip;';
						 	echo '</a>';
                         echo '</span>';
					endif;
					
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
		$instance['post_type'] = $new_instance['post_type'];
		$instance['post_count'] = $new_instance['post_count'];
		$instance['show_images'] = $new_instance['show_images'];
		$instance['use_sticky'] = $new_instance['use_sticky'];
		$instance['image_size'] = $new_instance['image_size'];
		$instance['image_align'] = $new_instance['image_align'];
		$instance['post_title'] = $new_instance['post_title'];
		$instance['post_meta'] = $new_instance['post_meta'];
		$instance['post_excerpt'] = $new_instance['post_excerpt'];
		$instance['excerpt_length'] = $new_instance['excerpt_length'];
		$instance['category_link'] = $new_instance['category_link'];
		$instance['category_text'] = $new_instance['category_text'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => '', 'categories' => 'all', 'post_count' => 3, 'image_size' => 'widget-image-thumb', 'image_align' => 'left', 'show_images' => 'on', 'use_sticky' => 'on', 'post_title' => 'on', 'post_meta' => null, 'post_excerpt' => null, 'excerpt_length' => 15, 'category_link' => null, 'category_text' => 'Read More');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
        
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('categories'); ?>">Filter by Category:</label> 
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('categories'), 'selected' => $instance['categories'], 'orderby' => 'Name' , 'hierarchical' => 1, 'show_option_all' => 'All Categories', 'hide_empty' => '0')); ?>
            <?php
			$post_types = get_post_types();
			unset($post_types['page'], $post_types['attachment'], $post_types['revision'], $post_types['nav_menu_item']);
			?>
            <select id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>" class="widefat categories" style="width:100%;">
			<?php
					foreach ($post_types as $post_type ) { ?>
						<option value="<?php echo $post_type ?>" <?php if ($post_type == $instance['post_type']) echo 'selected="selected"'; ?>>
                        	<?php echo $post_type ?>
                        </option>
					<?php } ?>
			</select>
		</p>
        				
		<p>
			<label for="<?php echo $this->get_field_id('post_count'); ?>">Post Count:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" value="<?php echo $instance['post_count']; ?>" />
		</p>
		
		<p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_images'], 'on'); ?> id="<?php echo $this->get_field_id('show_images'); ?>" name="<?php echo $this->get_field_name('show_images'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_images'); ?>">Show Thumbnail?</label>
		</p>
		
		<p>
            <input class="checkbox" type="checkbox" <?php checked($instance['use_sticky'], 'on'); ?> id="<?php echo $this->get_field_id('use_sticky'); ?>" name="<?php echo $this->get_field_name('use_sticky'); ?>" /> 
			<label for="<?php echo $this->get_field_id('use_sticky'); ?>">Show Only Sticky Posts?</label>
		</p>
		
        <p>
            <label for="<?php echo $this->get_field_id('image_size'); ?>">Image Size:</label>
			<?php  
			global $_wp_additional_image_sizes;   
            $sizes = $_wp_additional_image_sizes;  
			?>
            <select id="<?php echo $this->get_field_id('image_size'); ?>" name="<?php echo $this->get_field_name('image_size'); ?>" class="widefat" style="width:100%;">
            <?php 
            foreach ( $sizes as $key => $value ) : ?>
                <option value="<?php echo $key ?>" <?php if ($key == $instance['image_size']) echo 'selected="selected"'; ?>>
				<?php echo ucwords(preg_replace('/-/', ' ', $key))  . ' (' . $value['width'] . 'x' . $value['height'] . ')' ?>
                </option>
            <?php endforeach; ?>
            </select>
        </p>
		
		<p>
			<label for="<?php echo $this->get_field_id('image_align'); ?>">Image Alignment:</label> 
			<select id="<?php echo $this->get_field_id('image_align'); ?>" name="<?php echo $this->get_field_name('image_align'); ?>" class="widefat" style="width:100%;">
				<option value='alignleft' <?php if ('alignleft' == $instance['image_align']) echo 'selected="selected"'; ?>>Left</option>
				<option value='alignright' <?php if ('alignright' == $instance['image_align']) echo 'selected="selected"'; ?>>Right</option>
				<option value='alignnone' <?php if ('alignnone' == $instance['image_align']) echo 'selected="selected"'; ?>>None</option>
			</select>
		</p>
		
		<p>
            <input class="checkbox" type="checkbox" <?php checked($instance['post_title'], 'on'); ?> id="<?php echo $this->get_field_id('post_title'); ?>" name="<?php echo $this->get_field_name('post_title'); ?>" /> 
			<label for="<?php echo $this->get_field_id('post_title'); ?>">Show Post Title?</label>
		</p>
		
		<p>
            <input class="checkbox" type="checkbox" <?php checked($instance['post_meta'], 'on'); ?> id="<?php echo $this->get_field_id('post_meta'); ?>" name="<?php echo $this->get_field_name('post_meta'); ?>" />
			<label for="<?php echo $this->get_field_id('post_meta'); ?>">Show Post Meta?</label> 
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
=======
<?php

add_action('widgets_init', 'featured_posts_load_widgets');

function featured_posts_load_widgets()
{
	register_widget('Featured_Posts_Widget');
}

class Featured_Posts_Widget extends WP_Widget {
	
	function Featured_Posts_Widget()
	{
		$widget_ops = array('classname' => 'featured_posts', 'description' => '');

		$control_ops = array('id_base' => 'featured_posts-widget');

		$this->WP_Widget('featured_posts-widget', 'CRL: Featured Posts', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
        extract( $args );
		
		$post_type = 'all';
		
        $title = $instance['title'];
		$categories = $instance['categories'];
		$post_type = $instance['post_type'];
		$image_size = $instance['image_size'];
		$post_count = $instance['post_count'];
		$image_size = $instance['image_size'];
		$image_align = $instance['image_align'];
		$excerpt_length = $instance['excerpt_length'];
		$category_text = $instance['category_text'];
		
		$show_images = isset($instance['show_images']) ? 'true' : 'false';
		$use_sticky = isset($instance['use_sticky']) ? 'true' : 'false';
		$post_title = isset($instance['post_title']) ? 'true' : 'false';
		$post_meta = isset($instance['post_meta']) ? 'true' : 'false';
		$post_excerpt = isset($instance['post_excerpt']) ? 'true' : 'false';
		$category_link = isset($instance['category_link']) ? 'true' : 'false';
		
		$dimensions = '';
		if( isset( $_wp_additional_image_sizes[$size] ) ) {
			$width = $_wp_additional_image_sizes[$size]['width'];
			$height = $_wp_additional_image_sizes[$size]['height'];
			$dimensions = 'width="'.$width.'" height="'.$height.'" ';
		}
				
		echo $before_widget;
		
		
		if($post_type == 'all') {
			$post_type_array = $post_types;
		} else {
			$post_type_array = $post_type;
		}	        
        
        echo '<div id="featured-post-widget-wrap">';
		
            if ($title != '') :
				echo '<h4>' . $title . '</h4>';
			endif;
			
        echo '<div class="featured-post-block">';
						
			$cat_id = get_cat_ID( $categories );
			$cat_name = get_cat_name( $categories );
			$cat_link = get_category_link( $categories );
			
			if($use_sticky == 'true'):
				$sticky = get_option( 'sticky_posts' );
				$ignore_sticky = false;
			else:
				$ignore_sticky = true;
			endif;
			
			$recent_posts = new WP_Query(array(
				'showposts' => $post_count,
				'post_type' => $post_type_array,
				'ignore_sticky_posts' => $ignore_sticky,
				'cat' => $categories,
				'post__in'  => $sticky,
			));
			
			$count = 1;
			while($recent_posts->have_posts()): $recent_posts->the_post();
                
                echo '<div class="featured-post-item'; if($count == $post_count) { echo ' last'; }; echo '">';
                     
					
					if($show_images == 'true'):
                        echo '<a href="';
						the_permalink();
						echo '" title="';
						the_title();
						echo '">';
                            default_main_image($post->ID, $image_size, $image_align);
                        echo '</a>';
                    endif; 
					 
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
                    
					if($post_meta == 'true'):
                    	echo '<span class="featured-post-meta">';
							the_time('F j, Y');
							echo ', ';
							comments_popup_link();
						echo '</span>';
                    endif;   
                    
					if($post_excerpt == 'true'):
                    	echo '<span class="featured-post-excerpt">';
							echo '<a href="'; echo the_permalink(); echo '">';
								echo string_limit_words(get_the_excerpt(), $excerpt_length);
								echo ' &hellip;';
						 	echo '</a>';
                         echo '</span>';
					endif;
					
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
		$instance['post_type'] = $new_instance['post_type'];
		$instance['post_count'] = $new_instance['post_count'];
		$instance['show_images'] = $new_instance['show_images'];
		$instance['use_sticky'] = $new_instance['use_sticky'];
		$instance['image_size'] = $new_instance['image_size'];
		$instance['image_align'] = $new_instance['image_align'];
		$instance['post_title'] = $new_instance['post_title'];
		$instance['post_meta'] = $new_instance['post_meta'];
		$instance['post_excerpt'] = $new_instance['post_excerpt'];
		$instance['excerpt_length'] = $new_instance['excerpt_length'];
		$instance['category_link'] = $new_instance['category_link'];
		$instance['category_text'] = $new_instance['category_text'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => '', 'categories' => 'all', 'post_count' => 3, 'image_size' => 'widget-image-thumb', 'image_align' => 'left', 'show_images' => 'on', 'use_sticky' => 'on', 'post_title' => 'on', 'post_meta' => null, 'post_excerpt' => null, 'excerpt_length' => 15, 'category_link' => null, 'category_text' => 'Read More');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
        
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('categories'); ?>">Filter by Category:</label> 
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('categories'), 'selected' => $instance['categories'], 'orderby' => 'Name' , 'hierarchical' => 1, 'show_option_all' => 'All Categories', 'hide_empty' => '0')); ?>
            <?php
			$post_types = get_post_types();
			unset($post_types['page'], $post_types['attachment'], $post_types['revision'], $post_types['nav_menu_item']);
			?>
            <select id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>" class="widefat categories" style="width:100%;">
			<?php
					foreach ($post_types as $post_type ) { ?>
						<option value="<?php echo $post_type ?>" <?php if ($post_type == $instance['post_type']) echo 'selected="selected"'; ?>>
                        	<?php echo $post_type ?>
                        </option>
					<?php } ?>
			</select>
		</p>
        				
		<p>
			<label for="<?php echo $this->get_field_id('post_count'); ?>">Post Count:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" value="<?php echo $instance['post_count']; ?>" />
		</p>
		
		<p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_images'], 'on'); ?> id="<?php echo $this->get_field_id('show_images'); ?>" name="<?php echo $this->get_field_name('show_images'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_images'); ?>">Show Thumbnail?</label>
		</p>
		
		<p>
            <input class="checkbox" type="checkbox" <?php checked($instance['use_sticky'], 'on'); ?> id="<?php echo $this->get_field_id('use_sticky'); ?>" name="<?php echo $this->get_field_name('use_sticky'); ?>" /> 
			<label for="<?php echo $this->get_field_id('use_sticky'); ?>">Show Only Sticky Posts?</label>
		</p>
		
        <p>
            <label for="<?php echo $this->get_field_id('image_size'); ?>">Image Size:</label>
			<?php  
			global $_wp_additional_image_sizes;   
            $sizes = $_wp_additional_image_sizes;  
			?>
            <select id="<?php echo $this->get_field_id('image_size'); ?>" name="<?php echo $this->get_field_name('image_size'); ?>" class="widefat" style="width:100%;">
            <?php 
            foreach ( $sizes as $key => $value ) : ?>
                <option value="<?php echo $key ?>" <?php if ($key == $instance['image_size']) echo 'selected="selected"'; ?>>
				<?php echo ucwords(preg_replace('/-/', ' ', $key))  . ' (' . $value['width'] . 'x' . $value['height'] . ')' ?>
                </option>
            <?php endforeach; ?>
            </select>
        </p>
		
		<p>
			<label for="<?php echo $this->get_field_id('image_align'); ?>">Image Alignment:</label> 
			<select id="<?php echo $this->get_field_id('image_align'); ?>" name="<?php echo $this->get_field_name('image_align'); ?>" class="widefat" style="width:100%;">
				<option value='alignleft' <?php if ('alignleft' == $instance['image_align']) echo 'selected="selected"'; ?>>Left</option>
				<option value='alignright' <?php if ('alignright' == $instance['image_align']) echo 'selected="selected"'; ?>>Right</option>
				<option value='alignnone' <?php if ('alignnone' == $instance['image_align']) echo 'selected="selected"'; ?>>None</option>
			</select>
		</p>
		
		<p>
            <input class="checkbox" type="checkbox" <?php checked($instance['post_title'], 'on'); ?> id="<?php echo $this->get_field_id('post_title'); ?>" name="<?php echo $this->get_field_name('post_title'); ?>" /> 
			<label for="<?php echo $this->get_field_id('post_title'); ?>">Show Post Title?</label>
		</p>
		
		<p>
            <input class="checkbox" type="checkbox" <?php checked($instance['post_meta'], 'on'); ?> id="<?php echo $this->get_field_id('post_meta'); ?>" name="<?php echo $this->get_field_name('post_meta'); ?>" />
			<label for="<?php echo $this->get_field_id('post_meta'); ?>">Show Post Meta?</label> 
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
>>>>>>> 8f0ec37444fdd5d736c0ea14f5f7af594014484a
}