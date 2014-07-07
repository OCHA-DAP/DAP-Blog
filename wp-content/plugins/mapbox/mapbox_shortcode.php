<?php
/*
Plugin Name: Mapbox 
Plugin URI: http://mapbox.com/
Description: Make beautiful maps. Share them anywhere.
Version: 1.1.1
Author: Mapbox 
Author URI: http://mapbox.com
License: BSD 
*/

add_shortcode( 'mapbox' , 'mapbox_shortcode_handler' );

function mapbox_shortcode_handler($atts) {
	$defaults = array(
		'layers' => 'mapbox.world-bright',
		'width' => '500',
		'height' => '300',
		'frameborder' => '0',
		'lat' => false,
		'lon' => false,
		'z' => false,
		'options' => false
	);
	$atts = shortcode_atts($defaults, $atts);

	foreach(array('width', 'height', 'frameborder', 'lat', 'lon', 'z') as $k) {
		if (!is_numeric($atts[$k])) {
			$atts[$k] = $defaults[$k];
		}
	}
	if (!preg_match('/^[-_.,\/a-zA-Z0-9]+$/', $atts['layers'])) {
		$atts['layers'] = $defaults['layers']; 
	}
	if (!preg_match('/^[-_.,\/a-zA-Z0-9]+$/', $atts['options'])) {
		$atts['options'] = $defaults['options']; 
	}
	extract($atts);

	$uri = "http://a.tiles.mapbox.com/v3/{$layers}/mm";

	if ($options !== false) {
		$uri .= "/{$options}";
	}
	
	$uri .= '.html';

	if ($lat !== false && $lon !== false && $z !== false) {
		$uri .= "#{$z}/{$lat}/{$lon}";
	}

	return "<iframe src='{$uri}' width='{$width}' height='{$height}'></iframe>";
}

function mapbox_shortcode_init() {
	add_filter('content_save_pre', 'mapbox_shortcode_iframe_filter');
}
add_action('init', 'mapbox_shortcode_init');

function mapbox_shortcode_iframe_callback($matches) {
	if (count($matches) > 1) {
		$attr = array();
		foreach (explode(' ', trim($matches[1])) as $value) {
			list($k, $v) = explode('=', $value);
			$attr[trim($k)] = preg_replace("/('|\")/", "", $v);
		}

		if ($attr['src']) {
			if (preg_match('/http:\/\/a.tiles.mapbox.com\/v(2|3)\/([-_.,\/a-zA-Z0-9]+)\.html\#([-.\/0-9]+)/', $attr['src'], $m)) {
				list($layers, $api, $options) = explode('/', $m[2]);
				list($z, $lat, $lon) = explode('/', $m[3]);

				$ret = "[mapbox ";
				$ret .= "layers='{$layers}' api='{$api}' options='{$options}' ";
				$ret .= "lat='{$lat}' lon='{$lon}' z='{$z}' ";
				$ret .= "width='{$attr[width]}' height='{$attr[height]}'";
				$ret .= "]";
				return $ret;
			}
		}
	}
	return $matches[0];
}

function mapbox_shortcode_iframe_filter($content) {
	return preg_replace_callback('/<iframe([^>]+)>[^<]*<\/iframe>/', 'mapbox_shortcode_iframe_callback', $content);
}

?>
