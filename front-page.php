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

?>
<style>
 div.news {
   display: flex;
   flex-direction: row;
   flex-wrap: wrap;
   justify-content: space-around;
 }
</style>

<div>


 <img width=100% src="<?php echo get_stylesheet_directory_uri(); ?>/img/LacourTeam.jpg">

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
