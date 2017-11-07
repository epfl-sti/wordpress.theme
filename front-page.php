<?php
/**
 * The template for displaying the front page.
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
<style>
 div.news {
   display: flex;
   flex-direction: row;
   flex-wrap: wrap;
   justify-content: space-around;
 }
</style>

<img width=100% src="<?php echo get_stylesheet_directory_uri(); ?>/img/LacourTeam.jpg">

<div class="news">
  <?php
  echo html_body(curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?id=researchvideo&lang=eng&thunderbird=researchvideo"));

  $atts = array('tmpl' => 'bootstrap-card', 'number' => 20);
  echo epfl_actu_wp_shortcode($atts);
  ?>
</div>

<!-- end transclusion -->

<?php get_footer(); ?>
