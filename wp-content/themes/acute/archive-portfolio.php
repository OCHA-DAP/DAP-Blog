<?php /* Portfolio Archives */ ?>

<?php get_header(); ?>

<div class="row">
		
	<div id="main" class="twelve columns clearfix" role="main">
			
		<div id="isotope-container">
				
			<?php 
			//CATEGORY QUERY
			global $query_string; query_posts("{$query_string}&posts_per_page='-1'"); 
			if ( have_posts() ) : while ( have_posts() ) : the_post(); 

			//GENERATE PORTFOLIO TERMS FOR FILTER
			$terms =  get_the_terms( $post->ID, 'portfolio_category' ); 
			$term_list = null;
			if( is_array($terms) ) {
			    foreach( $terms as $term ) {
			        $term_list .= $term->slug;
			        $term_list .= ' ';
			    }
			}
			?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class("$term_list isotope-item"); ?>>
				
				<div class="post-thumb">
						
					<a title="<?php printf(__('Permanent Link to %s', 'bean'), get_the_title()); ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail-port'); ?></a> 
				   								
				</div><!-- END .post-thumb --> 
				
				<h5><a title="<?php printf(__('Permanent Link to %s', 'bean'), get_the_title()); ?>" href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h5>
				
				<?php if(!empty($post->post_excerpt)) { the_excerpt(); } ?>
			
			</article><!-- END post-<?php the_ID(); ?> --> 
			
			<?php endwhile; endif; ?>				

		</div><!-- END #isotope-container -->
			
	</div><!-- END .row -->
		
</div><!-- END #main -->

<?php get_footer(); ?>