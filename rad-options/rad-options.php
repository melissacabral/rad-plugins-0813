<?php
/*
Plugin Name: Company Info Options
Description: adds settings for company Information to the admin panel
Author: Melissa Cabral
Version: 0.1
License: GPLv3
*/

/**
 * Add a section to the Admin Panel under "settings"
 */
add_action('admin_menu', 'rad_settings_page');
function rad_settings_page(){
	//title of page, menu label, capability, menu slug, callback for HTML form
	add_options_page( 'Company Information', 'Company Info', 'manage_options', 'company-info', 'rad_options_build_form' );
}
//callback to build form
function rad_options_build_form(){
	if( ! current_user_can( 'manage_options' ) ):
		wp_die( 'Access denied' );
	else:
		//include the external file for the form
		require_once( plugin_dir_path(__FILE__) . 'rad-options-form.php' );
	endif;
}
/**
 * Create a group of settings in the options table
 */
add_action( 'admin_init', 'rad_register_settings' );
function rad_register_settings(){
	//name of group, name of DB row, sanitizing callback function
	register_setting( 'rad_options_group', 'rad_options', 'rad_options_sanitize' );
}

/**
 * Sanitizing Callback
 * @param $input = array containing all dirty data from the form
 */
function rad_options_sanitize( $input ){
	//strip all HTML and php tags out
	$input['phone'] = wp_filter_nohtml_kses( $input['phone'] );
	$input['email'] = wp_filter_nohtml_kses( $input['email'] );

	//allowed tags for the address
	$allowed = array(
		'br' => array(),
		'p' => array( 'class' => array() ),
		);
	$input['address'] = wp_kses( $input['address'], $allowed );

	//done! send clean data to the DB
	return $input;
}
