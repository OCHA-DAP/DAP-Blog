<?php 
/*===================================================================*/               							
/*  ADD CUSTOM CLASSES TO THE ARRAY OF BODY CLASSES					                							
/*===================================================================*/
function radium_browser_body_class($classes) {

	global $post, $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
		
	$options = get_option('bean_theme');
	
	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_NS4) $classes[] = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) {
	    $classes[] = 'ie';
	    $browser = $_SERVER[ 'HTTP_USER_AGENT' ];
	    if( preg_match( "/MSIE 7.0/", $browser ) ) {
	        $classes[] = 'ie7';
	    }
    }
	else $classes[] = 'unknown';

	if($is_iphone) $classes[] = 'iphone';

	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter('body_class','radium_browser_body_class');




/*===================================================================*/               							
/*  ADD HOME LINK TO MENU					                							
/*===================================================================*/
if ( !function_exists('radium_home_page_menu_args') ) {

	function radium_home_page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
	}
	add_filter( 'wp_page_menu_args', 'radium_home_page_menu_args' );

}




/*===================================================================*/               							
/*  RETURN TEMPLATE					                							
/*===================================================================*/
if ( !function_exists('radium_load_template_part') ) {

	function radium_load_template_part( $template_name, $part_name = null ) {

		ob_start();
			get_template_part( $template_name, $part_name );
			$output = ob_get_contents();
		ob_end_clean();

		return $output;

	}

}




/*===================================================================*/               							
/*  CHECK CURRENT POST FOR SHORT CODE					                							
/*===================================================================*/
if ( !function_exists('radium_has_shortcode') ) {

	function radium_has_shortcode($shortcode = '') {

		global $post;
		
		$post_obj = get_post( $post->ID );
		$found = false;
		
		// if no short code was provided, return false
		if ( !$shortcode )
			return $found;
			
		// check the post content for the short code
		if ( stripos( $post_obj->post_content, '[' . $shortcode ) !== false )
		
			// we have found the short code
			$found = true;

		// return our final results  
		return $found;

	}
}
 
 
 
 
/*===================================================================*/               							
/*  GET CUSTOM FIELD					                							
/*===================================================================*/
if ( !function_exists('radium_get_custom_field') ) {

	function radium_get_custom_field( $key, $post_id = null ) {

		global $wp_query;
		
		$post_id = $post_id ? $post_id : $wp_query->get_queried_object()->ID;
		
		return get_post_meta( $post_id, $key, true );

	}

}




/* ------------------------------------------------------------------ */
/*	Get Custom Taxonomies List. Usage: echo radium_custom_taxonomies_terms_links();
/*  This will List all Taxonomies
/* ------------------------------------------------------------------ */
if ( !function_exists('radium_custom_taxonomies_terms_links') ) {
	
	function radium_custom_taxonomies_terms_links() {
		global $post, $post_id;
		
		// get post by post id
		$post = &get_post($post->ID);
		
		// get post type by post
		$post_type = $post->post_type;
		
		// get post type taxonomies
		$taxonomies = get_object_taxonomies($post_type);
		foreach ($taxonomies as $taxonomy) {
			// get the terms related to post
			$terms = get_the_terms( $post->ID, $taxonomy );
			if ( !empty( $terms ) ) {
				$out = array();
				foreach ( $terms as $term )
					$out[] = '<a href="' .get_term_link($term->slug, $taxonomy) .'">'.$term->name.'</a>';
				$return = join( ', ', $out );
			}
		}
		
		return $return;
	}

}




/*===================================================================*/               							
/*  GET RELATED POSTS BY TAXONOMY					                							
/*===================================================================*/
if ( !function_exists('radium_get_posts_related_by_taxonomy') ) {

	function radium_get_posts_related_by_taxonomy($post_id, $taxonomy, $args=array()) {
	
		$query = new WP_Query();
		$terms = wp_get_object_terms($post_id, $taxonomy);
		if (count($terms)) {
		
		// Assumes only one term for per post in this taxonomy
		$post_ids = get_objects_in_term($terms[0]->term_id,$taxonomy);
		$post = get_post($post_id);
		$args = wp_parse_args($args,array(
		  'post_type' => $post->post_type, // The assumes the post types match
		  //'post__in' => $post_ids,
		  'post__not_in' => array($post_id),
		  'taxonomy' => $taxonomy,
		  'term' => $terms[0]->slug,
		  'orderby' => 'rand',
		  'posts_per_page' => -1
		));
		$query = new WP_Query($args);
		}
		return $query;
	}

}




/*===================================================================*/               							
/*  ADD MORE THEMES LINK				                							
/*===================================================================*/
add_action( 'admin_menu' , 'admin_menu_new_items' );
function admin_menu_new_items() {
    global $submenu;
    $submenu['index.php'][500] = array( 'ThemeBeans.com', 'manage_options' , 'http://themebeans.com/?ref=wp_sidebar' ); 
}




/*------------------------------------------------------------------------------------------------------------*/
/* Create a theme options feed
 * Since the options panel is only loaded in the admin, this has been placed here instead of the admin panel so 
 * that we don't have to load the entire options panel in the frontend when migrating/backing-up options
/*------------------------------------------------------------------------------------------------------------*/
if (!function_exists("radium_download_options")) {

	function radium_download_options(){
		//-'.$this->args['opt_name']
		if(!isset($_GET['secret']) || $_GET['secret'] != md5(AUTH_KEY.SECURE_AUTH_KEY)){wp_die('Invalid Secret for options use');exit;}
		if(!isset($_GET['feed'])){wp_die('No Feed Defined');exit;}
		$backup_options = get_option(str_replace('radiumopts-','',$_GET['feed']));
		$backup_options['radium-opts-backup'] = '1';
		$content = '###'.serialize($backup_options).'###';
		
		if(isset($_GET['action']) && $_GET['action'] == 'download_options'){
			header('Content-Description: File Transfer');
			header('Content-type: application/txt');
			header('Content-Disposition: attachment; filename="'.str_replace('radiumopts-','',$_GET['feed']).'_options_'.date('d-m-Y').'.txt"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			echo $content;
			exit;
		}else{
			echo $content;
			exit;
		}
	}
	add_action('do_feed_radiumopts-bean_theme',  'radium_download_options', 1, 1);
	
}

		


/*===================================================================*/               							
/*  ADD THEME OPTIONS BUTTON TO ADMIN HEADER			                							
/*===================================================================*/
add_action('admin_bar_menu', 'bean_add_toolbar_items', 100);
function bean_add_toolbar_items($admin_bar){
	$admin_bar->add_menu( array(
		'id'    => 'theme-options',
		'title' => 'Theme Options',
		'href'  => ''.get_admin_url().'themes.php?page=bean_options',	
		'meta'  => array(
		'title' => __('Theme Options'),			
		),
	));
}




/*===================================================================*/        							
/*  FOOTER TYPE EDIT									 					
/*===================================================================*/
function bean_footer_admin () {  
  echo 'Thank you for creating with <a href="http://themebeans.com/?ref=wp_footer" target="blank">ThemeBeans</a>. You rock.';  
}  
  
add_filter('admin_footer_text', 'bean_footer_admin'); 




/*===================================================================*/               							
/*  REDIRECT TO SEARCH RESULT, IF ONLY ONE RESULT IS FOUND				                							
/*===================================================================*/
function single_search_result() {  
    if (is_search()) {  
        global $wp_query;  
        if ($wp_query->post_count == 1) {  
            wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );  
        }  
    }  
}  

add_action('template_redirect', 'single_search_result'); 




/*===================================================================*/               							
/*  SEARCH ONLY POSTS, NOT PAGES					                							
/*===================================================================*/
function BeanSearchFilter($query) {
	if ($query->is_search) {
	$query->set('post_type', 'post');
	}	
	return $query;
	}

add_filter('pre_get_posts','BeanSearchFilter');




/*===================================================================*/               							
/*  BEAN PLUGIN NOTIFICATION					                							
/*===================================================================*/
add_action('admin_notices', 'bean_plugin_admin_notice');

function bean_plugin_admin_notice() {
	global $current_user ;
    $user_id = $current_user->ID;

	if ( ! get_user_meta($user_id, 'bean_ignore_notice') ) {
	    echo '<div class="updated"><p>'; 
	    printf(__('This theme is compatible with the ThemeBeans <a href="http://themebeans.com/plugin/portfolio-wordpress-plugin/?ref=plugin_notice" target="blank">Portfolio</a>, <a href="http://themebeans.com/plugin/bean-tweets-plugin/?ref=plugin_notice" target="blank">Tweets</a>, <a href="http://themebeans.com/plugin/bean-social-plugin/?ref=plugin_notice" target="blank">Social</a>,  <a href="http://themebeans.com/plugin/bean-shortcodes-plugin/?ref=plugin_notice" target="blank">Shortcodes</a> & <a href="http://themebeans.com/plugin/bean-instagram-plugin/?ref=plugin_notice" target="blank">Instagram</a> WordPress Plugins. <a href="%1$s">Dismiss</a>'), '?bean_plugin_ignore=0');
	    echo "</p></div>";
	}
	
}
add_action('admin_init', 'bean_plugin_ignore');

function bean_plugin_ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        if ( isset($_GET['bean_plugin_ignore']) && '0' == $_GET['bean_plugin_ignore'] ) {
             add_user_meta($user_id, 'bean_ignore_notice', 'true', true);
	}
}


/*===================================================================*/               							
/*  CUSTOM EDITOR STYLE						                							
/*===================================================================*/
function bean_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
	}
add_action( 'init', 'bean_add_editor_styles' );