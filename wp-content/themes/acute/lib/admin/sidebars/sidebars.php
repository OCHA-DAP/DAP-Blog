<?php
/**
* Plugin Name: Radium Sidebars
* Plugin URI: http://www.themebeans.com
* Description: Replace widget areas in your theme for specific pages, archives and other sections of WordPress.
* Author: RadiumThemes
* Author URI: http://radiumthemes.com/
* Version: 1.1.0
* License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* Based  on WooSidebars by WooThemes
*/
 
require_once(BEAN_ADMIN_DIR . '/sidebars/classes/class.radiumconditions.php' );
require_once(BEAN_ADMIN_DIR . '/sidebars/classes/class.radiumsidebars.php' );
 
global $radiumsidebars;
$radiumsidebars = new Radium_Sidebars();
$radiumsidebars->init();


function radium_add_widget_manager_admin_bar_link() {
	global $wp_admin_bar;
	
	if(!current_user_can('edit_theme_options')) return;

	$urlpagesBase = admin_url( 'edit.php' );
		
	// Add sub menu link "View All Posts"
	$wp_admin_bar->add_node( array(
		'parent' => 'radiumthemes-options',
		'id'     => 'radium_sbm',
		'title'  => __( 'Widget Areas','bean'),
		'href'   => _x($urlpagesBase.'?post_type=sidebar', 'bean'),
	));
	
}
add_action('admin_bar_menu', 'radium_add_widget_manager_admin_bar_link',9999);

/* Message for Widgets page. */

if ( ! function_exists( 'radium_sidebar_widgets_admin_page' ) ) {
	function radium_sidebar_widgets_admin_page() {
		// Kind of a sloppy w/all the yucky inline styles, but otherwise, 
		// we'd have to enqueue an entire stylesheet just for the widgets 
		// page of the admin panel.
		echo '<div style="width:300px;float:right;position:relative;z-index:1000"><p class="description" style="padding-left:5px">';
		_e( 'In the <a href="edit.php?post_type=sidebar">Widget Area Manager</a>, you can create and manage widget areas for specific pages of your website to override the default locations you see below.', 'bean');
		echo '</p></div>';
	}
} 


if ( current_user_can( 'edit_theme_options' ) ) {
   	add_action( 'widgets_admin_page', 'radium_sidebar_widgets_admin_page' );
}
