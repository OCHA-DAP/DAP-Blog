<?php /* Template Name: Portfolio*/ ?>

<?php get_header(); ?>

<?php $options = get_option('bean_theme');  ?>

<div class="row">
		
	<div id="main" class="twelve columns clearfix" role="main">
		
		<div class="entry-content">
		
			<?php while ( have_posts() ) : the_post(); the_content(); endwhile; // THE LOOP ?>
			
		</div><!--END .entry-content -->
		
		<?php
		$terms = get_terms( 'portfolio_category' );
		if( !empty($terms) ) {
			echo '<h5>';
				echo '<ul id="filter" class="hide-for-small" data-filter="isotope-item">';
						echo '<li>Filter</li>';
						echo '<li><a href="#all" class="active" data-filter="isotope-item">' . __('All', 'bean') . '</a></li>';
						foreach( $terms as $term ) {
							echo '<li><a href="' . get_term_link($term) .'" data-filter="' . $term->slug .'">' . $term->name . '</a></li>';
						}
						
				echo '</ul>';
			echo '</h5>';	
		} ?>
		
		<div id="isotope-container">
		
			<?php
			//LOAD PORTFOLIO QUERY - NEED BEAN PORTFOLIO POST TYPE PLUGIN
			$args = array(
			'post_type' 		=> 'portfolio',
			'order' 			=> 'ASC',
			'orderby' 			=> 'menu_order',
			'posts_per_page' 	=> -1,
			); 
			
			query_posts($args); if (have_posts()) : while (have_posts()) : the_post(); 

			// GENERATE TERMS FOR FILTER
			$terms =  get_the_terms( $post->ID, 'portfolio_category' ); 
			$term_list = null;
			if( is_array($terms) ) { foreach( $terms as $term ) { $term_list .= $term->slug; $term_list .= ' '; }}
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
		
		 <?php wp_reset_postdata(); ?>
	
	</div><!-- END .row -->
	
</div><!-- END #main -->

<?php get_footer(); ?>