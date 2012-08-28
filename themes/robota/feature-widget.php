<?php

add_action('widgets_init', 'feature_block_load_widgets');

function feature_block_load_widgets()
{
	register_widget('Feature_Block_Widget');
}

class Feature_Block_Widget extends WP_Widget {
	
	function Feature_Block_Widget()
	{
		$widget_ops = array('classname' => 'feature_block', 'description' => '');

		$control_ops = array('id_base' => 'feature_block-widget');

		$this->WP_Widget('feature_block-widget', 'CRL: Feature Block', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
        extract( $args );
		
        $title = $instance['title'];
		$main_text = $instance['main_text'];
		$sub_text = $instance['sub_text'];
		$bold_text = $instance['bold_text'];
				
		echo $before_widget;
        
			
        echo '<div class="feature-block">';
            if ($title != '') :
				echo '<h4>' . $title . '</h4>';
			endif;
			
            if ($main_text != '') :
				echo '<div class="feature-main-text">' . $main_text . '</div>';
			endif;
			
			echo '<div class="feature-white-box">';
				if ($sub_text != '') :
					echo '<div class="feature-sub-text">' . $sub_text . '</div>';
				endif;
				
				if ($bold_text != '') :
					echo '<div class="feature-bold-text">' . $bold_text . '</div>';
				endif;
			echo '</div>';	
			
		echo '</div>';
        
        
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
	
		$instance['title'] = $new_instance['title'];
		$instance['main_text'] = $new_instance['main_text'];
		$instance['sub_text'] = $new_instance['sub_text'];
		$instance['bold_text'] = $new_instance['bold_text'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => '');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
        
		<p>
			<label for="<?php echo $this->get_field_name('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_name('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
        
		<p>
			<label for="<?php echo $this->get_field_id('main_text'); ?>"Main Text:</label>
			<textarea class="widefat" style="width: 216px; height: 80px;" id="<?php echo $this->get_field_id('main_text'); ?>" name="<?php echo $this->get_field_name('main_text'); ?>"><?php echo $instance['main_text']; ?></textarea>
		</p>
        
		<p>
			<label for="<?php echo $this->get_field_id('sub_text'); ?>">Sub Text:</label>
			<textarea class="widefat" style="width: 216px; height: 80px;" id="<?php echo $this->get_field_id('sub_text'); ?>" name="<?php echo $this->get_field_name('sub_text'); ?>"><?php echo $instance['sub_text']; ?></textarea>
		</p>
        
		<p>
			<label for="<?php echo $this->get_field_id('bold_text'); ?>">Bold Text:</label>
			<textarea class="widefat" style="width: 216px; height: 50px;" id="<?php echo $this->get_field_id('bold_text'); ?>" name="<?php echo $this->get_field_name('bold_text'); ?>"><?php echo $instance['bold_text']; ?></textarea>
		</p>
	<?php }
}