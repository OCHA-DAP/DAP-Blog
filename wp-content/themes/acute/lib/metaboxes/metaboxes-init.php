<?php

/********************* META BOX DEFINITIONS ***********************/

$prefix = '_radium_';

global $meta_boxes, $pagenow;

$meta_boxes = array();


/********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function radium_register_meta_boxes() {
	global $meta_boxes;

	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( class_exists( 'RW_Meta_Box' ) )
	{
		foreach ( $meta_boxes as $meta_box )
		{
			new RW_Meta_Box( $meta_box );
		}
	}
}
// Hook to 'admin_init' to make sure the meta box class is loaded
//  before (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'radium_register_meta_boxes' );

add_action( 'admin_enqueue_scripts', 'radium_load_metabox_conditional_js');
function radium_load_metabox_conditional_js() {

	wp_enqueue_script( 'rwmb-post-formats', BEAN_LIB_URL . '/metaboxes/js/post-formats.js', 'jquery', RWMB_VER, true );

}