<?php
/**
 * The template for displaying the front page.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package understrap
 */

get_header();

$container   = get_theme_mod( 'understrap_container_type' );
$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );

?>
<br><br><br>
<img width=100% src="<?php echo get_stylesheet_directory_uri(); ?>/img/LacourTeam.jpg">

<div class="from-stisrv13">
<?php echo html_body(curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?id=researchvideo&lang=eng&thunderbird=researchvideo"));

?>
</div>

<!-- end transclusion -->

<?php get_footer(); ?>

