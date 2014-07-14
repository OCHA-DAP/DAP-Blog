<?php /* Video Post Format */ ?>

<section id="post-<?php the_ID(); ?>"<?php bean_post_class(); ?>>			
	
	<div class="row">
	
		<div class="twelve columns">
		
			<div class="entry-content-media">
			   
			    <?php 
			    	$embed = get_post_meta($post->ID, '_radium_video_embed', true);
			    	if( !empty($embed) ) {
			    		echo "<div class='video-frame'>";
			    	    echo stripslashes(htmlspecialchars_decode($embed));
			    	    echo "</div>";
			    	} else {
			    	    radium_video($post->ID);
			    } ?>
			 
			 </div><!-- END .entry-content-media -->
			 	
			<header class="entry-header clearfix">
			
				<?php if( is_singular() ) { ?>
				
					<h1 class="entry-title"><?php the_title(); ?></h1> 
					
				<?php } else { ?>
				
					<h1 class="entry-title">
							<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'bean' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
					</h1>
				
				<?php } bean_post_meta(); ?>
		
			</header><!-- .entry-header -->
					
			<article class="entry-content">	
				
				<?php the_content( __( '<span>Continue Reading</span>', 'bean' ) ); ?>
				
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'bean' ) . '</span>', 'after' => '</div>' ) ); ?>
			
			</article><!-- END .entry-content -->		
		
		</div><!-- END .twelve columns -->		
	
	</div><!-- END .row -->	
	
</section><!-- END #post-<?php the_ID(); ?> -->