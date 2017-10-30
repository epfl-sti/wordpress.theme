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
<br><br><br>
<div style="position:relative; top:-80px">


 <img width=100% src="<?php echo get_stylesheet_directory_uri(); ?>/img/LacourTeam.jpg">

 <div style="height:0px;">
  <div id=containernews class=containernews>
   <a class="titlelink" href=#>A long-term implant to restore walking</a><br>
   <a class="titlelink subtitlelink" href=#>Prof. Stéphanie Lacour of the Institute of Bioengineering</a><br>
  </div>
 </div>

<div class="flex-container">
 <div>
  <?php echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&id=news&lang=eng&thunderbird=researchvideo"); ?>
 </div>
 <div>
  <?php echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&id=researchvideo&lang=eng&thunderbird=researchvideo"); ?>
 </div>
 <div>
  <?php echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&id=inthenews&lang=eng&thunderbird=researchvideo"); ?>
 </div>
 <div>
  <?php echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&id=testimonials&lang=eng&thunderbird=researchvideo"); ?>
 </div>
 <div>
  <?php echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&id=campus&lang=eng&thunderbird=researchvideo"); ?>
 </div>
 <div>
  <?php echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&id=appointments&lang=eng&thunderbird=researchvideo"); ?>
 </div>
 <div>
  <?php echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&id=whatis&lang=eng&thunderbird=researchvideo"); ?>
 </div>
 <div>
  <?php echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&id=research&lang=eng&thunderbird=researchvideo"); ?>
 </div>
 <div>
  <?php echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&id=seminar&lang=eng&thunderbird=researchvideo"); ?>
 </div>
 <div>
  <?php echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&id=placement&lang=eng&thunderbird=researchvideo"); ?>
 </div>
 <div>
  <?php echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&id=masters&lang=eng&thunderbird=researchvideo"); ?>
 </div>
 <div>
  <?php echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&id=contact&lang=eng&thunderbird=researchvideo"); ?>
 </div>
</div>

</div>
<?php get_footer(); ?>

