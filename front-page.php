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

<div>
    <div class="pixelman">
        <div id="containercalendar">
            <table cellpadding="16" style="background-image:url('https://stisrv13.epfl.ch/proposals/darkpixel.png');">
                <td>
                    <table>
                        <tr>
                            <td>
                                <a href="#">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/upcoming_events.png" />
                                    <br />
                                    <br />
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td width="280" style="background-color:#fff; padding: 8px;">
                                Events
                            </td>
                        </tr>
                    </table>
                </td>
            </table>
        </div>
    </div>
</div>
<div class="div-wrapper" id="containerwave" style="">
   <div class="pixelman">
      <div id="sti-homecarousel" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner" role="listbox">

              <div class="carousel-item">
                  <div class="sti_carousel">
                      <div class="div-wrapper" id="containerwave" style="background-image:url('<?php echo get_stylesheet_directory_uri(); ?>/img/ProfCamilleBres.jpg');">
                          <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/waving.png">
                      </div>
                      <div class="pixelman">
                          <div id="containernews">
                              <a class="titlelink" href="http://sti.epfl.ch/page-108381.html#anchor2019">Early career award in photonics </a><br>
                              <a class="titlelink subtitlelink" href="http://sti.epfl.ch/page-108381.html#anchor2019">Prof. Camille Brès has received the Early Career Women/Entrepreneur award in Photonics</a><br>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="carousel-item">
                  <div class="sti_carousel">
                      <div class="div-wrapper" id="containerwave" style="background-image:url('<?php echo get_stylesheet_directory_uri(); ?>/img/LacourTeam.jpg');">
                          <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/waving.png">
                      </div>
                      <div class="pixelman">
                          <div id="containernews">
                              <a class="titlelink" href="#">A long-term implant to restore walking</a><br>
                              <a class="titlelink subtitlelink" href="#">Prof. Stéphanie Lacour of the Institute of Bioengineering</a><br>
                          </div>
                      </div>
                  </div>
              </div><!-- .carousel-item -->
            </div>
            <a class="sti-carousel-button prev" href="#sti-homecarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="sti-carousel-button next" href="#sti-homecarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div><!-- .sti-homecarousel -->
    </div>
</div>

<script>
    jQuery(function() {
        window.frontpage_init();
    });
</script>

<div class="news">
  <?php
    $atts = array('tmpl' => 'bootstrap-card', 'number' => 14);
    echo epfl_actu_wp_shortcode($atts);

    $newsids = ["news", "researchvideo", "inthenews", "testimonials", "campus", "appointments", "whatis", "research", "placement", "masters"];
    foreach ($newsids as $newsid) {
        $newshtml = curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=" . $newsid);
        echo "<div class=\"sti_news_html\">$newshtml</div>";
    }

  ?>
</div>
<?php get_footer(); ?>
