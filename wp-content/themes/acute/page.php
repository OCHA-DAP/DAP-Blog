<?php /* Default Template */ ?>

<?php get_header(); ?>

<div class="row">

<div id="main" class="twelve columns clearfix" role="main">

	<?php

		while ( have_posts() ) : the_post();

			get_template_part( 'lib/content/content', 'page' );

		endwhile; // END OF THE LOOP

	   // LOAD COMMENTS
	   if( bean_theme_supports( 'comments', 'pages' ) && ( comments_open() || '0' != get_comments_number() )  ) comments_template( '', true );

	?>

</div><!-- END #main -->

<?php get_footer(); ?>