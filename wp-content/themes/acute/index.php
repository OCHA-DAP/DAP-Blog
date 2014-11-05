<?php /* The Base */ ?>

<?php 

get_header(); 

$options = get_option('bean_theme');

?>
	
<div id="main" class="twelve columns" role="main">

	<!-- START:
	<section id="post" class="post post type-post animation_area status-publish format-standard hentry category-blog category-hdx even">
		<div class="row">
			<div class="twelve columns">
				<div>
					<header class="entry-header animation clearfix">
						<h1 class="entry-title animation_title">
							<a title="Introducing the Humanitarian Data Exchange: animation" href="https://www.youtube.com/watch?v=hCVyiZhYb4M">Introducing the Humanitarian Data Exchange: animation</a>
						</h1>
					</header>
					<article class="entry-content video_embed">
						<p>
							<iframe width="560" height="315" src="//www.youtube.com/embed/hCVyiZhYb4M" frameborder="0" allowfullscreen></iframe>
						</p>
					</article>
				</div>
			</div>
		</div>
	</section>
	END: animation special section -->

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