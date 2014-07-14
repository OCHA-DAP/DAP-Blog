<?php

/*--------------------------------------------------------------------

 	Widget Name: Bean Flickr Widget
 	Widget URI: http://www.themebeans.com
 	Description:  A custom widget that displays your Flickr photos.
 	Author: ThemeBeans
 	Author URI: http://www.themebeans.com
 
/*===================================================================*/

// ADD FUNTION TO WIDGETS_INIT 
add_action( 'widgets_init', 'reg_bean_flickr' );

// REGISTER WIDGET
function reg_bean_flickr() {
	register_widget( 'Radium_Flickr_Widget' );
}

// WIDGET CLASS
class Radium_Flickr_Widget extends WP_Widget {


/*===================================================================*/
/*	WIDGET SETUP
/*===================================================================*/
public function __construct() {
	parent::__construct(
 		'radium_flickr_widget', // BASE ID
		'Flickr Photos (ThemeBeans)', // NAME
		array( 'description' => __( 'A widget that displays your Flickr photos', 'bean' ), )
	);
}


/*===================================================================*/
/*	DISPLAY WIDGET
/*===================================================================*/	
function widget( $args, $instance ) {
	extract( $args );

	// WIDGET VARIABLES
	$title = apply_filters('widget_title', $instance['title'] );
	$flickrID = $instance['flickrID'];
	$type = $instance['type'];
	$display = $instance['display'];

	// BEFORE WIDGET
	echo $before_widget;

	if ( $title ) echo $before_title . $title . $after_title;

	?>
	
	<div class="flickr-image-wrapper">
	
		<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=8&amp;display=<?php echo $display ?>&amp;size=t&amp;layout=x&amp;source=<?php echo $type ?>&amp;<?php echo $type ?>=<?php echo $flickrID ?>"></script>
	
		<script type="text/javascript">
		    jQuery(document).ready(function($){
		        $('.bean_flickr_widget').imagesLoaded(function(){	
		    		$('.flickr_badge_image a').prepend('<span class="flickr-overlay"></span>');
		    	});
		    });
		</script>
	
	</div><!-- END .flickr-image-wrapper --> 
	
	<?php

	// AFTER WIDGET
	echo $after_widget;
}


/*===================================================================*/
/*	UPDATE WIDGET
/*===================================================================*/
function update( $new_instance, $old_instance ) {
	$instance = $old_instance;

	// STRIP TAGS TO REMOVE HTML - IMPORTANT FOR TEXT IMPUTS
	$instance['title'] = strip_tags( $new_instance['title'] );
	$instance['flickrID'] = strip_tags( $new_instance['flickrID'] );
	
	// NO NEED TO STRIP TAGS
	$instance['type'] = $new_instance['type'];
	$instance['display'] = $new_instance['display'];

	return $instance;
}


/*===================================================================*/
/*	WIDGET SETTINGS (FRONT END PANEL)
/*===================================================================*/	 
function form( $instance ) {

	// WIDGET DEFAULTS
	$defaults = array(
		'title' => 'Flickr.',
		'flickrID' => '36587311@N08',
		'postcount' => '8',
		'type' => 'user',
		'display' => 'latest',
		
	);
	
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'bean') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'flickrID' ); ?>"><?php _e('Flickr ID:', 'bean') ?> (<a href="http://idgettr.com/">idGettr</a>)</label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'flickrID' ); ?>" name="<?php echo $this->get_field_name( 'flickrID' ); ?>" value="<?php echo $instance['flickrID']; ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e('Type (user or group):', 'bean') ?></label>
		<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" class="widefat">
			<option <?php if ( 'user' == $instance['type'] ) echo 'selected="selected"'; ?>>user</option>
			<option <?php if ( 'group' == $instance['type'] ) echo 'selected="selected"'; ?>>group</option>
		</select>
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display (random or latest):', 'bean') ?></label>
		<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat">
			<option <?php if ( 'random' == $instance['display'] ) echo 'selected="selected"'; ?>>random</option>
			<option <?php if ( 'latest' == $instance['display'] ) echo 'selected="selected"'; ?>>latest</option>
		</select>
	</p>
	
	
	<?php
	} // END FORM
	
} // END CLASS
?>