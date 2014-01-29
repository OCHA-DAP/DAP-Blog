<?php if (is_single()) { // BLOG SINGLE POST HEADER ?>

	<h1>
	<?php if (!is_singular('portfolio')) { ?>
		<?php $options = get_option('bean_theme'); 
			
			if ($options['blog_header_text'] ) { do_action('bean_blog_header_text');
		
			} else { _e( 'The Blog.', 'bean' ); 
		} ?>
		
	<?php } 
		else { wp_title('');
	} ?>
	</h1>
	
<?php do_action('bean_single_pagination'); ?>

<?php } elseif (is_archive()) { // A FEW CHECKS FOR ARCHIVE TYPES ?>

<h1>
	
	<?php if ( is_author() ) :
	   	global $post; $author_id=$post->post_author;  ?>
	  		    		    
	    <h1><?php _e( 'All posts by: ', 'bean' ); $field='display_name'; the_author_meta( $field, $author_id ); ?></h1>

    <?php 
    
    elseif(is_tag() ):
		printf( __( 'Tagged: %s', 'bean' ), '<span>' . single_tag_title( '', false ) . '' );

	elseif (is_category() ) :
	    printf( __( 'Category: %s', 'bean' ), '' . single_cat_title( '', false ) . '' );
	
	elseif ( is_day() ) : 
		 printf( __( 'Archives: %s', 'bean' ), '' . get_the_date() . '' ); 
	
	elseif ( is_month() ) : 
		printf( __( 'Archives: %s', 'bean' ), '' . get_the_date( _x( 'F Y', 'monthly archives date format', 'bean' ) ) . '' ); 
	
	elseif ( is_year() ) :
		printf( __( 'Archives: %s', 'bean' ), '' . get_the_date( _x( 'Y', 'yearly archives date format', 'bean' ) ) . '' ); 
			    		
	else :
		printf(  __( 'Archives', 'bean' ) );
	
	endif; 
	
	?>

</h1>
	
<?php } elseif( is_search() ) { // SEARCH HEADER TEXT ?>	

	<h1>
		
		<?php global $query_string;
		
			query_posts( $query_string . '&posts_per_page=-1' );
		
			if ( have_posts() ) : printf( __('Results for: "%s"', 'bean'), get_search_query());
			
			else : printf( __('Nothing Found.', 'bean'), get_search_query());
			
			endif; 
			
		?>
		
	</h1>

<?php } elseif( is_404() ) { // 404 PAGE HEADERS ?>

	<h1>
		<?php $options = get_option('bean_theme'); 
			
			if ($options['404_error_header'] ) { do_action('bean_404_header');
	
			} else { printf( __('404 Error.', 'bean')); 
	
		} ?>
	</h1>

<?php } else { ?>

 	<h1><?php the_title(''); ?></h1>
 	
<?php } ?>