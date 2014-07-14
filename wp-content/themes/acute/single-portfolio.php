<?php get_header(); ?>

<?php //SETTING UP META
$options = get_option('bean_theme');
$portfolio_date = get_post_meta($post->ID, '_radium_portfolio_date', true); 
$portfolio_url = get_post_meta($post->ID, '_radium_portfolio_url', true); 
$portfolio_url_text = get_post_meta($post->ID, '_radium_portfolio_url_text', true);
$portfolio_client = get_post_meta($post->ID, '_radium_portfolio_client', true); 
$portfolio_cats = get_post_meta($post->ID, '_radium_portfolio_cats', true); 
?>

<div class="row">
		
	<div id="main" class="twelve columns clearfix" role="main">
			
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<?php if ( !post_password_required() ) { // START PASSWORD PROTECTED MEDIA ?>
						
				<div class="entry-content-media">
	
					<?php
					$thumb_ID = get_post_thumbnail_id( $post->ID ); // EXCLUDE USE THE FEATURED IMAGE
					$images =& get_children( array (
						'post_parent' => $post->ID,
						'order' => 'ASC',
						'orderby' => 'menu_order',
						'post_type' => 'attachment',
						'post_mime_type' => 'image',
						'exclude' => $thumb_ID // EXCLUDED
					));
					
					foreach ( $images as $attachment_id => $attachment ) { 
						$imageUrl = wp_get_attachment_image_src( $attachment->ID, 'thumbnail-port-single' );
						$caption = $attachment->post_excerpt;
						echo '<img src="'.$imageUrl[0].'"/>';
					}	
					?>
					
				</div><!-- END .entry-content-media -->
			
			<?php  } //END PASSWORD PROTECTED MEDIA ?>	
			
			<div class="entry-content">
			
				<?php the_content(); ?>					
			
				<ul class="portfolio-meta-list">
					
					<?php if ($portfolio_date == true) : // DISPLAY DATE ?> 
					
						<li><span><?php _e( 'Date: ', 'bean' ); ?></span><?php echo the_time('F Y'); ?></li>
						
					<?php endif; // END DATE ?>
					
					<?php if ($portfolio_client != '') : // DISPLAY CLIENT ?>
					
						<li><span><?php _e( 'Client: ', 'bean' ); ?></span><?php echo $portfolio_client;  ?></li>
					
					<?php endif; // END CLIENT ?>
				
					<?php if ($portfolio_url != '') : // DISPLAY PORTFOLIO URL ?>
						
						<li><span><?php _e( 'URL: ', 'bean' ); ?></span><a href="http://<?php echo $portfolio_url; ?>" target="blank"><?php echo $portfolio_url_text; ?></a></li>
					
					<?php endif; // DISPLAY PORTFOLIO URL ?> 
					
					<?php if ($portfolio_cats == true) : // DISPLAY CATEGORY ?>
								
						<li><span><?php _e( 'Skills: ', 'bean' ); ?></span><?php  the_terms($post->ID, 'portfolio_category', '', ' & ', ''); ?></li>
						  
					<?php endif; // END CATEGORY ?>
						
				</ul><!--END .portfolio-meta-list -->		   
				
			</div><!--END .entry-content -->
					

		<?php endwhile; endif; wp_reset_postdata(); ?>
	
		<div id="isotope-container">
		
			<?php
			//LOAD PORTFOLIO QUERY - NEED BEAN PORTFOLIO POST TYPE PLUGIN
			$args = array(
			'post_type' 		=> 'portfolio',
			'order' 			=> 'ASC',
			'orderby' 			=> 'menu_order',
			'posts_per_page' 	=> 9,
			'post__not_in' => array($post->ID)
			); 
			
			query_posts($args); if (have_posts()) : while (have_posts()) : the_post(); 
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
		
</div><!-- END #main -->	

<?php get_footer(); ?>