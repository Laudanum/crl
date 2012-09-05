<<<<<<< HEAD
<?php
add_action('widgets_init', 'page_list_load_widgets');

function page_list_load_widgets()
{
	register_widget('Page_List_Widget');
}

class Page_List_Widget extends WP_Widget {
	
	function Page_List_Widget()
	{
		$widget_ops = array('classname' => 'page-list', 'description' => 'Display a Menu of Sub-Pages.');

		$control_ops = array('id_base' => 'page-widget');

		$this->WP_Widget('page-widget', 'CRL: Page List', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		$page_id = $instance['page_id'];
		
		echo $before_widget;

		if($title) {
			echo $before_title.$title.$after_title;
		}
				
			$args = 	array(
				'post_type' 	=> 'page',
				'post_parent'	=> $page_id,
				'posts_per_page'=> -1
			);
			$pages_query = new WP_Query ( $args );
			echo '<div class="dropdown-menu"><ul>';
			while ( $pages_query->have_posts() ) : $pages_query->the_post();
				echo '<li>';
					echo '<a href="'; the_permalink(); echo '" title="'; the_title(); echo '">';
						the_title();
					echo '</a>';
				echo '</li>';
			endwhile;
			wp_reset_query();
			echo '</ul></div>';
			
			echo '<script type="text/javascript">$(function() {$(\'.dropdown-toggle\').dropdown();});</script>';
			echo '<script src="' . get_stylesheet_directory_uri() . '/bootstrap-dropdown.js"></script>';

		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['page_id'] = $new_instance['page_id'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => '', 'page_id' => '425');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('page_id'); ?>">Page:</label>
			<?php wp_dropdown_pages(array(
				'id' => $this->get_field_id('page_id'),
				'name' => $this->get_field_name('page_id'),
				'selected' => $instance['page_id'],
			)); ?> 
		</p>
		
	<?php
	}
}
=======
<?php
add_action('widgets_init', 'page_list_load_widgets');

function page_list_load_widgets()
{
	register_widget('Page_List_Widget');
}

class Page_List_Widget extends WP_Widget {
	
	function Page_List_Widget()
	{
		$widget_ops = array('classname' => 'page-list', 'description' => 'Display a Menu of Sub-Pages.');

		$control_ops = array('id_base' => 'page-widget');

		$this->WP_Widget('page-widget', 'CRL: Page List', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		$page_id = $instance['page_id'];
		
		echo $before_widget;

		if($title) {
			echo $before_title.$title.$after_title;
		}
				
			$args = 	array(
				'post_type' 	=> 'page',
				'post_parent'	=> $page_id,
				'posts_per_page'=> -1
			);
			$pages_query = new WP_Query ( $args );
			echo '<div class="dropdown-menu"><ul>';
			while ( $pages_query->have_posts() ) : $pages_query->the_post();
				echo '<li>';
					echo '<a href="'; the_permalink(); echo '" title="'; the_title(); echo '">';
						the_title();
					echo '</a>';
				echo '</li>';
			endwhile;
			wp_reset_query();
			echo '</ul></div>';
			
			echo '<script type="text/javascript">$(function() {$(\'.dropdown-toggle\').dropdown();});</script>';
			echo '<script src="' . get_stylesheet_directory_uri() . '/bootstrap-dropdown.js"></script>';

		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['page_id'] = $new_instance['page_id'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => '', 'page_id' => '425');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('page_id'); ?>">Page:</label>
			<?php wp_dropdown_pages(array(
				'id' => $this->get_field_id('page_id'),
				'name' => $this->get_field_name('page_id'),
				'selected' => $instance['page_id'],
			)); ?> 
		</p>
		
	<?php
	}
}
>>>>>>> 8f0ec37444fdd5d736c0ea14f5f7af594014484a
?>