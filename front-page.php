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

use function EPFL\STI\curl_get;

dynamic_sidebar( 'homepage' ); ?>
<center>
 <div class='secondaryrow whitebg'>
  <div class=secondarytitle>EDUCATION</div>
   <?php
    echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=testimonials&baseurl=/wp-content");
    echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=placement&baseurl=/wp-content");
    echo curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=masters&baseurl=/wp-content");
   ?>
 </div>
</center>
<center>
 <div class='secondaryrow whitebg'>
  <div class=secondarytitle>RESEARCH</div>
   <?php
    $output=curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=news&baseurl=/wp-content");
    echo str_replace("educationbg","researchbg",$output);
    $output=curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=research&baseurl=/wp-content");
    echo str_replace("educationbg","researchbg",$output);
    $output=curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=campus&baseurl=/wp-content");
    echo str_replace("educationbg","researchbg",$output);
   ?>
  </div>
 </div>
</center>
<center>
 <div class='secondaryrow whitebg'>
  <div class=secondarytitle>INNOVATION</div>
   <?php
    $output=curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=researchvideo&baseurl=/wp-content");
    echo str_replace("educationbg","innovationbg",$output);
    $output=curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=whatis&baseurl=/wp-content");
    echo str_replace("educationbg","innovationbg",$output);
    $output=curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=inthenews&baseurl=/wp-content");
    echo str_replace("educationbg","innovationbg",$output);
   ?>
 </div>
</center>
<br><br><br><br>

  <!--- Begin inline sti-shortcut-menu
  <div class="sti-shortcut-menu">
    <div class="sti-shortcut-menu-flex">
      <div class="sti-link-box">
        <a href="#" class="sti-link-box-a">SCHOOL</a>
      </div>
      <div class="sti-link-box">
        <a href="#" class="sti-link-box-a">EDUCATION</a>
      </div>
      <div class="sti-link-box">
        <a href="#" class="sti-link-box-a">SEMINARS</a>
      </div>
      <div class="sti-link-box">
        <a href="#" class="sti-link-box-a">CENTRES</a>
      </div>
      <div class="sti-link-box">
        <a href="#" class="sti-link-box-a">SERVICES</a>
      </div>
    </div>
  </div>
  End sti-shortcut-menu -->


<?php get_footer(); ?>
