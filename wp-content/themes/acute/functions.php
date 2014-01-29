<?php
/*===================================================================*/
/*	THIS FILE IS A PART OF THE THEME FRAMEWORK
/*===================================================================*/

do_action( 'bean_pre' );

/* FILTER FEATURES - DISABLE / ENABLE */
function bean_feature_setup() {
	$args = array(
	
		'primary' 	=> array(
			'forms'				=> false,
			'meta'				=> true,
			'menu'				=> true,
			'responsive' 		=> true,			  
			'skins' 			=> true,
			'widgets'			=> true, 
			),
		
		'comments' 	=> array(
			'pages'				=> false,
			'posts'				=> true,
			)
		
	);
	
	return apply_filters( 'bean_theme_config_args', $args );
}

add_action('bean_init', 'bean_feature_setup');

 
function bean_theme_supports( $group, $feature ) {
	$setup = bean_feature_setup();
	if( isset( $setup[$group][$feature] ) && $setup[$group][$feature] )
		return true;
	else
		return false;
}




/*===================================================================*/
/*                    												  
/*  LOAD CONSTANTS  		   		  
/*                    												  
/*===================================================================*/
function bean_constants() {

	/* DEFINE THEME DIRECTORY LOCATION CONSTANTS */
	define( 'PARENT_DIR', get_template_directory() );
	define( 'CHILD_DIR', get_stylesheet_directory() );
	
	/* DEFINE THEME URL LOCATION CONSTANTS */
	define( 'PARENT_URL', get_template_directory_uri() );
	define( 'CHILD_URL', get_stylesheet_directory_uri() );
	
	/* DEFINE THEME INFO CONSTANTS */
	$theme = wp_get_theme();
	$theme_title = $theme->name; 
	$theme_version = $theme->version;
	
	define( 'BEAN_THEME_SLUG', get_template() );
	define( 'BEAN_THEME_NAME', $theme_title );
	define( 'BEAN_THEME_VER', $theme_version );
	
	
  	/*===================================================================*/
  	/*	DEFINE GENERAL CONSTANTS
  	/*===================================================================*/	
 	define( 'BEAN_IMAGES_DIR', PARENT_DIR . '/assets/images' );
	define( 'BEAN_LIB_DIR', PARENT_DIR . '/lib' );
	define( 'BEAN_JS_DIR', PARENT_DIR . '/assets/js' );
	define( 'BEAN_CSS_DIR', PARENT_DIR . '/assets/css' );
	define( 'BEAN_FUNCTIONS_DIR', BEAN_LIB_DIR . '/functions' );
	define( 'BEAN_CONTENT_DIR', BEAN_LIB_DIR . '/content' );
	define( 'BEAN_LANGUAGES_DIR', BEAN_LIB_DIR . '/languages' );
	define( 'BEAN_CPT_DIR', BEAN_LIB_DIR . '/custom-post-types' );

	define( 'BEAN_STYLES_URL', PARENT_URL . '/assets/styles' );
	define( 'BEAN_IMAGES_URL', PARENT_URL . '/assets/images' );
	define( 'BEAN_LIB_URL', PARENT_URL . '/lib' );
	define( 'BEAN_JS_URL', PARENT_URL . '/assets/js' );
	define( 'BEAN_CSS_URL', PARENT_URL . '/assets/css' );
	define( 'BEAN_FUNCTIONS_URL', BEAN_LIB_URL . '/functions' );
	define( 'BEAN_CPT_URL', BEAN_LIB_URL . '/custom-post-types' );
	
	
	/*===================================================================*/
	/*	DEFINE ADMIN CONSTANTS
	/*===================================================================*/	
	define( 'BEAN_ADMIN_DIR', BEAN_LIB_DIR . '/admin' );
	define( 'BEAN_ADMIN_IMAGES_DIR', BEAN_LIB_DIR . '/admin/assets/images' );
	define( 'BEAN_ADMIN_CSS_DIR', BEAN_LIB_DIR . '/admin/assets/css' );
	define( 'BEAN_ADMIN_JS_DIR', BEAN_LIB_DIR . '/admin/assets/js' );
 		
	define( 'BEAN_ADMIN_URL', BEAN_LIB_URL . '/admin' );
	define( 'BEAN_ADMIN_IMAGES_URL', BEAN_LIB_URL . '/admin/assets/images' );
	define( 'BEAN_ADMIN_CSS_URL', BEAN_LIB_URL . '/admin/assets/css' );
	define( 'BEAN_ADMIN_JS_URL', BEAN_LIB_URL . '/admin/assets/js' );
		
	define( 'BEAN_FRAMEWORK_VERSION', '2.0' );
	define( 'BEAN_DOCUMENTATION_URL', 'http://support.themebeans.com' );
	define( 'BEAN_SUPPORT_URL', 'http://support.themebeans.com' );
		
	/* DEVELOPMENT MODE */
	define('DEV_MODE', false);
	
	/* CONDITIONALLY LOADED, THEME SPECIFIC */
	if( bean_theme_supports( 'primary', 'widgets' ) ){
		define( 'BEAN_WIDGETS_DIR', BEAN_LIB_DIR . '/widgets' );
 		define( 'BEAN_WIDGETS_URL', BEAN_LIB_URL . '/widgets' );
	}
}

add_action( 'bean_init', 'bean_constants' );




/*===================================================================*/
/*                    												  
/*  FRAMEWORK LOADER  		   		  
/*                    												  
/*===================================================================*/
function bean_load_framework() {

	do_action( 'bean_pre_framework' );
	
	/*===================================================================*/
	/*	LOAD GENERAL FUNCTIONS - THESE ARE PRETTY IMPORTANT
	/*===================================================================*/	
	require( BEAN_FUNCTIONS_DIR . '/theme-setup.php' );
	require( BEAN_FUNCTIONS_DIR . '/core-functions.php' );
	require( BEAN_FUNCTIONS_DIR . '/theme-functions.php' );
	require( BEAN_FUNCTIONS_DIR . '/customizer.php' );
	require( BEAN_FUNCTIONS_DIR . '/customizer-css.php' );
	require( BEAN_FUNCTIONS_DIR . '/i18n.php' );
	
	if( bean_theme_supports( 'primary', 'meta' ) )
		require_once (BEAN_ADMIN_DIR . '/metaboxes/metaboxes-init.php'); 
	
	/*===================================================================*/
	/*	LOAD THEME WIDGETS
	/*===================================================================*/
	if( bean_theme_supports( 'primary', 'widgets' ) ){
		include_once(BEAN_ADMIN_DIR . '/sidebars/sidebars.php' ); 
	
		include( BEAN_WIDGETS_DIR . '/widget-init.php' );
		include( BEAN_WIDGETS_DIR . '/widget-dribbble.php' );
		include( BEAN_WIDGETS_DIR . '/widget-flickr.php' );
		include( BEAN_WIDGETS_DIR . '/widget-newsletter.php' );
		include( BEAN_WIDGETS_DIR . '/widget-skills.php' );
		include( BEAN_WIDGETS_DIR . '/widget-popular.php' );
		include( BEAN_WIDGETS_DIR . '/widget-social-counter.php' );
	}
		
	if( is_admin() ) { 
		/*===================================================================*/
		/*	LOAD THEME OPTIONS
		/*===================================================================*/
		require( BEAN_ADMIN_DIR . '/admin-init.php' );
		require( BEAN_ADMIN_DIR . '/options/options-init.php' ); 
		require( BEAN_FUNCTIONS_DIR . '/theme-options.php' );
		
		/*===================================================================*/
		/*	LOAD CUSTOM META FIELDS
		/*===================================================================*/
		if( bean_theme_supports( 'primary', 'meta' ) ) {
			
			include( BEAN_LIB_DIR . '/metaboxes/metaboxes-init.php');
			include( BEAN_LIB_DIR . '/metaboxes/meta-post.php');
			include( BEAN_LIB_DIR . '/metaboxes/meta-portfolio.php');
		}
		/*===================================================================*/
		/*	LOAD THEME EXTRAS
		/*===================================================================*/	
		} else {		
		
		include( BEAN_FUNCTIONS_DIR . '/pagination.php' );
		include( BEAN_FUNCTIONS_DIR . '/comments.php' );
		require( BEAN_FUNCTIONS_DIR . '/media.php' );
	  	}
	}

add_action( 'bean_init', 'bean_load_framework' );

/* RUN THE BEAN_INIT HOOK */
do_action( 'bean_init' );

/* RUN THE BEAN_INIT HOOK */
do_action( 'bean_setup' );