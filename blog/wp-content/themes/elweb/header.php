<!DOCTYPE html>
<!--
Based on 'eleb' theme and 'Strongly Typed' by HTML5. Licenses and links on https://github.com/OCHA-DAP/DAP-Blog
-->

<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('&mdash;', 1, 'right'); ?><?php bloginfo('name'); ?></title>

    <meta name="description" content="<?php if (have_posts() && !is_home()): while (have_posts()): the_post(); echo strip_tags(get_the_excerpt()); endwhile; else: echo $GLOBALS['_DEFAULT_BLOG_DESCRIPTION']; endif;?>" />

    <meta name="keywords" content="<?php echo $GLOBALS['_DEFAULT_BLOG_KEYWORDS']; ?>,<?php $tags=get_the_tags();foreach($tags as $tag){echo $tag->name . ",";}?>"/>

    <?php if($GLOBALS['_DEFAULT_PUBLISHER']): ?>
        <meta name="publisher" content="<?php echo $GLOBALS['_DEFAULT_PUBLISHER']; ?>">
    <?php endif; ?>

    <?php if($GLOBALS['_DEFAULT_YKEY']): ?>
        <meta name="y_key" content="<?php echo $GLOBALS['_DEFAULT_YKEY']; ?>" />
    <?php endif; ?>

    <?php if($GLOBALS['_DEFAULT_MSVALIDATE']): ?>
        <meta name="msvalidate.01" content="<?php echo $GLOBALS['_DEFAULT_MSVALIDATE']; ?>" />
    <?php endif; ?>

    <?php if($GLOBALS['_DEFAULT_MSVALIDATE']): ?>
        <meta name="readability-verification" content="<?php echo $GLOBALS['_DEFAULT_READABILITY']; ?>"/>
    <?php endif; ?>

    <meta name="title" content="<?php wp_title('&mdash;', 1, 'right'); ?><?php bloginfo('name'); ?>" />
    <meta name="viewport" content="width=device-width">

<?php if(is_home()):?>
    <meta property="og:title" content="<?php echo $GLOBALS['_DEFAULT_OG_TITLE']; ?>"/>
    <meta property="og:type" content="<?php echo $GLOBALS['_DEFAULT_OG_TYPE']; ?>"/>
    <meta property="og:url" content="<?php bloginfo('url'); ?>"/>
    <meta property="og:image" content="<?php bloginfo('template_directory'); ?>/img/apple-touch-icon.png"/>
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>"/>
    <meta property="fb:admins" content="<?php echo $GLOBALS['_DEFAULT_OG_ADMINS']; ?>"/>
<? else: ?>
    <meta property="og:title" content="<?php wp_title('', 1, 'right'); ?>"/>
    <meta property="og:description" content="<?php echo strip_tags(get_the_excerpt()); ?>" />
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="<?php echo the_permalink(); ?>"/>
    <meta property="og:image" content="<?php echo catch_that_image(); ?>"/>
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>"/>
    <meta property="fb:admins" content="<?php echo $GLOBALS['_DEFAULT_OG_ADMINS']; ?>"/>
<? endif; ?>

    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/img/favicon.ico">
    <link rel="apple-touch-icon" href="<?php bloginfo('template_directory'); ?>/img/apple-touch-icon.png">
    <link rel="alternate" href="<?php echo $GLOBALS['_DEFAULT_RSSFEED_URL']; ?>" type="application/rss+xml" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <!-- HTML5 JS + CSS --> 
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="viewport" content="width=1040" />
        <!-- <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600|Arvo:700" rel="stylesheet" type="text/css" /> -->
        <link href='http://fonts.googleapis.com/css?family=Volkhov:400,700' rel='stylesheet' type='text/css'>
        <!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.dropotron.js"></script>
        <script src="js/config.js"></script>
        <script src="js/skel.min.js"></script>
        <script src="js/skel-panels.min.js"></script>
        <noscript>
            <link rel="stylesheet" href="css/skel-noscript.css" />
            <link rel="stylesheet" href="css/style.css" />
            <link rel="stylesheet" href="css/style-desktop.css" />
        </noscript>


    <?php wp_head(); ?>
</head>

<body class="left-sidebar"
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->

    <div class="header-wrapper">
        <div id="header" class="container">

                <!-- Logo -->
                <h1 id="logo"><a href="#">DAP <i>Blog</i></a></h1>
                <p>Blog roll for OCHA's Data & Analysis Project.</p>
                        
            <nav class="nav">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex6-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/logo@2x.png" alt="<?php bloginfo('name');?>" height="50" width="50"><span class="navbar-sitename"><?php bloginfo('name'); ?></span></a>
                </div> <!-- .navbar-header -->
                <div class="collapse navbar-collapse navbar-ex6-collapse">
                    <ul class="nav navbar-nav">
                        <?php wp_nav_menu( array('theme_location' => 'MobileNavigation', 'items_wrap'=> '%3$s', 'container'=> '')); ?>
                    </ul>
                </div><!-- .navbar-collapse -->
            </nav> <!-- .navbar-default -->
    </div>
    </div> <!-- .mobile-nav-wrapper -->
