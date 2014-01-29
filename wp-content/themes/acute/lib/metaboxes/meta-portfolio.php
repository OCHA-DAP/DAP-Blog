<?php
/*===================================================================*/	
/*  PORTFOLIO META							   			          							
/*===================================================================*/
$meta_boxes[] = array(
	'id'       => 'portfolio-meta',
	'title'    => __('Optional Portfolio Meta', 'bean'),
	'pages'    => array('portfolio'),
	'context'  => 'normal',
	'priority' => 'high',
	'fields'   => array(
		array(  
			"name" => __('Client:','bean'),
			"desc" => __('','bean'),
			"id" => $prefix."portfolio_client",
			"type" => "text",
			"std" => ''
			),		
		array(  
			"name" => __('URL:','bean'),
			"desc" => __('Insert a URL without the "http://" For example: www.themebeans.com','bean'),
			"id" => $prefix."portfolio_url",
			"type" => "text",
			"std" => ''
			),
		array(  
			"name" => __('URL Link Text:','bean'),
			"desc" => __('Enter the text to be displayed as the link.','bean'),
			"id" => $prefix."portfolio_url_text",
			"type" => "text",
			"std" => 'View Project &rarr;'
			),	
		array(
			'name'     => __('Display Categories:', 'radium'),
			"id" => $prefix."portfolio_cats",
			'type' => 'checkbox',
			'desc' => __('Yes, please do.', 'bean'),
			'std' => 1 
			),		
		array(
			'name'     => __('Display Date:', 'radium'),
			"id" => $prefix."portfolio_date",
			'type' => 'checkbox',
			'desc' => __('Yes, please do.', 'bean'),
			'std' => 1 
			),				
	)
);