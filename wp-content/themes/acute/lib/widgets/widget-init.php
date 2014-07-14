<?php 

$options 	= get_option('bean_theme');

/*-----------------------------------------------------------------------------------*/
/*	REGISTER BASE WIDGET AREAS
/*-----------------------------------------------------------------------------------*/
if ( function_exists('register_sidebar') ) {
    $allWidgetizedAreas = 
        array(
                __( 'Internal Sidebar', 'bean' ),  
                __( 'Header', 'bean' ),            
            );
            
    foreach ($allWidgetizedAreas as $WidgetAreaName) {
        register_sidebar(array(
           'name'			=> $WidgetAreaName,
           'before_widget' 	=> '<div class="widget %2$s clearfix">',
           'after_widget' 	=> '</div>',
           'before_title' 	=> '<h3 class="widget-title"><span>',
           'after_title' 	=> '</span></h3>',
        ));
    }


	
}


/*-----------------------------------------------------------------------------------*/
/*	REGISTER SLIDING PANEL WIDGET AREA
/*-----------------------------------------------------------------------------------*/
if ( isset($options['sliding_panel'] ) ) { 
	 
	 register_sidebar(
	 	array(
	 	'name' => __('Sliding Panel', 'bean'),
	 	'before_widget' 	=> '<div class="widget %2$s clearfix">',
	 	'after_widget' 	=> '</div>',
	 	'before_title' 	=> '<h3 class="widget-title"><span>',
	 	'after_title' 	=> '</span></h3>',
	 ));
	 
	} 
	
	
/*-----------------------------------------------------------------------------------*/
/*	REGISTER FULL WIDTH FOOTER WIDGET AREA
/*-----------------------------------------------------------------------------------*/
if ( isset( $options['footer_layout'] ) ) { $layout = $options['footer_layout'];
	
	 if ( $layout == 'split' || 'split_extra') { 
	 
	 register_sidebar(
	 	array(
	 	'name' => __('Footer', 'bean'),
	 	'before_widget' 	=> '<div class="widget %2$s clearfix">',
	 	'after_widget' 	=> '</div>',
	 	'before_title' 	=> '<h3 class="widget-title"><span>',
	 	'after_title' 	=> '</span></h3>',
	 ));
	 
	} 
	 
} 
?>