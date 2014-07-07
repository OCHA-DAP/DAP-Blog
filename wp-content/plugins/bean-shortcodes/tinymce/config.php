<?php

/*----------------------------------------------------------------------
 
	Content: Configuration file for our shortcode manager 
	Author:  ThemeBeans of http://www.themebeans.com
 
 ----------------------------------------------------------------------*/

/*--------------------------------------------------------------------*/
/*  BUTTON
/*--------------------------------------------------------------------*/
$bean_shortcodes['button'] = array(
	'no_preview' => true,
	'params' => array(
		'url' => array(
			'std' => 'http://',
			'type' => 'text',
			'label' => __('Button URL:', 'bean'),
			'desc' => __('', 'bean')
		),
		
		'target' => array(
			'type' => 'select',
			'label' => __('Link Target:', 'bean'),
			'desc' => __('', 'bean'),
			'options' => array(
				'_self' => '_self (Same Window)',
				'_blank' => '_blank (New Window)'
			)
		),
		
		'color' => array(
			'type' => 'select',
			'label' => __('Color:', 'bean'),
			'desc' => __('', 'bean'),
			'options' => array(
				'' => 'Default',
				'black' => 'Black',
				'blue' => 'Blue',
				'green' => 'Green',
				'grey' => 'Grey',
				'orange' => 'Orange',
				'pink' => 'Pink',
				'purple' => 'Purple',
				'red' => 'Red',
				'yellow' => 'Yellow',
			)
		),
		
		'size' => array(
			'type' => 'select',
			'label' => __('Size:', 'bean'),
			'desc' => __('', 'bean'),
			'options' => array(
				'small' => 'Small',
				'medium' => 'Medium',
				'large' => 'Large'
			)
		),
		
		'type' => array(
			'type' => 'select',
			'label' => __('Type:', 'bean'),
			'desc' => __('', 'bean'),
			'options' => array(
				'square' => 'Square',
				'round' => 'Round'
			)
		),
		
		'icon' => array(
			'type' => 'select',
			'label' => __('Icon:', 'bean'),
			'desc' => __('', 'bean'),
			'options' => array(
				'' => '',
				'arrow-down' => 'Arrow Down',
				'arrow-left' => 'Arrow Left',
				'arrow-right' => 'Arrow Right',
				'arrow-up' => 'Arrow Up',
				'bookmark' => 'Bookmark',
				'camera' => 'Camera',
				'camera-retro' => 'Camera Retro',
				'cloud' => 'Cloud',
				'cloud-download' => 'Cloud Download',
				'cloud-upload' => 'Cloud Upload',
				'code' => 'Code',
				'coffee' => 'Coffee',
				'cog' => 'Cog',
				'cogs' => 'Cogs',
				'comments' => 'Comments',
				'envelope' => 'Envelope',
				'exclamation-sign' => 'Exclamation Sign',
				'eye-open' => 'Eye Open',
				'facebook' => 'Facebook',
				'file' => 'File',
				'film' => 'Film',
				'globe' => 'Globe',
				'inbox' => 'Inbox',
				'link' => 'Link',
				'magic' => 'Magic',
				'music' => 'Music',
				'ok' => 'OK Icon',
				'pinterest' => 'Pinterest',
				'plus' => 'Plus Icon',
				'refresh' => 'Refresh',
				'rocket' => 'Rocket',
				'remove' => 'Remove',
				'rss' => 'RSS',
				'search' => 'Search',
				'shopping-cart' => 'Shopping Cart',
				'signal' => 'Signal',
				'star' => 'Star',
				'tasks' => 'Tasks',
				'trophy' => 'Trophy',
				'user' => 'User',
				'wrench' => 'Wrench',
			)
		),
			
		'content' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Button Text:', 'bean'),
			'desc' => __('', 'bean'),
			)
		),
		
	'shortcode' => '[button url="{{url}}" color="{{color}}" size="{{size}}" type="{{type}}" icon="{{icon}}" target="{{target}}"] {{content}} [/button]',
	'popup_title' => __('Button Shortcode', 'bean')
);



/*--------------------------------------------------------------------*/
/*	ALERT
/*--------------------------------------------------------------------*/
$bean_shortcodes['alert'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type' => 'select',
			'label' => __('Alert Style:', 'bean'),
			'desc' => __('', 'bean'),
			'options' => array(
				'note' => 'Note (Yellow)',
				'info' => 'Info (Blue)',
				'error' => 'Error (Red)',
				'success' => 'Success (Green)',
			)
		),
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('Alert Text:', 'bean'),
			'desc' => __('', 'bean'),
		)
		
	),
	
	'shortcode' => '[alert style="{{style}}"] {{content}} [/alert]',
	'popup_title' => __('Alert Shortcode', 'bean')
);



/*--------------------------------------------------------------------*/
/*	TOGGLE
/*--------------------------------------------------------------------*/
$bean_shortcodes['toggle'] = array(
	'no_preview' => true,
	'params' => array(
		'title' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Title:', 'bean'),
			'desc' => __('', 'bean'),
			
		),
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('Content:', 'bean'),
			'desc' => __('Add the toggle content. (HTML Accepted)', 'bean'),
		),
		'state' => array(
			'type' => 'select',
			'label' => __('Loading State:', 'bean'),
			'desc' => __('Select the state of the toggle on page load.', 'bean'),
			'options' => array(
				'open' => 'Open',
				'closed' => 'Closed'
			)
		),
		
	),
	'shortcode' => '[toggle title="{{title}}" state="{{state}}"] {{content}} [/toggle]',
	'popup_title' => __('Toggle Content Shortcode', 'bean')
);



/*--------------------------------------------------------------------*/
/*	FEATURE AREAS
/*--------------------------------------------------------------------*/
$bean_shortcodes['featurearea'] = array(
	'params' => array(),
	'shortcode' => ' {{child_shortcode}} ',
	'popup_title' => __('Feature Area Shortcode', 'bean'),
	'no_preview' => true,
	
	'child_shortcode' => array(
		'params' => array(
			
			'title' => array(
				'type' => 'text',
				'label' => __('Title:', 'bean'),
				'desc' => __('', 'bean'),
				'std' => ''
			),

			'icon' => array(
				'type' => 'select',
				'label' => __('Select Icon:', 'bean'),
				'desc' => __('', 'bean'),
				'options' => array(
					'book' => 'Book Icon',
					'calendar' => 'Calendar',
					'camera' => 'Camera',
					'chart' => 'Chart',
					'check' => 'Checkmark Icon',
					'crop' => 'Crop Icon',
					'danger' => 'Danger Icon',
					'download' => 'Download Icon',
					'fullscreen' => 'Fullscreen Icon',
					'ipad' => 'iPad Icon',
					'mail' => 'Mail Icon',
					'no' => 'X Icon',
					'notification' => 'Notification Icon',
					'package' => 'Package Icon',
					'pages' => 'Pages Icon',
					'printer' => 'Printer Icon',
					'reload' => 'Reload Icon',
					'settings' => 'Settings Icon',
					'support' => 'Support Icon',
					'upload' => 'Upload Icon',
					'wifi' => 'Wifi Icon',
				)
			),
			
			'content' => array(
				'std' => '',
				'type' => 'textarea',
				'label' => __('Content:', 'bean'),
				'desc' => __('', 'bean'),
			)
		),
		
		'shortcode' => '[featurearea title="{{title}}" icon="{{icon}}"] {{content}} [/featurearea] ',
		'clone_button' => __('Click to Add Another Feature Area', 'bean')
	)
);



/*--------------------------------------------------------------------*/
/*	TABS
/*--------------------------------------------------------------------*/
$bean_shortcodes['tabs'] = array(
    'params' => array(),
    'no_preview' => true,
    'shortcode' => '[tabs] {{child_shortcode}}  [/tabs]',
    'popup_title' => __('Tab Shortcode', 'bean'),
    
    'child_shortcode' => array(
        'params' => array(
            'title' => array(
                'std' => '',
                'type' => 'text',
                'label' => __('Title:', 'bean'),
                'desc' => __('', 'bean'),
            ),
            'content' => array(
                'std' => '',
                'type' => 'textarea',
                'label' => __('Tab Content:', 'bean'),
                'desc' => __('', 'bean')
            )
        ),
        
        'shortcode' => '[tab title="{{title}}"] {{content}} [/tab]',
        'clone_button' => __('Click to Add Another Tab', 'bean')
    )
);



/*--------------------------------------------------------------------*/
/*	COLUMNS
/*--------------------------------------------------------------------*/
$bean_shortcodes['columns'] = array(
	'params' => array(),
	'shortcode' => ' {{child_shortcode}} ',
	'popup_title' => __('Column Shortcode', 'bean'),
	'no_preview' => true,
	
	'child_shortcode' => array(
		'params' => array(
			'column' => array(
				'type' => 'select',
				'label' => __('Column Size:', 'bean'),
				'desc' => __('', 'bean'),
				'options' => array(
					'one_third' => '1/3 &nbsp;&nbsp;&nbsp;&nbsp;One Third',
					'one_third_last' => '1/3L &nbsp;&nbsp;One Third Last',
					'two_third' => '2/3 &nbsp;&nbsp;&nbsp;&nbsp;Two Thirds',
					'two_third_last' => '2/3L &nbsp;&nbsp;Two Thirds Last',
					'one_half' => '1/2 &nbsp;&nbsp;&nbsp;&nbsp;One Half',
					'one_half_last' => '1/2L &nbsp;&nbsp;One Half Last',
					'one_fourth' => '1/4 &nbsp;&nbsp;&nbsp;&nbsp;One Fourth',
					'one_fourth_last' => '1/4L &nbsp;&nbsp;One Fourth Last',
					'three_fourth' => '3/4 &nbsp;&nbsp;&nbsp;&nbsp;Three Fourth',
					'three_fourth_last' => '3/4L &nbsp;&nbsp;Three Fourths Last',
					'one_fifth' => '1/5 &nbsp;&nbsp;&nbsp;&nbsp;One Fifth',
					'one_fifth_last' => '1/5L &nbsp;&nbsp;One Fifth Last',
					'two_fifth' => '2/5 &nbsp;&nbsp;&nbsp;&nbsp;Two Fifth',
					'two_fifth_last' => '2/5L &nbsp;&nbsp;Two Fifths Last',
					'three_fifth' => '3/5 &nbsp;&nbsp;&nbsp;&nbsp;Three Fifth',
					'three_fifth_last' => '3/5L &nbsp;&nbsp;Three Fifths Last',
					'four_fifth' => '4/5 &nbsp;&nbsp;&nbsp;&nbsp;Four Fifth',
					'four_fifth_last' => '4/5L &nbsp;&nbsp;Four Fifths Last',
					'one_sixth' => '1/6 &nbsp;&nbsp;&nbsp;&nbsp;One Sixth',
					'one_sixth_last' => '1/6L &nbsp;&nbsp;One Sixth Last',
					'five_sixth' => '5/6 &nbsp;&nbsp;&nbsp;&nbsp;Five Sixths',
					'five_sixth_last' => '5/6L &nbsp;&nbsp;Five Sixths Last'
				)
			),
			'content' => array(
				'std' => '',
				'type' => 'textarea',
				'label' => __('Column Content:', 'bean'),
				'desc' => __('', 'bean'),
			)
		),
		
		'shortcode' => '[{{column}}] {{content}} [/{{column}}] ',
		'clone_button' => __('Click to Add Another Column', 'bean')
	)
);



/*--------------------------------------------------------------------*/
/*	SOCIAL ICON
/*--------------------------------------------------------------------*/
$bean_shortcodes['social-icons'] = array(
	'no_preview' => true,
	'params' => array(
		'type' => array(
					'type' => 'select',
					'label' => __('Select Icon:', 'bean'),
					'desc' => __('', 'bean'),
					'options' => array(
						'behance' => 'Behance',
						'delicious' => 'Delicious',
						'digg' => 'Digg',
						'dribbble' => 'Dribbble',
						'facebook' => 'Facebook',
						'flickr' => 'Flickr',
						'forrst' => 'Forrst',
						'foursquare' => 'FourSquare',
						'github' => 'GitHub',
						'googleplus' => 'Google Plus',
						'Linkedin' => 'LinkedIn',
						'pinterest' => 'Pinterest',
						'rss' => 'RSS',
						'stumbleupon' => 'StumbleUpon',
						'twitter' => 'Twitter',
						'vimeo' => 'Vimeo',
						'youtube' => 'YouTube',
						'zerply' => 'Zerply',
						'youtube' => 'YouTube',
					)
				),
		
		'link' => array(
			'std' => 'http://',
			'type' => 'text',
			'label' => __('Icon Link URL:', 'bean'),
			'desc' => __('', 'bean')
		),
	),
	
	'shortcode' => '[social-icon link="{{link}}" type="{{type}}"/]',
	'popup_title' => __('Social Icon Shortcode', 'bean')
);



/*--------------------------------------------------------------------*/
/*	POPUP MODAL
/*--------------------------------------------------------------------*/
$bean_shortcodes['popup'] = array(
	'no_preview' => true,
	'params' => array(
		'heading' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Pop-Up Title:', 'bean'),
			'desc' => __('', 'bean')
		),
		
		'popup_link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Link Text:', 'bean'),
			'desc' => __('', 'bean'),
		),
 				
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('Content:', 'bean'),
			'desc' => __('', 'bean'),
			),
		
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('CSS Class (Optional):', 'bean'),
			'desc' => __('', 'bean'),
		),	
		),
	
	'shortcode' => '[popup heading="{{heading}}" popup_link="{{popup_link}}" class="{{class}}"] {{content}} [/popup]',
	'popup_title' => __('Pop-Up Shortcode', 'bean')
);
		
?>
