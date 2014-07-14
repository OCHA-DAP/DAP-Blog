<?php /* Default Template */ ?>

<!DOCTYPE html>

<!-- DESIGN & CODE BY THEMEBEANS OF WWW.THEMEBEANS.COM -->

<html <?php language_attributes(); ?>> <!-- for left to right languages -->

<head>
	<!-- META TAGS -->
	<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- TITLE -->
	<title><?php if ( defined('WPSEO_VERSION') ) { wp_title(''); } else { if(is_home() OR is_404() OR is_search() ) { echo bloginfo("name"); echo " | "; echo bloginfo("description"); } else { echo bloginfo("name"); echo " | "; echo get_the_title();  } } ?></title>

	<!-- RSS & PINGBACKS -->
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?> RSS Feed" href="<?php bloginfo( 'rss2_url' ); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" /> 
	<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600" rel="stylesheet" type="text/css" />

	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<?php $options = get_option('bean_theme'); bean_analytics(true); ?>

	<?php wp_head(); ?>	
</head>

<body <?php body_class(); ?>>
<!-- the 'development site' ribbon
<a href="http://localhost:8888/development-site/"><img style="position: absolute; top: 0; right: 0; border: 0;" src="http://localhost:8888/wp-content/uploads/2014/01/dap-dev-ribbon-right.png" alt="This is a DEVELPMENT page."></a> -->

<?php if (is_404()) { } else { //HIDE HEADER FOR 404 PAGE ?>

 	<header>
 		<nav id="mobile-nav">

 		<?php if ( function_exists('wp_nav_menu') ) {
 			wp_nav_menu( array(
 				'theme_location' => 'main-menu'
 			));
 		} ?>

		</nav><!-- END #mobile-nav -->
	</header>

	<?php do_action('bean_announcement'); // CODE PULLED FROM THEME-FUNTIONS.PHP ?>

	<?php do_action('bean_sliding_panel'); // CODE PULLED FROM THEME-FUNTIONS.PHP ?>	

	<div id="header" class="row animated BeanFadeIn">

	  	<div class="six columns">

	  	<!-- Logo
		  	<div id="logo">

		  		<?php do_action( 'bean_header_logo' ); ?>

		  	</div>
		 -->

	  	</div><!-- END .six columns -->

	  	<div class="six columns right">	

	  		<?php if ( !dynamic_sidebar( 'Header' ) ): endif; ?>

	  	</div><!-- END .six columns -->

	  	<div class="twelve columns hide-for-small">

		  	<?php if ( bean_theme_supports( 'primary', 'menu') ){ ?>

  				<nav id="navigation" role="navigation">

  					<div class="container">

  						<div id="primary-nav" class="main_menu">

  							<?php
  								$args = array(
  								    'container' 	=> '',
  								    'menu_id' => 'primary-menu',
  								    'menu_class' => 'sf-menu main-menu',
  									'theme_location' => 'main-menu',
  									'after' => '<span class="nav-sep">/</span>',
  								);

  								wp_nav_menu( apply_filters( 'radium_main_menu_args', $args ) );
  							 ?>

  						</div><!-- END #primary-nav .main_menu -->

  					</div><!-- END .container -->

  				</nav><!-- END #navigation -->

		  	<?php } // END bean_theme_supports( 'primary', 'menu') ?>

		  </div><!-- END .twelve columns hide-for-small -->

 	</div><!-- END #header row -->

 	<header class="clear animated BeanFadeIn">

 		 <div class="row">

 		 	<div class="twelve columns">

				<div id="header-title" class="">

					<div id="taglinewrapper">

		<p style="font-family: 'Source Sans Pro';
							     font-size: 35px;
							     color: #0988bb;
							     text-align: left;
							     font-weight: 600;
							     line-height: 30px;
							     ">
							      	<img src = "../wp-content/uploads/logo-placeholder-main.png" />

							     </p>
						<!-- Tagline -->
						<section id="tagline">
						<p style = "color: #0988bb;
									font-size: 21px;
									line-height: 35x;">
						A project by the United Nations Office for the Coordination of Humanitarian Affairs to make humanitarian data easy to find and use for analysis.
						</p>

						<!-- Background Image -->

						<!-- <img src = "http://localhost:8888/wp-content/uploads/bg-network-grey.png" /> -->

						</section>

					</div>

				</div><!-- END #header-title -->

 	      	</div><!-- END .twelve columns -->

 	    </div><!-- END .row-->

 	</header><!-- END #page-header -->

 <?php } ?>

<section id="main-container" class="clear animated BeanFadeIn">

<div class="row">

<div id="main" class="twelve columns clearfix" role="main">
	<?php 

		while ( have_posts() ) : the_post();

			get_template_part( 'lib/content/content', 'page' ); 

		endwhile; // END OF THE LOOP

	?>

</div><!-- END #main -->

<?php get_footer(); ?>