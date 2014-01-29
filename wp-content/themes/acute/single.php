<?php /* Single Post */

get_header();
$options = get_option('bean_theme');

?>

<div class="row">

<div id="main" class="twelve columns" role="main">

	<?php if (have_posts()) :
	
		while (have_posts()) : the_post();
		
		$format = get_post_format(); 
		
		if( false === $format ) { $format = 'standard'; }
		if( $format == 'aside' || $format == 'gallery' || $format == 'image' || $format == 'link' || $format == 'quote') { }
		
		get_template_part( 'lib/content/content', $format );
		
		endwhile; 
	?>	
	
	<?php if ( isset($options['post_share'] ) ) { ?>

		<div class="post-share"> 					
			
			<a href="http://twitter.com/share?url=<?php the_permalink(); ?>&text= <?php the_title(); ?> via @<?php do_action('bean_profile_twitter'); ?>" target="_blank" class="btn post-share-btn twitter-btn"><?php echo __('Share on Twitter', 'bean'); ?></a> 
			
			<a onClick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php the_title(); ?>&amp;p[summary]=&amp;p[url]=<?php the_permalink(); ?>&amp;&amp;p[images][0]=','sharer','toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)" class="btn post-share-btn facebook-btn"><?php echo __('Share on Facebook', 'bean'); ?></a> 			
		
		</div><!-- END .post-share -->

	<?php } if ($options['blog_about_author'] ) { do_action('bean_about_author'); // CODE PULLED FROM THEME-FUNTIONS.PHP
			// if ()
	// Add co-author here.


	} else { printf( __('</div>', 'bean')); 

} ?>

<?php 	
 
	if( bean_theme_supports( 'comments', 'posts' ) && ( comments_open() || '0' != get_comments_number() )  ) comments_template( '', true );  

	endif; 
?>

<?php get_footer(); ?>