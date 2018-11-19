<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package epflsti
 */

require_once(__DIR__.'/inc/epfl.php');
use function EPFL\STI\{ get_current_language, get_institute, get_school_name_parts };

$cl = get_current_language();
$homelink = ($cl == 'fr') ? '/fr/' : '/';
$institute = get_institute();

if ($institute) {
    $nav_menu_slug = "primary-" . $institute->get_code();
    $topbar_class = sprintf(' class="topbar-%s"', $institute->get_code());
} else {
    $nav_menu_slug = 'primary';
    $topbar_class = '';
}

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
  <link rel="stylesheet" type="text/css" href="theme-epfl-sti/node_modules/npm-font-open-sans/open-sans.css">
  <link href='<?php echo get_stylesheet_directory_uri(); ?>/css/firststep.css' rel='stylesheet' type='text/css'>
  <!-- end custom -->

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="header" class="container-fluid px-0">
  <topbar<?php echo $topbar_class; ?>>
    <div class="language">
     <?php // This uses polylang. Change the language full name to get the FR / EN (https://polylang.pro/doc/configure-the-languages/#full-name) ?>
     <ul class="epflstilangmenu"><?php if (function_exists('pll_the_languages')) { pll_the_languages(); } ?></ul>
    </div>
    <?php if ( ! has_custom_logo() ) { ?>
     <div id="epfl-logo"><a href="https://www.epfl.ch"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/epfl.gif" /></a></div>
     <div id="sti-mini-nav">
      <?php
      list($firstline_school, $secondline_school) = get_school_name_parts();
      if ($institute) {
        list($firstline, $secondline) = $institute->get_name_parts();
        $url = home_url('/' . $institute->get_code());
        ?>
        <span class="sti-breadcrumb"><a href="<?php echo $homelink; ?>"><span class="firstline"><?php echo $firstline_school ?></span> <span class="secondline"><?php echo $secondline_school ?></span></a>&nbsp;&gt;</span>
        <br />
        <a class="text-logo" href="<?php echo esc_url($url); ?>">
          <span class="firstline"><?php echo $firstline ?></span>
          <br />
          <span class="secondline"><?php echo $secondline ?></span>
        </a>
        <?php
      } else {
        $url = home_url('/');
        ?>
        <a class="text-logo" href="<?php echo $homelink; ?>">
          <span class="firstline"><?php echo $firstline_school ?></span>
          <br />
          <span class="secondline"><?php echo $secondline_school ?></span>
        </a>
        <?php
      }
      ?>
    </div>
    <?php
    } else {
      the_custom_logo();
    } ?>

    <div class="searchbox" tabindex="1">
      <form action="/">
        <input type="text" placeholder="search" name="s">
        <a class="button">
          <i class="fa fa-search"></i>
        </a>
      </form>
    </div>
  </topbar>
  <megamenu>
    <?php wp_nav_menu( array(
      'theme_location'  => $nav_menu_slug,
    )); ?>
  </megamenu>
</div>
<div class="m_gris"></div>
