<?php 

/*-----------------------------------------------------------------------------------
	Switch off Comments on Pages by default. Thanks to http://wordpress.org/support/topic/turn-page-comments-off-by-default-311#post-2433904
-----------------------------------------------------------------------------------*/
function radium_default_comments_off( $data ) {
    if( $data['post_type'] == 'page' && $data['post_status'] == 'auto-draft' ) {
        $data['comment_status'] = 0;
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'radium_default_comments_off' );


/*-----------------------------------------------------------------------------------
	Comments
-----------------------------------------------------------------------------------*/
// Custom callback to list pings
function radium_custom_pings($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
    ?>
	<li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
	    <div class="comment-author">
	    	<?php printf(__('By %1$s on %2$s at %3$s', 'bean'),
	        get_comment_author_link(),
	        get_comment_date(),
	        get_comment_time() );
	        edit_comment_link(__('[ edit ]', 'bean'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?>
	    </div>
	<?php 
    if ($comment->comment_approved == '0') _e('\t\t\t\t\t<span class="unapproved">Your trackback is awaiting moderation</span>\n', 'bean') ?>
	    <div class="comment-content">
	     	<?php comment_text() ?>
	    </div>
                        
<?php } // end custom_pings


/*-----------------------------------------------------------------------------------
	Comments form
-----------------------------------------------------------------------------------*/
 function radium_custom_form_filters( $args = array(), $post_id = null ) {

	global $id;
	
	$options = get_option('bean_theme');
	
	$custom_comment_message = isset( $options['comment_form_text'] ) ? $options['comment_form_text'] : null;
	
	if ( null === $post_id )
		$post_id = $id;
	else
		$id = $post_id;

	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$fields =  array(
		
	'<div class="six">',
		
		'author' => '
			<div class="comment-form-author">
			<label for="author">' . __( 'Name.', 'bean' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> 
			' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' required/>
		</div>',
		
		'email'  => '
			<div class="comment-form-email">
			<label for="email">' . __( 'Email.', 'bean' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> 
			' . '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' required/>
		</div>',
		
		'url'    => '
			<label for="url">' . __( 'Website.', 'bean') . '</label>
			<div class="comment-form-url">
			' . '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
		</div>',
		
	'</div>',	
	
	);

	$required_text = sprintf( ' ' . __('Required fields are marked %s', 'bean'), '<span class="required">*</span>' );
	 
	$defaults = array(
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field'        => '
			<div class="six">
				<label for="comment">' . __( 'Comment.', 'bean') . '</label>
				<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required></textarea>','',
		
		'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'bean' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'bean' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'comment_notes_before' => null,
		'comment_notes_after'  => null,
		'id_form'              => 'commentform',
		'id_submit'            => 'submit',
		'title_reply'          => sprintf( __( 'Leave a Comment', 'bean' ), apply_filters( 'radium_comment_form_message', $custom_comment_message ) ), 
		'title_reply_to'       => __( 'Leave a Reply to %s', 'bean' ),
		'cancel_reply_link'    => __( 'Cancel', 'bean' ),
		'label_submit'         => __( 'Submit Comment', 'bean' ),
	);
		
	return $defaults;

}

add_filter( 'comment_form_defaults', 'radium_custom_form_filters' );