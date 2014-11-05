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
	
	<!-- Loading Source Sans Pro -->
	<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600" rel="stylesheet" type="text/css" />
	
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<!-- Loading C3 + D3 JavaScript -->
	<script src="/wp-content/themes/acute/assets/js/d3.min.js"></script>
	<script src='/wp-content/themes/acute/assets/js/c3.js'></script>
	<link href='/wp-content/themes/acute/assets/css/c3.css' rel='stylesheet' />

	<?php $options = get_option('bean_theme'); bean_analytics(true); ?>

	<?php wp_head(); ?>	
</head>

<body <?php body_class(); ?>>

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
		
	<div id="header" class="animated BeanFadeIn">	
		<div id="row" class="row animated BeanFadeIn">

		  	<div class="six columns">	

			  	<div id="logo">

			  	 <?php if ( !dynamic_sidebar( 'Header' ) ): endif; ?>

			  	</div><!-- END #logo -->

		  	</div><!-- END .six columns -->


		  	<div class="twelve columns hide-for-small">
				<div class="columns" style="padding-top: 15px;">
		  			<?php do_action( 'bean_header_logo' ); ?>
		  		</div>

		  	<div class="seven columns hide-for-small" style="padding-top: 15px;">

			  	<?php if ( bean_theme_supports( 'primary', 'menu') ){ ?>

	  				<nav id="navigation" role="navigation">

	  					<div class="container">

	  						<div id="primary-nav" class="main_menu">

	  							<!-- taking out the automatically generated menu
	  							<?php
	  								$args = array(
	  								    'container' 	=> '',
	  								    'menu_id' => 'primary-menu',
	  								    'menu_class' => 'sf-menu main-menu',
	  									'theme_location' => 'main-menu',
	  									'after' => '<span class="nav-sep">|</span>',
	  								);

	  								wp_nav_menu( apply_filters( 'radium_main_menu_args', $args ) );
	  							 ?>
	  							 -->

									<ul id="primary-menu" class="sf-menu main-menu sf-js-enabled"><li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1040"><a href="https://data.hdx.rwlabs.org/dataset">DATA</a><span class="nav-sep">|</span></li>
									<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1062"><a href="https://data.hdx.rwlabs.org/group">COUNTRIES</a><span class="nav-sep">|</span></li>
									<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1038"><a href="https://data.hdx.rwlabs.org/organization">ORGANISATIONS</a><span class="nav-sep">|</span></li>
									<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item menu-item-1054"><a href="/blog">BLOG</a><span class="nav-sep">|</span></li>
									<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1039"><a href="https://data.hdx.rwlabs.org/about">ABOUT</a></li>
									<a id="feedback-button" href="/get-involved">FEEDBACK</a>
									</ul>


	  						</div><!-- END #primary-nav .main_menu -->

	  					</div><!-- END .container -->

	  				</nav><!-- END #navigation -->

			  		<?php } // END bean_theme_supports( 'primary', 'menu') ?>
			  	</div><!-- END .ten columns hide-for-small -->
			  	<div class="three columns center submitData">	
                    <a href="http://data.hdx.rwlabs.org/dataset/preselect">
                      SUBMIT DATA
                      <img src="/wp-content/uploads/attachment.svg" style="width: 24px; height: 21px; margin-left: 20px;">
                    </a>
		  		</div><!-- END .submit data columns -->
			</div><!-- END .twelve columns hide-for-small -->
		</div>	
 	</div><!-- END #header row -->

 	<header id="page-header" class="clear animated BeanFadeIn">

 		 <div class="row">

 		 	<div class="twelve columns">

				<div id="header-title">

					<div class="ten columns">
						<?php echo do_shortcode( "[rotatingtweets screen_name='humdata']" ) ?>
					</div>
					<div class="two columns">
						<a href="http://www.twitter.com/humdata" title="Twitter" class="more-link twitterSpecial">FOLLOW US</a>
					</div>

				</div><!-- END #header-title -->

 	      	</div><!-- END .twelve columns -->

 	    </div><!-- END .row-->

 	</header><!-- END #page-header -->

 <?php } ?>

<section id="main-container" class="BlogFrontPage clear animated BeanFadeIn">