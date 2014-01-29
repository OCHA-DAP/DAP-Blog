<?php /* Template Name: Default, Right Sidebar */ ?>

<?php get_header(); ?>

<div class="row">
		
<div id="main" class="eight columns clearfix" role="main">
	
	<?php 
	
	while ( have_posts() ) : the_post();
	
	get_template_part( 'lib/content/content', 'page' ); 
	
	endwhile; // END OF THE LOOP
	    
	?>
	
</div><!-- END #main -->

<div class="sidebar four columns hide-for-small">
	
	<?php if ( !dynamic_sidebar( 'Internal Sidebar' ) ): ?><?php endif; ?>

</div><!-- END sidebar four columns hide-for-small -->

<?php get_footer(); ?>