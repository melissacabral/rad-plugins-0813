<?php
/*
Plugin Name: Custom Post Type - Products
Description: creates support for a catalog of products, organized by brand and features
Author: Melissa Cabral
Plugin URI: http://wordpress.melissacabral.com
Version: 0.1
License: GPLv3
*/

/**
 * Activate Custom Post type and admin UI
 * @since 0.1
 */

add_action('init', 'rad_register_cpt');
function rad_register_cpt(){
	register_post_type( 'product', array(
		'public' => true,
		'labels' => array( 
			'name' => 'Products',
			'singular_name' => 'Product',
			'not_found' => 'No Products Found',
			'add_new_item' => 'Add New Product',
		 ),
		'has_archive' => true,
		'rewrite' => array( 'slug' => 'shop' ),
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'  ),
	));
}

/**
 * Flush Rewrite rules automagically when the plugin is activated
 * @since 0.1
 */

function rad_flush(){
	rad_register_cpt();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'rad_flush' );

