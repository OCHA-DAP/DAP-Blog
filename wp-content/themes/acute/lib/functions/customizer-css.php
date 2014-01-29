<?php
/*===================================================================*/               						
/*  THEME CUSTOMIZER STYLES	                							
/*===================================================================*/
if ( !function_exists('Bean_Customize_Variables') ) {
	function Bean_Customize_Variables() {
	
	//VARIABLES
	$body_text_color = get_option('body_text_color');
	$theme_accent_color = get_option('theme_accent_color');
	$secondary_bg_color = get_option('secondary_bg_color');
	
	$footer_bg_color = get_option('footer_bg_color');
	$footer_extra_color = get_option('footer_extra_color');
	$selection_bg_color = get_option('selection_bg_color');
	$selection_text_color = get_option('selection_text_color');
	 
	//SET DEFAULTS
	if( !$body_text_color ) { $body_text_color = '#22272A'; }
	if( !$theme_accent_color ) { $theme_accent_color = '#3AC792'; }
	if( !$secondary_bg_color ) { $secondary_bg_color = '#F3F3F3'; }
	
	if( !$footer_bg_color ) { $footer_bg_color = '#22272A'; }
	if( !$footer_extra_color ) { $footer_extra_color = '#1C1C22'; }
	if( !$selection_bg_color ) { $selection_bg_color = '#F3F3F3'; }
	if( !$selection_text_color ) { $selection_text_color = '#22272A'; }
	?>		
		
		
<style>
p,
body { color:  <?php echo $body_text_color; ?>; }

.bean-tabs .bean-tab,
.bean-toggle .target { color:  <?php echo $body_text_color; ?>!important;}

pre,
code,
.blog .even,
#page-header,
.about-author,
.archive .odd,
.widget_nav_menu,
.search-results .odd,
.flickr_badge_image img,
.bean-dribbble-shots img { background-color:  <?php echo $secondary_bg_color; ?>; }

.flickr_badge_image img,
.bean-dribbble-shots img,
.single-portfolio .entry-content {
	border-color: <?php echo $secondary_bg_color; ?>!important;
}

a:hover,
p a:hover,
blockquote,
blockquote p,
span.required,
#footer a:hover,
.format-quote p,
div.bean-note a,
#filter a.active,
.widget_bean_tweets li span a:hover,
.format-link p a,	
.archives-list ul,
.page-entry-meta a,
.modal .close:hover,
.logged-in-as span a,	
.comment-author a:hover,	
.entry-header h1 a:hover,
.entry-header h2 a:hover,
.entry-header h3 a:hover,
.entry-content p a:hover,
#footer .btn:hover, 
#footer .button:hover, 
#footer button.button:hover, 
#footer .btn[type="submit"]:hover,
#footer .button[type="submit"]:hover,
#footer input[type="button"]:hover, 
#footer input[type="reset"]:hover, 
#footer input[type="submit"]:hover,
.portfolio-meta-list a:hover,
.archives-list ul ,
.format-link .entry-header h2 a,	
.format-quote .entry-header h2 a,
.widget_bean_popular .popular-title a:hover,
#panel .widget_bean_popular .popular-title a:hover { 
	color:<?php echo $theme_accent_color; ?>; 
}

.btn, 
.button, 
.short-btn, 
a.more-link,
.tagcloud a,
a.more-link,
.tagcloud a,
#toTop:hover,
button.button:hover,
.btn[type="submit"], 
input[type="reset"], 
input[type="button"],
input[type="submit"],
.button[type="submit"],
div.jp-play-bar,
#panel .post-thumb,
a.social-icon:hover,
.comment-body .reply a,
div.jp-volume-bar-value,
.entry-content a.more-link,
.flex-control-paging li a.flex-active,
.pagination-arrows span.page-next:hover,
.pagination-arrows span.page-previous:hover,
#footer .btn, 
#footer .button, 
#footer button.button, 
#footer .btn[type="submit"],
#footer .button[type="submit"],
#footer input[type="button"], 
#footer input[type="reset"], 
#footer input[type="submit"],
#panel .btn:hover, 
#panel .button:hover, 
#panel .tagcloud a:hover,
#panel button.button:hover, 
#panel .btn[type="submit"]:hover,
#panel .button[type="submit"]:hover,
#panel input[type="button"]:hover, 
#panel input[type="reset"]:hover, 
#panel input[type="submit"]:hover {
	background-color: <?php echo $theme_accent_color ?>; 
}

.bean-quote,
.featurearea_icon .icon { background-color: <?php echo $theme_accent_color; ?>!important; }

#footer { background-color: <?php echo $footer_bg_color; ?>; }
.footer-extra { background-color: <?php echo $footer_extra_color; ?>; }
::selection { background-color: <?php echo $selection_bg_color; ?>; }
::selection { color: <?php echo $selection_text_color; ?>; }
	





<?php 
//CSS FOR CUSTOM CSS
echo get_theme_mod( 'custom_css', '' ); 

// STYLES FOR THE BEAN PRICING TABLE PLUGIN, IF ACTIVATED
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); if (is_plugin_active('bean-pricingtables/bean-pricingtables.php')) { ?>
	.bean-pricing-table .pricing-column li span { color:<?php echo $theme_accent_color; ?>!important; }
	#powerTip, .bean-pricing-table .pricing-highlighted { background-color:<?php echo $theme_accent_color; ?>!important; }
	#powerTip:after { border-color:<?php echo $theme_accent_color; ?> transparent!important; }
<?php } // END is_plugin_active ?>

</style>	

<?php } add_action( 'wp_head', 'Bean_Customize_Variables', 1 );
}