<?php
/**
 * The template for displaying image attachments.
 */

get_header(); ?>

<div id="main" class="twelve columns image-attachment">

	<div class="post-box" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<div class="entry-content">

					<div class="entry-attachment">
						
						<div class="attachment">
													
							<?php
								/**
								 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
								 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
								 */
								$attachments = array_values( 
									get_children( array( 
										'post_parent' => $post->post_parent, 
										'post_status' => 'inherit', 
										'post_type' => 'attachment', 
										'post_mime_type' => 'image', 
										'order' => 'ASC', 
										'orderby' => 'menu_order ID' 
										) 
									) 
								);
								
								foreach ( $attachments as $k => $attachment ) {
									if ( $attachment->ID == $post->ID )
										break;
								}
								$k++;
								// If there is more than 1 attachment in a gallery
								if ( count( $attachments ) > 1 ) {
									if ( isset( $attachments[ $k ] ) )
										// get the URL of the next image attachment
										$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
									else
										// or get the URL of the first image attachment
										$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
								} else {
									// or, if there's only 1 image, get the URL of the image
									$next_attachment_url = wp_get_attachment_url();
								}
							?>
							<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php the_title_attribute(); ?>" rel="attachment">
								<?php
									$attachment_size = apply_filters( 'full', 910 );
									echo wp_get_attachment_image( $post->ID, array( $attachment_size, 1024 ) ); // filterable image width with 1024px limit for image height.
								?>
							</a>

							<?php if ( ! empty( $post->post_excerpt ) ) : ?>
							
							<div class="entry-caption">
							
								<?php the_excerpt(); ?>
							
							</div><!-- END .entry-caption -->
							
							<?php endif; ?>
						
						</div><!-- END .attachment -->

					</div><!-- END .entry-attachment -->

					<div class="entry-description">
						
						<?php the_content(); ?>
						
						<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'bean' ) . '</span>', 'after' => '</div>' ) ); ?>
						
					</div><!-- END .entry-description -->

				</div><!-- END .entry-content -->

			</article><!-- END #post-<?php the_ID(); ?> -->

			<?php 
				// LOAD COMMENTS
				if( bean_theme_supports( 'comments', 'posts' ) && ( comments_open() || '0' != get_comments_number() )  ) comments_template( '', true );  
			 ?>

		<?php endwhile; // End of the loop. ?>

	</div><!-- END .post-box -->
	
</div><!-- END #main -->

<?php get_footer(); ?>