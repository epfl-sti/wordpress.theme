<?php
/**
 * The template for displaying all pages.
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

## You can look at the output of error_log with the following command:
# docker exec -it jahia2wp_httpd_1 tail  -fsrv/test/logs/error_log
error_log("is_front_page() is " + is_front_page());
error_log("is_home() is " + is_home());

?>
<img width=100% src="<?php echo get_stylesheet_directory_uri(); ?>/img/LacourTeam.jpg" style="padding-top: 40px">

<style>
  div.news {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
  }
</style>

<div class="news">
  <?php for ($i=0; $i < 20; $i++) { ?>
    <div class="card" style="width: 20rem;">
    <img class="card-img-top" src="https://placehold.it/500x400" alt="Card image cap">
    <div class="card-body">
      <h4 class="card-title">EPFL</h4>
      <p class="card-text">This is the new #<?php echo $i?></p>
      <a href="#" class="btn btn-primary">Read more</a>
    </div>
    </div>
  <?php } ?>
</div>



<?php //echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?id=researchvideo&lang=eng&thunderbird=researchvideo");

?>

<?php get_footer(); ?>
