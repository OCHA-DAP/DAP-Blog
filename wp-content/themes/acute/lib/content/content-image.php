<?php /* Image Post Format */ ?>

<section id="post-<?php the_ID(); ?>"<?php bean_post_class(); ?>>		

	<div class="row ">	
	
		<div class="twelve columns">
		
			<header class="entry-header clearfix">
						
				<?php if (( function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { // FEATURED IMAGE ?>
					
					<div class="entry-content-media">
					
						<div class="post-thumb">
						
							<?php if( is_singular() ) { the_post_thumbnail('post-full'); // DIMENSIONS IN /LIB/FUNCTIONS/THEME-SETUP.PHP ?>
							
							<?php } else { // IF NOT SINGLE ?>
							
							<a title="<?php printf(__('Permanent Link to %s', 'bean'), get_the_title()); ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('post-full'); ?></a>
												
							<?php } ?>
						
						</div><!-- END .post-thumb -->
					
					</div><!-- END .entry-content-media -->  
					
				<?php } // END has_post_thumbnail ?>
					
				<?php if( is_singular() ) { ?>
				
					<h1 class="entry-title"><?php the_title(); ?></h1> 
					
				<?php } else { ?>
				
					<h1 class="entry-title">
					
						<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'bean' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
					
					</h1><!-- END .entry-title -->
				
				<?php } bean_post_meta(); ?>
					
			</header><!-- .entry-header -->
				
			<article class="entry-content">	
				
				<?php the_content( __( '<span>Continue Reading</span>', 'bean' ) ); ?>
				
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'bean' ) . '</span>', 'after' => '</div>' ) ); ?>
			
			</article><!-- END .entry-content -->		
		
		</div><!-- END .twelve columns -->	
	
	</div><!-- END .row -->
	
</section><!-- END #post-<?php the_ID(); ?> -->