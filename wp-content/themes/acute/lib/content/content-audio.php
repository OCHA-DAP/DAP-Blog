<?php /* Audio Post Format */ ?>

<section id="post-<?php the_ID(); ?>"<?php bean_post_class(); ?>>			

	<div class="row">
	
		<div class="twelve columns">	
		
			<header class="entry-header clearfix">
					
				<div class="entry-content-media">
					
					<?php radium_audio($post->ID); ?>
				
				</div><!-- .entry-content-media -->
				
				<?php if( is_singular() ) { ?>
				
					<h1 class="entry-title"><?php the_title(); ?></h1> 
					
				<?php } else { ?>
				
					<h1 class="entry-title">
							
						<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'bean' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
					</h1>
					
				<?php } do_action( 'bean_post_meta' ); ?>
		
			</header><!-- .entry-header -->
			
			<article class="entry-content">	
				
				<?php the_content( __( '<span>Continue Reading</span>', 'bean' ) ); ?>
				
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'bean' ) . '</span>', 'after' => '</div>' ) ); ?>
			
			</article><!-- END .entry-content -->
		
		</div><!-- END .twelve columns -->
	
	</div><!-- END .row -->	
				
</section><!-- END #post-<?php the_ID(); ?> -->