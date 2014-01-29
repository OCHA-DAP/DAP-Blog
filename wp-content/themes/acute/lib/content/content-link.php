<?php /* Link Post Type */ ?>

<section id="post-<?php the_ID(); ?>"<?php bean_post_class(); ?>>		

	<div class="row">	
	
		<div class="twelve columns">
		
			<header class="entry-header clearfix">
	
				<?php $link = get_post_meta($post->ID, '_radium_link_url', true); ?>
					
				<span class="link-icon"></span>
				
				<h1 class="entry-title">
					
				    <a href="http://<?php echo $link; ?>"><?php the_title(); ?></a>
				
				</h1>
				
			<!--	<p><a href="http://<?php echo $link; ?>"><?php echo $link; ?></a></p>-->
				
			</header><!-- END .entry-header -->	
					
			<article class="entry-content">	
				
				<?php the_content( __( '<span>Continue Reading</span>', 'bean' ) ); ?>
				
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'bean' ) . '</span>', 'after' => '</div>' ) ); ?>
			
			</article><!-- END .entry-content -->		
		
		</div><!-- END .twelve columns -->
	
	</div><!-- END .row -->
	
</section><!-- END #post-<?php the_ID(); ?> -->