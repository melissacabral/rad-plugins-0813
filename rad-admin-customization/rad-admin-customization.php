<?php 
/*
Plugin Name: Admin Panel Tweaks
Description: Makes small changes to the admin and login screens
Author: Melissa Cabral
Version: 0.1
License: GPLv3
*/

/**
 * Style the login and register screens
 * @since ver. 0.1
 */
//add_action('login_head', 'rad_login_style');
function rad_login_style(){ 
	//plugins_url = use this when writing relative paths between files in your plugin
	?>
	<style type="text/css">
		.login h1 a{
			background-image:url(<?php echo plugins_url( 'images/logo.png', __FILE__ ); ?>);
			background-size:auto;
		}
		body.login{
			background-color: #B9D8DB;
		}
	</style>
<?php }

/**
 * Optional: External stylesheet for login
 */
add_action('login_enqueue_scripts', 'rad_login_stylesheet');
function rad_login_stylesheet(){
	$style_path = plugins_url( 'rad-login.css', __FILE__ );
	wp_register_style( 'login-stylesheet', $style_path );
	wp_enqueue_style( 'login-stylesheet' );
}
/**
 * Filter the default login wordpress logo link and title
 */
add_filter('login_headerurl', 'rad_login_link');
function rad_login_link(){
	return home_url();
}
add_filter('login_headertitle', 'rad_login_title');
function rad_login_title(){
	return get_bloginfo( 'description' );
}

/**
 * Change wordpress logo on the admin bar
 */
add_action( 'admin_head', 'rad_admin_bar_logo' );
add_action( 'wp_head', 'rad_admin_bar_logo' );
function rad_admin_bar_logo(){
	if( is_admin_bar_showing() ){ 
		//figure out the path to the icon
		$image_path = plugins_url( 'images/admin-logo.png', __FILE__ );
		?>

		<style type="text/css">
			#wp-admin-bar-wp-logo>.ab-item .ab-icon{
				background-image:url(<?php echo $image_path; ?>);
				background-position: 0 0;
			}
		</style>

	<?php 
	}
}
/**
 * Add Custom Dashboard Widget
 */
add_action( 'wp_dashboard_setup', 'rad_dashboard_help_widget' );
function rad_dashboard_help_widget(){
	wp_add_dashboard_widget( 'rad-dashboard-help', 'Contact me for help!', 'rad_dashboard_widget_body' );
	
	//PUSH THE WIDGET TO THE TOP if the logged in user has not set a position for it
	global $wp_meta_boxes;

	//extract the array of widgets in the 'normal' priority (our widget is at the end of this list)
	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

	//back-up our widget, then delete it
	$backup_dashboard = array('rad-dashboard-help' => $normal_dashboard['rad-dashboard-help']);
	unset($normal_dashboard['rad-dashboard-help']);

	//add our widget at the top of the list
	$sorted_dashboard = array_merge($backup_dashboard, $normal_dashboard);

	//put the array back on the dashboard
	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;

}
//callback for the body of the widget
function rad_dashboard_widget_body(){
	echo 'this is the meat of the widget';
}

/**
 * Remove some dashboard widgets
 */
add_action('wp_dashboard_setup', 'rad_remove_dashboard_widgets');
function rad_remove_dashboard_widgets(){
	//'normal' is the left hand side, 'side' is the right hand column
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
}
/**
 * Hide Welcome Screen
 */
add_action('load-index.php', 'rad_hide_welcome_screen');
function rad_hide_welcome_screen(){
	$user_id = get_current_user_id();

	//if the current user has the dashboard visible, turn it off
	if( 1 == get_user_meta( $user_id, 'show_welcome_panel', true ) ):
		update_user_meta( $user_id, 'show_welcome_panel', 0 );
	endif;
}




