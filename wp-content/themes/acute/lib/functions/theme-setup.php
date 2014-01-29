<?php

/*===================================================================*/
/*                    										
/*     ADD OUR SCRIPTS										
/*                    										
/*===================================================================*/
if ( !function_exists( 'add_our_scripts') ) {
		 
	function add_our_scripts() {
		global $post;
		
		$options = get_option('bean_theme');
	 	
	 	wp_enqueue_style('bean', get_stylesheet_directory_uri(). '/assets/css/framework.css', false,'1.0','all'); 
	 	wp_enqueue_style('main-style', get_stylesheet_directory_uri(). '/style.css', false, '1.4', 'all');
		wp_enqueue_style('mobile', get_stylesheet_directory_uri(). '/assets/css/mobile.css',false,'1.0','all');
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('custom-libraries', BEAN_JS_URL . '/custom-libraries.js', 'jquery', '1.0', true);
		wp_enqueue_script('custom', BEAN_JS_URL . '/custom.js', 'jquery', '2.0', true);
		wp_enqueue_script('fitvids', BEAN_JS_URL . '/jquery.fitvids.js', 'jquery', '1.0.3', true);
		
		if( get_theme_mod( 'retina_option' ) == true) {
			wp_enqueue_script('retina', BEAN_JS_URL . '/retina.js', 'jquery', '2.0', true);
		}
				
		global $is_IE;
		
		if ( $is_IE ) {
			wp_enqueue_script('selectivizr', BEAN_JS_URL . '/selectivizr-min.js', 'jquery', '2.0', false);
		}
	 			
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
		
		if ( is_page_template('page-contact.php') || is_page_template('page-contact-sidebar-right.php') || is_singular('post') ) { 	
			wp_register_script('validation', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js', 'jquery', '1.9', true);
			wp_enqueue_script('validation');
		}	
		
	}
	add_action( 'wp_enqueue_scripts', 'add_our_scripts',0);
}




/*===================================================================*/
/*                    										
/*     BEAN THEME SETUP											
/*                    										
/*===================================================================*/
if ( !function_exists( 'bean_theme_setup') ) {

	function bean_theme_setup() {
		if ( function_exists( 'add_theme_support' ) ) {
	
			add_theme_support('post-thumbnails');
			add_theme_support('automatic-feed-links');
			add_image_size( 'thumbnail-large', 725, 235, true );
			add_image_size( 'thumbnail-port', 480, 360, true );
			add_image_size( 'thumbnail-port-single', 760 ); 
			add_image_size( 'post-full', 780, 999, true );
			
			set_post_thumbnail_size( 140, 140, true );
	
			// POST FORMATS
			add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio'));
			
		}
		//add editor style so as to match content in editor
		add_editor_style('assets/css/style-editor.css');
		if ( ! isset( $content_width ) ) $content_width = 940;
	 
	}
	add_action('after_setup_theme', 'bean_theme_setup');
}



/*===================================================================*/
/*                    										
/*     PRIMARY NAVIGATION									
/*                    										
/*===================================================================*/
// Register WP Menus */
if ( function_exists( 'wp_nav_menu') ) {

	add_theme_support( 'nav-menus' );
	$menus = array(
		'main-menu' => __( 'Primary Navigation', 'bean' ),
	);
	$menus = apply_filters( 'radium_nav_menus', $menus );
	register_nav_menus( $menus );
	
}
 
 
 
/*===================================================================*/
/*                    										
/*     CUSTOM CSS STYLES									
/*                    										
/*===================================================================*/
/* Background Image */
if ( !function_exists('radium_insert_custom_styles') ) {
	function radium_insert_custom_styles() {
		$options = get_option('bean_theme');	
 	?>

<style>
<?php 
	/* IF SCROLLABLE SIDEBAR */
	if( $options['sliding_panel_scroll'] ): ?>	
	
	#panel .holder {
		overflow-y:scroll;
		height: 100%;
		}
	
	<?php endif;
	/* 404 BG IMAGE */
		if ($options['error_404_bg']): ?>
			
		<?php $error404_bg_image = $options['error_404_bg']; ?>
				
		body.error404 {
			background: url(<?php echo $error404_bg_image; ?>) no-repeat fixed center center; 
			-webkit-background-size: cover;
			   -moz-background-size: cover;
			     -o-background-size: cover;
			        background-size: cover;
			}	

	<?php endif; ?>

</style>


<?php } add_action('wp_head', 'radium_insert_custom_styles'); }