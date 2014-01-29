<?php /* The Comments */ ?>                      

<div id="comments" class="columns">
	
	<?php /* Run some checks for bots and password protected posts */   
	
    	$req = get_option('require_name_email'); // Checks if fields are required.
    	if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
    	        die ( 'Please do not load this page directly. Thanks!' );
		if ( ! empty($post->post_password) ) :
	        if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) : ?>
    			<div class="nopassword"><?php _e('This post is password protected. Enter the password to view any comments.', 'bean') ?></div>
	
				<?php do_action( 'radium_protected_comment' ); 
    
				/* Stop the rest of comments.php from being processed,
 				 * but don't kill the script entirely -- we still have
				 * to fully load the template.
 				 */
	    	        return;
		    	endif;
		endif;

		do_action( 'radium_before_comment_template' );

	/*-----------------------------------------------------------------------------------*/
	/*	DISPLAY THE COMMENTS
	/*-----------------------------------------------------------------------------------*/

	/* See if there are comments and do the comments stuff! */
	if ( have_comments() ) :

		/* Count the number of comments and trackbacks (or pings) */
		$ping_count = $comment_count = 0;
		foreach ( $comments as $comment )
	    	get_comment_type() == "comment" ? ++$comment_count : ++$ping_count;

			/* If there are comments, show the comments */

		if ( ! empty($comments_by_type['comment']) ) : ?>
		<div class="twelve">

			<div class="comments-title">

				<h5><?php printf($comment_count > 1 ? __('<span>%d</span> Comments', 'bean') : __('<span>One</span> Comment', 'bean'), $comment_count) ?></h5>

			</div><!-- END #comments-title -->

			<div id="comments-list" class="comments">

				<?php /* If there are enough comments, build the comment navigation  */ ?>  

				<?php $total_pages = get_comment_pages_count(); if ( $total_pages > 1 ) : ?>

			        <div id="comments-nav-above" class="comments-navigation">

			        	<div class="paginated-comments-links"><?php paginate_comments_links(); ?></div>

			        </div><!-- END #comments-nav-above -->

				<?php endif;

				do_action( 'radium_before_comments' );

				/* An ordered list of our custom comments callback, custom_comments(), in functions.php   */ ?>                           
		        	<ol>
		       			<?php wp_list_comments('type=comment&avatar_size=150'); ?>
		        	</ol>
				<?php /* If there are enough comments, build the comment navigation */

				$total_pages = get_comment_pages_count(); if ( $total_pages > 1 ) : ?>    

		        	<div id="comments-nav-below" class="comments-navigation">

		        		<div class="paginated-comments-links"><?php paginate_comments_links(); ?></div>

		       		</div><!-- END #comments-nav-below -->

				<?php endif; ?>

			</div><!-- END #comments-list .comments -->

		</div><!-- END .twelve columns -->

		<?php endif; /* If ( $comment_count ) */

		/*-----------------------------------------------------------------------------------*/
		/*	DISPLAY THE PINGS
		/*-----------------------------------------------------------------------------------*/

		/* If there are trackbacks(pings), show the trackbacks  */
		if ( ! empty($comments_by_type['pings']) ) : ?>

		<div id="trackbacks-list" class="comments">

			<div class="row">

				<div class="twelve columns">

					<div class="entry-meta">

				    	<h5><?php printf($ping_count > 1 ? __('<span>%d</span> Trackbacks', 'bean') : __('<span>One</span> Trackback for this Post.', 'bean'), $ping_count) ?></h5>

					</div><!-- END .entry-meta -->

				</div><!-- END .twelve columns -->

			</div><!-- END .row -->

			<div class="row">

				<?php /* An ordered list of our custom trackbacks callback, custom_pings(), in functions.php   */ ?>  

					<ol>
						<?php wp_list_comments('type=pings&callback=radium_custom_pings'); ?>
					</ol>

			</div><!-- END .row -->

		</div><!-- END #trackbacks-list .comments -->

		<?php endif; /* if ( $ping_count ) */

		endif; /* if ( $comments ) */



	/*-----------------------------------------------------------------------------------*/
	/*	RESPOND TO COMMENTS
	/*-----------------------------------------------------------------------------------*/
	if ( comments_open() ) :
				
		//Comment Form
		comment_form();
	 
	endif; /* if ( get_option('comment_registration') && !$user_ID ) */
	
	/* Display comments disabled message if there's already comments, but commenting is disabled */
	
	if ( ! comments_open() && have_comments() && ! is_page() ) : ?>
		
		<div id="respond">
		
			<p id="reply-title"><strong><?php _e( 'Comments have been disabled.', 'bean' ); ?></strong></p>
	   
	        <?php do_action( 'radium_comments_disabled' ); ?>
	        
	    </div>
	
	<?php endif; ?>

</div><!-- END #comments-respond -->