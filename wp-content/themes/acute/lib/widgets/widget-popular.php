<?php

/*--------------------------------------------------------------------

 	Widget Name: Bean Popular Widget
 	Widget URI: http://www.themebeans.com
 	Description:  A widget that displays the most popular post
 	Author: ThemeBeans
 	Author URI: http://www.themebeans.com
 	
/*===================================================================*/

// ADD FUNTION TO WIDGETS_INIT
add_action( 'widgets_init', 'reg_bean_popular_posts' );

// REGISTER WIDGET
function reg_bean_popular_posts() {
	register_widget( 'Bean_Popular_Posts_Widget' );
}

// WIDGET CLASS
class Bean_Popular_Posts_Widget extends WP_Widget {


/*===================================================================*/
/*	WIDGET SETUP
/*===================================================================*/
public function __construct() {
	parent::__construct(
 		'bean_popular', // BASE ID
		'Popular Post (ThemeBeans)', // NAME
		array( 'description' => __( 'A widget that displays the most popular post', 'bean' ), )
	);
}
		
		
/*===================================================================*/
/*	DISPLAY WIDGET
/*===================================================================*/
function widget( $args, $instance ) {
	extract( $args );
	
	// WIDGET VARIABLES
	$title = apply_filters('widget_title', $instance['title'] );
	$number = ( isset($instance['number']) ) ? $instance['number'] : 0;

	// BEFORE WIDGET
	echo $before_widget;

	if ( $title ) { echo $before_title . $title . $after_title; }
	
	$query = new WP_Query();
	$query->query( array(
		'post_type' => 'post',
	    'posts_per_page' => '1',
	    'ignore_sticky_posts' => 1,
	    'orderby' => 'comment_count'
	));

	if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
			
		if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { ?>
			
			<div class="post-thumb">
			
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('sidebar-thumb'); ?></a>
			
			</div><!-- END .post-thumb -->
		
		<?php } ?>
			
		<div class="popular-title">
		
			<a href="<?php the_permalink(); ?>"><?php echo wp_trim_words( get_the_title(), '20' ); ?></a>
		
		</div><!-- END .popular-title -->
		
		<div class="popular-meta"><?php the_time( get_option('date_format') ); ?></div>

	<?php endwhile; endif; wp_reset_query(); ?>
                           
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

	return $instance;
}
	

/*===================================================================*/
/*	WIDGET SETTINGS (FRONT END PANEL)
/*===================================================================*/
function form( $instance ) {

	// WIDGET DEFAULTS
	$defaults = array(
	    'title' => 'Popular.',
		);
	
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'bean') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>
	
<?php
	} // END FORM

} // END CLASS
?>