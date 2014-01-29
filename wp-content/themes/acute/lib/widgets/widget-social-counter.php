<?php

/*--------------------------------------------------------------------

 	Widget Name: Bean Header Social Widget
 	Widget URI: http://www.themebeans.com
 	Description:  A custom widget that displays the header social counter.
 	Author: ThemeBeans
 	Author URI: http://www.themebeans.com
 
/*===================================================================*/

// ADD FUNTION TO WIDGETS_INIT
add_action( 'widgets_init', 'reg_bean_social_counter' );

// REGISTER WIDGET
function reg_bean_social_counter() {
	register_widget( 'bean_social_counter' );
}

// WIDGET CLASS
class bean_social_counter extends WP_Widget {


/*===================================================================*/
/*	WIDGET SETUP
/*===================================================================*/
public function __construct() {
	parent::__construct(
 		'bean_social_counter', // BASE ID
		'Social Counter (ThemeBeans)', // NAME
		array( 'description' => __( 'A widget that displays the header social counter', 'bean' ), )
	);
}
	

/*===================================================================*/
/*	DISPLAY WIDGET
/*===================================================================*/
function widget( $args, $instance ) {
	extract( $args );

	$title = apply_filters('widget_title', $instance['title'] );
	
	// WIDGET VARIABLES
	$twitterprofile = $instance['twitterprofile'];
	$facebookpage = $instance['facebookpage'];
	$dribbbleprofile = $instance['dribbbleprofile'];

	// BEFORE WIDGET
	echo $before_widget;
	
	?>
	
	<?php if ( $title ) echo $before_title . $title . $after_title; ?>
	
		<div class="bean-social-counter">                      
		           	
            <ul class="social-links">				
				
				<?php if( $twitterprofile != '' ) : ?>
	            
	                <li class="twitter">
	                   
	                    <a href="http://twitter.com/<?php echo $twitterprofile; ?>" target="_blank" class="counter-icon twitter"></a>	
	                   
	                    <span class="numbers"><?php echo $this->do_twitter_followers_count($twitterprofile);?></span> 
	                   
	                    <span class="count-follow"><?php _e(' Followers','bean'); ?></span>
	                
	                </li><!-- END li.twitter -->
				
				<?php endif; ?>
                
                <?php if( $facebookpage != '' ) : ?>
	            
	                <li class="facebook">
	              	
	              		<a href="http://www.facebook.com/<?php echo $facebookpage; ?>" target="_blank" class="counter-icon facebook"></a>
	                
	                   	<span class="numbers"><?php echo $this->do_count_facebook_likes( $facebookpage );?></span>
	                
	                   	<span class="count-follow"><?php _e(' Fans','bean'); ?></span>
	                
	                </li><!-- END li.facebook -->
               	
               	<?php endif; ?>

                <?php if( $dribbbleprofile != '' ) : ?>
	            
	                 <li class="dribbble">
	                 
	                 	<a href="http://dribbble.com/<?php echo $dribbbleprofile; ?>" target="_blank" class="counter-icon dribbble"></a>	
	             	
	             		<span class="numbers"><?php echo $this->do_count_dribbbler( $dribbbleprofile );?></span>
	             	
	             		<span class="count-follow"><?php _e(' Followers','bean'); ?></span>
	               
	                </li><!-- END li.dribbble --> 
                
                <?php endif; ?>
            
            </ul><!-- END .social-links -->
            
      	</div><!-- END .bean-social-counter -->
      
	<?php
	
	// AFTER WIDGET
	echo $after_widget;
}


/*===================================================================*/
/*	TWITTER API FUNCTION
/*===================================================================*/
function do_twitter_followers_count( $screen_name = 'themebeans' ) {
	$key = 'rm__twit_followers_count_' . $screen_name;

	$followers_count = get_transient($key);
	if ($followers_count !== false)
		return $followers_count;
	else
	{
		$response = wp_remote_get("http://api.twitter.com/1/users/show.json?screen_name={$screen_name}");
		if (is_wp_error($response))
		{
			return get_option($key);
		}
		else
		{
			$json = json_decode(wp_remote_retrieve_body($response));
			$count = $json->followers_count;
			set_transient($key, $count, 60*60*24);
			update_option($key, $count);
			return $count;
		}
	}
}


/*===================================================================*/
/*	FACEBOOK API FUNCTION
/*===================================================================*/
function do_count_facebook_likes( $account ) {
		
	$key = 'rm_counter_facebook_' . $account;
	$cache = get_transient($key);
	
	if( $cache === false ) {
		
		$url = "https://graph.facebook.com/$account"; 
		$response 	= wp_remote_get( $url );
				
		if( is_wp_error( $response ) ) 
			return;
		
		$xml = wp_remote_retrieve_body( $response );
		
		if( is_wp_error( $xml ) )
			return;
				
		if( $response['response']['code'] == 200 ) {
			
			$json = json_decode( $xml );
			
			$followers = $json->likes;
					
			set_transient($key, $followers, 60*5);
			
		}
		
	}  else {
	
		$followers = $cache;
			
	}
	
	return $followers;
}
	 	
	 	
/*===================================================================*/
/*	DRIBBBLE API FUNCTION
/*===================================================================*/
function do_count_dribbbler( $account ) {
		
	$key = 'rm_counter_dribbble_' . $account;
	$cache = get_transient($key);
	
	if( $cache === false ) {
		
		$url = "http://api.dribbble.com/$account"; 
		$response 	= wp_remote_get( $url );
				
		if( is_wp_error( $response ) ) 
			return;
		
		$xml = wp_remote_retrieve_body( $response );
		
		if( is_wp_error( $xml ) )
			return;
		
		if( $response['headers']['status'] == 200 ) {
			
			$json = json_decode( $xml );
			
			$followers = $json->followers_count;
			
			set_transient($key, $followers, 60*5);
			
		}
		
	}  else {
	
		$followers = $cache;
			
	}
	
	return $followers;
}
	

/*===================================================================*/
/*	UPDATE WIDGET
/*===================================================================*/
function update( $new_instance, $old_instance ) {
	$instance = $old_instance;

	// STRIP TAGS TO REMOVE HTML - IMPORTANT FOR TEXT IMPUTS 
	$instance['title'] = strip_tags( $new_instance['title'] );

	// NO NEED TO STRIP TAGS
	$instance['twitterprofile'] = $new_instance['twitterprofile'];
	$instance['facebookpage'] = $new_instance['facebookpage'];
	$instance['dribbbleprofile'] = $new_instance['dribbbleprofile'];
 	
	return $instance;
}


/*===================================================================*/
/*	WIDGET SETTINGS (FRONT END PANEL)
/*===================================================================*/	
function form( $instance ) {

	// WIDGET DEFAULTS
	$defaults = array(
		'title' => 'Social Stats.',
		'twitterprofile' => 'ThemeBeans',
		'facebookpage' => 'ThemeBeans',
		'dribbbleprofile' => '',
	);
		
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'bean') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'twitterprofile' ); ?>"><?php _e('Twitter Username (ex: <a href="http://www.twitter.com/themebeans">ThemeBeans</a>):', 'bean') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitterprofile' ); ?>" name="<?php echo $this->get_field_name( 'twitterprofile' ); ?>" value="<?php echo $instance['twitterprofile']; ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'facebookpage' ); ?>"><?php _e('Facebook Page Name:', 'bean') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'facebookpage' ); ?>" name="<?php echo $this->get_field_name( 'facebookpage' ); ?>" value="<?php echo $instance['facebookpage']; ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id( 'dribbbleprofile' ); ?>"><?php _e('Dribbble Username:', 'bean') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'dribbbleprofile' ); ?>" name="<?php echo $this->get_field_name( 'dribbbleprofile' ); ?>" value="<?php echo $instance['dribbbleprofile']; ?>" />
	</p>


 			
	<?php
	}
}
  
?>