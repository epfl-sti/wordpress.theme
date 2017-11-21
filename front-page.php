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
                    <table class="slider-event-table">
                        <tr>
                            <td>
                                <a href="#">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/upcoming_events.png" />
                                </a>
                            </td>
                        </tr>
                        <?php
                          $events = get_memento_events($url='https://memento.epfl.ch/api/jahia/mementos/sti/events/en/?format=json', $limit=4);
                          foreach ($events as $event) {
                             $event_day = date("d", strtotime($event->event_start_date));
                             $event_month = strtoupper(date("M", strtotime($event->event_start_date)));
                        ?>
                            <tr class="slider-event-row" data-link="<?php echo $event->absolute_slug; ?>">
                                <td class="slider-event-cell">
                                    <div class="slider-event-date">
                                       <span class="slider-event-date-day">
                                          <?php echo $event_day; ?>
                                       </span>
                                       <span class="slider-event-date-month">
                                          <?php echo $event_month; ?>
                                       </span>
                                    </div>
                                    <div class="slider-event-title">
                                       <?php echo $event->title; ?><br />
                                    </div>
                                </td>
                            </tr>
                        <?php
                          }
                         ?>
                    </table>
                </td>
            </table>
        </div>
    </div>
</div>
<script>
// For the events in the slider
$( "div.slider-event-date" )
  .mouseenter(function() {
    $( this ).css( { backgroundColor: "#55576A", color: "#FA2400", "font-weight": "bold" })
    $( this ).parent().css({ "border-right": "1px solid #FA2400" });
  })
  .mouseleave(function() {
    $( this ).css( { backgroundColor: "#fff", color: "#000", "font-weight": "normal" })
    $( this ).parent().css({ "border-right": "1px solid #fff" });

  });
$( "tr.slider-event-row" )
  .click(function() {
    window.location = $( this ).data("link");
    return true;
   });
</script>
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
