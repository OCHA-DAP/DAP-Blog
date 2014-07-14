<?php /* The Search */

get_header(); ?>

<div id="main" class="<?php echo $radium_content_class; ?> clearfix" role="main">
	
	<?php 
	
	global $query_string;
	
	query_posts( $query_string . '&posts_per_page=-1' );
	
	if ( have_posts() ) : ?>
		
			<?php  
			
			$i = 0;
		
			while (have_posts()) : the_post();
			
			$format = get_post_format(); 
			
			if( false === $format ) { $format = 'standard'; }
			if( $format == 'aside' || $format == 'gallery' || $format == 'image' || $format == 'link' || $format == 'quote') { }
			
			get_template_part( 'lib/content/content', $format );
			
			endwhile; 
			
			?>

	<?php else : ?>
	
	<div class="row">	
		
			<div class="page-box">
			
				<div class="post no-results not-found">
			
					<div class="twelve columns">
					
						<p><?php printf( __('Sorry, we could&#39;nt find anything for "%s".','bean'), get_search_query() ); ?></p>
					
						<form id="searchform" class="searchform" method="get" action="<?php echo get_home_url(); ?>">
						
						    <div class="clearfix default_searchform">
						
						        <input type="text" name="s" class="s" onblur="if (this.value == '') {this.value = '<?php _e('Search for another term or keyword...','bean'); ?>';}" 
						        
						        onfocus="if (this.value == '<?php _e('Search for another term or keyword...','bean'); ?>') {this.value = '';}" value="<?php _e('Search for another term or keyword...','bean'); ?>" />
						        
						        <button type="submit" class="button"><span><?php _e('Search Again', 'bean'); ?></span></button>
						
						    </div><!-- END .clearfix defaul_searchform -->
						
						</form><!-- END #searchform -->
					
					</div><!-- END .twelve columns -->
				
				</div><!--END .post no-results not-found -->
			
			</div><!--END .page-box -->
		
	<?php endif; ?>
	
</div><!-- END #main -->

<?php get_footer(); ?>