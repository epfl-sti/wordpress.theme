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

<div>

 <div class=div-wrapper id=containerwave style="background-image:url('<?php echo get_stylesheet_directory_uri(); ?>/img/LacourTeam.jpg');">
  <img src=<?php echo get_stylesheet_directory_uri(); ?>/img/waving.png>
 </div>

 <div class="pixelman">
  <div id=containercalendar>
   <table cellpadding=16 style="background-image:url('https://stisrv13.epfl.ch/proposals/darkpixel.png');"><td>
   <table>
    <tr>
     <td>
      <a href=#>
       <img src=<?php echo get_stylesheet_directory_uri(); ?>/img/src/upcoming_events.png><br><br>
      </a> 
     </td>
    </tr>
    <tr>
     <td width=280 style="background-color:#fff; padding: 8px;">
      Events
     </td>
    </tr>
   </table>
   </td></table>
  </div>
 </div>
</div>
 <div class="pixelman">
  <div id=containernews>
   <a class="titlelink" href=#>A long-term implant to restore walking</a><br>
   <a class="titlelink subtitlelink" href=#>Prof. Stéphanie Lacour of the Institute of Bioengineering</a><br>
  </div>
 </div>
</div>
<div class="news">
  <?php
    $atts = array('tmpl' => 'bootstrap-card', 'number' => 14);
    echo epfl_actu_wp_shortcode($atts);

    $newsids = ["news", "researchvideo", "inthenews", "testimonials", "campus", "appointments", "whatis", "research", "placement", "masters"];
    foreach ($newsids as $newsid) {
//    	$newshtml = curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=" . $newsid);
        echo "<div>$newshtml</div>";
    }

  ?>
</div>
<?php get_footer(); ?>
