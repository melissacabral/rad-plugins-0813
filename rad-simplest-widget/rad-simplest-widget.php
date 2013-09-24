<?php 
/*
Plugin Name: Simplest Widget Template
Description: Starting point for building widgets
Author: Melissa Cabral
Version: 0.1
*/

/**
 * Register the wordpress so WordPress knows it exists
 * @since 0.1
 */
add_action('widgets_init', 'rad_register_simple_widget');
function rad_register_simple_widget(){
	register_widget('Rad_Simple_Widget');
}
/**
 * Set up the Widget Class
 * @since 0.1
 */
class Rad_Simple_Widget extends WP_Widget{
	//base settings for the widget
	function Rad_Simple_Widget(){
		$widget_settings = array( 
			'classname' => 'simple-widget',
			'description' => 'A basic widget with just a title, nothing else. For learning.',
		 );
		$control_settings = array(
			'id-base' => 'simple-widget',
			//'width' => 300, //width in the admin panel
		);
		//id-base, title, widget settings, control settings
		$this->WP_Widget( 'simple-widget', 'Title of Simple Widget', $widget_settings, $control_settings );
	}
	//user-facing display. HTML can go here
	//$args = arguments from register_sidebar, $instance = settings from once instance of this widget
	function widget( $args, $instance ){
		//pull out all the args from the theme function: 'register_sidebar'
		extract($args);
		//get all the data from our instance
		$title = $instance['title'];
		//apply filter hooks to the title
		$title = apply_filters( 'widget_title', $title );
		//more fields go here

		//begin output
		echo $before_widget;
		echo $before_title . $title . $after_title;
	?>
		This is where you would put the actual HTML that makes up your widget display!
	<?php
		echo $after_widget;
	}
	//data sanitization and validation. clean every input from the form
	//new instance: dirty data
	//old instance: previous values saved in the database
	function update( $new_instance, $old_instance ){
		$instance = array();

		//go through each field in the form and sanitize
		$instance['title'] = wp_filter_nohtml_kses( $new_instance['title'] );
		//add more fields here
		
		//return the cleaned data
		return $instance;
	}
	//optional - the form for the admin panel side of the widget
	//instance - the current array of settings for this instance of the widget
	function form( $instance ){
		//set defaults for each field
		$defaults = array( 
			'title' => 'Default title!!!',
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
		<?php
	}
}