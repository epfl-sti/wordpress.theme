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

use function EPFL\STI\{ get_events_from_memento,
                        get_news_from_actu,
                        curl_get };

?>

<div>
    <div class="pixelman">
        <div style='display:none;' id="containercalendar">
            <table cellpadding="16" style="background-color:#43455a;">
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
                          $url='https://memento.epfl.ch/api/jahia/mementos/sti/events/' . substr(get_locale(),0,2) . '/?category=CONF&format=json';
                          echo "<!-- MEMENTO URL = " . $url . "  -->";
                          $events = get_events_from_memento($url, $limit=4);
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
                                       <?php
                                       $max_len = 63;
                                       $s = $event->title;
                                       if (strlen($event->title) > $max_len) {
                                         $offset = ($max_len - 3) - strlen($event->title);
                                         $s = substr($event->title, 0, strrpos($event->title, ' ', $offset)) . '…';
                                       };
                                       echo $s;
                                       ?><br />
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
<?php require dirname(__FILE__) . "/widgets/carousel.php"; ?>
<center>
 <div class=frontrow>
  <div class=frontrowcontainer>
   <div class=frontrowheader>
    RESEARCH&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span class=frontrowred>NEWS</a>
   </div>
   <div class=frontrowcontent>
<?php

$epfl_news = get_news_from_actu();
foreach ($epfl_news as $new) {
 if ($x<3) {
  echo "<div class=frontrownews style=\"background-image:url('$new->news_large_thumbnail_absolute_url');\">";
  echo "<a class=whitelink href=$new->absolute_slug>"; 
  echo "<div class=frontrownewstitle>";
  echo $new->title;
  echo "</div>";
  echo "</a></div>";
 }
 $x++;
}

?>

   </div>
  </div>

  <div class=frontrowcontainer>
   <div class=frontrowheader>
    SCHOOL OF
    <span class=frontrowred>ENGINEERING
   </div>
   <div class=frontrowlistbox>
    <ul class=frontrowlist>
	<li><a href=#>About</a></li>
	<li><a href=#>Administration</a></li>
	<li><a href=#>Faculty</a></li>
	<li><a href=#>Education</a></li>
	<li><a href=#>Research</a></li>
	<li><a href=#>Innovation</a></li>
	<li><a href=#>Themes</a></li>
    </ul> 
    <ul class=frontrowlist>
	<li><a href=#>News</a></li>
	<li><a href=#>Open Facuilty Positions</a></li>
   </div>
  </div>

  <div class=frontrowcontainer>
   <div class=frontrowheader>
    INSTITUTES
    <span class=frontrowred>&amp;&nbsp;CENTRES
   </div>
   <div class=frontrowlistbox>
    <ul class=frontrowlist>
	<li><a href=#>Bioengineering</a></li>
	<li><a href=#>Electrical Engineering</a></li>
	<li><a href=#>Materials Science &amp; Engineering</a></li>
	<li><a href=#>Mechanical Engineering</a></li>
	<li><a href=#>Microengineering</a></li>
    </ul> 
    <ul class=frontrowlist>
	<li><a href=#>Research Centres</a></li>
	<li><a href=#>Platforms &amp; Workshops</a></li>
    </ul> 
   </div>
  </div>
  <div class=frontrowcontainer>
   <div class=frontrowheader>
    UPCOMING
    <span class=frontrowred>EVENTS
   </div>
   <div class=frontrowevents>

<?php
 echo "<table class='slider-event-table'>";
 $events = get_events_from_memento($url='https://memento.epfl.ch/api/jahia/mementos/sti/events/en/?category=CONF&format=json', $limit=5);
 $max_len = 52;
 foreach ($events as $event) {
  $event_day = date("d", strtotime($event->event_start_date));
  $event_month = strtolower(date("M", strtotime($event->event_start_date)));
  echo "<tr class='slider-event-row' data-link='$event->absolute_slug'>";
  echo "<td class='slider-event-cell'>
   <div class='slider-event-date'>
    <span class='slider-event-date-day'>
     $event_day 
    </span>
    <span class='slider-event-date-month'>
     $event_month 
    </span>
   </div>
   <div class='slider-event-title'>";
  $s = $event->title;
  if (strlen($event->title) > $max_len) {
   $offset = ($max_len - 3) - strlen($event->title);
   $s = substr($event->title, 0, strrpos($event->title, ' ', $offset)) . '…';
  };
  echo $s;
  $calendarlink="https://stisrv13.epfl.ch/outlink.php?enddate=20171214T113000&datestring=20171214T103000&speaker=Dr.%20Noris%20GallandatLaboratory%20for%20Materials%20in%20Renewable%20EnergyEPFL%20Valais/Wallis&title=Hydrogen%20Technologies%20and%20Synthetic%20Fuels%20-%20From%20the%20Lab%20to%20the%20Market&room=Zeuzier,%20I17%204%20K2";
  echo "<span class='eventsplus'><a href=$calendarlink title='Add to calendar' class='eventspluslink'>+</a></span></div> </td> </tr>";
 }
 echo "</table>";
?>
    <a href=#><img class=frontrowmore align=right src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/more.png"></a>
  </div> 
 </div>
</center>
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
<!---

      $newsids = ["news", "researchvideo", "inthenews", "testimonials", "campus", "appointments", "whatis", "research", "placement", "masters"];

-->
<script>
// For the events in the slider
$( "div.slider-event-date" )
  .mouseenter(function() {
    $( this ).css( { backgroundColor: "#55576A", color: "#fff", "font-weight": "normal" })
    $( this ).parent().css({ "border-right": "1px solid #FA2400" });
  })
  .mouseleave(function() {
    $( this ).css( { backgroundColor: "#ccc", color: "#000", "font-weight": "normal" })
    $( this ).parent().css({ "border-right": "1px solid #fff" });

  });
$( "tr.slider-event-row" )
  .click(function() {
    window.location = $( this ).data("link");
    return true;
   });
</script>
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
