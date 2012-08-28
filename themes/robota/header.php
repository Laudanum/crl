<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title(); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/scripts/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>

</head>

<body <?php body_class() ?>>
	<div id="wrapper">
    	<div id="header-wrap">
            <header id="header">
                <a href="<?php echo home_url( '/' ); ?>" rel="home">
                <<?php esplanade_title_tag( 'site' ); ?> id="site-title"><?php bloginfo( 'name' ); ?></<?php esplanade_title_tag( 'site' ); ?>>
                <?php if( ! is_active_sidebar( 1 ) ) : ?>
                    <<?php esplanade_title_tag( 'desc' ); ?> id="site-description"><?php bloginfo( 'description' ); ?></<?php esplanade_title_tag( 'desc' ); ?>>
                <?php endif; ?>
                </a>
                
                <nav id="access">
                    <a class="nav-toggle" href="#">Navigation</a>
                    <?php wp_nav_menu( array( 'theme_location' => 'primary_nav' ) ); ?>
                    <div class="clear"></div>
                </nav><!-- #access -->
        </div><!-- #header-wrap -->