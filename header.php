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


$container = get_theme_mod( 'epflsti_container_type' );
// hp Home Page URL (use polylang if available otherwise the WP site url)
$hp = (function_exists( 'pll_home_url' )) ? pll_home_url() : get_site_url();
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

<!-- Ugly hard coded menu -->
<div class="menu-container">

    <div class="menu">

        <!-- Your site logo -->
        <?php if ( ! has_custom_logo() ) { ?>
           <a href="https://www.epfl.ch"> <img width=174 id=epfl_logo src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/epfl.gif" /></a>
           <a href="<?php echo esc_url( home_url( '/' ) ); ?>"> <img id=sti_logo src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/sti.png" /></a>
        <?php } else {
            the_custom_logo();
        } ?><!-- end custom logo -->

        <div class="language">
            <?php // This uses polylang. Change the language full name to get the FR / EN (https://polylang.pro/doc/configure-the-languages/#full-name) ?>
            <ul class="epflstilangmenu"><?php if (function_exists('pll_the_languages')) { pll_the_languages(); } ?></ul>
        </div>

        <ul class="sti_menu_link_ul">
            <li>
                <?php echo "<!-- THE SCHOOL MENU -->"; echo get_page_by_title( 'menu-school-'. $cl )->post_content ; ?>
            </li>
            <li>
                <?php echo "<!-- THE EDUCATION MENU -->"; echo get_page_by_title( 'menu-education-'. $cl )->post_content ; ?>
            </li>
            <li>
                <?php echo "<!-- THE RESEARCH MENU -->"; echo get_page_by_title( 'menu-research-'. $cl )->post_content ; ?>
            </li>
            <li>
                <?php echo "<!-- THE INNOVATION MENU -->"; echo get_page_by_title( 'menu-innovation-'. $cl )->post_content ; ?>
            </li>
            <li>
                <?php echo "<!-- THE THEMES MENU -->"; echo get_page_by_title( 'menu-themes-'. $cl )->post_content ; ?>
            </li>
        </ul>
    </div>
</div>
<!-- End ugly hard coded menu -->
