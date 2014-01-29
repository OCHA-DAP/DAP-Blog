<?php
/*--------------------------------------------------------------------*/                							
/*  TEXT WIDGET SHORTCODE FILTERS					                							
/*--------------------------------------------------------------------*/
add_filter('widget_text', 'shortcode_unautop', 10);
add_filter('widget_text', 'do_shortcode', 10);


/*--------------------------------------------------------------------*/                							
/*  COLUMNS					                							
/*--------------------------------------------------------------------*/
if (!function_exists('bean_one_third')) {
	function bean_one_third( $atts, $content = null ) {
	   return '<div class="bean-one-third">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('one_third', 'bean_one_third');
}

if (!function_exists('bean_one_third_last')) {
	function bean_one_third_last( $atts, $content = null ) {
	   return '<div class="bean-one-third bean-column-last">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('one_third_last', 'bean_one_third_last');
}

if (!function_exists('bean_two_third')) {
	function bean_two_third( $atts, $content = null ) {
	   return '<div class="bean-two-third">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('two_third', 'bean_two_third');
}

if (!function_exists('bean_two_third_last')) {
	function bean_two_third_last( $atts, $content = null ) {
	   return '<div class="bean-two-third bean-column-last">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('two_third_last', 'bean_two_third_last');
}

if (!function_exists('bean_one_half')) {
	function bean_one_half( $atts, $content = null ) {
	   return '<div class="bean-one-half">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('one_half', 'bean_one_half');
}

if (!function_exists('bean_one_half_last')) {
	function bean_one_half_last( $atts, $content = null ) {
	   return '<div class="bean-one-half bean-column-last">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('one_half_last', 'bean_one_half_last');
}

if (!function_exists('bean_one_fourth')) {
	function bean_one_fourth( $atts, $content = null ) {
	   return '<div class="bean-one-fourth">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('one_fourth', 'bean_one_fourth');
}

if (!function_exists('bean_one_fourth_last')) {
	function bean_one_fourth_last( $atts, $content = null ) {
	   return '<div class="bean-one-fourth bean-column-last">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('one_fourth_last', 'bean_one_fourth_last');
}

if (!function_exists('bean_three_fourth')) {
	function bean_three_fourth( $atts, $content = null ) {
	   return '<div class="bean-three-fourth">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('three_fourth', 'bean_three_fourth');
}

if (!function_exists('bean_three_fourth_last')) {
	function bean_three_fourth_last( $atts, $content = null ) {
	   return '<div class="bean-three-fourth bean-column-last">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('three_fourth_last', 'bean_three_fourth_last');
}

if (!function_exists('one_fifth')) {
	function bean_one_fifth( $atts, $content = null ) {
	   return '<div class="bean-one-fifth">' . do_shortcode($content) . '</div>';
	}
	add_shortcode('one_fifth', 'bean_one_fifth');
}

if (!function_exists('bean_one_fifth_last')) {
	function bean_one_fifth_last( $atts, $content = null ) {
	   return '<div class="bean-one-fifth bean-column-last">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('one_fifth_last', 'bean_one_fifth_last');
}

if (!function_exists('bean_two_fifth')) {
	function bean_two_fifth( $atts, $content = null ) {
	   return '<div class="bean-two-fifth">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('two_fifth', 'bean_two_fifth');
}

if (!function_exists('bean_two_fifth_last')) {
	function bean_two_fifth_last( $atts, $content = null ) {
	   return '<div class="bean-two-fifth bean-column-last">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('two_fifth_last', 'bean_two_fifth_last');
}

if (!function_exists('bean_three_fifth')) {
	function bean_three_fifth( $atts, $content = null ) {
	   return '<div class="bean-three-fifth">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('three_fifth', 'bean_three_fifth');
}

if (!function_exists('bean_three_fifth_last')) {
	function bean_three_fifth_last( $atts, $content = null ) {
	   return '<div class="bean-three-fifth bean-column-last">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('three_fifth_last', 'bean_three_fifth_last');
}

if (!function_exists('bean_four_fifth')) {
	function bean_four_fifth( $atts, $content = null ) {
	   return '<div class="bean-four-fifth">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('four_fifth', 'bean_four_fifth');
}

if (!function_exists('bean_four_fifth_last')) {
	function bean_four_fifth_last( $atts, $content = null ) {
	   return '<div class="bean-four-fifth bean-column-last">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('four_fifth_last', 'bean_four_fifth_last');
}

if (!function_exists('bean_one_sixth')) {
	function bean_one_sixth( $atts, $content = null ) {
	   return '<div class="bean-one-sixth">' . do_shortcode($content) . '</div>';
	}
	add_shortcode('one_sixth', 'bean_one_sixth');
}

if (!function_exists('bean_one_sixth_last')) {
	function bean_one_sixth_last( $atts, $content = null ) {
	   return '<div class="bean-one-sixth bean-column-last">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('one_sixth_last', 'bean_one_sixth_last');
}

if (!function_exists('bean_five_sixth')) {
	function bean_five_sixth( $atts, $content = null ) {
	   return '<div class="bean-five-sixth">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('five_sixth', 'bean_five_sixth');
}

if (!function_exists('bean_sixth_last')) {
	function bean_five_sixth_last( $atts, $content = null ) {
	   return '<div class="bean-five-sixth bean-column-last">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode('five_sixth_last', 'bean_five_sixth_last');
}


/*--------------------------------------------------------------------*/                							
/*  CLEAR					                							
/*--------------------------------------------------------------------*/
if(!function_exists('bean_clear')) {
	function bean_clear( $atts ) {
	   return '<div class="clearfix"></div>';
	}
	add_shortcode('clear', 'bean_clear');
	add_shortcode('clearfix', 'bean_clear');
}


/*--------------------------------------------------------------------*/                							
/*  TOGGLES					                							
/*--------------------------------------------------------------------*/
if (!function_exists('bean_toggle')) {
	function bean_toggle( $atts, $content = null ) {
	    extract(shortcode_atts(array(
			'title'    	 => 'Title goes here',
			'state'		 => 'open'
	    ), $atts));

		return "<div data-id='".$state."' class=\"bean-toggle\"><span class=\"bean-toggle-title\">". $title ."</span><div class=\"bean-toggle-inner\"><div class=\"target\">". do_shortcode( $content ) ."</div></div></div>";
	}
	add_shortcode('toggle', 'bean_toggle');
}


/*--------------------------------------------------------------------*/                							
/*  TABS					                							
/*--------------------------------------------------------------------*/
if (!function_exists('bean_tabs')) {
	function bean_tabs( $atts, $content = null ) {
		$defaults = array();
		extract( shortcode_atts( $defaults, $atts ) );

		preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );

		$tab_titles = array();
		if( isset($matches[1]) ){ $tab_titles = $matches[1]; }

		$output = '';

		if( count($tab_titles) ){
		    $output .= '<div id="bean-tabs-'. rand(1, 100) .'" class="bean-tabs clearfix"><div class="bean-tab-inner">';
			$output .= '<ul class="bean-nav clearfix">';

			foreach( $tab_titles as $tab ){
				$output .= '<li><a href="#bean-tab-'. sanitize_title( $tab[0] ) .'">' . $tab[0] . '</a></li>';
			}

		    $output .= '</ul>';
		    $output .= do_shortcode( $content);
		    $output .= '</div></div>';
		} else {
			$output .= do_shortcode( $content );
		}

		return $output;
	}
	add_shortcode( 'tabs', 'bean_tabs' );

}

if (!function_exists('bean_tab')) {

	function bean_tab( $atts, $content = null ) {

		$defaults = array( 'title' => 'Tab' );
		extract( shortcode_atts( $defaults, $atts ) );

		return '<div id="bean-tab-'. sanitize_title( $title ) .'" class="bean-tab">'. do_shortcode( $content ) .'</div>';
	}
	add_shortcode( 'tab', 'bean_tab' );
}


/*--------------------------------------------------------------------*/                							
/*  ALERTS					                							
/*--------------------------------------------------------------------*/
if (!function_exists('bean_alert')) {

	function bean_alert( $atts, $content = null ) {

	   extract(shortcode_atts(array(
	   		'style' => '',
	       ), $atts));

	   	$class = 'bean-alert';
	   	$icon = null;

	   	if ($style == 'inset') {
	   		$class = 'insetBox';
	   	} elseif ($style) {
	   		$class .= ' '. $style;
	   	}
	   	if ($icon) $class .= ' icon';

	   	$box = '<div class="'.$class.'"><span class="alert-close"></span>';
	   	$box .= '<span>'.do_shortcode( $content ).'</span>';
	   	$box .= '</div>';

	      return $box;
	}

	add_shortcode('alert', 'bean_alert');
}


/*--------------------------------------------------------------------*/                							
/*  HIGHLIGHT					                							
/*--------------------------------------------------------------------*/
if(!function_exists('bean_highlight_sc')) {
	function bean_highlight_sc ( $atts, $content = null ) {
		$defaults = array();
		extract( shortcode_atts( $defaults, $atts ) );
		return '<span class="highlight">' . $content . '</span>';
	}
	add_shortcode( 'highlight', 'bean_highlight_sc' );
}


/*--------------------------------------------------------------------*/                							
/*  NOTE					                							
/*--------------------------------------------------------------------*/
if(!function_exists('bean_note_sc')) {
	function bean_note_sc ( $atts, $content = null ) {
		$defaults = array();
		extract( shortcode_atts( $defaults, $atts ) );
		return '<div class="bean-note">' . $content . '</div>';
	}
	add_shortcode( 'note', 'bean_note_sc' );
}


/*--------------------------------------------------------------------*/                							
/*  PRETTYPRINT					                							
/*--------------------------------------------------------------------*/
if(!function_exists('bean_pre_sc')) {
	function bean_pre_sc ( $atts, $content = null ) {
		$defaults = array();
		extract( shortcode_atts( $defaults, $atts ) );
		return '<pre class="prettyprint">' . $content . '</pre>';
	}
	add_shortcode( 'pre', 'bean_pre_sc' );
}


/*--------------------------------------------------------------------*/                							
/*  LISTS					                							
/*--------------------------------------------------------------------*/
if (!function_exists('bean_lists_sc')) {
	function bean_lists_sc($atts, $content = null) {
		extract( shortcode_atts( array(
			'style' => ''
		), $atts ) );
	     $content = do_shortcode( $content );
		return '<div class="shortcode-list">'.$content.'</div>';
	}
	add_shortcode("list", "bean_lists_sc");
}


/*--------------------------------------------------------------------*/                							
/*  SOCIAL ICONS					                							
/*--------------------------------------------------------------------*/
if (!function_exists('bean_social_icon_sc')) {
	function bean_social_icon_sc( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'size' => '',			
			'type' => '', 			
			'link' => '#',			
			'title' => '',			
			'style' => '',			
			'link' => false,		
			'title' => false,		
			'target' => '_blank',	
			'tooltip' => false,		
			'container' => 'div',	
			'return' => ''			
	    ), $atts));

		$icon = null;
	 	$class = null;

		if ($type) {

			$type = strtolower($type);

			// Icon size
			switch ($size) {
				case 'small':
					$class .= 'icon16';
					break;
				case 'medium':
					$class .= 'icon24';
					break;
				default:
					$class .= 'icon32';
			}

			$iconSocial = array( 'digg', 'twitter', 'dribbble',  'youtube',  'facebook', 'forrst', 'zerply', 'flickr', 'envato', 'rss' );

			$class .= ' iconSocial';

			if ($title == '0')	$title = $icon;

			$class .= ' '. $type;

			if ($return == 'class') return $class;

			$output = null;

			if ($link) {

				$link = trim($link);

				if		($target == 'blank' || $target == '_blank' || $target == 'new' )	{ $target = ' target="_blank"'; }
				elseif	($target == 'parent')	{ $target = ' target="_parent"'; }
				elseif	($target == 'self')		{ $target = ' target="_self"'; }
				elseif	($target == 'top')		{ $target = ' target="_top"'; }
				else	{ $target = ''; }

				$tooltip_content = null;
				if($tooltip)
					$tooltip_content = ' has-tip tip-top';

				$output .= '<div class="'.$class.'">';
				$output .= '<a href="'.$link.'" title="'.$type.'" '.$target.' class="'. $style  .' social-icons'. $tooltip_content .'">';
				$output .= '</a>';
				$output .= '</div>';

			}

			return $output;
		}
	}
	add_shortcode('social-icon', 'bean_social_icon_sc');
}


/*--------------------------------------------------------------------*/                							
/*  BUTTONS					                							
/*--------------------------------------------------------------------*/
if (!function_exists('bean_button_sc')) {
	function bean_button_sc( $atts, $content = null ) {
	    extract(shortcode_atts(array(
			'url'		=> '#',
 			'target'	=> '',
			'color'		=> '',
			'type'      => '',
			'size'      => '',
			'icon'	    => '',
	    ), $atts));

		// VARIABLES
 		if ($color) $color = $color;
 		if ($type)  $type = $type;
 		if ($size)  $size = $size;

		// TARGET SETUP
		if		($target == 'blank' || $target == '_blank' || $target == 'new' ) { $target = ' target="_blank"'; }
		elseif	($target == 'parent')	{ $target = ' target="_parent"'; }
		elseif	($target == 'self')		{ $target = ' target="_self"'; }
		elseif	($target == 'top')		{ $target = ' target="_top"'; }
		else	{$target = '';}

		// ICONS
		if ($icon !="") { $icon_type = ( $icon ) ? '<i class="icon-'.$icon.'"></i>': '' ; }
		else {}
		// BUTTON OUTPUT
		$button = '<a href=" '.$url.' " class="short-btn '.$color.' '.$size.' '.$type.' " '.$target.'> '.$icon_type.' '.do_shortcode($content).' </a>';

	    return $button;
	}
	add_shortcode('button', 'bean_button_sc');
}



/* -------------------------------------------------- */
/*	FEATURE AREAS
/* -------------------------------------------------- */
if (!function_exists('bean_icon_box_sc')) {

	function bean_icon_box_sc($atts, $content=null ) {	
		extract(shortcode_atts(array(
			'title' => '', 
			'icon' => ''
			), $atts));
		
		if($icon != "") $icon = "<span class='icon $icon'></span>";
		
		// add blockquotes to the content
		$output  = '<div class="featurearea">';
		$output .= '<span class="featurearea_icon">'.$icon.'</span>';
		$output .= '<div class="featurearea_content">';
		$output .= '<h5 class="featurearea_content_title">'.$title."</h5>";
		$output .= wpautop( $content );
		$output .= '</div></div>';
		
		return $output;
	}
	add_shortcode('featurearea', 'bean_icon_box_sc');

}



/*--------------------------------------------------------------------*/                							
/*  PULL QUOTE					                							
/*--------------------------------------------------------------------*/
if(!function_exists('bean_quote_sc')) {
	function bean_quote_sc ( $atts, $content = null ) {
		$defaults = array();
		extract( shortcode_atts( $defaults, $atts ) );
		return '<div class="bean-quote">' . $content . '</div>';
	}
	add_shortcode( 'quote', 'bean_quote_sc' );
}



/*--------------------------------------------------------------------*/                							
/*  POPUP					                							
/*--------------------------------------------------------------------*/
if (!function_exists('bean_popup_sc')) {
	function bean_popup_sc( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'heading' 	=> '',
			'popup_link' 	=> null,
			'type' 			=> null,
			'class' 		=> null,
			'height' 		=> 500,
			'width' 		=> 400,
			'href' 			=> null,
			'state'			=> 'closed',
		), $atts ) );

		$id = rand(1, 10000);

		if ($content) $output ='

		<!-- modal content -->
		<div id="bean-modal-'. $id  .'" class="modal hide fade.in animated BeanModalBounceIn '.$class.'">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">×</a>
				<h1>'. $heading .'</h1>
			</div>
			<div class="modal-body">'. do_shortcode( $content ) .'</div>
		</div>

		<p><a data-toggle="modal" href="#bean-modal-'.$id .'" class="modal-popup-link '.$class.'">'.$popup_link.'</a></p>';

		return $output;
	}
	add_shortcode('popup', 'bean_popup_sc');
}
?>