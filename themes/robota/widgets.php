<<<<<<< HEAD
<?php
class Portfolio_Display_Widget extends WP_Widget {
	
	function Portfolio_Display_Widget() {
			parent::WP_Widget(false, 'CRL - Portfolio Widget');
		}
	
	function form($instance) {
			
		}
	 
	function update($new_instance, $old_instance) {
			
		}
	
	function widget($args, $instance) {
			// outputs the content of the widget
			echo $before_widget;

			$code = '<div id="newsletter-widget">';
			$code .= '<div id="newsletter-top">Get on the List!</div>';
			$code .= '</div>';
			
			$code .='<div id="newsletter-popup"><div>';
			$code .='<p><span style="font-weight: bold;">Join the Locker Room!</p>';
			$code .='<form action="http://fanatchicks.us2.list-manage.com/subscribe/post?u=c5622d085cb512d99a3b1108e&amp;id=778c4ba171" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
				<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required="">
				<input type="submit" value="Submit" name="subscribe" id="mc-embedded-subscribe" class="button">
				</form>';
			$code .='<p style="font-size: 16px">We will never sell your info...that\'s so tacky!</p>';
			$code .='<p id="close-popup">Close</p>';
			$code .='</div></div>';
			
			
			echo $code;
			
			add_action('genesis_after_footer', 'newsletter_script');
			function newsletter_script(){
			$script .='<script type="text/javascript">(function($){';
				$script .='$(\'#newsletter-widget\').click(function(){';
					$script .= 'var url = $(this).find(\'a\').attr(\'href\');';
					$script .= '$(this).css(\'box-shadow\', \'1px 2px 3px #999 inset\');';
					$script .= '$(this).css(\'cursor\', \'pointer\');';
					$script .='$(\'#newsletter-popup\').fadeIn(\'fast\', function(){ $(\'input[name="EMAIL"]\').focus(); });';
					$script .= '$(this).css(\'box-shadow\', \'none\');';
				$script .= '});';
				$script .= '$(\'#close-popup\').click(function(){';
					$script .= '$(\'#newsletter-popup\').fadeOut(\'fast\');';
				$script .= '});';
			$script .= '})(jQuery);</script>';
			
			echo $script;
			}
			
			
			
			echo $after_widget;
		}
}
=======
<?php
class Portfolio_Display_Widget extends WP_Widget {
	
	function Portfolio_Display_Widget() {
			parent::WP_Widget(false, 'CRL - Portfolio Widget');
		}
	
	function form($instance) {
			
		}
	 
	function update($new_instance, $old_instance) {
			
		}
	
	function widget($args, $instance) {
			// outputs the content of the widget
			echo $before_widget;

			$code = '<div id="newsletter-widget">';
			$code .= '<div id="newsletter-top">Get on the List!</div>';
			$code .= '</div>';
			
			$code .='<div id="newsletter-popup"><div>';
			$code .='<p><span style="font-weight: bold;">Join the Locker Room!</p>';
			$code .='<form action="http://fanatchicks.us2.list-manage.com/subscribe/post?u=c5622d085cb512d99a3b1108e&amp;id=778c4ba171" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
				<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required="">
				<input type="submit" value="Submit" name="subscribe" id="mc-embedded-subscribe" class="button">
				</form>';
			$code .='<p style="font-size: 16px">We will never sell your info...that\'s so tacky!</p>';
			$code .='<p id="close-popup">Close</p>';
			$code .='</div></div>';
			
			
			echo $code;
			
			add_action('genesis_after_footer', 'newsletter_script');
			function newsletter_script(){
			$script .='<script type="text/javascript">(function($){';
				$script .='$(\'#newsletter-widget\').click(function(){';
					$script .= 'var url = $(this).find(\'a\').attr(\'href\');';
					$script .= '$(this).css(\'box-shadow\', \'1px 2px 3px #999 inset\');';
					$script .= '$(this).css(\'cursor\', \'pointer\');';
					$script .='$(\'#newsletter-popup\').fadeIn(\'fast\', function(){ $(\'input[name="EMAIL"]\').focus(); });';
					$script .= '$(this).css(\'box-shadow\', \'none\');';
				$script .= '});';
				$script .= '$(\'#close-popup\').click(function(){';
					$script .= '$(\'#newsletter-popup\').fadeOut(\'fast\');';
				$script .= '});';
			$script .= '})(jQuery);</script>';
			
			echo $script;
			}
			
			
			
			echo $after_widget;
		}
}
>>>>>>> 8f0ec37444fdd5d736c0ea14f5f7af594014484a
add_action( 'widgets_init', create_function('', 'return register_widget("Portfolio_Display_Widget");') );