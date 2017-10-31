<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package epflsti
 */

get_header();

$container   = get_theme_mod( 'epflsti_container_type' );
$sidebar_pos = get_theme_mod( 'epflsti_sidebar_position' );

## You can look at the output of error_log with the following command:
# docker exec -it jahia2wp_httpd_1 tail  -fsrv/test/logs/error_log
error_log("is_front_page() is " + is_front_page());
error_log("is_home() is " + is_home());

?>
<img width=100% src="<?php echo get_stylesheet_directory_uri(); ?>/img/LacourTeam.jpg">

<div>



<?php //echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?id=researchvideo&lang=eng&thunderbird=researchvideo");

?>

<?php get_footer(); ?>
