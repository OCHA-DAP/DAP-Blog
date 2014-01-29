<?php /* Template Name: Contact, Right Sidebar */ ?>

<?php $options = get_option('bean_theme');

if(isset($_POST['submitted'])) {
		if(trim($_POST['contactName']) === '') {
			$hasError = true;
		} else {
			$name = trim($_POST['contactName']);
		}
		
		if(trim($_POST['email']) === '')  {
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}
			
		if(trim($_POST['comments']) === '') {
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}
			
		if(!isset($hasError)) {
			$options = get_option('bean_theme'); 
			$contactEmail = $options['contact_email'];
			if (!isset($contactEmail) || ($contactEmail == '') ){
				$contactEmail = get_option('admin_email');
			}
			
			$subject = '[Contact Form] from '.$name;
			$body = "Name: $name \n\nEmail: $email \n\nMessage: $comments";
			$headers = 'From: '.$name.' <'.$email.'>' . "\r\n" . 'Reply-To: ' . $email;
			
			mail($contactEmail, $subject, $body, $headers);
			$emailSent = true;
		}
	
} ?>

<?php get_header(); ?>

<script type="text/javascript">
	jQuery(document).ready(function(){ jQuery("#BeanForm").validate({ errors: { contactName: '', email: { required: '', email: '' }, comments: '' } }); });
</script>
	
<div class="row">
		
	<div id="main" class="eight columns clearfix" role="main">
	
		<div class="entry-content page-box">
		
			<?php while ( have_posts() ) : the_post(); the_content(); endwhile; // THE LOOP ?>
		
			<?php if(isset($emailSent) && $emailSent == true) { ?>
				
				<div class="contact-alert success">
				
					<?php _e('Awesome '.$name.'. Your message has been sent.', 'bean') ?>
					
				</div><!-- END .alert alert-success -->
		
			<?php } // END SUCCESS ALERT ?>
		
			<?php if(isset($hasError) || isset($captchaError)) { ?>
			
				<div class="contact-alert fail">
				
					<?php _e('Well now... an error occured. Please try again.', 'bean') ?>
				
				</div><!-- END .alert alert-success -->
				
			<?php } // END FAIL ALERT ?>
		
		</div><!-- END .entry-content -->
	
		<form action="<?php the_permalink(); ?>" id="BeanForm" method="post">
			<?php $required = '<span class="required">*</span>'; ?>
			<ul class="contactform">
				
				<li>
					<label for="contactName"><?php _e('Name: ', 'bean'); echo $required ?></label>
					<input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="required requiredField" />
				</li>
	
				<li>
					<label for="email"><?php _e('Email: ', 'bean'); echo $required ?></label>
					<input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="required requiredField email" />
				</li>
	
				<li class="textarea"><label for="commentsText"><?php _e('Message: ', 'bean'); echo $required ?></label>
					<textarea name="comments" id="commentsText" rows="20" cols="30" class="required requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
					
				</li>
				
				<li class="submit right">
					<input type="hidden" name="submitted" id="submitted" value="true" />
					<button type="submit" class="button"><?php _e('Send Your Message', 'bean') ?></button>
				</li>
		
			</ul>
		
		</form><!-- END #BeanForm -->
				
	</div><!-- END #main -->

	<div class="sidebar four columns hide-for-small">
	
		<?php if ( !dynamic_sidebar( 'Internal Sidebar' ) ): ?><?php endif; ?>

	</div><!-- END sidebar four columns hide-for-small -->

<?php get_footer(); ?>