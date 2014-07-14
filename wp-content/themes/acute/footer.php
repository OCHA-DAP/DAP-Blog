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
					<div class="twelve columns hide-for-small left">
						<div class="eight columns hide-for-small left">
							<nav id="navigation" role="navigation">
							<div class="container">
							<div id="primary-nav" class="main_menu">
									<?php
		  								$args = array(
		  								    'container' 	=> '',
		  								    'menu_id' => 'footer-menu',
		  								    'menu_class' => 'sf-menu main-menu',
		  									'theme_location' => 'main-menu',
		  									'after' => '<span class="nav-sep">|</span>',
		  								);
		  								wp_nav_menu( apply_filters( 'radium_main_menu_args', $args ) );
		  							 ?>
		  					</div>
		  					</div>
		  					</nav>

		  							 <span> HUMANITARIAN DATA EXCHANGE BETA   v0.3.0 </span>
		  				</div>

		  				<div class="one columns hide-for-small center">
		  					<img src="/wp-content/uploads/cc_logo.png" />
		  				</div>
		  				<div class="three columns hide-for-small right">
		  					Except where otherwise noted, content on this site is licensed under a Creative Commons Attribution 4.0 International license.
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
					<div class="twelve columns hide-for-small left">
						<div class="eight columns hide-for-small left">
							<nav id="navigation" role="navigation">
							<div class="container">
							<div id="primary-nav" class="main_menu">
									<?php
		  								$args = array(
		  								    'container' 	=> '',
		  								    'menu_id' => 'footer-menu',
		  								    'menu_class' => 'footer-menu nav-sep',
		  									'after' => '<span class="nav-sep">|</span>',
		  								);
		  								wp_nav_menu( apply_filters( 'radium_main_menu_args', $args ) );
		  							 ?>
		  					</div>
		  					</div>
		  					</nav>

		  							 <span> HUMANITARIAN DATA EXCHANGE BETA   v0.3.0 </span>
		  							 <div class="twelve columns" style="padding-left: 0px;">
		  							 	<span> Subscribe to our mailing list.</span>
		  							 		<!-- Begin MailChimp Signup Form -->
											<div id="mc_embed_signup">
											<form action="//unocha.us2.list-manage.com/subscribe/post?u=83487eb1105d72ff2427e4bd7&amp;id=6fd988326c" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>

											<div class="six columns left" style="padding-left: 0px;">
												<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
											</div>
											<div id="mce-responses" class="clear">
												<div class="response" id="mce-error-response" style="display:none"></div>
												<div class="response" id="mce-success-response" style="display:none"></div>
											</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
											    <!--
											    <div>
											    	<input type="text" name="b_83487eb1105d72ff2427e4bd7_6fd988326c" tabindex="-1" value="">
											    </div>
											    -->
											<div class="clear one columns left">
												<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="mailchimp">
											</div>
											</form>
											</div>
											<!--End mc_embed_signup-->
		  							 </div>
		  				</div>

		  				<div class="one columns hide-for-small center">
		  					<img src="/wp-content/uploads/cc_logo.png" />
		  				</div>
		  				<div class="three columns hide-for-small right">
		  					Except where otherwise noted, content on this site is licensed under a Creative Commons Attribution 4.0 International license.
						</div><!-- END .footer-fullwidth -->


					</div><!-- END .twelve columns -->
				</div><!-- END .row -->

			</footer><!-- END #footer -->

			<div class="footer-extra animated BeanFadeIn"><!-- Footer EXTRA -->
			
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

				<div class="footer-extra animated BeanFadeIn"><!-- Footer EXTRA -->
				
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