<?php /* Footer */
 
$options = get_option('bean_theme');
 
?>

</div><!-- END .row-->	

</section>

<?php if (is_404()) { } else { //HIDE FOOTER FOR 404 ?>	

	<?php if ( isset( $options['footer_layout'] ) ) { $layout = $options['footer_layout'];
		
		  if ( $layout == 'fullwidth') { ?>
	
			<footer id="footer" class="animated BeanFadeIn">
			
				<div class="row">  
				
					<div class="twelve columns">
					
						<div class="footer-fullwidth">	
						
							<?php do_action('bean_colophon'); ?>
										
						</div><!-- END .footer-fullwidth -->	
						
					</div><!-- END .twelve columns -->
					
				</div><!-- END .row -->				
						
			</footer><!-- END #footer -->					
						
			<?php } if ( $layout == 'split') { ?>
						
			<footer id="footer" class="animated BeanFadeIn">
						 				
				<div class="row">  		 	
				 	
					<div class="five columns">
					
						<?php do_action('bean_colophon'); ?>
						
					</div><!-- END .five columns -->
						
					<div class="seven columns">
					
						<?php if ( !dynamic_sidebar( 'Footer' ) ): ?><?php endif; ?>
					
					</div><!-- END .seven columns -->				 		
					
				</div><!-- END .row -->
				
			</footer><!-- END #footer -->	 			
				
			<?php } if ( $layout == 'fullwidth_extra') { ?>
			
			<footer id="footer" class="animated BeanFadeIn">
			
				<div class="row">  
				
					<div class="twelve columns">
					
						<div class="footer-fullwidth">	
						
							<?php do_action('bean_colophon'); ?>
										
						</div><!-- END .footer-fullwidth -->	
						
					</div><!-- END .twelve columns -->
					
				</div><!-- END .row -->	
				
			</footer><!-- END #footer -->
				
			<div class="footer-extra animated BeanFadeIn">
			
				<div class="row ">
				
					<div class="eight columns">
						
						<?php bean_social_footer(); ?>
					
					</div><!-- END .four columns -->
				
					<div class="four columns">	
					
						<span class="bold"><?php $count_posts = wp_count_posts(); echo $count_posts->publish;?></span> 
					
						<?php _e('Published Posts', 'bean'); ?>
						
					</div><!-- END .four columns -->
				
				</div><!-- END .row -->
				
			</div><!-- END .footer-extra -->
						
			<?php } if ( $layout == 'split_extra') { ?>
						
				<footer id="footer" class="animated BeanFadeIn">
							 				
					<div class="row">  		 	
					 	
						<div class="five columns">
						
							<?php do_action('bean_colophon'); ?>
							
						</div><!-- END .five columns -->
							
						<div class="seven columns">
						
							<?php if ( !dynamic_sidebar( 'Footer' ) ): ?><?php endif; ?>
						
						</div><!-- END .seven columns -->				 		
						
					</div><!-- END .row -->
					
				</footer><!-- END #footer -->
				
				<div class="footer-extra animated BeanFadeIn">
				
					<div class="row ">
					
						<div class="eight columns">
							
							<?php bean_social_footer(); ?>
						
						</div><!-- END .four columns -->
					
						<div class="four columns">	
						
							<span class="bold"><?php $count_posts = wp_count_posts(); echo $count_posts->publish;?></span> 
						
							<?php _e('Published Posts', 'bean'); ?>
							
						</div><!-- END .eight columns -->
					
					</div><!-- END .row -->
					
				</div><!-- END .footer-extra -->
							
			<?php } } } ?>
	
<?php bean_analytics(false);  wp_footer(); ?>

<!--<?php echo ''. BEAN_THEME_NAME .' WordPress Theme (ThemeBeans.com) with '. $wpdb->num_queries .' queries in '. timer_stop(0, 2) .' seconds'; ?>-->

</body>

</html>