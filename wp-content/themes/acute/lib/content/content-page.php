<?php /* Default Page Content */ ?>

<section id="post-<?php the_ID(); ?>"<?php bean_post_class(); ?>>

	<div class="page-box entry-content">

		<?php the_content(); ?>

	</div><!-- END .entry-content -->

</article><!-- END #post-<?php the_ID(); ?> -->
