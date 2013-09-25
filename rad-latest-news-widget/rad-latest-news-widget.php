<?php 
/*
Plugin Name: Latest News with thumbnails
Description: Displays a configurable number of posts in a widget. 
Author: Melissa Cabral
Version: 0.1
*/

/**
 * Attach the stylesheet
 * @since 0.1
 */
add_action( 'wp_enqueue_scripts', 'rad_latest_news_stylesheet' );
function rad_latest_news_stylesheet(){
	$style_path = plugins_url( 'rad-latest-news-widget.css', __FILE__ );
	wp_register_style( 'rad-news-style', $style_path );
	wp_enqueue_style( 'rad-news-style' );
}



/**
 * Register the wordpress so WordPress knows it exists
 * @since 0.1
 */
add_action('widgets_init', 'rad_register_latest_news_widget');
function rad_register_latest_news_widget(){
	register_widget('Rad_Latest_News_Widget');
}
/**
 * Set up the Widget Class
 * @since 0.1
 */
class Rad_Latest_News_Widget extends WP_Widget{
	//base settings for the widget
	function Rad_Latest_News_Widget(){
		$widget_settings = array( 
			'classname' => 'latest-news-widget',
			'description' => 'Displays any number of recent posts with pictures',
		 );
		$control_settings = array(
			'id-base' => 'latest-news-widget',
			//'width' => 300, //width in the admin panel
		);
		//id-base, title, widget settings, control settings
		$this->WP_Widget( 'latest-news-widget', 'Latest News', $widget_settings, $control_settings );
	}
	//user-facing display. HTML can go here
	//$args = arguments from register_sidebar, $instance = settings from once instance of this widget
	function widget( $args, $instance ){
		//pull out all the args from the theme function: 'register_sidebar'
		extract($args);
		//get all the data from our instance
		$title = $instance['title'];
		$number = $instance['number'];
		$show_excerpt = $instance['show_excerpt'];

		//apply filter hooks to the title
		$title = apply_filters( 'widget_title', $title );
		//more fields go here

		//begin output

		//set up a new instance of the WP_Query object
		$news_query = new WP_Query( array(
			'showposts' => $number,
			'ignore_sticky_posts' => 1,
			) );
		if( $news_query->have_posts() ):

			echo $before_widget;
			echo $before_title . $title . $after_title;
	?>
		<ul>
			<?php while( $news_query->have_posts() ): 
				$news_query->the_post();?>
			<li>
				<a href="<?php the_permalink(); ?>" class="thumbnail-link">
					<?php the_post_thumbnail('thumbnail'); ?>
				</a>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<?php if($show_excerpt == 1): ?>
					<p><?php the_excerpt(); ?></p>
				<?php endif; ?>
			</li>
			<?php endwhile; ?>
		</ul>
	<?php
			echo $after_widget;
		endif; //have posts
		//clean up after our custom loop variables
		wp_reset_postdata();
	}
	//data sanitization and validation. clean every input from the form
	//new instance: dirty data
	//old instance: previous values saved in the database
	function update( $new_instance, $old_instance ){
		$instance = array();

		//go through each field in the form and sanitize
		$instance['title'] = wp_filter_nohtml_kses( $new_instance['title'] );
		$instance['number'] = wp_filter_nohtml_kses( $new_instance['number'] );
		$instance['show_excerpt'] = wp_filter_nohtml_kses( $new_instance['show_excerpt'] );
		//add more fields here
		
		//return the cleaned data
		return $instance;
	}
	//optional - the form for the admin panel side of the widget
	//instance - the current array of settings for this instance of the widget
	function form( $instance ){
		//set defaults for each field
		$defaults = array( 
			'title' => 'Awesome News',
			'number' => 5,
			'show_excerpt' => 1,
			//add more fields here in the future
			);
		//merge the defaults with the user-provided values
		$instance = wp_parse_args( (array) $instance, $defaults );

		//HTML for the form fields
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input type="text" 
			name="<?php echo $this->get_field_name('title'); ?>" 
			id="<?php echo $this->get_field_id('title'); ?>" 
			value="<?php echo $instance['title']; ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>">Number of Posts:</label>
			<input type="number" 
			name="<?php echo $this->get_field_name('number'); ?>" 
			id="<?php echo $this->get_field_id('number'); ?>" 
			value="<?php echo $instance['number']; ?>">
		</p>
		<p>
			<input type="checkbox" 
			name="<?php echo $this->get_field_name('show_excerpt'); ?>" 
			id="<?php echo $this->get_field_id('show_excerpt'); ?>" 
			value="1" 
			<?php checked( $instance['show_excerpt'], 1 ); ?> >

			<label for="<?php echo $this->get_field_id('show_excerpt'); ?>">Show the excerpt?</label>
		</p>

		<?php
	}
}