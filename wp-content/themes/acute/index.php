<?php /* The Base */ ?>

<?php 

get_header(); 

$options = get_option('bean_theme');

?>
	
<div id="main" class="twelve columns" role="main">
		
	<?php if (have_posts()) : while (have_posts()) : the_post(); 

		$format = get_post_format(); 
		if( false === $format ) { $format = 'standard'; }
		if( $format == 'aside' || $format == 'gallery' || $format == 'image' || $format == 'link' || $format == 'quote') { }
	
	    get_template_part( 'lib/content/content', $format ); 
	
		endwhile; 

 		endif; 
		
		echo bean_index_pagination(); 
	
	?>

</div> <!-- END #main -->

<?php get_footer(); ?>