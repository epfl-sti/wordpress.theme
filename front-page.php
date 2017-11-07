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
<div style="position:relative; top:-53px">


 <img width=100% src="<?php echo get_stylesheet_directory_uri(); ?>/img/LacourTeam.jpg">

 <style>
  div.news {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-around;
  }
 </style>


 <div style="height:0px;">
  <div id=containernews class=containernews>
   <a class="titlelink" href=#>A long-term implant to restore walking</a><br>
   <a class="titlelink subtitlelink" href=#>Prof. Stéphanie Lacour of the Institute of Bioengineering</a><br>
  </div>
 </div>

 <div class="news">
   <?php
   $atts = array('tmpl' => 'bootstrap-card', 'number' => 14);
   echo epfl_actu_wp_shortcode($atts);

   $newsids = ["news", "researchvideo", "inthenews", "testimonials", "campus", "appointments", "whatis", "research", "placement", "masters"];
   foreach ($newsids as $newsid) {
    	$newshtml = curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=" . $newsid);
        echo "<div>$newshtml</div>";
   }
   ?>
 </div>
<?php get_footer(); ?>

