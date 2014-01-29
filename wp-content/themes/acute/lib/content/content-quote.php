<?php /* Quote Post Format */ ?>

<section id="post-<?php the_ID(); ?>"<?php bean_post_class(); ?>>

	<div class="row">	
	
		<div class="twelve columns">
			
			<header class="entry-header clearfix">
						
				<h1 class="entry-title"><span><?php the_title(); ?></span></h1> 
		
			</header><!-- .entry-header -->
			
			<article class="entry-content">	
				
				<?php the_content( __( '<span>Continue Reading</span>', 'bean' ) ); ?>
				
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'bean' ) . '</span>', 'after' => '</div>' ) ); ?>
			
			</article><!-- END .entry-content -->		
			
		</div><!-- END .twelve columns -->
		
	</div><!-- END .row -->

</section><!-- END #post-<?php the_ID(); ?> -->