<?php

function bean_load_framework_theme_options() {

$sections[] = array(
				'title' => __('General Options', 'bean'),
				'desc' => __('<p class="description">This page contains general theme options.</p>', 'bean'),
				'fields' => array(					
					array(
						'id' => 'logo',
						'type' => 'upload', 
						'title' => __('Upload Logo', 'bean'),
						'sub_desc' => __('Upload your custom logo here. If left empty, the site title will be displayed instead.', 'bean'),
						),							

					array(
						'id' => 'favicon',
						'type' => 'upload', 
						'title' => __('Upload Favicon', 'bean'),
						'sub_desc' => __('Upload a favicon here that will override the default favicon. (16px by 16px)', 'bean'),
						),
					
					array(
						'id' => 'appleicon',
						'type' => 'upload', 
						'title' => __('Upload Apple Touch Icon', 'bean'),
						'sub_desc' => __('Upload you custom icon which will be displayed when your website is saved to an iOS device homescreen. (114px by 114px)', 'bean'),
						),
					
					array(
						'id' => 'contact_email',
						'type' => 'text',
						'title' => __('Contact Email Address', 'bean'),
						'sub_desc' => __('This email address overrides your admin email address - to be used for the contact template.', 'bean'),
						'std' => ''
						),
						
					array(
						'id' => 'sliding_panel',
						'type' => 'checkbox_hide_below',
						'title' => __( 'Display Sliding Panel', 'bean'), 
						'sub_desc' => __('Elect to display the sliding panel globally on your website.', 'bean'),
						'desc' => __('Yes do it', 'bean'),
						'std' => 1 
						),	
					
					array(
						'id' => 'sliding_panel_scroll',
						'type' => 'checkbox',
						'title' => __( 'Enable Panel Scrolling', 'bean'), 
						'sub_desc' => __('If selected, the panel will become a scroll area of it&#389s own.', 'bean'),
						'desc' => __('Yes do it', 'bean'),
						'std' => 1 
						),	
						
					array(
						'id' => 'announcement',
						'type' => 'checkbox_hide_below',
						'title' => __( 'Display Global Announcement', 'bean'), 
						'sub_desc' => __('Elect to display the header announcemnt globally on your website. It is enabled via cookies, which means your visitors will not see it again if they have closed it.', 'bean'),
						'desc' => __('Yes do it', 'bean'),
						'std' => 0 
						),	
					
					array(
						'id' => 'announcement_text',
						'type' => 'text',
						'title' => __('Announcement Text', 'bean'),
						'sub_desc' => __('Keep it short and simple for maximum effectiveness. HTML is allowed.', 'bean'),
						'std' => ''
						),
								
					array(
						'id' => 'header_analytics',
						'type' => 'textarea',
						'title' => __('Header Analytics', 'bean'),
						'sub_desc' => __('Paste any analytics code that belongs in the head element of your site here.', 'bean'),
						'std' => ''
						),
								
 					)
				);
  				
  								
$sections[] = array(
				'title' => __('Blog Settings', 'bean'),
				'desc' => __('<p class="description">Manage multiple general page & blog view settings.</p>', 'bean'),
				'icon' => '',
				'fields' => array(					
					
					array(
						'id' => 'blog_header_text',
						'type' => 'text',
						'title' => __('Blog Home Title:', 'bean'),
						'sub_desc' => __('Customize the header title of the blog posts page.', 'bean'),
						'std' => ''
						),		
						
					array(
						'id' => 'post_options',
						'type' => 'multi_checkbox',
						'title' => __( 'Post Meta Options:', 'bean'), 
						'sub_desc' => __('Select which post meta you would like to display under the each post title.', 'bean'),
						'options' => array(
							'post_author'      => 'Author',
							'post_date'    	   => 'Publish Date',
							'post_category'    => 'Category',
							'post_comments'    => 'Comments',
							'post_tags'    	   => 'Tags',
						),
						
						'std' => array(
							'post_author'      => '0',
							'post_date'		   => '1',
							'post_category'    => '1',
							'post_comments'    => '1',
							'post_tags'    => '0',
						)
					),				
						
					array(
						'id' => 'blog_pagination',
						'type' => 'checkbox',
						'title' => __( 'Show Post Pagination', 'bean'), 
						'sub_desc' => __('Elect to display the previous/next arrows on the top of your single blog posts.', 'bean'),
						'desc' => __('Yes do it', 'bean'),
						'options' => array(
							'posts'     => 'Yes, do it.',
						),
						
						'std' => array(
						'posts'     => '1', 
						)
					),				
					
					array(
						'id' => 'post_share',
						'type' => 'checkbox',
						'title' => __( 'Display Share Buttons', 'bean'), 
						'sub_desc' => __('Elect to display the sharing box with Facebook & Twitter share buttons on the blog single posts.', 'bean'),
						'desc' => __('Yes do it', 'bean'),
						'std' => '1', 
						),	
						
					array(
						'id' => 'blog_about_author',
						'type' => 'checkbox',
						'title' => __( 'Show About the Author', 'bean'), 
						'sub_desc' => __('Elect to display the "About the Author" section below each blog post.', 'bean'),
						'desc' => __('Yes do it', 'bean'),
						'std' => '1', 
						),						
				)
			);							

$sections[] = array(
				'title' => __('Footer Settings', 'bean'),
				'desc' => __('<p class="description">Manage & customize the theme footer.</p>', 'bean'),
				'fields' => array(
					array(
						'id' => 'footer_logo',
						'type' => 'upload', 
						'title' => __('Upload Footer Logo', 'bean'),
						'sub_desc' => __('Upload your custom footer logo here. If left empty, the site title will be displayed instead.', 'bean'),
						),	
					
					array(
						'id' => 'footer_layout',
						'type' => 'radio_img',
						'title' => __('Footer Layout', 'bean'), 
						'sub_desc' => __('Please select a style for your site. There are 4 styles included in this theme.', 'bean'),
							'options' => array(
								'fullwidth' => array(
									'title' => 'Full', 
									'img' => RADIUM_OPTIONS_URL.'assets/images/full.png'
								),
								'split' => array(
									'title' => 'Split', 
									'img' => RADIUM_OPTIONS_URL.'assets/images/split.png'
								),
								'fullwidth_extra' => array(
									'title' => 'Full Extra', 
									'img' => RADIUM_OPTIONS_URL.'assets/images/full-extra.png'
								),
								'split_extra' => array(
									'title' => 'Split Extra', 
									'img' => RADIUM_OPTIONS_URL.'assets/images/split-extra.png'
								),
							),
							
						'std' => 'split_extra'
						),	
					
					array(
						'id' => 'social_checklist',
						'type' => 'multi_checkbox',
						'title' => __( 'Social Media Footer Icons', 'bean'), 
						'sub_desc' => __('Select which icons are displayed in your footer. Remember to set up your social profiles/URLs in the "Social Media" options tab.', 'bean'),
						'options' => array(
							'behance_select'    => 'Behance',
							'dribbble_select'   => 'Dribbble',
							'email_select'      => 'Email',
							'facebook_select'   => 'Facebook',
							'flickr_select'   	=> 'Flickr',
							'forrst_select'   	=> 'Forrst',
							'github_select'     => 'GitHub',
							'googleplus_select' => 'Google Plus',
							'instagram_select' 	=> 'Instagram', 
							'linkedin_select' 	=> 'LinkedIn',
							'pinterest_select'  => 'Pinterest',
							'rss_select'   		=> 'RSS',
							'twitter_select'    => 'Twitter',
							'youtube_select'    => 'YouTube',
							'zerply_select'     => 'Zerply',
					
						),
						
						'std' => array(
							'behance_select'    => '1',
							'dribbble_select'   => '1',
							'email_select'      => '1',
							'facebook_select'   => '1',
							'flickr_select'   	=> '1',
							'forrst_select'   	=> '1',
							'github_select'     => '1',
							'googleplus_select' => '1',
							'instagram_select' 	=> '1', 
							'linkedin_select' 	=> '1',
							'pinterest_select'  => '1',
							'rss_select'   		=> '1',
							'twitter_select'    => '1',
							'youtube_select'    => '1',
							'zerply_select'     => '1', 
						)
					),	
					
					array(
						'id' => 'copyright_text',
						'type' => 'textarea',
						'title' => __('Footer Copyright Text', 'bean'),
						'sub_desc' => __('This text overrides the default copyright message located in the theme footer.', 'bean'),
						'std' => '© 2013 <a href="http://themeforest.net/user/ThemeBeans/?ref=themebeans">Acute</a> WordPress Theme.'
						),
						
					array(
						'id' => 'other_analytics',
						'type' => 'textarea',
						'title' => __('Footer Analytics', 'bean'),
						'sub_desc' => __('Paste any analytics code that belongs before the closing body tag here.', 'bean'),
						'std' => ''
						),

					)
				);
					

$sections[] = array(
				'title' => __('Site Archives', 'bean'),
				'desc' => __('<p class="description">Customize the headers and content displayed on the Archives Template.</p>', 'bean'),
 				'fields' => array(
						array(
							'id' => 'archives_content',
							'type' => 'multi_checkbox',
							'title' => __( 'Archive Page Content', 'bean'), 
							'sub_desc' => __('Select which contexts are displayed on the Archives page.', 'bean'),
							'options' => array(
								'posts'    => 'All Posts',
								'latest'   => 'Latest Posts', 
								'month'    => 'Archives by Month',
								'category' => 'Archives by Category', 
								'pages'    => 'Site Map',
							),
							
							'std' => array(
								'posts'    => '1', 
								'latest'   => '1', 
								'category' => '1', 
								'month'    => '1',
								'pages'    => '0', 
							)
						),	
						
						array(
							'id' => 'archive_all_text',
							'type' => 'text',
							'title' => __('All Published Posts', 'bean'),
							'sub_desc' => __('Replace the text above the all posts archive content.', 'bean'),
							'std' => 'All Published Posts.'
							),	
							
						array(
							'id' => 'archive_latest',
							'type' => 'text',
							'title' => __('Last 30 Posts Header', 'bean'),
							'sub_desc' => __('Replace the text above the latest 30 posts archive content.', 'bean'),
							'std' => 'Last 30 Posts.'
							),
															
						array(
							'id' => 'archive_monthly',
							'type' => 'text',
							'title' => __('Monthly Archive Header', 'bean'),
							'sub_desc' => __('Replace the text above the monthly posts archive content.', 'bean'),
							'std' => 'Monthly Archives.'
							),	
							
						array(
							'id' => 'archive_category',
							'type' => 'text',
							'title' => __('Category Archive Header', 'bean'),
							'sub_desc' => __('Replace the text above the all category archive content.', 'bean'),
							'std' => 'Category Archives.'
							),	
							
						array(
							'id' => 'archive_sitemap',
							'type' => 'text',
							'title' => __('Site Map Header', 'bean'),
							'sub_desc' => __('Replace the text above the site map content.', 'bean'),
							'std' => 'Site Map.'
							),	
																							
					)
  				); 			
  				

$sections[] = array(
				'title' => __('404 Error Page', 'bean'),
				'desc' => __('<p class="description">Manage & customize your theme 404 error page.</p>', 'bean'),
 				'fields' => array(
 						array(
 							'id' => 'error_404_bg',
 							'type' => 'upload', 
 							'title' => __('Upload 404 Background Image', 'bean'),
 							'sub_desc' => __('Upload a custom background image to be displayed full screen on your 404 page.', 'bean'),
 							),
 							
						array(
							'id' => '404_error_header',
							'type' => 'text',
							'title' => __('Header Intro Text', 'bean'),
							'sub_desc' => __('Customize the text that is displayed 404 page header.', 'bean'),
							'std' => '404 Error.'
							),	
						
						array(
							'id' => '404_error_btn_text',
							'type' => 'text',
							'title' => __('Back Button', 'bean'),
							'sub_desc' => __('Customize the text that is displayed on the 404 Page "Back" button.', 'bean'),
							'std' => 'Head on Back'
							),	
						
						array(
							'id' => '404_error_text',
							'type' => 'textarea',
							'title' => __('404 Page Header', 'bean'),
							'sub_desc' => __('Customize the bold header text displayed under the "Page Not Found" on the 404 page.', 'bean'),
							'std' => 'Oh Snap! It appears the page you are looking for has completely disappeared. #shucks'
							),	
													
						array(
							'id' => '404_error_p_text',
							'type' => 'textarea',
							'title' => __('404 Page Paragraph', 'bean'),
							'sub_desc' => __('Customize the paragraph text displayed on the 404 page to say anything you would liket.', 'bean'),
							'std' => 'We hate these little bugs just as much as you do. Really. Customize this 404 page in the Theme Options Panel. No coding skills required... it just works.'
							),									
					)
  				);
  				
				
$sections[] = array(
				'title' => __('Social Media', 'bean'),
				'desc' => __('<p class="description">Social account which will populate corresponding parts throughout the theme.</p>', 'bean'),
 				'fields' => array(											
					
					array(
						'id' => 'profile_behance',
						'type' => 'text',
						'title' => __('Behance Profile URL', 'bean'),
						'sub_desc' => __('Enter your Behance public profile URL.', 'bean'),
						'std' => ''
						),
					
					array(
						'id' => 'profile_dribbble',
						'type' => 'text',
						'title' => __('Dribbble Username', 'bean'),
						'sub_desc' => __('Enter your Dribbble username.', 'bean'),
						'std' => 'ThemeBeans'
						),
					
					array(
						'id' => 'profile_email',
						'type' => 'text',
						'title' => __('Email Address', 'bean'),
						'sub_desc' => __('Enter your preferred contact email address.', 'bean'),
						'std' => 'hello@themebeans.com'
						),	
					
					array(
						'id' => 'profile_facebook',
						'type' => 'text',
						'title' => __('FaceBook URL', 'bean'),
						'sub_desc' => __('Enter your FaceBook URL.', 'bean'),
						'std' => 'ThemeBeans'
						),		
					
					array(
						'id' => 'profile_flickr',
						'type' => 'text',
						'title' => __('Flickr URL', 'bean'),
						'sub_desc' => __('Enter your Flickr URL.', 'bean'),
						'std' => ''
						),
					
					array(
						'id' => 'profile_forrst',
						'type' => 'text',
						'title' => __('Forrst Username', 'bean'),
						'sub_desc' => __('Enter your Forrst username.', 'bean'),
						'std' => ''
						),	
						
					array(
						'id' => 'profile_github',
						'type' => 'text',
						'title' => __('GitHub Username', 'bean'),
						'sub_desc' => __('Enter your GitHub username.', 'bean'),
						'std' => ''
						),	
						
					array(
						'id' => 'profile_google',
						'type' => 'text',
						'title' => __('Google Plus Profile URL', 'bean'),
						'sub_desc' => __('Enter your Google Plus public profile URL.', 'bean'),
						'std' => ''
						),
					
					array(
						'id' => 'profile_instagram',
						'type' => 'text',
						'title' => __('Instagram Username', 'bean'),
						'sub_desc' => __('Enter your Instagram username.', 'bean'),
						'std' => ''
						),		
					
					array(
						'id' => 'profile_linkedin',
						'type' => 'text',
						'title' => __('LinkedIn URL', 'bean'),
						'sub_desc' => __('Enter your LinkedIn profile URL.', 'bean'),
						'std' => ''
						),		
					
					array(
						'id' => 'profile_pinterest',
						'type' => 'text',
						'title' => __('Pinterest Username', 'bean'),
						'sub_desc' => __('Enter your Pinterest username.', 'bean'),
						'std' => ''
						),		
					
					array(
						'id' => 'profile_rss',
						'type' => 'text',
						'title' => __('RSS Subscribe URL', 'bean'),
						'sub_desc' => __('Enter your RSS Subscribe URL.', 'bean'),
						'std' => ''
						),
							
					array(
						'id' => 'profile_twitter',
						'type' => 'text',
						'title' => __('Twitter Username', 'bean'),
						'sub_desc' => __('Enter your Twitter username.', 'bean'),
						'std' => 'ThemeBeans'
						),	
					
					array(
						'id' => 'profile_youtube',
						'type' => 'text',
						'title' => __('YouTube URL', 'bean'),
						'sub_desc' => __('Enter your YouTube URL.', 'bean'),
						'std' => ''
						),	
						
					array(
						'id' => 'profile_zerply',
						'type' => 'text',
						'title' => __('Zerply Username', 'bean'),
						'sub_desc' => __('Enter your Zerply username.', 'bean'),
						'std' => ''
						),	
					)
  				); 
  				
				
								
  return $sections;
  
}

add_filter('radium-opts-sections-bean_theme', 'bean_load_framework_theme_options');

?>