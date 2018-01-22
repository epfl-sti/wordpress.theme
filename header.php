<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package epflsti
 */

use function EPFL\STI\{ get_current_language };
$cl = get_current_language();

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <!-- Custom -->
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/normalize.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/ionicons.min.css">
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300' rel='stylesheet' type='text/css'>
    <link href='<?php echo get_stylesheet_directory_uri(); ?>/css/firststep.css' rel='stylesheet' type='text/css'>
    <!-- end custom -->

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="header" class="menu-container container-fluid">

    <div id="megamenu" class="menu row no-gutters">

        <?php # Site logo and top-most navigation in a 0-height div ?>
        <topbar>
            <?php if ( ! has_custom_logo() ) { ?>
               <a href="https://www.epfl.ch"> <img width=174 id=epfl_logo src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/epfl.gif" /></a>
               <a href="<?php echo esc_url( home_url( '/' ) ); ?>"> <img id=sti_logo src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/sti.png" /></a>
            <?php } else {
                the_custom_logo();
            } ?>

            <div class="language">
                <?php // This uses polylang. Change the language full name to get the FR / EN (https://polylang.pro/doc/configure-the-languages/#full-name) ?>
                <ul class="epflstilangmenu"><?php if (function_exists('pll_the_languages')) { pll_the_languages(); } ?></ul>
            </div>
            <div class="searchbox" tabindex="1">
             <form action="/">
              <input type="text" placeholder="search" name="s">
              <a class="button">
               <i class="fa fa-search"></i>
              </a>
             </form>
            </div>
        </topbar>

        <?php wp_nav_menu( array(
          'theme_location'  => 'primary',
	  'container_class' => 'collapse navbar-collapse',
	  'container_id'    => 'navbarNavDropdown',
	  'menu_class'      => 'navbar-nav',
	  'fallback_cb'     => '',
	  'menu_id'         => 'main-menu',
        )); ?>
    </div>
</div>
