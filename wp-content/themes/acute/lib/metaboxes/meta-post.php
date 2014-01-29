<?php 
/*===================================================================*/              							
/*  POST FORMAT: LINK							   			          							
/*===================================================================*/
$meta_boxes[] = array(
	'id' => 'radium-meta-box-link',
	'title' =>  __('Link Settings', 'bean'),
	'page' => 'post',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => __('Link URL:','bean'),
				"desc" => __('ex: data.rwlabs.org','bean'),
				"id" => $prefix."link_url",
				"type" => "text",
				"std" => 'www.'
			),
	),
	
);



/*===================================================================*/              							
/*  POST FORMAT: AUDIO							   			          							
/*===================================================================*/
$meta_boxes[] = array(
	'id' => 'radium-meta-box-audio',
	'title' =>  __('Audio Settings', 'bean'),
	'page' => 'post',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => __('MP3 File URL','bean'),
				"desc" => __('','bean'),
				"id" => $prefix."audio_mp3",
				"type" => "text",
				"std" => ''
			),
		array( "name" => __('OGA File URL','bean'),
				"desc" => __('','bean'),
				"id" => $prefix."audio_ogg",
				"type" => "text",
				"std" => ''
			),
	),
);



/*===================================================================*/              							
/*  POST FORMAT: GALLERY							   			          							
/*===================================================================*/
$meta_boxes[] = array(
	'id' => 'radium-meta-box-video',
	'title' =>  __('Video Settings', 'bean'),
	'page' => 'post',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => __('M4V File URL','bean'),
				"desc" => __('','bean'),
				"id" => $prefix."video_m4v",
				"type" => "text",
				"std" => ''
			),
		array( "name" => __('OGV File URL','bean'),
				"desc" => __('','bean'),
				"id" => $prefix."video_ogv",
				"type" => "text",
				"std" => ''
			),
		array( "name" => __('Poster Image','bean'),
				"desc" => __('','bean'),
				"id" => $prefix."video_poster",
				"type" => "text",
				"std" => ''
			),
		array( "name" => __('Embeded Code','bean'),
				"desc" => __('If you\'re not using self hosted video then you can include embeded code here.','bean'),
				"id" => $prefix."video_embed",
				"type" => "textarea",
				"std" => ''
			),
	)
	
);