<?php
/*===================================================================*/
/*                    							
/*                    							
/*                    							 
/*     GENERAL THEME FUNCTIONS					
/*     PLAY SAFE YOUNG ONE, INCORRECT MODIFICATIONS TO THIS CODE CAN BREAK THINGS BIG TIME. 	 
/*                    							
/*                    							
/*                    							
/*===================================================================*/

/*===================================================================*/               							
/* 	ENQEUE THEME FONTS FROM GOOGLE FONTS		                							
/*===================================================================*/
function bean_theme_fonts() {
    $protocol = is_ssl() ? 'https' : 'http';
    wp_enqueue_style( 'SourceSansPro', "$protocol://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400" );
    }
add_action( 'wp_enqueue_scripts', 'bean_theme_fonts' );


/*===================================================================*/           							
/*  CLEAN UP THE <HEAD>						                   							
/*===================================================================*/
//remove_action('wp_head','feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
//remove_action('wp_head','feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
//remove_action('wp_head','rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
//remove_action('wp_head','wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
//remove_action('wp_head','index_rel_link'); // index link
//remove_action('wp_head','parent_post_rel_link', 10, 0); // prev link
//remove_action('wp_head','start_post_rel_link', 10, 0); // start link
//remove_action('wp_head','adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.

remove_action('wp_head','wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version


/*===================================================================*/               							
/* 	OVERRIDE DEFAULT LOGO IF SET IN OPTIONS bean_custom_logo()		                							
/*===================================================================*/
if ( !function_exists('bean_custom_logo') ) {

	function bean_custom_logo() { $options = get_option('bean_theme'); ?>
	  	
	  	<a href="<?php echo 'http://data.hdx.rwlabs.org'; ?>" title="<?php echo bloginfo( 'name' ); ?>" rel="home">
	
	 		<?php if(!$options['logo']) { ?>
			
				 <h1 id="logo_text"><?php echo bloginfo( 'name' ); ?></h1>
				 
	 		 <?php 
	 		 
	 		 } else { $logo_img = $options['logo'];

					if(empty($logo_img)) { $logo_img = BEAN_IMAGES_URL.'/logo.png'; } ?>

				<img src=" <?php echo $logo_img; ?>" class="logo" alt="logo"/>	

			<?php } ?>
			  
		</a>
		
		<?php  
	 
	 }
	add_action('bean_header_logo', 'bean_custom_logo');
}




/*===================================================================*/               							
/* 	FOOTER LOGO OPTION		                							
/*===================================================================*/
if ( !function_exists('bean_footer_logo') ) {

	function bean_footer_logo() { $options = get_option('bean_theme'); ?>
	  	
	  	<a href="<?php echo home_url(); ?>" title="<?php echo bloginfo( 'name' ); ?>" rel="home">
	
	 		<?php if(!$options['footer_logo']) { ?>
			
				 <h1 id="logo_text"><?php echo bloginfo( 'name' ); ?></h1>
				 
	 		 <?php 
	 		 
	 		 } else { $footer_logo_img = $options['footer_logo'];
				
					if(empty($footer_logo_img)) { $footer_logo_img = BEAN_IMAGES_URL.'/logo-foot.png'; } ?>
		
				<img src="<?php echo $footer_logo_img; ?>" class="logo" alt="logo"/>	
							
			<?php } ?>
			  
		</a>
		
		<?php  
	 
	 }
	add_action('bean_footer_logo', 'bean_footer_logo');
}




/*===================================================================*/   							
/*  CUSTOM ADMIN & LOGIN LOGO				      							
/*===================================================================*/
if ( !function_exists('radium_login_head') ) {
 
	function radium_login_head() {
	  	
	  	$options = get_option('bean_theme');
	
		$logo_url = '';
		if ( isset($options['logo']) && $options['logo'] != '' ) { $logo_url = $options['logo'];
		} else { $logo_url = BEAN_IMAGES_URL.'/login-logo.png'; }
		$logo_url = '/wp-content/uploads/logo-placeholder-footer-31.png';
		if ( $logo_url != '' ) {
			$dimensions = @getimagesize( $logo_url );
			echo '<style>' . "\n" . 'body.login #login h1 a { background: url("' . $logo_url . '") no-repeat scroll center top transparent; height: ' . $dimensions[1] . 'px; width: auto!important; background-size: auto !important; width: auto;}' . "\n" . '</style>' . "\n";
			
		}
	
	} 
	add_filter('login_head', 'radium_login_head');	
}

if ( !function_exists('radium_login_header_url') ) {

	function radium_login_header_url($url) {
		
		$options = get_option('bean_theme');
		
		$login_url = home_url();
	
		return $login_url;
	    
	} 
	add_filter('login_headerurl', 'radium_login_header_url');
}

if ( !function_exists('radium_login_header_title') ) {

	function radium_login_header_title($title) {
	
		$title_text = get_bloginfo('name').' &raquo; Log In';
	
		return $title_text;
	
	} 
	add_filter('login_headertitle', 'radium_login_header_title');
}	




/*===================================================================*/       							
/*  CUSTOM FAVICON AND APPLE TOUCH ICON		       							
/*===================================================================*/
if ( !function_exists('bean_add_favicon') ) {

	function bean_add_favicon() { 
	
	 	$options = get_option('bean_theme');
		
		$favicon = $options['favicon'];
		$appleicon = $options['appleicon'];
		
		if(empty($favicon)) { 
			$favicon = BEAN_IMAGES_URL.'/favicon.png';
		}
		
		if(empty($appleicon)) { 
			$appleicon = BEAN_IMAGES_URL.'/apple-touch-icon.png';
		}
		?>
			<link rel="shortcut icon" href="<?php echo $favicon ?>"/> 
			<link rel="apple-touch-icon-precomposed" href="<?php echo $appleicon ?>"/>
			
		<?php 
	}
	
	add_action('wp_head', 'bean_add_favicon');
}




/*===================================================================*/        							
/*  RENDER THE ANALYTICS EITHER IN THE FOOTER OR HEADER						 		                 			              				
/*===================================================================*/
if( !function_exists('bean_analytics') ) {

	function bean_analytics($IsHeader) {
	
		$options = get_option('bean_theme');
		
		if($IsHeader):
			if ($options['header_analytics']):
				echo $options['header_analytics'];
			endif;
		else:
			if($options['other_analytics']):
		    	echo $options['other_analytics'];
			endif;
		endif;
		
	}
}




/*===================================================================*/              							
/*  RENDERS THE THEME FEED URL (USER OR WORDPRESS FEED)				                							
/*===================================================================*/
if ( !function_exists('radium_feed_url') ) {
	
	function radium_feed_url() {
		
 		$options = get_option('bean_theme');
 	
 		if ( isset( $options['feedburner_url'] ) ) {
 			   echo $options['feedburner_url'];
 			
		} else {
		
			 echo get_bloginfo_rss('rss2_url');
			 
		}
		
	}
}
 



/*===================================================================*/               							
/*  ADD ODD / EVEN CLASSES TO POSTS					                							
/*===================================================================*/
if ( !function_exists('bean_post_class') ) {

	function bean_post_class() {
	
		global $post_num;
			if ( ++$post_num % 2 )
				$class = 'even';
			else
				$class = 'odd';
		?>
	
	<?php post_class($class) ?> 

	<?php } add_action('bean_post_class','bean_post_class'); 
}





/*===================================================================*/               							
/*  ADD SOCIAL MEDIA FIELDS TO PROFILE META					                							
/*===================================================================*/
function bean_author_fields( $contactmethods ) {
	$contactmethods['twitter'] = 'Twitter Username:';
	$contactmethods['dribbble'] = 'Dribbble URL:';
	$contactmethods['facebook'] = 'Facebook URL:';
	$contactmethods['instagram'] = 'Instagram URL:';
	$contactmethods['google_plus'] = 'Google Plus URL:';

	return $contactmethods;
	}
	
	add_filter('user_contactmethods','bean_author_fields',10,1);




/*===================================================================*/
/*  LOAD SINGLE POST NAVIGATION				          							
/*===================================================================*/
if ( !function_exists('bean_single_posts_navigation') ) {

	function bean_single_posts_navigation() { 
		
		$options = get_option('bean_theme');
		 
		if( is_single() && isset($options['blog_pagination'] ) ) : ?>
	
			<div class="pagination hide-for-small animated BeanBounceIn">
				
				<span class="pagination-arrows">
				
					<span class="page-previous">
				
						<?php previous_post_link('%link', ''); ?>
				
					</span><!-- END .page-previous -->
				
				</span><!-- END .pagination-arrows -->
				
				<span class="pagination-arrows">
		
					<span class="page-next">
				
						<?php next_post_link('%link', ''); ?>
				
					</span><!-- END .page-next -->
				
				</span><!-- END .pagination-arrows -->
		
			</div><!-- END .pagination -->
		
		<?php endif;
		
	} add_action('bean_single_pagination', 'bean_single_posts_navigation');
}





/*===================================================================*/               							
/*  BEAN ANNOUNCEMENT TEXT					                							
/*===================================================================*/
if( !function_exists('bean_announcement_text') ) {
 
	function bean_announcement_text(){ 
		
		$options = get_option('bean_theme');
		
		if (isset( $options['announcement_text'] ))
			  echo $options['announcement_text'];
	
	} add_action('bean_announcement_text', 'bean_announcement_text', 1);
}




/*===================================================================*/               							
/*  DISPLAY BEAN ANNOUNCEMENT IN HEADER					                							
/*===================================================================*/
if ( !function_exists('bean_announcement') ) {

	function bean_announcement() {  $options = get_option('bean_theme'); 
	
	if ( isset($options['announcement'] ) ) { ?>
		
			<div id="bean_announcement" class="load-announcement animated BeanFadeIn hide-for-small">
				
				<div class="row">
					
					<div class="twelve columns">
						
						<p><?php do_action('bean_announcement_text'); ?></p>
						
						<div class="close-btn"></div>
					
					</div><!-- END .twelve columns --> 
				
				</div><!-- END .row --> 
			
			</div><!-- END #bean_announcement --> 		
		
		<?php } } add_action('bean_announcement','bean_announcement'); 
}




/*===================================================================*/               							
/*  DISPLAY SLIDING PANEL				                							
/*===================================================================*/
if ( !function_exists('bean_sliding_panel') ) {

	function bean_sliding_panel() { $options = get_option('bean_theme'); 

		 if ( isset($options['sliding_panel'] ) ) { ?> 
			
		  	<div id="panel" class="animated BeanFadeIn BeanPanelIntro hide-for-small">
		  	
		  		<div class="toggle">
		  			
		  			<span class="toggle-icon"></span>
		  		
		  		</div><!-- END .toggle -->
		  	
		  		<div class="holder">
		  		
		  			<?php if ( !dynamic_sidebar( 'Sliding Panel' ) ): endif; ?>
		  		
		  		</div><!-- END .holder -->
		  	
		  	</div><!-- END #panel -->

		<?php } } add_action('bean_sliding_panel','bean_sliding_panel'); 
}





/*===================================================================*/               							
/*  CUSTOM BLOG HEADER - SINGLE POSTS					                							
/*===================================================================*/
if( !function_exists('bean_blog_header_text') ) {
 
	function bean_blog_header_text(){ 
		
		$options = get_option('bean_theme');

		if (isset( $options['blog_header_text'] )) {
			echo $options['blog_header_text'];
			}
	
	} add_action('bean_blog_header_text', 'bean_blog_header_text', 1);
}




/*===================================================================*/            							
/*  POST META 								               							
/*===================================================================*/
if ( !function_exists('bean_time_stamp') ) {

	function bean_time_stamp() {
		printf( __( '<time class="entry-date" datetime="%3$s">%4$s</time>', 'bean' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'bean' ), get_the_author() ) ),
			get_the_author()
		);
	}

}

// META IT UP
if ( !function_exists('bean_post_meta') ) {

	function bean_post_meta() { ?>

	    <div class="entry-meta">

	    	<?php $options = get_option('bean_theme'); $post_options = isset($options['post_options']) ? $options['post_options'] : false;  	


    		if ( $post_options && array_key_exists('post_author', $post_options) ) {
				echo '<span class="author-icon">';
					echo get_avatar( get_the_author_meta('user_email'), '30', '' );
					the_author();
				echo '</span>';

			}

			if ( $post_options && array_key_exists('post_date', $post_options) ) { 
				echo '<span class="post-icon meta-time"></span>'; bean_time_stamp(); 
			}

			/* getting rid of categories
			if ( $post_options && array_key_exists('post_category', $post_options) ) { 
				echo '<span class="post-icon meta-category"></span><span class="meta-label">'; the_category(', '); echo '</span>'; 
			}
			*/

			/* getting rid of tags
    		if ( $post_options && array_key_exists('post_tags', $post_options) ) { 
    			if( has_tag() ) { echo '<span class="post-icon meta-tags"></span><span class="meta-label">'; the_tags('', ', ', '');  echo '</span>'; }
    		}
    		*/
    		
	    	if ( $post_options && array_key_exists('post_comments', $post_options) ) { 
	    	
		    	if ( comments_open() && ! post_password_required() ) : 
	
			    	if (get_comments_number()==0) {
				    	
				    	} else {
				    	     echo '<span class="comment-count"><span class="post-icon meta-comment"></span>';
				    	     
				    	     comments_popup_link( '', _x( '1 Comment', 'comments number', 'bean' ), _x( '% Comments', 'comments number', 'bean' ) );
				    	     
			    	}

		    		if (get_comments_number()!=0) { echo '</span>'; }    	
		    			
		    	endif;
	    	
	    	}
	    	
	    	edit_post_link( __( '[Edit]', 'bean' ), '<span class="edit-link">', '</span>' ); 
	    	
	    	?>
	    	
	    </div><!-- END .entry-meta --><?php
	
	} add_action('bean_post_meta','bean_post_meta');
}


/*===================================================================*/               							
/*  LOAD FOOTER COLOPHON					                							
/*===================================================================*/
// COPYRIGHT TEXT INPUT
if( !function_exists('bean_copyright') ) {
 
	function bean_copyright(){ $options = get_option('bean_theme');
		
		if ( isset($options['copyright_text'] ) )
			  echo $options['copyright_text'];
	
	} add_action('bean_copyright', 'bean_copyright', 1);
}

// COLOPHON INPUT (INCLUDES THE ABOVE THEME OPTIONS INPUT)
if ( !function_exists('bean_colophon') ) {

	function bean_colophon() { ?>
		
		<div class="colophon">
		
			<?php do_action( 'bean_footer_logo' ); ?>
			
			<span class="copyright">
			
				<?php do_action('bean_copyright'); ?> 
				
			</span>

		</div> <!--END .colophon-->
				
	<?php } add_action('bean_colophon','bean_colophon'); 
}





/*===================================================================*/            							
/*  ARCHIVES TEMPLATE									                  							
/*===================================================================*/
if( !function_exists('bean_archive_all_text') ) {
 
	function bean_archive_all_text(){ $options = get_option('bean_theme');
		
		if (isset( $options['archive_all_text'] ))
			  echo $options['archive_all_text'];
	
	} add_action('bean_archive_all_text', 'bean_archive_all_text', 1);	
}


if( !function_exists('bean_archive_latest') ) {
 
	function bean_archive_latest(){ $options = get_option('bean_theme');
		
		if (isset( $options['archive_latest'] ))
			  echo $options['archive_latest'];
	
	} add_action('bean_archive_latest', 'bean_archive_latest', 1);
}


if( !function_exists('bean_archive_monthly') ) {
 
	function bean_archive_monthly(){ $options = get_option('bean_theme');
		
		if (isset( $options['archive_monthly'] ))
			  echo $options['archive_monthly'];
	
	} add_action('bean_archive_monthly', 'bean_archive_monthly', 1);	
}


if( !function_exists('bean_archive_category') ) {
 
	function bean_archive_category(){ $options = get_option('bean_theme');
		
		if (isset( $options['archive_category'] ))
			  echo $options['archive_category'];
	
	} add_action('bean_archive_category', 'bean_archive_category', 1);
}


if( !function_exists('bean_archive_sitemap') ) {
 
	function bean_archive_sitemap(){ $options = get_option('bean_theme');
		
		if (isset( $options['archive_sitemap'] ))
			  echo $options['archive_sitemap'];
	
	} add_action('bean_archive_sitemap', 'bean_archive_sitemap', 1);
}




/*===================================================================*/            							
/*  404 TEMPLATE					                  							
/*===================================================================*/
if( !function_exists('bean_404_header') ) {
 
	function bean_404_header(){ $options = get_option('bean_theme');
		
		if (isset( $options['404_error_header'] ))
			  echo $options['404_error_header'];
	
	} add_action('bean_404_header', 'bean_404_header', 1);
}

if( !function_exists('bean_404_error_text') ) {
 
	function bean_404_error_text(){ $options = get_option('bean_theme');
		
		if (isset( $options['404_error_text'] ))
			  echo $options['404_error_text'];
	
	} add_action('bean_404_error_text', 'bean_404_error_text', 1);
}

if( !function_exists('bean_404_error_p') ) {
 
	function bean_404_error_p(){ $options = get_option('bean_theme');
		
		if (isset( $options['404_error_p_text'] ))
			  echo $options['404_error_p_text'];
	
	} add_action('bean_404_error_p', 'bean_404_error_p', 1);
}

if( !function_exists('bean_404_btn') ) {
 
	function bean_404_btn(){ $options = get_option('bean_theme');
		
		if (isset( $options['404_error_btn_text'] ))
			  echo $options['404_error_btn_text'];
	
	} add_action('bean_404_btn', 'bean_404_btn', 1);
}




/*===================================================================*/               							
/*  SOCIAL PROFILES THEME OPTIONS INPUT									      							
/*===================================================================*/
// TWITTER
if( !function_exists('bean_profile_twitter') ) {
 
	function bean_profile_twitter(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_twitter'] ))
			  echo $options['profile_twitter'];
	
	} add_action('bean_profile_twitter', 'bean_profile_twitter', 1);
}

// FACEBOOK  
if( !function_exists('bean_profile_facebook') ) {
 
	function bean_profile_facebook(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_facebook'] ))
			  echo $options['profile_facebook'];
	
	} add_action('bean_profile_facebook', 'bean_profile_facebook', 1);
}

// DRIBBBLE  
if( !function_exists('bean_profile_dribbble') ) {
 
	function bean_profile_dribbble(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_dribbble'] ))
			  echo $options['profile_dribbble'];
	
	} add_action('bean_profile_dribbble', 'bean_profile_dribbble', 1);
}

// INSTAGRAM  
if( !function_exists('bean_profile_instagram') ) {
 
	function bean_profile_instagram(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_instagram'] ))
			  echo $options['profile_instagram'];
	
	} add_action('bean_profile_instagram', 'bean_profile_instagram', 1);
}

// ZERPLY 
if( !function_exists('bean_profile_zerply') ) {
 
	function bean_profile_zerply(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_zerply'] ))
			  echo $options['profile_zerply'];
	
	} add_action('bean_profile_zerply', 'bean_profile_zerply', 1);
}

// GITHUB  
if( !function_exists('bean_profile_github') ) {
 
	function bean_profile_github(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_github'] ))
			  echo $options['profile_github'];
	
	} add_action('bean_profile_github', 'bean_profile_github', 1);
}

// EMAIL 
if( !function_exists('bean_profile_email') ) {
 
	function bean_profile_email(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_email'] ))
			  echo $options['profile_email'];
	
	} add_action('bean_profile_email', 'bean_profile_email', 1);
}

// FORRST 
if( !function_exists('bean_profile_forrst') ) {
 
	function bean_profile_forrst(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_forrst'] ))
			  echo $options['profile_forrst'];
	
	} add_action('bean_profile_forrst', 'bean_profile_forrst', 1);
}

// FLICKR  
if( !function_exists('bean_profile_flickr') ) {
 
	function bean_profile_flickr(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_flickr'] ))
			  echo $options['profile_flickr'];
	
	} add_action('bean_profile_flickr', 'bean_profile_flickr', 1);
}

// RSS  
if( !function_exists('bean_profile_rss') ) {
 
	function bean_profile_rss(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_rss'] ))
			  echo $options['profile_rss'];
	
	} add_action('bean_profile_rss', 'bean_profile_rss', 1);
}

// LINKEDIN  
if( !function_exists('bean_profile_linkedin') ) {
 
	function bean_profile_linkedin(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_linkedin'] ))
			  echo $options['profile_linkedin'];
	
	} add_action('bean_profile_linkedin', 'bean_profile_linkedin', 1);
}

// GOOGLE PLUS  
if( !function_exists('bean_profile_google') ) {
 
	function bean_profile_google(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_google'] ))
			  echo $options['profile_google'];
	
	} add_action('bean_profile_google', 'bean_profile_google', 1);
}

// BEHANCE  
if( !function_exists('bean_profile_behance') ) {
 
	function bean_profile_behance(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_behance'] ))
			  echo $options['profile_behance'];
	
	} add_action('bean_profile_behance', 'bean_profile_behance', 1);
}

// PINTEREST  
if( !function_exists('bean_profile_pinterest') ) {
 
	function bean_profile_pinterest(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_pinterest'] ))
			  echo $options['profile_pinterest'];
	
	} add_action('bean_profile_pinterest', 'bean_profile_pinterest', 1);
}

// YOUTUBE  
if( !function_exists('bean_profile_youtube') ) {
 
	function bean_profile_youtube(){ $options = get_option('bean_theme');
		
		if (isset( $options['profile_youtube'] ))
			  echo $options['profile_youtube'];
	
	} add_action('bean_profile_youtube', 'bean_profile_youtube', 1);
}


/*===================================================================*/               							
/*  DISPLAY FOOTER SOCIAL ICONS					                							
/*===================================================================*/
if ( !function_exists('bean_social_footer') ) {

	function bean_social_footer() {  $options = get_option('bean_theme'); ?>
	
		<ul class="social-foot">
									
			<?php $social_footer = isset($options['social_checklist']) ? $options['social_checklist'] : false;  ?>	
				
			<?php if ( $social_footer && array_key_exists('twitter_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="http://www.twitter.com/<?php do_action('bean_profile_twitter'); ?>" title="Twitter" class="social-icon twitter"></a>
				</li>
				
			<?php } if ( $social_footer && array_key_exists('facebook_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="<?php do_action('bean_profile_facebook'); ?>" title="Facebook" class="social-icon facebook"></a>
				</li>
				
			<?php } if ( $social_footer && array_key_exists('dribbble_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="http://www.dribbble.com/<?php do_action('bean_profile_dribbble'); ?>" title="Dribbble" class="social-icon dribbble"></a>
				</li>
			
			<?php } if ( $social_footer && array_key_exists('instagram_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="http://www.instagram.com/<?php do_action('bean_profile_instagram'); ?>" title="Instagram" class="social-icon instagram"></a>
				</li>
			
			<?php } if ( $social_footer && array_key_exists('googleplus_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="<?php do_action('bean_profile_google'); ?>" title="Google Plus" class="social-icon googleplus"></a>
				</li>
			
			<?php } if ( $social_footer && array_key_exists('linkedin_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="<?php do_action('bean_profile_linkedin'); ?>" title="Linkedin" class="social-icon linkedin"></a>
				</li>	
			
			<?php } if ( $social_footer && array_key_exists('zerply_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="http://zerply.com/<?php do_action('bean_profile_zerply'); ?>" title="Zerply" class="social-icon zerply"></a>
				</li>			
			
			<?php } if ( $social_footer && array_key_exists('behance_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="http://www.behance.net/<?php do_action('bean_profile_behance'); ?>" title="Behance" class="social-icon behance"></a>
				</li>	
			
			<?php } if ( $social_footer && array_key_exists('pinterest_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="http://pinterest.com/<?php do_action('bean_profile_pinterest'); ?>" title="Pinterst" class="social-icon pinterest"></a>
				</li>	
				
			<?php } if ( $social_footer && array_key_exists('github_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="<?php do_action('bean_profile_github'); ?>" title="GitHub" class="social-icon github"></a>
				</li>		
			
			<?php } if ( $social_footer && array_key_exists('youtube_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="<?php do_action('bean_profile_youtube'); ?>" title="YouTube" class="social-icon youtube"></a>
				</li>
			
			<?php } if ( $social_footer && array_key_exists('email_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="mailto:<?php do_action('bean_profile_email'); ?>" title="Email Us" class="social-icon email"></a>
				</li>			
			
			<?php } if ( $social_footer && array_key_exists('flickr_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="<?php do_action('bean_profile_flickr'); ?>" title="Flickr" class="social-icon flickr"></a>
				</li>			
			
			<?php } if ( $social_footer && array_key_exists('rss_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="<?php do_action('bean_profile_rss'); ?>" title="Subscribe via RSS" class="social-icon rss"></a>
				</li>
				
			<?php } if ( $social_footer && array_key_exists('forrst_select', $social_footer) ) { ?>
				 	
				<li>
				    <a href="http://www.forrst.com/<?php do_action('bean_profile_forrst'); ?>" title="Forrst" class="social-icon forrst"></a>
				</li>
							
			<?php } ?>
					
		</ul>				

	<?php } add_action('bean_social_footer','bean_social_footer'); 
}



/*===================================================================*/               							
/*  DISPLAY ABOUT THE AUTHOR				                							
/*===================================================================*/
if ( !function_exists('bean_about_author') ) {

	function bean_about_author() {  $options = get_option('bean_theme'); ?>
	
	<?php if ( isset($options['blog_about_author'] ) ) { ?> 
	
	</div><!-- END #main -->
	
	</div> <!-- END .row -->
	
	<div class="about-author">
		
		<div class="row">
		
			<?php echo get_avatar( get_the_author_meta('user_email'), '150', '' ); ?>
			
			<div class="author-right">
			
				<p>	
					<span class="author-name">
					
						<a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author_meta('display_name');?></a>
			
						<?php // DISPLAY AUTHOR URL ICON IF THERE IS ONE
						
							if (get_the_author_meta('user_url')) { ?>
						
							<a href="<?php the_author_meta('user_url'); ?>" class="author-url" title="<?php the_author_meta('user_url'); ?>"></a>
							
						<?php } ?>
					
					</span>
					
					<?php the_author_meta('description'); ?> 
				</p>
				
				<ul class="author-icon-list">
				
					<?php if (get_the_author_meta('twitter')) { ?>
					
						<li>
							<a href="http://www.twitter.com/<?php the_author_meta('twitter'); ?>" class="author-social-icon twitter" title="<?php the_author_meta('display_name'); ?> on Twitter">@<?php the_author_meta('twitter'); ?></a>
						</li>
						
					<?php } if (get_the_author_meta('dribbble')) { ?>	
						
						<li>
							<a href="<?php the_author_meta('dribbble'); ?>" class="author-social-icon dribbble" title="<?php the_author_meta('display_name'); ?> on Dribbble"></a>
						</li>
					
					<?php } if (get_the_author_meta('facebook')) { ?>
						
						<li>
							<a href="<?php the_author_meta('facebook'); ?>" class="author-social-icon facebook" title="<?php the_author_meta('display_name'); ?> on Facebook"></a>
						</li>
					
					<?php } if (get_the_author_meta('instagram')) { ?>
						
						<li>
							<a href="<?php the_author_meta('instagram'); ?>" class="author-social-icon instagram" title="<?php the_author_meta('display_name'); ?> on Instagram"></a>
						</li>
					
					<?php } if (get_the_author_meta('google_plus')) { ?>
						
						<li>
							<a href="<?php the_author_meta('google_plus'); ?>" class="author-social-icon google_plus" title="<?php the_author_meta('display_name'); ?> on Google Plus"></a>
						</li>
					
					<?php } if (get_the_author_meta('email')) { ?>
						
						<li>
							<a href="mailto:<?php the_author_meta('email'); ?>" class="author-social-icon email" title="Email <?php the_author_meta('display_name'); ?>"></a>
						</li>			
						
					<?php } ?>	
				
				</ul>

			</div><!-- END #author-right --> 
			
		</div><!-- END .row -->
			
	</div><!-- END #about-author -->
		
	<div class="row"><!-- OPENS BACK UP THE ROW FOR COMMENTS / COMMENTS FORM -->	
				
	<?php } } add_action('bean_about_author','bean_about_author'); 
}