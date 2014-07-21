<?php
/*
Plugin Name: Rotating Tweets (Twitter widget & shortcode)
Description: Replaces a shortcode such as [rotatingtweets screen_name='your_twitter_name'], or a widget, with a rotating tweets display 
Version: 1.7.2
Text Domain: rotatingtweets
Author: Martin Tod
Author URI: http://www.martintod.org.uk
License: GPL2
*/
/*  Copyright 2014 Martin Tod email : martin@martintod.org.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/**
 * Replaces a shortcode such as [rotatingtweets screen_name='your_twitter_name'], or a widget, with a rotating tweets display 
 *
 * @package WordPress
 * @since 3.3.2
 *
 */
require_once('lib/wp_twitteroauth.php');
/**
 * rotatingtweets_Widget_Class
 * Shows tweets sequentially for a given user
 */
class rotatingtweets_Widget extends WP_Widget {
    /** constructor */
    function rotatingtweets_Widget() {
        parent::WP_Widget(false, $name = 'Rotating Tweets',array('description'=>__('A widget to show tweets for a particular user in rotation.','rotatingtweets')));	
		if ( is_active_widget( false, false, $this->id_base ) )
			rotatingtweets_enqueue_scripts(); 
		}

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
		extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$positive_variables = array('screen_name','shorten_links','include_rts','exclude_replies','links_in_new_window','tweet_count','show_follow','timeout','rotation_type','show_meta_reply_retweet_favorite','official_format','show_type','list_tag','search');
		foreach($positive_variables as $var) {
			if(isset($instance['tw_'.$var])):
				$newargs[$var] = $instance['tw_'.$var];
			endif;
		}
		$negative_variables = array('meta_timestamp','meta_screen_name','meta_via');
		foreach($negative_variables as $var) {
			$newargs['show_'.$var] = !$instance['tw_hide_'.$var];
		}
		switch($newargs['show_follow']) {
		case 2: 
			$newargs['no_show_count'] = TRUE;
			$newargs['no_show_screen_name'] = FALSE;
			break;
		case 3: 
			$newargs['no_show_count'] = FALSE;
			$newargs['no_show_screen_name'] = TRUE;
			break;
		case 4:
			$newargs['no_show_count'] = TRUE;
			$newargs['no_show_screen_name'] = TRUE;
			break;
		default: 
			$newargs['no_show_count'] = FALSE;
			$newargs['no_show_screen_name'] = FALSE;
			break;
		}
		if(empty($newargs['timeout'])) $newargs['timeout'] = 4000;
		switch($newargs['show_type']) {
			case 1:
				$tweets = rotatingtweets_get_tweets($newargs['screen_name'],$newargs['include_rts'],$newargs['exclude_replies'],true);
				break;
			case 2:
				$tweets = rotatingtweets_get_tweets($newargs['screen_name'],$newargs['include_rts'],$newargs['exclude_replies'],false,$newargs['search']);
//				$newargs['screen_name'] = '';   // Originally put in to avoid confusion when people have a 'follow' button and a search tweet
				break;
			case 3:
				$tweets = rotatingtweets_get_tweets($newargs['screen_name'],$newargs['include_rts'],$newargs['exclude_replies'],false,false,$newargs['list_tag']);
				break;			
			case 0:
			default:
				$tweets = rotatingtweets_get_tweets($newargs['screen_name'],$newargs['include_rts'],$newargs['exclude_replies']);
				break;
		}
        ?>
              <?php echo $before_widget; 
						if ( $title )
							echo $before_title . $title . $after_title; 
						rotating_tweets_display($tweets,$newargs,TRUE);
					echo $after_widget;
					?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['tw_screen_name'] = strip_tags(trim($new_instance['tw_screen_name']));
		$instance['tw_list_tag'] = strip_tags(trim($new_instance['tw_list_tag']));
		$instance['tw_search'] = strip_tags(trim($new_instance['tw_search']));
		$instance['tw_rotation_type'] = strip_tags(trim($new_instance['tw_rotation_type']));
		$instance['tw_include_rts'] = absint($new_instance['tw_include_rts']);
		$instance['tw_links_in_new_window'] = absint($new_instance['tw_links_in_new_window']);
		$instance['tw_exclude_replies'] = absint($new_instance['tw_exclude_replies']);
		$instance['tw_shorten_links'] = absint($new_instance['tw_shorten_links']);
		$instance['tw_tweet_count'] = max(1,intval($new_instance['tw_tweet_count']));
		$instance['tw_show_follow'] = absint($new_instance['tw_show_follow']);
		$instance['tw_show_type'] = absint($new_instance['tw_show_type']);
		# Complicated way to ensure the defaults remain as they were before the 0.500 upgrade - i.e. showing meta timestamp, screen name and via, but not reply, retweet, favorite
		$instance['tw_hide_meta_timestamp'] = !$new_instance['tw_show_meta_timestamp'];
		$instance['tw_hide_meta_screen_name'] = !$new_instance['tw_show_meta_screen_name'];
		$instance['tw_hide_meta_via'] = !$new_instance['tw_show_meta_via'];
		$instance['tw_official_format'] = absint($new_instance['tw_official_format']);
		$instance['tw_show_meta_reply_retweet_favorite'] = absint($new_instance['tw_show_meta_reply_retweet_favorite']);
		$instance['tw_timeout'] = max(min(intval($new_instance['tw_timeout']/1000)*1000,20000),3000);
	return $instance;
    }
	
    /** @see WP_Widget::form */
    function form($instance) {				
		$variables = array( 
			'title' => array('title','','string'),
			'tw_screen_name' => array ('tw_screen_name','', 'string'),
			'tw_rotation_type' => array('tw_rotation_type','scrollUp', 'string'),
			'tw_include_rts' => array('tw_include_rts', false, 'boolean'),
			'tw_exclude_replies' => array('tw_exclude_replies', false, 'boolean'),
			'tw_tweet_count' => array('tw_tweet_count',5,'number'),
			'tw_show_follow' => array('tw_show_follow',false, 'boolean'),
			'tw_shorten_links' => array('tw_shorten_links',false, 'boolean'),
			'tw_official_format' => array('tw_official_format',0,'format'),
			'tw_show_type' => array('tw_show_type',0,'number'),
			'tw_links_in_new_window' => array('tw_links_in_new_window',false, 'boolean'),
			'tw_hide_meta_timestamp' => array('tw_show_meta_timestamp',true, 'notboolean',true),
			'tw_hide_meta_screen_name' => array('tw_show_meta_screen_name',true, 'notboolean',true),
			'tw_hide_meta_via'=> array('tw_show_meta_via',true,'notboolean',true),
			'tw_show_meta_reply_retweet_favorite' => array('tw_show_meta_reply_retweet_favorite',false,'boolean',true),
			'tw_timeout' => array('tw_timeout',4000,'number'),
			'tw_list_tag' => array('tw_list_tag','','string'),
			'tw_search' => array('tw_search','','string')
		);
		foreach($variables as $var => $val) {
			if(isset($instance[$var])):
				switch($val[2]):
					case "string":
						$$val[0] = esc_attr(trim($instance[$var]));
						break;
					case "format":
						if($instance[$var]==='custom'):
							$$val[0] = 'custom';
						else:
							$$val[0] = absint($instance[$var]);
						endif;
						break;
					case "number":
					case "boolean":
						$$val[0] = absint($instance[$var]);
						break;
					case "notboolean":
						$$val[0] = !$instance[$var];
						break;
				endswitch;
			else:
				$$val[0] = $val[1];
			endif;
			if(isset($val[3])):
				$metaoption[$val[0]]=$$val[0];
				unset($$val[0]);
			endif;
		}
/*			
		if(isset($instance['title'])) $title = esc_attr($instance['title']);
		if(isset($instance['tw_screen_name'])) $tw_screen_name = esc_attr(trim($instance['tw_screen_name']));
		if(isset($instance['tw_rotation_type'])) $tw_rotation_type = $instance['tw_rotation_type'];
		if(isset($instance['tw_include_rts'])) $tw_include_rts = absint($instance['tw_include_rts']);
		if(isset($instance['tw_exclude_replies'])) $tw_exclude_replies = absint($instance['tw_exclude_replies']);
		if(isset($instance['tw_tweet_count'])) $tw_tweet_count = intval($instance['tw_tweet_count']);
		if(isset($instance['tw_show_follow'])) $tw_show_follow = absint($instance['tw_show_follow']);
		if(isset($instance['tw_official_format'])) $tw_official_format = absint($instance['tw_official_format']);
		if(isset($instance['tw_show_type'])) $tw_show_type = absint($instance['tw_show_type']);
		if(isset($instance['tw_links_in_new_window'])) $tw_links_in_new_window = absint($instance['tw_links_in_new_window']);
		if(isset($instance['tw_hide_meta_timestamp'])) $metaoption['tw_show_meta_timestamp'] = !$instance['tw_hide_meta_timestamp'];
		if(isset($instance['tw_hide_meta_screen_name'])) $metaoption['tw_show_meta_screen_name'] = !$instance['tw_hide_meta_screen_name'];
		if(isset($instance['tw_hide_meta_via'])) $metaoption['tw_show_meta_via'] = !$instance['tw_hide_meta_via'];
		if(isset($instance['tw_show_meta_reply_retweet_favorite'])) $metaoption['tw_show_meta_reply_retweet_favorite'] = absint($instance['tw_show_meta_reply_retweet_favorite']);
		if(isset($instance['tw_timeout'])) $tw_timeout = intval($instance['tw_timeout']);
# If values not set, set default values
		if(empty($tw_rotation_type)) $tw_rotation_type = 'scrollUp';
		if(empty($tw_timeout)) $tw_timeout = 4000;
		if(empty($tw_tweet_count)) $tw_tweet_count = 5;
*/
        ?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','rotatingtweets'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<?php
		$hidestr ='';
		if($tw_show_type < 3) $hidestr = ' style="display:none;"';
		if($tw_show_type != 2):
			$hidesearch = ' style="display:none;"';
			$hideuser = '';
		else:
			$hideuser =  ' style="display:none;"';
			$hidesearch = '';
		endif;
		?>
		<p class='rtw_ad_not_search' <?php echo $hideuser;?>><label for="<?php echo $this->get_field_id('tw_screen_name'); ?>"><?php _e('Twitter name:','rotatingtweets'); ?><input class="widefat" id="<?php echo $this->get_field_id('tw_screen_name'); ?>" name="<?php echo $this->get_field_name('tw_screen_name'); ?>"  value="<?php echo $tw_screen_name; ?>" /></label></p>
		<p class='rtw_ad_search'<?php echo $hidesearch;?>><label for="<?php echo $this->get_field_id('tw_search'); ?>"><?php _e('Search:','rotatingtweets'); ?><input class="widefat" id="<?php echo $this->get_field_id('tw_search'); ?>" name="<?php echo $this->get_field_name('tw_search'); ?>"  value="<?php echo $tw_search; ?>" /></label></p>
		<p class='rtw_ad_list_tag' <?=$hidestr;?>><label for="<?php echo $this->get_field_id('tw_list_tag'); ?>"><?php _e('List Tag:','rotatingtweets'); ?> <input class="widefat" id="<?php echo $this->get_field_id('tw_list_tag'); ?>" name="<?php echo $this->get_field_name('tw_list_tag'); ?>"  value="<?php echo $tw_list_tag; ?>" /></label></p>
		<p><?php _e('Type of Tweets?','rotatingtweets'); ?></p><p>
		<?php
		$typeoptions = array (
							"0" => __("User timeline (default)",'rotatingtweets'),
							"1" => __("Favorites",'rotatingtweets'),
							"2" => __("Search",'rotatingtweets'),
							"3" => __("List",'rotatingtweets')
		);
		foreach ($typeoptions as $val => $html) {
			echo "<input type='radio' value='$val' id='".$this->get_field_id('tw_show_type_'.$val)."' name= '".$this->get_field_name('tw_show_type')."'";
			if($tw_show_type==$val): ?> checked="checked" <?php endif; 
			echo " class='rtw_ad_type'><label for='".$this->get_field_id('tw_show_type_'.$val)."'> $html</label><br />";
		};
		?></p>
		<p><input id="<?php echo $this->get_field_id('tw_include_rts'); ?>" name="<?php echo $this->get_field_name('tw_include_rts'); ?>" type="checkbox" value="1" <?php if($tw_include_rts==1): ?>checked="checked" <?php endif; ?>/><label for="<?php echo $this->get_field_id('tw_include_rts'); ?>"> <?php _e('Include retweets?','rotatingtweets'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('tw_exclude_replies'); ?>" name="<?php echo $this->get_field_name('tw_exclude_replies'); ?>" type="checkbox" value="1" <?php if($tw_exclude_replies==1): ?>checked="checked" <?php endif; ?>/><label for="<?php echo $this->get_field_id('tw_exclude_replies'); ?>"> <?php _e('Exclude replies?','rotatingtweets'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('tw_shorten_links'); ?>" name="<?php echo $this->get_field_name('tw_shorten_links'); ?>" type="checkbox" value="1" <?php if(!empty($tw_shorten_links)): ?>checked="checked" <?php endif; ?>/><label for="<?php echo $this->get_field_id('tw_shorten_links'); ?>"> <?php _e('Shorten links?','rotatingtweets'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('tw_links_in_new_window'); ?>" name="<?php echo $this->get_field_name('tw_links_in_new_window'); ?>" type="checkbox" value="1" <?php if($tw_links_in_new_window==1): ?>checked="checked" <?php endif; ?>/><label for="<?php echo $this->get_field_id('tw_links_in_new_window'); ?>"> <?php _e('Open all links in new window or tab?','rotatingtweets'); ?></label></p>
		<p><label for="<?php echo $this->get_field_id('tw_tweet_count'); ?>"><?php _e('How many tweets?','rotatingtweets'); ?> <select id="<?php echo $this->get_field_id('tw_tweet_count'); ?>" name="<?php echo $this->get_field_name('tw_tweet_count');?>">
		<?php 
		for ($i=1; $i<31; $i++) {
			echo "\n\t<option value='$i' ";
		if($tw_tweet_count==$i): ?>selected="selected" <?php endif; 
			echo ">$i</option>";
		}			
		?></select></label></p>
		<p><label for="<?php echo $this->get_field_id('tw_timeout'); ?>"><?php _e('Speed','rotatingtweets'); ?> <select id="<?php echo $this->get_field_id('tw_timeout'); ?>" name="<?php echo $this->get_field_name('tw_timeout');?>">
		<?php 
		$timeoutoptions = array (
							"3000" => __("Faster (3 seconds)",'rotatingtweets'),
							"4000" => __("Normal (4 seconds)",'rotatingtweets'),
							"5000" => __("Slower (5 seconds)",'rotatingtweets'),
							"6000" => __("Slowest (6 seconds)",'rotatingtweets'),
							"20000" => __("Ultra slow (20 seconds)",'rotatingtweets'),
		);
		foreach ($timeoutoptions as $val => $words) {
			echo "\n\t<option value='$val' ";
		if($tw_timeout==$val): ?>selected="selected" <?php endif; 
			echo ">$words</option>";
		}			
		?></select></label></p>
		<?php
		# For reference, all the rotations that look good.
		# $goodRotations = array('blindX','blindY','blindZ','cover','curtainY','fade','growY','none','scrollUp','scrollDown','scrollLeft','scrollRight','scrollHorz','scrollVert','shuffle','toss','turnUp','turnDown','uncover');
		$rotationoptions = rotatingtweets_possible_rotations(true);
		asort($rotationoptions);
		?>
		<p><label for="<?php echo $this->get_field_id('tw_rotation_type'); ?>"><?php _e('Type of rotation','rotatingtweets'); ?> <select id="<?php echo $this->get_field_id('tw_rotation_type'); ?>" name="<?php echo $this->get_field_name('tw_rotation_type');?>">
		<?php 		
		foreach ($rotationoptions as $val => $words) {
			echo "\n\t<option value='$val' ";
		if($tw_rotation_type==$val): ?>selected="selected" <?php endif; 
			echo ">$words</option>";
		}			
		?></select></label></p>
		<?php /* Ask about which Tweet details to show */ ?>
		<p><?php _e('Display format','rotatingtweets'); ?></p>
<?php
		$officialoptions = array (
			0 => __('Original rotating tweets layout','rotatingtweets'),
			1 => __("<a target='_blank' href='https://dev.twitter.com/terms/display-guidelines'>Official Twitter guidelines</a> (regular)",'rotatingtweets'),
			2 => __("<a target='_blank' href='https://dev.twitter.com/terms/display-guidelines'>Official Twitter guidelines</a> (wide)",'rotatingtweets'),
		);
		if (function_exists('rotatingtweets_display_override')) {
			$officialoptions['custom'] = __('Custom display layout','rotatingtweets');  
		}
		foreach ($officialoptions as $val => $html) {
			echo "<input type='radio' value='$val' id='".$this->get_field_id('tw_official_format_'.$val)."' name= '".$this->get_field_name('tw_official_format')."'";
			if($tw_official_format==$val): ?> checked="checked" <?php endif; 
			echo " class='rtw_ad_official'><label for='".$this->get_field_id('tw_official_format_'.$val)."'> $html</label><br />";
		};
		$hideStr='';
		if($tw_official_format > 0) $hideStr = ' style = "display:none;" ';
		?>
		<p /><div class='rtw_ad_tw_det' <?=$hideStr;?>><p><?php _e('Show tweet details?','rotatingtweets'); ?></p><p>
		<?php
		$tweet_detail_options = array(
			'tw_show_meta_timestamp' => __('Time/date of tweet','rotatingtweets'),
			'tw_show_meta_screen_name' => __('Name of person tweeting','rotatingtweets'),
			'tw_show_meta_via' => __('Source of tweet','rotatingtweets'),
			'tw_show_meta_reply_retweet_favorite' => __("'reply &middot; retweet &middot; favorite' links",'rotatingtweets')
		);
		$tw_br='';
		foreach ($tweet_detail_options as $field => $text):
		echo $tw_br;
		?>
		<input id="<?php echo $this->get_field_id($field); ?>" name="<?php echo $this->get_field_name($field); ?>" type="checkbox" value="1" <?php if($metaoption[$field]==1): ?>checked="checked" <?php endif; ?>/><label for="<?php echo $this->get_field_id($field); ?>"> <?php echo $text; ?></label>
		<?php 
		$tw_br = "<br />";
		endforeach; ?></p></div>
		<div class='rtw_ad_sf'>
		<p><?php _e('Show follow button?','rotatingtweets'); ?></p>
<?php
		$showfollowoptions = array (
			0 => _x('None','Show follow button?','rotatingtweets'),
			1 => __("Show name and number of followers",'rotatingtweets'),
			2 => __("Show name only",'rotatingtweets'),
			3 => __("Show followers only",'rotatingtweets'),
			4 => __("Show button only",'rotatingtweets')
		);

		foreach ($showfollowoptions as $val => $html) {
			echo "<input type='radio' value='$val' id='".$this->get_field_id('tw_tweet_count_'.$val)."' name= '".$this->get_field_name('tw_show_follow')."'";
			if($tw_show_follow==$val): ?> checked="checked" <?php endif; 
			echo "><label for='".$this->get_field_id('tw_tweet_count_'.$val)."'> $html</label><br />";
		}
		# This is an appalling hack to deal with the problem that jQuery gets broken when people hit save - as per http://lists.automattic.com/pipermail/wp-hackers/2011-March/037997.html - but it works!
//		echo "<script type='text/javascript' src='".plugins_url('js/rotating_tweet_admin.js', __FILE__)."'></script>";
		echo "</div>\n<script type='text/javascript'>\n";
		$rtw_admin_script_original = file_get_contents(plugin_dir_path(__FILE__).'js/rotating_tweet_admin.js');
		$rtw_admin_script_final = str_replace(
			array('.rtw_ad_official','.rtw_ad_type'),
			array('[name="'.$this->get_field_name('tw_official_format').'"]','[name="'.$this->get_field_name('tw_show_type').'"]'),
			$rtw_admin_script_original);
		echo $rtw_admin_script_final;
		echo "\n</script>";
	}
} // class rotatingtweets_Widget

// register rotatingtweets_Widget widget
add_action('widgets_init', create_function('', 'return register_widget("rotatingtweets_Widget");'));

# Converts Tweet timestamp into a time description
function rotatingtweets_contextualtime($small_ts, $large_ts=false) {
  if(!$large_ts) $large_ts = time();
  $n = $large_ts - $small_ts;
  if($n <= 1) return __('less than a second ago','rotatingtweets');
  if($n < (60)) return sprintf(__('%d seconds ago','rotatingtweets'),$n);
  if($n < (60*60)) { $minutes = round($n/60); return sprintf(_n('about a minute ago','about %d minutes ago',$minutes,'rotatingtweets'),$minutes); }
  if($n < (60*60*16)) { $hours = round($n/(60*60)); return sprintf(_n('about an hour ago','about %d hours ago',$hours,'rotatingtweets'),$hours); }
  if($n < (time() - strtotime('yesterday'))) return __('yesterday','rotatingtweets');
  if($n < (60*60*24)) { $hours = round($n/(60*60)); return sprintf(_n('about an hour ago','about %d hours ago',$hours,'rotatingtweets'),$hours); }
  if($n < (60*60*24*6.5)) { $days = round($n/(60*60*24)); return sprintf(_n('about a day ago','about %d days ago',$days,'rotatingtweets'),$days); }
  if($n < (time() - strtotime('last week'))) return __('last week','rotatingtweets');
  if($n < (60*60*24*7*3.5)) { $weeks = round($n/(60*60*24*7)); return sprintf(_n('about a week ago','about %d weeks ago',$weeks,'rotatingtweets'),$weeks); } 
  if($n < (time() - strtotime('last month'))) return __('last month','rotatingtweets');
  if($n < (60*60*24*7*4*11.5)) { $months = round($n/(60*60*24*7*4)) ; return sprintf(_n('about a month ago','about %d months ago',$months,'rotatingtweets'),$months);}
  if($n < (time() - strtotime('last year'))) return __('last year','rotatingtweets');
  if($n >= (60*60*24*7*4*12)){$years=round($n/(60*60*24*7*52)) ;return sprintf(_n('about a year ago','about %d years ago',$years,'rotatingtweets'),$years);}
  return false;
}
# Converts Tweet timestamp into a short time description - as specified by Twitter
function rotatingtweets_contextualtime_short($small_ts, $large_ts=false) {
  if(!$large_ts) $large_ts = time();
  $n = $large_ts - $small_ts;
  if($n < (60)) return sprintf(_x('%ds','abbreviated timestamp in seconds','rotatingtweets'),$n);
  if($n < (60*60)) { $minutes = round($n/60); return sprintf(_x('%dm','abbreviated timestamp in minutes','rotatingtweets'),$minutes); }
  if($n < (60*60*24)) { $hours = round($n/(60*60)); return sprintf(_x('%dh','abbreviated timestamp in hours','rotatingtweets'),$hours); }
  if($n < (60*60*24*364)) return date(_x('j M','short date format as per http://uk.php.net/manual/en/function.date.php','rotatingtweets'),$small_ts);
  return date(_x('j M Y','slightly longer date format as per http://uk.php.net/manual/en/function.date.php','rotatingtweets'),$small_ts);
}
# Get reply,retweet,favorite intents - either words-only (option 0) or icons only (option 1) or both (option 2)
function rotatingtweets_intents($twitter_object,$lang, $icons = 1,$targetvalue='') {
	$addstring = array();
	$types = array (
		array ( 'link'=>'https://twitter.com/intent/tweet?in_reply_to=', 'icon'=>'images/reply.png', 'text' => __('reply', 'rotatingtweets')),
		array ( 'link'=>'https://twitter.com/intent/retweet?tweet_id=', 'icon'=>'images/retweet.png', 'text' => __('retweet', 'rotatingtweets')),
		array ( 'link'=>'https://twitter.com/intent/favorite?tweet_id=', 'icon'=>'images/favorite.png', 'text' => __('favorite', 'rotatingtweets'))
	);
	foreach($types as $type) {
		$string = "\n\t\t\t<a href='".$type['link'].$twitter_object['id_str']."' title='".esc_attr($type['text'])."' lang='{$lang}'{$targetvalue}>";
		switch($icons) {
		case 2:
			$addstring[] = $string."<img src='".plugins_url($type['icon'],__FILE__)."' width='16' height='16' alt='".esc_attr($type['text'])."' /> {$type['text']}</a>";
			$glue = ' ';		
			break;
		case 1:
			$addstring[] = $string."<img src='".plugins_url($type['icon'],__FILE__)."' width='16' height='16' alt='".esc_attr($type['text'])."' /></a>";
			$glue = '';
			break;
		case 0:
		default:
			$addstring[] = $string.$type['text'].'</a>';
			$glue = ' &middot; ';
			break;
		}
	}
	$string = implode($glue,$addstring);
	return($string);
}
// Produces a link to someone's name, icon or screen name (or to the text of your choice) using the 'intent' format for linking
function rotatingtweets_user_intent($person,$lang,$linkcontent,$targetvalue='') {
	if(!is_array($person)) return;
	$return = "<a href='https://twitter.com/intent/user?user_id={$person['id']}' title='".esc_attr($person['name'])."' lang='{$lang}'{$targetvalue}>";
	switch($linkcontent){
	case 'icon':
		if(isset($_SERVER['HTTPS'])):
			$return .= "<img src='{$person['profile_image_url_https']}' alt='".esc_attr($person['name'])."' /></a>";
		else:
			$return .= "<img src='{$person['profile_image_url']}' alt='".esc_attr($person['name'])."' /></a>";		
		endif;
		break;
	case 'name':
		$return .= $person['name']."</a>";
		break;
	case 'screen_name':
		$return .= "@".$person['screen_name']."</a>";
		break;
	case 'blue_bird':
		$return = "<a href='https://twitter.com/intent/user?user_id={$person['id']}' title='".esc_attr(sprintf(__('Follow @%s','rotatingtweets'),$person['name']))."' lang='{$lang}'{$targetvalue}>";
		$return .= '<img src="'.plugins_url('images/bird_blue_32.png', __FILE__).'" class="twitter_icon" alt="'.__('Twitter','rotatingtweets').'" /></a>';
		break;
	default:
		$return .= strip_tags($linkcontent,'<img>')."</a>";
		break;
	}
	return ($return);
}
// Produces a linked timestamp for including in the tweet
function rotatingtweets_timestamp_link($twitter_object,$timetype = 'default',$targetvalue='') {
	$string = '<a '.$targetvalue.' href="https://twitter.com/twitterapi/status/'.$twitter_object['id_str'].'">';
	$tweettimestamp = strtotime($twitter_object['created_at'] );
	// echo "<!-- ".$twitter_object['created_at'] . " | " .get_option('timezone_string') ." | $tweettimestamp -->";
	switch($timetype) {
		case 'short':
			$string .= rotatingtweets_contextualtime_short($tweettimestamp);
			break;
		case 'long':
			$string .= date_i18n(get_option('time_format'),$tweettimestamp + ( get_option('gmt_offset') * 60 * 60 ) )." &middot; ".date_i18n(get_option('date_format') ,$tweettimestamp + ( get_option('gmt_offset') * 60 * 60 ) );
			break;
		default:
			$string .= ucfirst(rotatingtweets_contextualtime($tweettimestamp));
			break;
	}
	$string .= '</a>';
	return ($string);
}
# Wraps the shortcode
function rotatingtweets_display($atts) {
	rotatingtweets_display_shortcode($atts,null,'',TRUE);
};
#
function rotatingtweets_link_to_screenname($link) {
	$match = '%(http://|https://|)(www\.|)twitter\.com/(#!\/|)([0-9a-z\_]+)%i';
	if(preg_match($match,$link,$result)):
		return($result[4]);
	else:
		return FALSE;
	endif;
}
# Processes the shortcode 
function rotatingtweets_display_shortcode( $atts, $content=null, $code="", $print=FALSE ) {
	// $atts    ::= twitter_id,include_rts,exclude_replies, $tweet_count,$show_follow
/**
	Possible values for get_cforms_entries()
	$screen_name :: [text]	Twitter user name
	$include_rts :: [boolean] include RTS - optional
	$exclude_replies :: [boolean] exclude replies - optional
	$tweet_count :: [integer] number of tweets to show - optional - default 5
	$show_follow :: [boolean] show follow button
	$no_show_count :: [boolean] remove count from follow button
	$no_show_screen_name :: [boolean] remove screen name from follow button
*/
	$args = shortcode_atts( array(
			'screen_name' => '',
			'url' => 'http://twitter.com/twitter',
			'include_rts' => FALSE,
			'exclude_replies' => FALSE,
			'tweet_count' => 5,
			'show_follow' => FALSE,
			'timeout' => 4000,
			'no_show_count' => FALSE,
			'no_show_screen_name' => FALSE,
			'show_meta_timestamp' => TRUE,
			'show_meta_screen_name' => TRUE,
			'show_meta_via' => TRUE,
			'show_meta_reply_retweet_favorite' => FALSE,
			'show_meta_prev_next' => FALSE,
			'rotation_type' => 'scrollUp',
			'official_format' => FALSE,
			'links_in_new_window' => FALSE,
			'url_length' => 29,
			'search' => FALSE,
			'list' => FALSE,
			'get_favorites' => FALSE,
			'ratelimit' => FALSE,
			'next' => __('next','rotatingtweets'),
			'prev' => __('prev','rotatingtweets'),
			'middot' => ' &middot; ',
			'np_pos' => 'top',
			'speed' => 1000,
			'offset' => 0,
			'link_all_text' => FALSE,
			'no_rotate' => FALSE,
			'show_media' => FALSE,
			'screen_name_plural' => 0,
			'tweet_length' => 0,
			'carousel_horizontal' => 0,
			'carousel_count' => 0,
			'carousel_responsive' => 0,
			'no_emoji' => 0
		), $atts ) ;
	extract($args);
	if(empty($screen_name) && empty($search) && !empty($url)):
		$screen_name = rotatingtweets_link_to_screenname($url);
		$args['screen_name'] = $screen_name;
		if(WP_DEBUG) {
			echo "<!-- $url => $screen_name -->";
		}
	endif;
	if(empty($screen_name)) $screen_name = 'twitter';
	# Makes sure the scripts are listed
	rotatingtweets_enqueue_scripts(); 
	$tweets = rotatingtweets_get_tweets($screen_name,$include_rts,$exclude_replies,$get_favorites,$search,$list);
	$returnstring = rotating_tweets_display($tweets,$args,$print);
	return $returnstring;
}
add_shortcode( 'rotatingtweets', 'rotatingtweets_display_shortcode' );

/*

Management page for the Twitter API options

*/
function rotatingtweets_settings_check() {
	$api = get_option('rotatingtweets-api-settings');
	$error = get_option('rotatingtweets_api_error');
	if(!empty($api)):
		$apistring = implode('',$api);
	endif;
	$optionslink = 'options-general.php?page=rotatingtweets';
	if(empty($apistring)):
		$msgString = __('Please update <a href="%2$s">your settings for Rotating Tweets</a>. The Twitter API <a href="%1$s">changed on June 11, 2013</a> and new settings are needed for Rotating Tweets to continue working.','rotatingtweets');
		// add_settings_error( 'rotatingtweets_settings_needed', esc_attr('rotatingtweets_settings_needed'), sprintf($msgString,'https://dev.twitter.com/calendar',$optionslink), 'error');
		echo "<div class='error'><p><strong>".sprintf($msgString,'https://dev.twitter.com/blog/api-v1-is-retired',$optionslink)."</strong></p></div>";
	elseif($error[0]['code'] == 32 ):
		// add_settings_error( 'rotatingtweets_settings_needed', esc_attr('rotatingtweets_settings_needed'), sprintf(__('Please update <a href="%1$s">your settings for Rotating Tweets</a>. Currently Twitter cannot authenticate you with the details you have given.','rotatingtweets'),$optionslink), 'error');
		echo "<div class='error'><p><strong>".sprintf(__('Please update <a href="%1$s">your settings for Rotating Tweets</a>. Currently Rotating Tweets cannot authenticate you with Twitter using the details you have given.','rotatingtweets'),$optionslink)."</strong></p></div>";
	endif;
};
add_action( 'admin_notices', 'rotatingtweets_settings_check' );

add_action( 'admin_menu', 'rotatingtweets_menu' );

function rotatingtweets_menu() {
	add_options_page( __('Rotating Tweets: Twitter API settings','rotatingtweets'), 'Rotating Tweets', 'manage_options', 'rotatingtweets', 'rotatingtweets_call_twitter_API_options' );
}

function rotatingtweets_call_twitter_API_options() {
	echo '<div class="wrap">';
	screen_icon();
	echo '<h2>'.__('Rotating Tweets: Twitter API settings','rotatingtweets').'</h2>';	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.','rotatingtweets' ) );
	}
	echo sprintf(__('<p>Twitter <a href="%s">has changed</a> the way that they allow people to use the information in their tweets.</p><p>You need to take the following steps to make sure that Rotating Tweets can access the information it needs from Twitter:</p>','rotatingtweets'),'https://dev.twitter.com/blog/changes-coming-to-twitter-api');
	echo sprintf(__('<h3>Step 1:</h3><p>Go to the <a href="%s">My applications page</a> on the Twitter website to set up your website as a new Twitter \'application\'. You may need to log-in using your Twitter user name and password.</p>','rotatingtweets'),'https://dev.twitter.com/apps');
	echo sprintf(__('<h3>Step 2:</h3><p>If you don\'t already have a suitable \'application\' that you can use for your website, set one up on the <a href="%s">Create an Application page</a>.</p> <p>It\'s normally best to use the name, description and website URL of the website where you plan to use Rotating Tweets.</p><p>You don\'t need a Callback URL.</p>','rotatingtweets'),'https://dev.twitter.com/apps/new');
	_e('<h3>Step 3:</h3><p>After clicking <strong>Create your Twitter application</strong>, on the following page, click on <strong>Create my access token</strong>.</p>','rotatingtweets');
	_e('<h3>Step 4:</h3><p>Copy the <strong>Consumer key</strong>, <strong>Consumer secret</strong>, <strong>Access token</strong> and <strong>Access token secret</strong> from your Twitter application page into the settings below.</p>','rotatingtweets');
	_e('<h3>Step 5:</h3><p>Click on <strong>Save Changes</strong>.','rotatingtweets');
	_e('<h3>If there are any problems:</h3><p>If there are any problems, you should get an error message from Twitter displayed as a "rotating tweet" which should help diagnose the problem.</p>','rotatingtweets');
	echo "<ol>\n\t<li>";
	_e('If you are getting problems with "rate limiting", try changing the first connection setting below to increase the time that Rotating Tweets waits before trying to get new data from Twitter.','rotatingtweets');
	echo "</li>\n\t<li>";
	_e('If you are getting time-out problems, try changing the second connection setting below to increase how long Rotating Tweets waits when connecting to Twitter before timing out.','rotatingtweets');
	echo "</li>\n\t<li>";
	_e('If the error message references SSL, try changing the "Verify SSL connection to Twitter" setting below to "No".','rotatingtweets');
	echo "\n</ol>";
	_e('<h3>Getting information from more than one Twitter account</h3>','rotatingtweets');
	_e('<p>Even though you are only entering one set of Twitter API data, Rotating Tweets will continue to support multiple widgets and shortcodes pulling from a variety of different Twitter accounts.</p>','rotatingtweets');
	echo '<form method="post" action="options.php">';
	settings_fields( 'rotatingtweets_options' );
	do_settings_sections('rotatingtweets_api_settings');
	submit_button(__('Save Changes','rotatingtweets'));
	echo '</form></div>';
}
add_action('admin_init', 'rotatingtweets_admin_init');

function rotatingtweets_admin_init(){
// API settings
	register_setting( 'rotatingtweets_options', 'rotatingtweets-api-settings', 'rotatingtweets_api_validate' );
	add_settings_section('rotatingtweets_api_main', __('Twitter API Settings','rotatingtweets'), 'rotatingtweets_api_explanation', 'rotatingtweets_api_settings');
	add_settings_field('rotatingtweets_key', __('Twitter API Consumer Key','rotatingtweets'), 'rotatingtweets_option_show_key', 'rotatingtweets_api_settings', 'rotatingtweets_api_main');
	add_settings_field('rotatingtweets_secret', __('Twitter API Consumer Secret','rotatingtweets'), 'rotatingtweets_option_show_secret', 'rotatingtweets_api_settings', 'rotatingtweets_api_main');
	add_settings_field('rotatingtweets_token', __('Twitter API Access Token','rotatingtweets'), 'rotatingtweets_option_show_token', 'rotatingtweets_api_settings', 'rotatingtweets_api_main');
	add_settings_field('rotatingtweets_token_secret', __('Twitter API Access Token Secret','rotatingtweets'), 'rotatingtweets_option_show_token_secret', 'rotatingtweets_api_settings', 'rotatingtweets_api_main');
// Connection settings	
	add_settings_section('rotatingtweets_connection_main', __('Connection Settings','rotatingtweets'), 'rotatingtweets_connection_explanation', 'rotatingtweets_api_settings');
	add_settings_field('rotatingtweets_cache_delay', __('How often should Rotating Tweets try to get the latest tweets from Twitter?','rotatingtweets'), 'rotatingtweets_option_show_cache_delay','rotatingtweets_api_settings','rotatingtweets_connection_main');
	add_settings_field('rotatingtweets_timeout', __("When connecting to Twitter, how long should Rotating Tweets wait before timing out?",'rotatingtweets'), 'rotatingtweets_option_show_timeout','rotatingtweets_api_settings','rotatingtweets_connection_main');
	add_settings_field('rotatingtweets_ssl_verify', __('Verify SSL connection to Twitter','rotatingtweets'), 'rotatingtweets_option_show_ssl_verify','rotatingtweets_api_settings','rotatingtweets_connection_main');
//	JQuery settings
	add_settings_section('rotatingtweets_jquery_main', __('JavaScript Settings','rotatingtweets'), 'rotatingtweets_jquery_explanation', 'rotatingtweets_api_settings');
	add_settings_field('rotatingtweets_jquery_cycle_version', __('Version of JQuery Cycle','rotatingtweets'), 'rotatingtweets_option_show_cycle_version','rotatingtweets_api_settings','rotatingtweets_jquery_main');
	add_settings_field('rotatingtweets_js_in_footer', __('Where to load Rotating Tweets JavaScript','rotatingtweets'), 'rotatingtweets_option_show_in_footer','rotatingtweets_api_settings','rotatingtweets_jquery_main');
}
function rotatingtweets_option_show_key() {
	$options = get_option('rotatingtweets-api-settings');
	echo "<input id='rotatingtweets_api_key_input' name='rotatingtweets-api-settings[key]' size='70' type='text' value='{$options['key']}' />";
}
function rotatingtweets_option_show_secret() {
	$options = get_option('rotatingtweets-api-settings');
	echo "<input id='rotatingtweets_api_secret_input' name='rotatingtweets-api-settings[secret]' size='70' type='text' value='{$options['secret']}' />";
}
function rotatingtweets_option_show_token() {
	$options = get_option('rotatingtweets-api-settings');
	echo "<input id='rotatingtweets_api_token_input' name='rotatingtweets-api-settings[token]' size='70' type='text' value='{$options['token']}' />";
}
function rotatingtweets_option_show_token_secret() {
	$options = get_option('rotatingtweets-api-settings');
	echo "<input id='rotatingtweets_api_token_secret_input' name='rotatingtweets-api-settings[token_secret]' size='70' type='text' value='{$options['token_secret']}' />";
}
function rotatingtweets_option_show_ssl_verify() {
	$options = get_option('rotatingtweets-api-settings');
	$choice = array(
		1 => _x('Yes','Verify SSL connection to Twitter','rotatingtweets'),
		0 => _x('No','Verify SSL connection to Twitter','rotatingtweets')
	);
	echo "\n<select id='rotatingtweets_api_ssl_verify_input' name='rotatingtweets-api-settings[ssl_verify]'>";
	foreach($choice as $value => $text) {
		if($options['ssl_verify_off'] != $value ) {
			$selected = 'selected = "selected"';
		} else {
			$selected = '';
		}
		echo "\n\t<option value='".$value."'".$selected.">".$text."</option>";
	}
	echo "\n</select>";
}
function rotatingtweets_option_show_timeout() {
	$options = get_option('rotatingtweets-api-settings');
	$choice = array(
		1 => _x('1 second','Connection timeout','rotatingtweets'),
		3 => _x('3 seconds (default)','Connection timeout','rotatingtweets'),
		5 => _x('5 seconds','Connection timeout','rotatingtweets'),
		7 => _x('7 seconds','Connection timeout','rotatingtweets'),
		20 => _x('20 seconds','Connection timeout','rotatingtweets')
	);
	echo "\n<select id='rotatingtweets_api_timeout_input' name='rotatingtweets-api-settings[timeout]'>";
	if(!isset($options['timeout']))	$options['timeout'] = 3;
	foreach($choice as $value => $text) {
		if($options['timeout'] == $value ) {
			$selected = ' selected = "selected"';
		} else {
			$selected = '';
		}
		echo "\n\t<option value='".$value."'".$selected.">".$text."</option>";
	}
	echo "\n</select>";
}
function rotatingtweets_option_show_cache_delay() {
	$options = get_option('rotatingtweets-api-settings');
	$choice = array(
		60 => _x('1 minute','Cache Delay','rotatingtweets'),
		120 => _x('2 minutes (default)','Cache Delay','rotatingtweets'),
		300 => _x('5 minutes','Cache Delay','rotatingtweets'),
		3600 => _x('1 hour','Cache Delay','rotatingtweets'),
		86400 => _x('24 hours','Cache Delay','rotatingtweets')
	);
	echo "\n<select id='rotatingtweets_cache_delay_input' name='rotatingtweets-api-settings[cache_delay]'>";
	if(!isset($options['cache_delay'])) $options['cache_delay'] = 120;
	foreach($choice as $value => $text) {
		if($options['cache_delay'] == $value ) {
			$selected = ' selected = "selected"';
		} else {
			$selected = '';
		}
		echo "\n\t<option value='".$value."'".$selected.">".$text."</option>";
	}
	echo "\n</select>";
}
function rotatingtweets_option_show_cycle_version() {
	$options = get_option('rotatingtweets-api-settings');
	$choice = array(
		1 => _x('Version 1 (default)','Version of JQuery Cycle','rotatingtweets'),
		2 => _x('Version 2 (beta)','Version of JQuery Cycle','rotatingtweets')
	);
	echo "\n<select id='rotatingtweets_api_jquery_cycle_version_input' name='rotatingtweets-api-settings[jquery_cycle_version]'>";
	if(!isset($options['jquery_cycle_version']))	$options['jquery_cycle_version'] = 1;
	foreach($choice as $value => $text) {
		if($options['jquery_cycle_version'] == $value ) {
			$selected = ' selected = "selected"';
		} else {
			$selected = '';
		}
		echo "\n\t<option value='".$value."'".$selected.">".$text."</option>";
	}
	echo "\n</select>";
}
function rotatingtweets_option_show_in_footer() {
	$options = get_option('rotatingtweets-api-settings');
	$choice = array(
		0 => _x('Load in header (default)','Location of JavaScript','rotatingtweets'),
		1 => _x('Load in footer','Location of JavaScript','rotatingtweets')
	);
	echo "\n<select id='rotatingtweets_api_js_in_footer_input' name='rotatingtweets-api-settings[js_in_footer]'>";
	if(!isset($options['js_in_footer'])) $options['js_in_footer'] = FALSE;
	foreach($choice as $value => $text) {
		if($options['js_in_footer'] == $value ) {
			$selected = ' selected = "selected"';
		} else {
			$selected = '';
		}
		echo "\n\t<option value='".$value."'".$selected.">".$text."</option>";
	}
	echo "\n</select>";
}
// Explanatory text
function rotatingtweets_api_explanation() {
	
};
// Explanatory text
function rotatingtweets_connection_explanation() {
	
};
// Explanatory text
function rotatingtweets_jquery_explanation() {
//	_e('This section is experimental and currently only displays if WP_DEBUG is set','rotatingtweets');
};
// validate our options
function rotatingtweets_api_validate($input) {
	$options = get_option('rotatingtweets-api-settings');
	$error = 0;
	// Check 'key'
	$options['key'] = trim($input['key']);
	if(!preg_match('/^[a-z0-9]+$/i', $options['key'])) {
		$options['key'] = '';
		$error = 1;
		add_settings_error( 'rotatingtweets', esc_attr('rotatingtweets-api-key'), __('Error: Twitter API Consumer Key not correctly formatted.','rotatingtweets'));
	}
	// Check 'secret'
	$options['secret'] = trim($input['secret']);
	if(!preg_match('/^[a-z0-9]+$/i', $options['secret'])) {
		$options['secret'] = '';
		$error = 1;
		add_settings_error( 'rotatingtweets', esc_attr('rotatingtweets-api-secret'), __('Error: Twitter API Consumer Secret not correctly formatted.','rotatingtweets'));
	}
	// Check 'token'
	$options['token'] = trim($input['token']);
	if(!preg_match('/^[a-z0-9]+\-[a-z0-9]+$/i', $options['token'])) {
		$options['token'] = '';
		$error = 1;
		add_settings_error( 'rotatingtweets', esc_attr('rotatingtweets-api-token'), __('Error: Twitter API Access Token not correctly formatted.','rotatingtweets'));
	}
	// Check 'token_secret'
	$options['token_secret'] = trim($input['token_secret']);
	if(!preg_match('/^[a-z0-9]+$/i', $options['token_secret'])) {
		$options['token_secret'] = '';
		$error = 1;
		add_settings_error( 'rotatingtweets', esc_attr('rotatingtweets-api-token-secret'), __('Error: Twitter API Access Token Secret not correctly formatted.','rotatingtweets'));
	}
	// Check 'ssl_verify'
	if(isset($input['ssl_verify']) && $input['ssl_verify']==0):
		$options['ssl_verify_off']=true;
	else:
		$options['ssl_verify_off']=false;
	endif;	
	// Check 'timeout'
	if(isset($input['timeout'])):
		$options['timeout'] = max(1,intval($input['timeout']));
	endif;
	// Check 'cache delay'
	if(isset($input['cache_delay'])):
		$options['cache_delay'] = max(60,intval($input['cache_delay']));
	else:
		$options['cache_delay']=120;
	endif;
	// Check 'jquery_cycle_version'
	if(isset($input['jquery_cycle_version'])):
		$options['jquery_cycle_version']=max(min(absint($input['jquery_cycle_version']),2),1);
	else:
		$options['jquery_cycle_version']=1;
	endif;
	// Check 'in footer'
	if(isset($input['js_in_footer'])):
		$options['js_in_footer'] = (bool) $input['js_in_footer'];
	else:
		$options['js_in_footer'] = FALSE;
	endif;
	// Now a proper test
	if(empty($error)):
		$transientname = 'rotatingtweets_check_wp_remote_request'; // This whole code is to help someone who has a problem with wp_remote_request
		if(!get_transient($transientname)):
			set_transient($transientname,true,24*60*60);
			$test = rotatingtweets_call_twitter_API('statuses/user_timeline',NULL,$options);
			delete_transient($transientname);
			$error = get_option('rotatingtweets_api_error');
			if(!empty($error)):
				if($error[0]['type'] == 'Twitter'):
					add_settings_error( 'rotatingtweets', esc_attr('rotatingtweets-api-'.$error[0]['code']), sprintf(__('Error message received from Twitter: %1$s. <a href="%2$s">Please check your API key, secret, token and secret token on the Twitter website</a>.','rotatingtweets'),$error[0]['message'],'https://dev.twitter.com/apps'), 'error' );
				else:				
					add_settings_error( 'rotatingtweets', esc_attr('rotatingtweets-api-'.$error[0]['code']), sprintf(__('Error message received from Wordpress: %1$s. Please check your connection settings.','rotatingtweets'),$error[0]['message']), 'error' );
				endif;
			endif;
		endif;
	endif;
	return $options;
}
/*
And now the Twitter API itself!
*/

function rotatingtweets_call_twitter_API($command,$options = NULL,$api = NULL ) {
	if(empty($api)) $api = get_option('rotatingtweets-api-settings');
	if(!empty($api)):
		$connection = new rotatingtweets_TwitterOAuth($api['key'], $api['secret'], $api['token'], $api['token_secret'] );
		//    $result = $connection->get('statuses/user_timeline', $options);
		if(WP_DEBUG && ! is_admin()):
			echo "\n<!-- Using OAuth - version 1.1 of API - ".esc_attr($command)." -->\n";
		endif;
		if(isset($api['ssl_verify_off']) && $api['ssl_verify_off']):
			if(WP_DEBUG  && ! is_admin() ):
				echo "\n<!-- NOT verifying SSL peer -->\n";
			endif;
			$connection->ssl_verifypeer = FALSE;
		else:
			if(WP_DEBUG && ! is_admin() ):
				echo "\n<!-- Verifying SSL peer -->\n";
			endif;
			$connection->ssl_verifypeer = TRUE;
		endif;
		if(isset($api['timeout'])):
			$timeout = max(1,intval($api['timeout']));
			if(WP_DEBUG && ! is_admin() ):
				echo "\n<!-- Setting timeout to $timeout seconds -->\n";
			endif;
			$connection->timeout = $timeout;
		endif;
		$result = $connection->get($command , $options);
	else:
		// Construct old style API command
		unset($string);
		if($command == 'application/rate_limit_status'):
			$command = 'account/rate_limit_status';
			unset($options);
		endif;
		if(is_array($options)):
			foreach($options as $name => $val) {
				$string[] = $name . "=" . urlencode($val);
			}
		endif;
		if($command != 'search/tweets'):
			$apicall = "http://api.twitter.com/1/".$command.".json";
		else:
			$apicall = "http://search.twitter.com/search.json";
		endif;
		if(!empty($string)) $apicall .= "?".implode('&',$string);
		if(WP_DEBUG  && ! is_admin() ) echo "<!-- Using version 1 of API - calling string ".esc_attr($apicall)." -->";
		$result = wp_remote_request($apicall);
	endif;
	if(!is_wp_error($result)):
		if(isset($result['body'])):
			$data = json_decode($result['body'],true);
			if(isset($data['errors'])):
				$data['errors'][0]['type'] = 'Twitter';
				if( empty($api) ) $errorstring[0]['message'] = 'Please enter valid Twitter API Settings on the Rotating Tweets settings page';
				if(WP_DEBUG  && ! is_admin() ) echo "<!-- Error message from Twitter - {$data['errors']} -->";
				update_option('rotatingtweets_api_error',$data['errors']);
			else:
				if(WP_DEBUG  && ! is_admin() ) echo "<!-- Successfully read data from Twitter -->";
				delete_option('rotatingtweets_api_error');
			endif;
		else:
			if(WP_DEBUG  && ! is_admin() ):
				echo "<!-- Failed to read valid data from Twitter: problem with wp_remote_request(). Data read was: ";
				print_r($result);
				echo "\n-->";
			endif;
			$errorstring[0]['code']= 999;
			$errorstring[0]['message']= 'Failed to read valid data from Twitter: problem with wp_remote_request()';
			$errorstring[0]['type'] = 'Wordpress';
			update_option('rotatingtweets_api_error',$errorstring);
		endif;
	else:
		$errorstring = array();
		$errorstring[0]['code']= $result->get_error_code();
		$errorstring[0]['message']= $result->get_error_message();
		$errorstring[0]['type'] = 'Wordpress';
		if(WP_DEBUG  && ! is_admin() ) echo "<!-- Error message from Wordpress - {$errorstring[0]['message']} -->";
		update_option('rotatingtweets_api_error',$errorstring);
	endif;
	return($result);
}
# Clear tweets (if too much memory used)
function rotatingtweets_shrink_cache() {
	# Solves a problem that 40+ caches can overload the memory - cuts it to fewer without risking deletion of the tweets on display
	$optionname = "rotatingtweets-cache";
	$option = get_option($optionname);
	$numberidentities = count($option);
	if(WP_DEBUG) echo "<!-- There are currently ".$numberidentities." identities cached -->";
	# If there are fewer than 10 sets of information cached - just return (for speed)
	if ( !is_array($option) or $numberidentities == 0 ) return;
	# Now make sure that we don't overwrite 'live' tweets
	$minageindays = 1000000;
	$totalcachesize = 0;
	# Get the age and size of tweets remaining
	foreach($option as $stringname => $contents) {
		$ageindays = (time()-$contents['datetime'])/60/60/24;
		if($ageindays < $minageindays) $minageindays = $ageindays;		
		if(WP_DEBUG):
			$cachesize = strlen(json_encode($contents));
			echo "\n<!-- $stringname - $cachesize - ".date('d-m-Y',$contents['datetime'])." - ".number_format($ageindays,1)." days -->";
			$totalcachesize = $totalcachesize + $cachesize;
		endif;
	};	
	if($totalcachesize == 0):
		if(WP_DEBUG) echo "<!-- Cache failed to read successfully -->";
		return;
	endif;
	if(WP_DEBUG) echo "\n<!-- The youngest age of any cache is ".number_format($minageindays*24*60,2)." minutes (".number_format($minageindays,8)." days) and total cache size is ".$totalcachesize.". -->";
	if($numberidentities < 10) return;
	# Set the goal of deleting all the tweets more than 30 days older than the most recent tweets
	$targetageindays = $minageindays + 30;
	# Now run through and delete 
	foreach($option as $stringname => $contents) {
		$ageindays = (time()-$contents['datetime'])/60/60/24;
		if($ageindays > $targetageindays) unset($option[$stringname]);
	};
	$numberidentities = count($option);
	if(WP_DEBUG) echo "<!-- There are now ".$numberidentities." identities cached -->";
	update_option($optionname,$option);
}

# Get the latest data from Twitter (or from a cache if it's been less than 2 minutes since the last load)
function rotatingtweets_get_tweets($tw_screen_name,$tw_include_rts,$tw_exclude_replies,$tw_get_favorites = FALSE,$tw_search = FALSE,$tw_list = FALSE ) {
	# Set timer
	$rt_starttime = microtime(true);
	# Check cache
	rotatingtweets_shrink_cache();
	# Clear up variables
	$tw_screen_name = trim(remove_accents(str_replace('@','',$tw_screen_name)));
	if($tw_list):
		$tw_list = strtolower(sanitize_file_name( $tw_list ));
	endif;
	if(empty($tw_search)):
		$possibledividers = array(' ',';',',');
		$rt_namesarray = false;
		foreach($possibledividers as $possibledivider):
			if(strpos($tw_screen_name,$possibledivider) !== false ):
				$rt_namesarray = explode(' ',$tw_screen_name);
				$tw_search = 'from:'.implode(' OR from:',$rt_namesarray);
			endif;
		endforeach;	
	else:
		$tw_search = trim($tw_search);
	endif;
	$cacheoption = get_option('rotatingtweets-api-settings');
	if(!isset($cacheoption['cache_delay'])):
		$cache_delay = 120;
	else:
		$cache_delay = max(60,intval($cacheoption['cache_delay']));
	endif;
	if($tw_include_rts != 1) $tw_include_rts = 0;
	if($tw_exclude_replies != 1) $tw_exclude_replies = 0;
	
	# Get the option strong
	if($tw_search) {
		$stringname = 'search-'.$tw_include_rts.$tw_exclude_replies.'-'.sanitize_file_name($tw_search);
	} elseif ($tw_get_favorites) {
		$stringname = $tw_screen_name.$tw_include_rts.$tw_exclude_replies.'favorites';
	} elseif ($tw_list) {
		$stringname = $tw_screen_name.$tw_include_rts.$tw_exclude_replies.'list-'.$tw_list;
	} else {
		$stringname = $tw_screen_name.$tw_include_rts.$tw_exclude_replies;
	}
	$optionname = "rotatingtweets-cache";
	$option = get_option($optionname);
	# Attempt to deal with 'Cannot use string offset as an array' error
	$timegap = $cache_delay + 1;
	if(is_array($option)):
		if(WP_DEBUG):
			echo "\n<!-- var option is an array -->";
		endif;
		if(isset($option[$stringname]['json'][0])):
			if(WP_DEBUG) echo "<!-- option[$stringname] exists -->";
			if(is_array($option[$stringname]['json'][0])):
				$latest_json = $option[$stringname]['json'];
				$latest_json_date = $option[$stringname]['datetime'];
				$timegap = time()-$latest_json_date;
				if(WP_DEBUG):
					echo "<!-- option[$stringname]['json'][0] is an array - $timegap seconds since last load -->";
				endif;
			elseif(is_object($option[$stringname]['json'][0])):
				if(WP_DEBUG) echo "<!-- option[$stringname]['json'][0] is an object -->";
				unset($option[$stringname]);
			else:
				if(WP_DEBUG) echo "<!-- option[$stringname]['json'][0] is neither an object nor an array! -->";
				unset($option[$stringname]);
			endif;
		elseif(WP_DEBUG):
			echo "<!-- option[$stringname] does not exist -->";
		endif;
	else:
		if(WP_DEBUG):
			echo "\n<!-- var option is NOT an array -->";
		endif;
		unset($option);
	endif;
	# Checks if it is time to call Twitter directly yet or if it should use the cache
	if($timegap > $cache_delay):
		$apioptions = array('screen_name'=>$tw_screen_name,'include_entities'=>1,'count'=>40,'include_rts'=>$tw_include_rts,'exclude_replies'=>$tw_exclude_replies);
		if($tw_search) {
			$apioptions['q']=$tw_search;
//			$apioptions['result_type']='recent';
			$twitterdata = rotatingtweets_call_twitter_API('search/tweets',$apioptions);
		} elseif($tw_get_favorites) {
			$twitterdata = rotatingtweets_call_twitter_API('favorites/list',$apioptions);
		} elseif($tw_list) {
			unset($apioptions['screen_name']);
			$apioptions['slug']=$tw_list;
			$apioptions['owner_screen_name']=$tw_screen_name;
			$twitterdata = rotatingtweets_call_twitter_API('lists/statuses',$apioptions);
		} else {
			$twitterdata = rotatingtweets_call_twitter_API('statuses/user_timeline',$apioptions);
		}
		if(!is_wp_error($twitterdata)):
			$twitterjson = json_decode($twitterdata['body'],TRUE);
			if(WP_DEBUG):
				$rt_time_taken = number_format(microtime(true)-$rt_starttime,4);
				echo "<!-- Rotating Tweets - got new data - time taken: $rt_time_taken seconds -->";
			endif;
		else:
			set_transient('rotatingtweets_wp_error',$twitterdata->get_error_messages(), 120);
		endif;
	elseif(WP_DEBUG):
		$rt_time_taken = number_format(microtime(true)-$rt_starttime,4);
		echo "<!-- Rotating Tweets - used cache - ".($cache_delay - $timegap)." seconds remaining  - time taken: $rt_time_taken seconds -->";
	endif;
	# Checks for errors in the reply
	if(!empty($twitterjson['errors'])):
		# If there's an error, reset the cache timer to make sure we don't hit Twitter too hard and get rate limited.
//		print_r($twitterjson);
		if( $twitterjson['errors'][0]['code'] == 88 ):
			$rate = rotatingtweets_get_rate_data();
			if($rate && $rate['remaining_hits'] == 0):
				$option[$stringname]['datetime']= $rate['reset_time_in_seconds'] - $cache_delay + 1;
				update_option($optionname,$option);
			else:
				$option[$stringname]['datetime']=time();
				update_option($optionname,$option);
			endif;
		else:
			$option[$stringname]['datetime']=time();
			update_option($optionname,$option);
		endif;
	elseif(!empty($twitterjson['error'])):
		# If Twitter is being rate limited, delays the next load until the reset time
		# For some reason the rate limiting error has a different error variable!
		$rate = rotatingtweets_get_rate_data();
		if($rate && $rate['remaining_hits'] == 0):
			$option[$stringname]['datetime']= $rate['reset_time_in_seconds'] - $cache_delay + 1;
			update_option($optionname,$option);
		endif;
	elseif(!empty($twitterjson)):
		unset($firstentry);
		if(isset($twitterjson['statuses'])):
			if(WP_DEBUG):
				echo "<!-- using [statuses] -->";
			endif;
			$twitterjson = $twitterjson['statuses'];
		elseif(isset($twitterjson['results'])):
			if(WP_DEBUG):
				echo "<!-- using [results] -->";
			endif;
			$twitterjson = $twitterjson['results'];
		endif;
		if(is_array($twitterjson) && isset($twitterjson[0] )) $firstentry = $twitterjson[0];
		if(!empty($firstentry['text'])):
			$latest_json = rotatingtweets_shrink_json($twitterjson);
			$option[$stringname]['json']=$latest_json;
			$option[$stringname]['datetime']=time();
			if(WP_DEBUG):
				echo "<!-- Storing cache entry for $stringname in $optionname -->";
			endif;
			update_option($optionname,$option);
		endif;
	endif;
	if(isset($latest_json)):
		return($latest_json);
	else:
		return;
	endif;
}
function rotatingtweets_shrink_json($json) {
	$return = array();
	foreach($json as $item):
		$return[]=rotatingtweets_shrink_element($item);
	endforeach;
	if(WP_DEBUG):
		$startsize = strlen(json_encode($json));
		$endsize = strlen(json_encode($return));
		$shrink = (1-$endsize/$startsize)*100;
		echo  "<!-- Cachesize shrunk by ".number_format($shrink)."% -->";
	endif;
	return($return);
}
function rotatingtweets_shrink_element($json) {
	global $args;
	$rt_top_elements = array('text','retweeted_status','user','entities','source','id_str','created_at');
	$return = array();
	foreach($rt_top_elements as $rt_element):
		if(isset($json[$rt_element])):
			switch($rt_element) {
			case "user":
				$return[$rt_element]=rotatingtweets_shrink_user($json[$rt_element]);
				break;
			case "entities":
				$return[$rt_element]=rotatingtweets_shrink_entities($json[$rt_element]);
				break;
			case "retweeted_status":
				$return[$rt_element]=rotatingtweets_shrink_element($json[$rt_element]);
				break;
			default:
				if(isset($args['no_emoji']) && $args['no_emoji']):
					$before='/\\p{C}/u'; # Removed all 'other' characters - http://php.net/manual/en/regexp.reference.unicode.php
					$after='';
					$json[$rt_element] = str_replace($before,$after,$json[$rt_element]);
				endif;		
/*	Experiment to deal with problem caused by emoji crashing a poorly configured database
				if(function_exists("mb_convert_encoding")):
					$return[$rt_element]=mb_convert_encoding($json[$rt_element], "UTF-8");
				else:
*/
				$return[$rt_element]=$json[$rt_element];
//				endif;
				break;
			};
		endif;
	endforeach;
	return($return);
}
function rotatingtweets_shrink_user($user) {
	$rt_user_elements = array('screen_name','id','name','profile_image_url_https','profile_image_url');
	$return = array();
	foreach($rt_user_elements as $rt_element):
		if(isset($user[$rt_element])) $return[$rt_element]=$user[$rt_element];
	endforeach;
	return($return);
}
function rotatingtweets_shrink_entities($json) {
	$rt_entity_elements = array('urls','media','user_mentions');
	$return = array();
	foreach($rt_entity_elements as $rt_element):
		if(isset($json[$rt_element])) $return[$rt_element]=$json[$rt_element];
	endforeach;
	return($return);
}

# Gets the rate limiting data to see how long it will be before we can tweet again
function rotatingtweets_get_rate_data() {
//	$callstring = "http://api.twitter.com/1/account/rate_limit_status.json";
//	$command = 'account/rate_limit_status';
	if(WP_DEBUG) echo "<!-- Retrieving Rate Data \n";
	$ratedata = rotatingtweets_call_twitter_API('application/rate_limit_status',array('resources'=>'statuses'));
//	$ratedata = wp_remote_request($callstring);
	if(!is_wp_error($ratedata)):
		$rate = json_decode($ratedata['body'],TRUE);
		if(isset($rate['resources']['statuses']['/statuses/user_timeline']['limit']) && $rate['resources']['statuses']['/statuses/user_timeline']['limit']>0):
			$newrate['hourly_limit']=$rate['resources']['statuses']['/statuses/user_timeline']['limit'];
			$newrate['remaining_hits']=$rate['resources']['statuses']['/statuses/user_timeline']['remaining'];
			$newrate['reset_time_in_seconds']=$rate['resources']['statuses']['/statuses/user_timeline']['reset'];
			if(WP_DEBUG):
				print_r($newrate);
				echo "\n -->";
			endif;
			return($newrate);
		else:
			if(WP_DEBUG):
				print_r($rate);
				echo "\n -->";
			endif;
			return($rate);
		endif;
	else:
		set_transient('rotatingtweets_wp_error',$ratedata->get_error_messages(), 120);
		return(FALSE);
	endif;
}

# Gets the language options
# Once a day finds out what language options Twitter has.  If there's any issue, pushes back the next attempt by another day.
function rotatingtweets_get_twitter_language() {
	$cache_delay = 60*60*24;
	$fallback = array ('id','da','ru','msa','ja','no','ur','nl','fa','hi','de','ko','sv','tr','fr','it','en','fil','pt','he','zh-tw','fi','pl','ar','es','hu','th','zh-cn');
	$optionname = 'rotatingtweets-twitter-languages';
	$option = get_option($optionname);
	# Attempt to deal with 'Cannot use string offset as an array' error
	if(is_array($option)):
		$latest_languages = $option['languages'];
		$latest_date = $option['datetime'];
		$timegap = time()-$latest_date;
	else:
		$latest_languages = $fallback;
		$timegap = $cache_delay + 1;
		$option['languages'] = $fallback;
		$option['datetime'] = time();
	endif;
	if($timegap > $cache_delay):
//		$callstring = "https://api.twitter.com/1/help/languages.json";
//		$twitterdata = wp_remote_request($callstring);
		if(WP_DEBUG) echo "<!-- Retrieving Twitter Language Options -->";
		$twitterdata = rotatingtweets_call_twitter_API('help/languages');
		if(!is_wp_error($twitterdata)):
			$twitterjson = json_decode($twitterdata['body'],TRUE);
			if(!empty($twitterjson['errors'])||!empty($twitterjson['error'])):
				# If there's an error, reset the cache timer to make sure we don't hit Twitter too hard and get rate limited.
				$option['datetime']=time();
				update_option($optionname,$option);
			else:
				# If there's regular data, then update the cache and return the data
				$latest_languages = array();
				if(is_array($twitterjson)):
					foreach($twitterjson as $langarray):
						$latest_languages[] = $langarray['code'];
					endforeach;
				endif;
				if(!empty($latest_languages)):
					$option['languages']=$latest_languages;
					$option['datetime']=time();
					update_option($optionname,$option);
					if(WP_DEBUG) echo "<!-- ".count($option['languages'])." language options successfully retrieved -->";
				endif;
			endif;
		else:
			$option['datetime']=time();
			update_option($optionname,$option);
		endif;
	endif;
	if(empty($latest_languages)) $latest_languages = $fallback;
	return($latest_languages);
}

# This function is used for debugging what happens when the site is rate-limited - best not used otherwise!
function rotatingtweets_trigger_rate_limiting() {
//	$callstring = "http://api.twitter.com/1/statuses/user_timeline.json?screen_name=twitter";
	$apidata = array('screen_name'=>'twitter');
	for ($i=1; $i<150; $i++) {
//		$ratedata = wp_remote_request($callstring);
		$ratedata = rotatingtweets_call_twitter_API('statuses/user_timeline',$apidata);
	}
}

# Displays the tweets
function rotating_tweets_display($json,$args,$print=TRUE) {
	unset($result);
	$tweet_count = max(1,intval($args['tweet_count']));
	$timeout = max(intval($args['timeout']),0);
	$defaulturllength = 29;
	if(isset($args['url_length'])):
		$urllength = intval($args['url_length']);
		if($urllength < 1):
			$urllength = $defaulturllength;
		endif;
	elseif(isset($args['shorten_links']) && $args['shorten_links']==1 ): 
		$urllength = 20;
	else:
		$urllength = $defaulturllength;
	endif;
	if(isset($args['speed'])):
		$speed = max(100,intval($args['speed']));
	else:
		$speed = 1000;
	endif;
	# Check that the rotation type is valid. If not, leave it as 'scrollUp'
	$rotation_type = 'scrollUp';
	# Get Twitter language string
	$rtlocale = strtolower(get_locale());
	$rtlocaleMain = explode('_',$rtlocale);
	$possibleOptions = rotatingtweets_get_twitter_language();
	if(in_array($rtlocale,$possibleOptions)):
		$twitterlocale = $rtlocale;
	elseif(in_array($rtlocaleMain[0],$possibleOptions)):
		$twitterlocale = $rtlocaleMain[0];
	else:
		# Default
		$twitterlocale = 'en';
	endif;
	# Now get the possible rotationgs that are permitted
	$api = get_option('rotatingtweets-api-settings');
	$possibleRotations = rotatingtweets_possible_rotations();
	foreach($possibleRotations as $possibleRotation):
		if(strtolower($args['rotation_type']) == strtolower($possibleRotation)) $rotation_type = $possibleRotation;
	endforeach;
	# Create an ID that has all the relevant info in - rotation type and speed of rotation
	$id = uniqid('rotatingtweets_'.$timeout.'_'.$rotation_type.'_');
	$result = '';
	$nextprev = '';
	# Put in the 'next / prev' buttons - although not very styled!
	if(isset($args['show_meta_prev_next']) && $args['show_meta_prev_next']):
		$nextprev = '<a href="#" class="'.$id.'_rtw_prev rtw_prev">'.wp_kses_post($args['prev']).'</a>'.wp_kses_post($args['middot']).'<a href="#" class="'.$id.'_rtw_next rtw_next">'.wp_kses_post($args['next']).'</a>';
		if(strtolower($args['np_pos'])=='top'):
			$result .= '<div class="rotatingtweets_nextprev">'.$nextprev.'</div>';
		endif;
	endif;
	if(isset($args['no_rotate']) && $args['no_rotate']):
		$rotclass = 'norotatingtweets';
	else:
		$rotclass = 'rotatingtweets';
	endif;
	# Now set all the version 2 options
	$v2string = '';
	if( strtolower(get_stylesheet()) == 'magazino' || isset($api['jquery_cycle_version']) && $api['jquery_cycle_version'] == 2):
		$v2options = array(
			'auto-height' => 'calc',
			'fx' => $rotation_type,
			'pause-on-hover' => 'true',
			'timeout' => $timeout,
			'speed' => $speed,
			'easing' => 'swing',
			'slides'=> 'div.rotatingtweet'
		);
		if(isset($args['show_meta_prev_next']) && $args['show_meta_prev_next']):
			$v2options['prev'] = '.'.$id.'_rtw_prev';
			$v2options['next'] = '.'.$id.'_rtw_next';
		endif;
		if(! WP_DEBUG) $v2options['log'] = 'false';
		if($rotation_type == 'carousel'):
			if(empty($args['carousel_horizontal'])):
				$v2options['carousel-vertical'] = true;
				if(isset($args['carousel_count'])):
					$v2options['carousel-visible'] = max(2,intval($args['carousel_count']));
				else:
					$v2options['carousel-visible'] = 3;
				endif;
			else:
				if(isset($args['carousel_count'])):
					$v2options['carousel-visible'] = max(2,intval($args['carousel_count']));
				endif;
				if(isset($args['carousel_responsive'])):
					$v2options['carousel-fluid'] = true;
				endif;
			endif;
		endif;
		$v2stringelements = array();
		foreach ($v2options as $name => $value) {
			$v2stringelements[] = ' data-cycle-'.$name.'="'.$value.'"';
		}
		$v2string = implode(' ',$v2stringelements);
	endif;
	# Now finalise things
	if(WP_DEBUG):
		$result .= "\n<div class='$rotclass wp_debug rotatingtweets_format_".+intval($args['official_format'])."' id='$id'$v2string>";
	else:
		$result .= "\n<div class='$rotclass rotatingtweets_format_".+intval($args['official_format'])."' id='$id'$v2string>";
	endif;
	$error = get_option('rotatingtweets_api_error');
	if(!empty($error)):
		$result .= "\n<!-- ".esc_html($error[0]['type'])." error: ".esc_html($error[0]['code'])." - ".esc_html($error[0]['message'])." -->";
	endif;
	if(empty($json)):
		$result .= "\n\t<div class = 'rotatingtweet'><p class='rtw_main'>". __('Problem retrieving data from Twitter','rotatingtweets'). "</p></div>";
		if(!empty($error)):
			$result .= "\n<div class = 'rotatingtweet' style='display:none'><p class='rtw_main'>".sprintf(__('%3$s error code: %1$s - %2$s','rotatingtweets'), esc_html($error[0]['code']), esc_html($error[0]['message']),esc_html($error[0]['type'])). "</p></div>";
			switch($error[0]['code']) {
				case 88:
					$rate = rotatingtweets_get_rate_data();
					# Check if the problem is rate limiting
					$result .= "\n\t<div class = 'rotatingtweet' style='display:none'><p class='rtw_main'>". sprintf(__('This website is currently <a href=\'%s\'>rate-limited by Twitter</a>.','rotatingtweets'),'https://dev.twitter.com/docs/rate-limiting-faq') . "</p></div>";
					if(isset($rate['hourly_limit']) && $rate['hourly_limit']>0 && $rate['remaining_hits'] == 0):
						$waittimevalue = intval(($rate['reset_time_in_seconds'] - time())/60);
						$waittime = sprintf(_n('Next attempt to get data will be in %d minute','Next attempt to get data will be in %d minutes',$waittimevalue,'rotatingtweets'),$waittimevalue);
						if($waittimevalue == 0) $waittime = __("Next attempt to get data will be in less than a minute",'rotatingtweets');
						$result .= "\n\t<div class = 'rotatingtweet' style='display:none'><p class='rtw_main'>{$waittime}.</p></div>";
					endif;
					break;
				case 32:
					$result .= "\n\t<div class = 'rotatingtweet' style='display:none'><p class='rtw_main'>". sprintf(__('Please check your <a href=\'%s\'>Rotating Tweets settings</a>.','rotatingtweets'),admin_url().'options-general.php?page=rotatingtweets')."</p></div>";
					break;
				case 34:
					$result .= "\n\t<div class = 'rotatingtweet' style='display:none'><p class='rtw_main'>". __('Please check the Twitter screen name or list slug in the widget or shortcode.','rotatingtweets')."</p></div>";
					break;
				default:
					switch($error[0]['type']) {
						case 'Twitter':
							$result .= "\n\t<div class = 'rotatingtweet' style='display:none'><p class='rtw_main'>". sprintf(__('Please check the Twitter name in the widget or shortcode, <a href=\'%2$s\'>Rotating Tweets settings</a> or the <a href=\'%1$s\'>Twitter API status</a>.','rotatingtweets'),'https://dev.twitter.com/status',admin_url().'options-general.php?page=rotatingtweets')."</p></div>";
							break;
						case 'Wordpress':
							$result .= "\n\t<div class = 'rotatingtweet' style='display:none'><p class='rtw_main'>". sprintf(__('Please check your PHP and server settings.','rotatingtweets'),'https://dev.twitter.com/status',admin_url().'options-general.php?page=rotatingtweets')."</p></div>";
							break;
						default:
							$result .= "\n\t<div class = 'rotatingtweet' style='display:none'><p class='rtw_main'>". sprintf(__('Please check the Twitter name in the widget or shortcode, <a href=\'%2$s\'>Rotating Tweets settings</a> or the <a href=\'%1$s\'>Twitter API status</a>.','rotatingtweets'),'https://dev.twitter.com/status',admin_url().'options-general.php?page=rotatingtweets')."</p></div>";
							break;
					}
				break;
			}
		elseif(!empty($args['search'])):
			$result .= "\n<div class = 'rotatingtweet' style='display:none'><p class='rtw_main'>".sprintf(__('No Tweet results for search <a href="%2$s"><strong>%1$s</strong></a>','rotatingtweets'),esc_html($args['search']),esc_url('https://twitter.com/search?q='.urlencode($args['search']))). "</p></div>";
		endif;
	else:
		$tweet_counter = 0;
		/*
		$rate = rotatingtweets_get_rate_data();
		# Check if the problem is rate limiting
		if($rate['hourly_limit']>0 && $rate['remaining_hits'] == 0):
			$waittimevalue = intval(($rate['reset_time_in_seconds'] - time())/60);
			$result .= "<!-- Rate limited - ";
			$result .= sprintf(_n('Next attempt to get data will be in %d minute','Next attempt to get data will be in %d minutes',$waittimevalue,'rotatingtweets'),$waittimevalue)." -->";
		endif;
		*/
		# Set up the link treatment
		if(isset($args['links_in_new_window']) && !empty($args['links_in_new_window']) ) {
			$targetvalue = ' target="_blank" ';
		} else {
			$targetvalue = '';
		}
		if(count($json)==1):
			$firstelement = reset($json);
			$json[] = $firstelement;
		endif;
		if(isset($args['offset']) && $args['offset']>=1 && count($json)>1):
			for ($i = 1; $i <= $args['offset']; $i++) {
				$firstelement = array_shift($json);
				array_push($json,$firstelement);
			}
		endif;
		foreach($json as $twitter_object):
			if ( ! (  ($args['exclude_replies'] && isset($twitter_object['text']) && substr($twitter_object['text'],0,1)=='@') ||  (!$args['include_rts'] && isset($twitter_object['retweeted_status']))  )  ):
//			if (! ($args['exclude_replies'] && isset($twitter_object['text']) && substr($twitter_object['text'],0,1)=='@')): // This works to exlude replies
//			if (! (!$args['include_rts'] && isset($twitter_object['retweeted_status'])) ) : // This works to exclude retweets
				$tweet_counter++;
				if($tweet_counter <= $tweet_count):
					if($tweet_counter == 1 || ( isset($args['no_rotate']) && $args['no_rotate'] ) || $rotation_type == 'carousel' ):
						$result .= "\n\t<div class = 'rotatingtweet'>";
					else:
						$result .= "\n\t<div class = 'rotatingtweet' style='display:none'>";				
					endif;
					# Now to process the text
					// print_r($twitter_object);
					$main_text = $twitter_object['text'];
					if(!empty($main_text)):
						$user = $twitter_object['user'];
						$tweetuser = $user;
						# Now the substitutions
						$entities = $twitter_object['entities'];
						# Fix up retweets, links, hashtags and use names
						unset($before);
						unset($after);
						unset($retweeter);
						# First clean up the retweets
						if(isset($twitter_object['retweeted_status'])):
							$rt_data = $twitter_object['retweeted_status'];
						else:
							unset($rt_data);
						endif;
						if(!empty($rt_data)):
							$rt_user = $rt_data['user'];
							// The version numbers in this array remove RT and use the original text
							$rt_replace_array = array(1,2,3);
							if(in_array($args['official_format'],$rt_replace_array) || $args['official_format'] === 'custom' ):
								$main_text = $rt_data['text'];
								$retweeter = $user;
								$tweetuser = $rt_user;
							else:
								$main_text = "RT @".$rt_user['screen_name'] . " " . $rt_data['text'];
							endif;
							$before[] = "*@".$rt_user['screen_name']."\b*i";
							$after[] = rotatingtweets_user_intent($rt_user,$twitterlocale,'screen_name',$targetvalue);
							$entities = $rt_data['entities'];
						endif;
						# First the user mentions
						if(isset($args['tweet_length']) && $args['tweet_length']>0 ):
							$tweetwords = explode(" ",$main_text);
							$new_main_text = array_shift($tweetwords);
							foreach($tweetwords as $tweetword):
								if(strlen($new_main_text." ".$tweetword)<$args['tweet_length']):
									$new_main_text .= " ".$tweetword;
									if(WP_DEBUG):
										echo "<!-- adding '$tweetword' -->";
									endif;
								else:
									$new_main_text .= "&hellip;";
									if(WP_DEBUG):
										echo "<!-- finishing and adding '&hellip;' -->";
									endif;
									break;
								endif;
							endforeach;
							$main_text = $new_main_text;
						endif;
						if(isset($entities['user_mentions'])):
							$user_mentions = $entities['user_mentions'];
						else:
							unset($user_mentions);
						endif;
						if(!empty($user_mentions)):
							foreach($user_mentions as $user_mention):
								$before[] = "*@".$user_mention['screen_name']."\b*i";
								$after[] = rotatingtweets_user_intent($user_mention,$twitterlocale,'screen_name',$targetvalue);
							endforeach;
							# Clearing up duplicates to avoid strange result (possibly risky?)
							$before = array_unique($before);
							$after = array_unique($after);
						endif;
						# Now the URLs
						if(isset($entities['urls'])):
							$urls = $entities['urls'];
						else:
							unset($urls);
						endif;
						if(!empty($urls)):
							foreach($urls as $url):
								$before[] = "*".$url['url']."*";
								$displayurl = $url['display_url'];
								if(strlen($displayurl)>$urllength):
									# PHP sometimes has a really hard time with unicode characters - this one removes the ellipsis
									$displayurl = str_replace(json_decode('"\u2026"'),"",$displayurl);
									$displayurl = substr($displayurl,0,$urllength)."&hellip;";
								endif;
								$after[] = "<a href='".$url['url']."' title='".$url['expanded_url']."'".$targetvalue.">".esc_html($displayurl)."</a>";
							endforeach;
						endif;
						if(isset($entities['media'])):
							$media = $entities['media'];
							$media_data = $media[0];
							if(isset($args['show_media']) && $args['show_media']):
								$alt = esc_html(trim(str_replace($media_data['url'],'',strip_tags($main_text))));
								$show_media = "<a href='{$media_data['url']}' title='{$alt}'><img src='{$media_data['media_url_https']}' alt='{$alt}' /></a>";
							endif;
						else:
							unset($media);
							$show_media='';
						endif;
						if(!empty($media)):
							foreach($media as $medium):
								$before[] = "*".$medium['url']."*";
								$displayurl = $medium['display_url'];
								if(strlen($displayurl)>$urllength):
									$displayurl = str_replace(json_decode('"\u2026"'),"",$displayurl);
									$displayurl = substr($displayurl,0,$urllength)."&hellip;";
								endif;
								$after[] = "<a href='".$medium['url']."' title='".$medium['expanded_url']."'".$targetvalue.">".esc_html($displayurl)."</a>";
							endforeach;			
						endif;
	//					$before[]="%#([0-9]*[\p{L}a-zA-Z_]+\w*)%";
						# This is designed to find hashtags and turn them into links...
						$before[]="%#\b(\d*[^\d\s[:punct:]]+[^\s[:punct:]]*)%u";
						$after[]='<a href="http://twitter.com/search?q=%23$1&amp;src=hash" title="#$1"'.$targetvalue.'>#$1</a>';
						# Attempts to remove emoji - see http://www.regular-expressions.info/unicode.html https://en.wikipedia.org/wiki/Emoji
						if(isset($args['no_emoji']) && $args['no_emoji']):
							// $before[]='/\\p{InGreek_Extended}/u'; #Not supported by PCRE http://php.net/manual/en/regexp.reference.unicode.php
							$before[]='/\\p{C}/u'; # Removed all 'other' characters - http://php.net/manual/en/regexp.reference.unicode.php
							$after[]='';
						endif;						
						if( defined('DB_CHARSET') && strtoupper(DB_CHARSET) !='UTF-8' && strtoupper(DB_CHARSET)!= 'UTF8' && strtoupper(DB_CHARSET)!= '' ):
							$new_text = iconv("UTF-8",DB_CHARSET . '//TRANSLIT',$main_text);
							if(empty($main_text)):
								if(WP_DEBUG):
									echo "<!-- iconv to ".DB_CHARSET." failed -->";
								endif;
							else:
								$main_text = $new_text;
							endif;
						endif;
						$new_text = preg_replace($before,$after,$main_text);
						if(empty($new_text)):
							if(WP_DEBUG):
								echo "<!-- preg_replace failed -->";
							endif;
							array_pop($before);
							$before[]="%#\b(\d*[^\d\s[:punct:]]+[^\s[:punct:]]*)%";
							$new_text = preg_replace($before,$after,$main_text);
							if(empty($new_text)):
								if(WP_DEBUG):
									echo "<!-- simplified preg_replace failed -->";
								endif;
							else:
								$main_text = $new_text;
							endif;
						else:
							$main_text = $new_text;
						endif;
						if(isset($args['link_all_text']) && $args['link_all_text']):
							$new_text = rotatingtweets_user_intent($tweetuser,$twitterlocale,$main_text,$targetvalue);
							if(empty($new_text)):
								if(WP_DEBUG):
									echo "<!-- linking all text failed -->";
								endif;
							else:
								$main_text = $new_text;
							endif;
						endif;
						// Attempt to deal with a very odd situation where no text is appearing
						if(empty($main_text)):
							if(WP_DEBUG):
								echo "<!-- Main Text Empty - Debug Data: \n";print_r($before);print_r($after);print_r($args);echo "\n-->\n";
							endif;
							$main_text = $twitter_object['text'];
						endif;
						# Now for the different display options
						switch ($args['official_format']) {
						case 'custom':
							# This first option lets you use your own function to display tweets
							if (function_exists('rotatingtweets_display_override')) {
								if(!isset($retweeter)) $retweeter = '';
								$result .= rotatingtweets_display_override(	
									$args, $tweetuser, $main_text, $twitter_object, $twitterlocale, $targetvalue, $retweeter, $show_media, $nextprev );
								break;
							}
						case 0:
							# This is the original Rotating Tweets display routine
							$result .= "\n\t\t<p class='rtw_main'>$main_text</p>";
							$meta = '';
							if(isset($args['show_media']) && !empty($show_media)):
								$result .= "<div class='rtw_media'>$show_media</div>";
							endif;
							if($args['show_meta_timestamp']):
								$meta .= rotatingtweets_timestamp_link($twitter_object,'default',$targetvalue);
							endif;
							if($args['show_meta_screen_name']):
								if(!empty($meta)) $meta .= ' ';
								if(isset($args['screen_name_plural'])):
									$screennamecount = max(1,$args['screen_name_plural']+1);
								else:
									$screennamecount = 1;
								endif;
								$meta .= sprintf(_n('from <a href=\'%1$s\' title=\'%2$s\'>%2$s\'s Twitter</a>','from <a href=\'%1$s\' title=\'%2$s\'>%2$s\' Twitter</a>',$screennamecount,'rotatingtweets'),'https://twitter.com/intent/user?user_id='.$user['id'],$user['name']);
							endif;
							if($args['show_meta_via']):
								if(!empty($meta)) $meta .= ' ';
								$meta .=sprintf(__("via %s",'rotatingtweets'),$twitter_object['source']);
							endif;
							if($args['show_meta_reply_retweet_favorite']):
								if(!empty($meta)) $meta .= ' &middot; ';
								$meta .= rotatingtweets_intents($twitter_object,$twitterlocale, 0,$targetvalue);
							endif;

							if(isset($args['show_meta_prev_next']) && $args['show_meta_prev_next'] && $args['np_pos']=='tweets'):
								if(!empty($meta)) $meta .= ' &middot; ';
								$meta .= $nextprev;
							endif;

							if(!empty($meta)) $result .= "\n\t\t<p class='rtw_meta'>".ucfirst($meta)."</p>";
							break;
						case 1:
							# This is an attempt to replicate the original Tweet
							$result .= "\n\t<div class='rtw_info'>";
							$result .= "\n\t\t<div class='rtw_twitter_icon'><img src='".plugins_url('images/twitter-bird-16x16.png', __FILE__)."' width='16' height='16' alt='".__('Twitter','rotatingtweets')."' /></div>";
							$result .= "\n\t\t<div class='rtw_icon'>".rotatingtweets_user_intent($tweetuser,$twitterlocale,'icon',$targetvalue)."</div>";
							$result .= "\n\t\t<div class='rtw_name'>".rotatingtweets_user_intent($tweetuser,$twitterlocale,'name',$targetvalue)."</div>";
							$result .= "\n\t\t<div class='rtw_id'>".rotatingtweets_user_intent($tweetuser,$twitterlocale,'screen_name',$targetvalue)."</div>";
							$result .= "\n\t</div>";
							$result .= "\n\t<p class='rtw_main'>".$main_text."</p>";
							if(isset($args['show_media']) && !empty($show_media)):
								$result .= "<div class='rtw_media'>$show_media</div>";
							endif;
							$result .= "\n\t<div class='rtw_meta'><div class='rtw_intents'>".rotatingtweets_intents($twitter_object,$twitterlocale, 1,$targetvalue).'</div>';
							$result .= "\n\t<div class='rtw_timestamp'>".rotatingtweets_timestamp_link($twitter_object,'long',$targetvalue);
							if(isset($retweeter)) {
								$result .= " &middot; </div>".rotatingtweets_user_intent($retweeter,$twitterlocale,sprintf(__('Retweeted by %s','rotatingtweets'),$retweeter['name']),$targetvalue);
							} else {
								$result .=  "</div>";
							}
							if(isset($args['show_meta_prev_next']) && $args['show_meta_prev_next'] && $args['np_pos']=='tweets'):
								$result .= " &middot; ".$nextprev;
							endif;
							$result .= "\n</div>";
							break;
						case 2:
							# This is a slightly adjusted version of the original tweet - designed for wide boxes - consistent with Twitter guidelines
							$result .= "\n\t\t<div class='rtw_wide'>";
							$result .= "\n\t\t<div class='rtw_wide_icon'>".rotatingtweets_user_intent($tweetuser,$twitterlocale,'icon',$targetvalue)."</div>";
							$result .= "\n\t\t<div class='rtw_wide_block'><div class='rtw_info'>";
							$result .= "\n\t\t\t<div class='rtw_time_short'>".rotatingtweets_timestamp_link($twitter_object,'short',$targetvalue).'</div>';
							$result .= "\n\t\t\t<div class='rtw_name'>".rotatingtweets_user_intent($tweetuser,$twitterlocale,'name',$targetvalue)."</div>";
							$result .= "\n\t\t\t<div class='rtw_id'>".rotatingtweets_user_intent($tweetuser,$twitterlocale,'screen_name',$targetvalue)."</div>";
							$result .= "\n\t\t</div>";
							$result .= "\n\t\t<p class='rtw_main'>".$main_text."</p>";
							if(isset($args['show_media']) && !empty($show_media)):
								$result .= "<div class='rtw_media'>$show_media</div>";
							endif;
	//						$result .= "\n\t\t<div class='rtw_meta'><div class='rtw_intents'>".rotatingtweets_intents($twitter_object,$twitterlocale, 1).'</div>';
							if(isset($retweeter)) {
								$result .= "\n\t\t<div class='rtw_rt_meta'>".rotatingtweets_user_intent($retweeter,$twitterlocale,"<img src='".plugins_url('images/retweet_on.png',__FILE__)."' width='16' height='16' alt='".sprintf(__('Retweeted by %s','rotatingtweets'),$retweeter['name'])."' />".sprintf(__('Retweeted by %s','rotatingtweets'),$retweeter['name']),$targetvalue)."</div>";
							}
							$result .= "\n\t\t<div class='rtw_meta'><span class='rtw_expand' style='display:none;'>".__('Expand','rotatingtweets')."</span><span class='rtw_intents'>".rotatingtweets_intents($twitter_object,$twitterlocale, 2,$targetvalue);
							if(isset($args['show_meta_prev_next']) && $args['show_meta_prev_next'] && $args['np_pos']=='tweets'):
								$result .= wp_kses_post($args['middot']).$nextprev;
							endif;
							$result .= "</span></div></div></div>";
							break;
						case 3:
							# This one uses the twitter standard approach for embedding via their javascript API - unfortunately I can't work out how to make it work with the rotating tweet javascript!  If anyone can work out how to calculate the height of a oEmbed Twitter tweet, I will be very grateful! :-)
							$result .= '<blockquote class="twitter-tweet">';
							$result .= "<p>".$main_text."</p>";
							$result .= '&mdash; '.$user['name'].' (@'.$user['screen_name'].') <a href="https://twitter.com/twitterapi/status/'.$twitter_object['id_str'].'" data-datetime="'.date('c',strtotime($twitter_object['created_at'])).'"'.$targetvalue.'>'.date_i18n(get_option('date_format') ,strtotime($twitter_object['created_at'])).'</a>';
							$result .= '</blockquote>';
							break;
						case 4:
							$result .= "\n\t\t<p class='rtw_main'>$main_text</p>";
							$result .= "\n\t<div class='rtw_meta rtw_info'><div class='rtw_intents'>".rotatingtweets_intents($twitter_object,$twitterlocale, 1,$targetvalue).'</div>';
							if($args['show_meta_screen_name']):
								$result .= sprintf(__('from <a href=\'%1$s\' title=\'%2$s\'>%2$s\'s Twitter</a>','rotatingtweets'),'https://twitter.com/intent/user?user_id='.$user['id'],$user['name']).' &middot; ';
							endif;
							$result .= rotatingtweets_timestamp_link($twitter_object,'long',$targetvalue);
							if(isset($args['show_meta_prev_next']) && $args['show_meta_prev_next'] && $args['np_pos']=='tweets'):
								$result .= ' &middot; '.$nextprev;
							endif;
							$result .= "\n</div>";
							break;
						case 5:
							# This is an adjusted Rotating Tweets display routine
							$result .= "\n\t\t<p class='rtw_main'><img src='".plugins_url('images/bird_16_black.png', __FILE__)."' alt='Twitter' />&nbsp;&nbsp; $main_text ";
							$meta = '';
							if($args['show_meta_timestamp']):
								$meta .= rotatingtweets_timestamp_link($twitter_object,'default',$targetvalue);
							endif;
							if($args['show_meta_screen_name']):
								if(!empty($meta)) $meta .= ' ';
								$meta .= sprintf(__('from <a href=\'%1$s\' title=\'%2$s\'>%2$s\'s Twitter</a>','rotatingtweets'),'https://twitter.com/intent/user?user_id='.$user['id'],$user['name']);
							endif;
							if($args['show_meta_via']):
								if(!empty($meta)) $meta .= ' ';
								$meta .=sprintf(__("via %s",'rotatingtweets'),$twitter_object['source']);
							endif;
							if($args['show_meta_reply_retweet_favorite']):
								if(!empty($meta)) $meta .= ' &middot; ';
								$meta .= rotatingtweets_intents($twitter_object,$twitterlocale, 0,$targetvalue);
							endif;
							if(isset($args['show_meta_prev_next']) && $args['show_meta_prev_next'] && $args['np_pos']=='tweets'):
								if(!empty($meta)) $meta .= ' &middot; ';
								$meta .= $nextprev;
							endif;
							if(!empty($meta)) $result .= "\n\t\t<span class='rtw_meta'>".ucfirst($meta)."</span></p>";
							break;
						case 6:
							# This is the original Rotating Tweets display routine - adjusted for a user
							$result .= "\n\t\t<p class='rtw_main'>".rotatingtweets_user_intent($user,$twitterlocale,'blue_bird',$targetvalue).$main_text."</p>";
							$meta = '';
							if($args['show_meta_timestamp']):
								$meta .= rotatingtweets_timestamp_link($twitter_object,'default',$targetvalue);
							endif;
							if($args['show_meta_screen_name']):
								if(!empty($meta)) $meta .= ' ';
								$meta .= sprintf(__('from <a href=\'%1$s\' title=\'%2$s\'>%2$s\'s Twitter</a>','rotatingtweets'),'https://twitter.com/intent/user?user_id='.$user['id'],$user['name']);
							endif;
							if($args['show_meta_via']):
								if(!empty($meta)) $meta .= ' ';
								$meta .=sprintf(__("via %s",'rotatingtweets'),$twitter_object['source']);
							endif;
							if($args['show_meta_reply_retweet_favorite']):
								if(!empty($meta)) $meta .= ' &middot; ';
								$meta .= rotatingtweets_intents($twitter_object,$twitterlocale, 0,$targetvalue);
							endif;

							if(isset($args['show_meta_prev_next']) && $args['show_meta_prev_next'] && $args['np_pos']=='tweets'):
								if(!empty($meta)) $meta .= ' &middot; ';
								$meta .= $nextprev;
							endif;

							if(!empty($meta)) $result .= "\n\t\t<p class='rtw_meta'>".ucfirst($meta)."</p>";
							break;
						}
					else:
						$result .= "\n\t\t<p class='rtw_main'>".__("Problem retrieving data from Twitter.",'rotatingtweets')."</p></div>";
						$result .= "<!-- rotatingtweets plugin was unable to parse this data: ".print_r($json,TRUE)." -->";
						$result .= "\n\t\t<div class = 'rotatingtweet' style='display:none'><p class='rtw_main'>".__("Please check the comments on this page's HTML to understand more.",'rotatingtweets')."</p>";
					endif;
					$result .= "</div>";
				endif;
			endif;
		endforeach;
	endif;
	if(isset($args['show_meta_prev_next']) && $args['show_meta_prev_next'] && $args['np_pos']=='insidebottom'):
		$result .= $nextprev;
	endif;
	$result .= "\n</div>";
	if(isset($args['show_meta_prev_next']) && $args['show_meta_prev_next'] && $args['np_pos']=='bottom'):
		$result .= '<div class="rotatingtweets_nextprev">'.$nextprev.'</div>';
	endif;
/*
	if($args['show_meta_progress_blobs']):
		$result .= "<div id='".$id."_nav' class='rtw_nav'>";
		for ($i = 1; $i <= $tweet_count; $i++) {
			$result .= '<a href="#">&bull;</a> ';
		}
		$result .= "</div>";
	endif;
*/
	if($args['show_follow'] && !empty($args['screen_name']) && !strpos($args['screen_name'],' ') && !strpos($args['screen_name'],',') && !strpos($args['screen_name'],';')):
		$shortenvariables = '';
		if($args['no_show_count']) $shortenvariables = ' data-show-count="false"';
		if($args['no_show_screen_name']) $shortenvariables .= ' data-show-screen-name="false"';
		$followUserText = sprintf(__('Follow @%s','rotatingtweets'),remove_accents(str_replace('@','',$args['screen_name'])));
		$result .= "\n<div class='rtw_follow follow-button'><a href='http://twitter.com/".$args['screen_name']."' class='twitter-follow-button'{$shortenvariables} title='".$followUserText."' data-lang='{$twitterlocale}'>".$followUserText."</a></div>";
	endif;
	rotatingtweets_enqueue_scripts();
	if($print) echo $result;
	return($result);
}
# Load the language files - needs to come after the widget_init line - and possibly the shortcode one too!
function rotatingtweets_init() {
	load_plugin_textdomain( 'rotatingtweets', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'rotatingtweets_init');

function rotatingtweets_possible_rotations($dropbox = FALSE) {
	# Check if we're using jQuery Cycle 1 or 2 - sends back response for validity checking or raw data for a drop down box
	$api = get_option('rotatingtweets-api-settings');
	if(isset($api['jquery_cycle_version']) && $api['jquery_cycle_version']==2):
		if($dropbox):
			$possibleRotations = array (
				'scrollUp' => __('Scroll Up','rotatingtweets'),
				'scrollDown' => __('Scroll Down','rotatingtweets'),
				'scrollLeft' => __('Scroll Left','rotatingtweets'),
				'scrollRight' => __('Scroll Right','rotatingtweets'),
				'fade' => __('Fade','rotatingtweets'),
				'carousel' => __('Carousel','rotatingtweets')
			);
		else:
			$possibleRotations = array('scrollUp','scrollDown','scrollHorz','scrollLeft','scrollRight','toss','scrollVert','fade','carousel');
		endif;
	else:
		if($dropbox):
			$possibleRotations = array (
				'scrollUp' => __('Scroll Up','rotatingtweets'),
				'scrollDown' => __('Scroll Down','rotatingtweets'),
				'scrollLeft' => __('Scroll Left','rotatingtweets'),
				'scrollRight' => __('Scroll Right','rotatingtweets'),
				'fade' => __('Fade','rotatingtweets')
			);
		else:
			$possibleRotations = array('blindX','blindY','blindZ','cover','curtainX','curtainY','fade','fadeZoom','growX','growY','none','scrollUp','scrollDown','scrollLeft','scrollRight','scrollHorz','scrollVert','shuffle','slideX','slideY','toss','turnUp','turnDown','turnLeft','turnRight','uncover','wipe','zoom');
		endif;
	endif;
	return($possibleRotations);
}

function rotatingtweets_enqueue_scripts() {
	wp_enqueue_script( 'jquery' ); 
	# Set the base versions of the strings
	$cyclejsfile = 'js/jquery.cycle.all.min.js';
	$rotatingtweetsjsfile = 'js/rotating_tweet.js';
	# Check for evil plug-ins
	if ( ! function_exists( 'is_plugin_active' ) )
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
//		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active('wsi/wp-splash-image.php')) {
		//plugin is activated
		$dependence = array('jquery','jquery.tools.front');
	} else {
		$dependence = array('jquery');
	}
	# Check if we're using jQuery Cycle 1 or 2
	$api = get_option('rotatingtweets-api-settings');
	if(!isset($api['js_in_footer'])) $api['js_in_footer'] = FALSE;
	$style = strtolower(get_stylesheet());
	$rt_data = get_plugin_data( __FILE__ );
	$rt_cycleversion = $rt_data;
	// Fixes a problem with the magazino template
	if($style == 'magazino' || (isset($api['jquery_cycle_version']) && $api['jquery_cycle_version']==2)):
/*
	'jquery-easing' => 'http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js',
*/			
		if (is_plugin_active('cyclone-slider-2/cyclone-slider.php')):
			$cyclepath = str_replace('rotatingtweets/','cyclone-slider-2/cyclone-slider.php',plugin_dir_path( __FILE__ ));
			$rt_cycleversion = get_plugin_data( $cyclepath );
			$rt_enqueue_script_list = array(
				'jquery-cycle2' => plugins_url('cyclone-slider-2/libs/cycle2/jquery.cycle2.min.js'),
				'jquery-cycle2-carousel' => plugins_url('cyclone-slider-2/libs/cycle2/jquery.cycle2.carousel.min.js'),
				'jquery-cycle2-scrollvert' => plugins_url('cyclone-slider-2/libs/cycle2/jquery.cycle2.scrollVert.min.js'),
				'rotating_tweet' => plugins_url('js/rotatingtweets_v2_cyclone.js', __FILE__)
			);
		else:
			$rt_enqueue_script_list = array(
				'jquery-cycle2-renamed' => plugins_url('js/jquery.cycle2.renamed.js', __FILE__),
				'jquery-cycle2-scrollvert-renamed' => plugins_url('js/jquery.cycle2.scrollVert.renamed.js', __FILE__),
				'jquery-cycle2-carousel-renamed' => plugins_url('js/jquery.cycle2.carousel.renamed.js', __FILE__),
				'rotating_tweet' => plugins_url('js/rotatingtweets_v2.js', __FILE__)
			);
	//		$dependence[]='jquery-effects-core';
		endif;
		foreach($rt_enqueue_script_list as $scriptname => $scriptlocation):
			if( $scriptname == 'rotating_tweet' ):
				$scriptver = $rt_data['Version'];
			else:
				$scriptver = $rt_cycleversion['Version'];
			endif;
			wp_enqueue_script($scriptname,$scriptlocation,$dependence,$scriptver,$api['js_in_footer']);
			$dependence[] = $scriptname;
		endforeach;
	else:
		# Get Stylesheet
		switch ($style):
			case 'bremen_theme':
			case 'zeebizzcard':
	//		case 'zeeStyle':
				wp_dequeue_script( 'zee_jquery-cycle');
				wp_enqueue_script( 'zee_jquery-cycle', plugins_url($cyclejsfile, __FILE__),$dependence,FALSE,$api['js_in_footer']);
				$dependence[]='zee_jquery-cycle';
				break;
			case 'oxygen':
				wp_dequeue_script( 'oxygen_cycle');
				wp_enqueue_script( 'oxygen_cycle', plugins_url($cyclejsfile, __FILE__),$dependence,FALSE,$api['js_in_footer']);
				$dependence[]='oxygen_cycle';
				break;		
			case 'avada':
			case 'avada child':
			case 'avada-child-theme':
			case 'avada child theme':
			case 'a52cars':
				wp_dequeue_script( 'jquery.cycle');
				wp_enqueue_script( 'jquery.cycle', plugins_url($cyclejsfile, __FILE__),$dependence,FALSE,$api['js_in_footer'] );
				$dependence[]='jquery.cycle';
				break;
			default:
				wp_enqueue_script( 'jquery-cycle', plugins_url($cyclejsfile, __FILE__),$dependence,FALSE,$api['js_in_footer'] );
				$dependence[]='jquery-cycle';
				break;
		endswitch;
		wp_enqueue_script( 'rotating_tweet', plugins_url($rotatingtweetsjsfile, __FILE__),$dependence,$rt_data['Version'],$api['js_in_footer'] );
	endif;
}
function rotatingtweets_enqueue_style() {
	wp_enqueue_style( 'rotatingtweets', plugins_url('css/style.css', __FILE__));
	$uploads = wp_upload_dir();
	$personalstyle = array(
		plugin_dir_path(__FILE__).'css/yourstyle.css' => plugins_url('css/yourstyle.css', __FILE__),
		$uploads['basedir'].'/rotatingtweets.css' => $uploads['baseurl'].'/rotatingtweets.css'
	);
	$scriptname = 'rotatingtweet-yourstyle';
	$scriptcounter = 1;
	foreach($personalstyle as $dir => $url):
		if(file_exists( $dir )):
			wp_enqueue_style( $scriptname, $url);
			$scriptname = 'rotatingtweet-yourstyle-'.$scriptcounter;
			$scriptcounter ++;
		endif;
	endforeach;
}
function rotatingtweets_enqueue_admin_scripts($hook) {
	if( 'widgets.php' != $hook ) return;
	wp_enqueue_script( 'jquery' ); 
	wp_enqueue_script( 'rotating_tweet_admin', plugins_url('js/rotating_tweet_admin.js', __FILE__),array('jquery'),FALSE,FALSE );		
}
add_action( 'admin_enqueue_scripts', 'rotatingtweets_enqueue_admin_scripts' );

/*
Forces the inclusion of Rotating Tweets CSS in the header - irrespective of whether the widget or shortcode is in use.  I wouldn't normally do this, but CSS needs to be in the header for HTML5 compliance (at least if the intention is not to break other browsers) - and short-code only pages won't do that without some really time-consuming and complicated code up front to check for this
*/
add_action('wp_enqueue_scripts','rotatingtweets_enqueue_style');
// add_action('wp_enqueue_scripts','rotatingtweets_enqueue_scripts'); // Use this if you are loading the tweet page via ajax
$style = strtolower(get_stylesheet());
if($style == 'gleam'):
	add_action('wp_enqueue_scripts','rotatingtweets_enqueue_scripts');
endif;

// Add the deactivation and uninstallation functions
function rotatingtweets_deactivate() {
	// Gets rid of the cache - but not the settings
	delete_option('rotatingtweets_api_error');
	delete_option('rotatingtweets-cache');
	delete_option('rotatingtweets-twitter-languages');
}
function rotatingtweets_uninstall() {
	// Gets rid of all data stored - including settings
	rotatingtweets_deactivate();
	delete_option('rotatingtweets-api-settings');
}

register_deactivation_hook( __FILE__, 'rotatingtweets_deactivate' );
register_uninstall_hook( __FILE__, 'rotatingtweets_uninstall' );

// Filters that can be used to adjust transports - if you have problems with connecting to Twitter, try commenting in one of the following lines
// From a brilliant post by Sam Wood http://wordpress.org/support/topic/warning-curl_exec-has-been-disabled?replies=6#post-920787
function rotatingtweets_block_transport() { return false; }
// add_filter('use_http_extension_transport', 'rotatingtweets_block_transport');
// add_filter('use_curl_transport', 'rotatingtweets_block_transport');
// add_filter('use_streams_transport', 'rotatingtweets_block_transport');
// add_filter('use_fopen_transport', 'rotatingtweets_block_transport');
// add_filter('use_fsockopen_transport', 'rotatingtweets_block_transport');
?>