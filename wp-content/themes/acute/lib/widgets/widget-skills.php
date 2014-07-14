<?php

/*--------------------------------------------------------------------

 	Widget Name: Bean Skills Widget
 	Widget URI: http://www.themebeans.com
 	Description:  A custom widget that displays up to 5 skill sets.
 	Author: ThemeBeans
 	Author URI: http://www.themebeans.com
 
/*===================================================================*/

// ADD FUNTION TO WIDGETS_INIT
add_action( 'widgets_init', 'reg_bean_skills' );

// REGISTER WIDGET
function reg_bean_skills() {
	register_widget( 'Bean_Skills_Widget' );
}

// WIDGET CLASS
class Bean_Skills_Widget extends WP_Widget {


/*===================================================================*/
/*	WIDGET SETUP
/*===================================================================*/
public function __construct() {
	parent::__construct(
 		'bean_skills', // BASE ID
		'Skills Widget (ThemeBeans)', // NAME
		array( 'description' => __( 'A custom widget that displays up to 5 skill sets', 'bean' ), )
	);
}
	
/*===================================================================*/
/*	DISPLAY WIDGET
/*===================================================================*/
function widget( $args, $instance ) {
	extract( $args );
	
	$title = apply_filters('widget_title', $instance['title'] );

	// WIDGET VARIABLES
	$skill1 = $instance['skill1'];
	$skill2 = $instance['skill2'];
	$skill3 = $instance['skill3'];
	$skill4 = $instance['skill4'];
	$skill5 = $instance['skill5'];
	
	$color1 = $instance['color1'];
	$color2 = $instance['color2'];
	$color3 = $instance['color3'];
	$color4 = $instance['color4'];
	$color5 = $instance['color5'];
	
	$percent1 = $instance['percent1'];	
	$percent2 = $instance['percent2'];
	$percent3 = $instance['percent3'];
	$percent4 = $instance['percent4'];
	$percent5 = $instance['percent5'];

	/* Before widget (defined by themes). */
	echo $before_widget;

	?> 
	
    <?php if ( $title ) echo $before_title . $title . $after_title; ?>
		
	<div class="bean-skills">
		
		<ul class="bean-skillset">
		
			<?php if($skill1 != '') : ?>
		
				<li class="skill-bar" style="width:<?php echo $percent1; ?>; background-color:<?php echo $color1; ?>">
				
					<span class="skill-title"><?php echo $skill1; ?></span>
				
					<span class="skill-percent"><?php echo $percent1; ?></span>
				
				</li>
				
			<?php endif; ?>
			
			<?php if($skill2 != '') : ?>
			
				<li class="skill-bar" style="width:<?php echo $percent2; ?>; background-color:<?php echo $color2; ?>">
				
					<span class="skill-title"><?php echo $skill2; ?></span>
				
					<span class="skill-percent"><?php echo $percent2; ?></span>
				
				</li>
				
			<?php endif; ?>
			
			<?php if($skill3 != '') : ?>
			
				<li class="skill-bar" style="width:<?php echo $percent3; ?>; background-color:<?php echo $color3; ?>">
				
					<span class="skill-title"><?php echo $skill3; ?></span>
				
					<span class="skill-percent"><?php echo $percent3; ?></span>
				
				</li>
				
			<?php endif; ?>
			
			<?php if($skill4 != '') : ?>
			
				<li class="skill-bar" style="width:<?php echo $percent4; ?>; background-color:<?php echo $color4; ?>">
				
					<span class="skill-title"><?php echo $skill4; ?></span>
				
					<span class="skill-percent"><?php echo $percent4; ?></span>
				
				</li>
				
			<?php endif; ?>
			
			<?php if($skill5 != '') : ?>
			
				<li class="skill-bar" style="width:<?php echo $percent5; ?>; background-color:<?php echo $color5; ?>">
				
					<span class="skill-title"><?php echo $skill5; ?></span>
				
					<span class="skill-percent"><?php echo $percent5; ?></span>
				
				</li>
				
			<?php endif; ?>
				
		</ul><!-- END .bean-skillset -->

    </div><!-- END .bean-skills -->
	
	<?php

	// AFTER WIDGET
	echo $after_widget;
}


/*===================================================================*/
/*	UPDATE WIDGET
/*===================================================================*/
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		// NO NEED TO STRIP TAGS
		$instance['skill1'] = $new_instance['skill1'];
		$instance['skill2'] = $new_instance['skill2'];
		$instance['skill3'] = $new_instance['skill3'];
		$instance['skill4'] = $new_instance['skill4'];
		$instance['skill5'] = $new_instance['skill5'];
		
		$instance['color1'] = $new_instance['color1'];
		$instance['color2'] = $new_instance['color2'];
		$instance['color3'] = $new_instance['color3'];
		$instance['color4'] = $new_instance['color4'];
		$instance['color5'] = $new_instance['color5'];
		
		$instance['percent1'] = $new_instance['percent1'];
		$instance['percent2'] = $new_instance['percent2'];
		$instance['percent3'] = $new_instance['percent3'];
		$instance['percent4'] = $new_instance['percent4'];
		$instance['percent5'] = $new_instance['percent5'];
		
		
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
		'title' => 'Skills.',
		
		'skill1' => 'HTMLCSS',
		'color1' => '#F1433F',
		'percent1' => '100%',
		
		'skill2' => 'Photoshop',
		'color2' => '#70B7BA',
		'percent2' => '80%',
		
		'skill3' => 'MySQL',
		'color3' => '#3D4C53',
		'percent3' => '60%',
		
		'skill4' => 'PHP',
		'color4' => '#A9CF54',
		'percent4' => '50%',
		
		'skill5' => 'Illustrator',
		'color5' => '#C44D58',
		'percent5' => '80%',
		
		);
		
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>
     
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'bean') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>
	
	<table>
		<tr>
	    	<td width="50%">		
				<label for="<?php echo $this->get_field_id( 'skill1' ); ?>"><?php _e('Skill Title:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'skill1' ); ?>" name="<?php echo $this->get_field_name( 'skill1' ); ?>" value="<?php echo $instance['skill1']; ?>" />
			</td>
			
			<td width="30%">	
				<label for="<?php echo $this->get_field_id( 'color1' ); ?>"><?php _e('Hex:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'color1' ); ?>" name="<?php echo $this->get_field_name( 'color1' ); ?>" value="<?php echo $instance['color1']; ?>" />
			</td>
				
			<td width="20%">	
				<label for="<?php echo $this->get_field_id( 'percent1' ); ?>"><?php _e('Per:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'percent1' ); ?>" name="<?php echo $this->get_field_name( 'percent1' ); ?>" value="<?php echo $instance['percent1']; ?>" />
	 	 	</td>
		</tr>
	</table>
	
	<table>
		<tr>
	    	<td width="50%">		
				<label for="<?php echo $this->get_field_id( 'skill2' ); ?>"><?php _e('Skill Title:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'skill2' ); ?>" name="<?php echo $this->get_field_name( 'skill2' ); ?>" value="<?php echo $instance['skill2']; ?>" />
			</td>
			
			<td width="30%">	
				<label for="<?php echo $this->get_field_id( 'color2' ); ?>"><?php _e('Hex:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'color2' ); ?>" name="<?php echo $this->get_field_name( 'color2' ); ?>" value="<?php echo $instance['color2']; ?>" />
			</td>
			
			<td width="20%">	
				<label for="<?php echo $this->get_field_id( 'percent2' ); ?>"><?php _e('Per:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'percent2' ); ?>" name="<?php echo $this->get_field_name( 'percent2' ); ?>" value="<?php echo $instance['percent2']; ?>" />
	 	 	</td>
		</tr>
	</table>	
		
	<table>
		<tr>
	    	<td width="50%">		
				<label for="<?php echo $this->get_field_id( 'skill3' ); ?>"><?php _e('Skill Title:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'skill3' ); ?>" name="<?php echo $this->get_field_name( 'skill3' ); ?>" value="<?php echo $instance['skill3']; ?>" />
			</td>
			
			<td width="30%">	
				<label for="<?php echo $this->get_field_id( 'color3' ); ?>"><?php _e('Hex:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'color3' ); ?>" name="<?php echo $this->get_field_name( 'color3' ); ?>" value="<?php echo $instance['color3']; ?>" />
			</td>
			
			<td width="20%">	
				<label for="<?php echo $this->get_field_id( 'percent3' ); ?>"><?php _e('Per:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'percent3' ); ?>" name="<?php echo $this->get_field_name( 'percent3' ); ?>" value="<?php echo $instance['percent3']; ?>" />
	 	 	</td>
		</tr>
	</table>
	
	<table>
		<tr>
	    	<td width="50%">		
				<label for="<?php echo $this->get_field_id( 'skill4' ); ?>"><?php _e('Skill Title:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'skill4' ); ?>" name="<?php echo $this->get_field_name( 'skill4' ); ?>" value="<?php echo $instance['skill4']; ?>" />
			</td>
			
			<td width="30%">	
				<label for="<?php echo $this->get_field_id( 'color4' ); ?>"><?php _e('Hex:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'color4' ); ?>" name="<?php echo $this->get_field_name( 'color4' ); ?>" value="<?php echo $instance['color4']; ?>" />
			</td>
			
			<td width="20%">	
				<label for="<?php echo $this->get_field_id( 'percent4' ); ?>"><?php _e('Per:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'percent4' ); ?>" name="<?php echo $this->get_field_name( 'percent4' ); ?>" value="<?php echo $instance['percent4']; ?>" />
	 	 	</td>
		</tr>
	</table>		
	
	<table>
		<tr>
	    	<td width="50%">		
				<label for="<?php echo $this->get_field_id( 'skill5' ); ?>"><?php _e('Skill Title:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'skill5' ); ?>" name="<?php echo $this->get_field_name( 'skill5' ); ?>" value="<?php echo $instance['skill5']; ?>" />
			</td>
			
			<td width="30%">	
				<label for="<?php echo $this->get_field_id( 'color5' ); ?>"><?php _e('Hex:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'color5' ); ?>" name="<?php echo $this->get_field_name( 'color5' ); ?>" value="<?php echo $instance['color5']; ?>" />
			</td>
			
			<td width="20%">	
				<label for="<?php echo $this->get_field_id( 'percent5' ); ?>"><?php _e('Per:', 'bean') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'percent5' ); ?>" name="<?php echo $this->get_field_name( 'percent5' ); ?>" value="<?php echo $instance['percent5']; ?>" />
	 	 	</td>
		</tr>
	</table>
	
	<?php
	} // END FORM

} // END CLASS
?>