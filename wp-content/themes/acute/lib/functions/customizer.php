<?php 
/*===================================================================*/               							
/*  LIVE PREVIEW EDITING (JS) - GRABS THE JS		                							
/*===================================================================*/
function mytheme_customizer_live_preview() {
	wp_enqueue_script('theme-customizer', BEAN_JS_URL . '/theme-customizer.js', 'jquery', '1.0', true);
}
add_action( 'customize_preview_init', 'mytheme_customizer_live_preview' );




/*===================================================================*/               							
/*  THEME CUSTOMIZER FUNCTIONS		                							
/*===================================================================*/
// 1. ADD BACKGROUND IMAGE UPLOADER SUPPORT
add_theme_support( 'custom-background' ); 

function Bean_Customize_Register( $wp_customize ) {
/*===================================================================*/               							
/*  COLORS SECTION			                							
/*===================================================================*/
	$colors = array();
	
	
	$colors[] = array(
		'slug'=>'secondary_bg_color', 
		'default' => '#F3F3F3',
		'label' => __('Secondary Background Color', 'bean')
	);

	$colors[] = array(
		'slug'=>'body_text_color', 
		'default' => '#22272A',
		'label' => __('Body Text Color', 'bean'),
		'priority' => 2,
	);
	
	$colors[] = array(
		'slug'=>'theme_accent_color', 
		'default' => '#3AC792',
		'label' => __('Accent Color', 'bean')
	);
	
	$colors[] = array(
		'slug'=>'footer_bg_color', 
		'default' => '#22272A',
		'label' => __('Footer Background Color', 'bean')
	);
	
	$colors[] = array(
		'slug'=>'footer_extra_color', 
		'default' => '#1C1C22',
		'label' => __('Footer Extra Color', 'bean')
	);
	
	$colors[] = array(
		'slug'=>'selection_bg_color', 
		'default' => '#F3F3F3',
		'label' => __('Selection Background Color', 'bean')
	);
	
	$colors[] = array(
		'slug'=>'selection_text_color', 
		'default' => '#22272A',
		'label' => __('Selection Text Color', 'bean')
	);
	
	foreach( $colors as $color ) {
		$wp_customize->add_setting(
			$color['slug'], array(
				'default' => $color['default'],
				'type' => 'option', 
				'capability' => 
				'edit_theme_options'
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['slug'], 
				array('label' => $color['label'], 
				'section' => 'colors',
				'settings' => $color['slug'])
			)
		);
		
		
/*===================================================================*/        	
/*  THEME OPTIONS SECTION			                							
/*===================================================================*/		
$wp_customize->add_section(
    'theme_settings',
    array(
        'title' => 'Global Options',
        'priority' => 34,
    )
);

		$wp_customize->add_setting( 'retina_option', array( 'default' => false ) );
		$wp_customize->add_control( 'retina_option',
		    array(
		        'type' => 'checkbox',
		        'label' => 'Enable Retina.js for Images',
		        'section' => 'theme_settings',
		        'priority' => 1,
		    )
		);
			
}


/*===================================================================*/               						
/*  CUSTOM CSS SECTION			                							
/*===================================================================*/		
	$wp_customize->add_section(
	    'custom_css',
	    array(
	        'title' => 'Custom CSS',
	        'priority' => 300,
	    )
	);
	
	
// CUSTOM CSS
class Custom_CSS_Control extends WP_Customize_Control {
    public $type = 'custom_css';
 
    public function render_content() {
        ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <textarea rows="7" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
            </label>
        <?php
    }
}
$wp_customize->add_setting( 'custom_css' );
$wp_customize->add_control(
    new Custom_CSS_Control( $wp_customize, 'custom_css',
        array(
            'label' => 'Enter Custom CSS',
            'section' => 'custom_css',
            'settings' => 'custom_css'
        )
    )
);

	
/*===================================================================*/               							
/*  TRANSPORTS FOR LIVE PREVIEW EDITING		                							
/*===================================================================*/
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'footer_copyright' )->transport = 'postMessage';

}
add_action( 'customize_register', 'Bean_Customize_Register' );