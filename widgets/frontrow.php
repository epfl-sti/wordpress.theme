<?php

/**
 * "Front row" thing on the homepage, immediately after the carousel
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
    die( 'Access denied.' );
}

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

require_once(dirname(dirname(__FILE__)) . "/inc/epfl.php");
use function EPFL\STI\{
    get_events_from_memento,
    get_news_from_actu,
    get_actu_link,
    get_current_language
};

class FrontRow extends \WP_Widget
{
    public function __construct ()
    {
		parent::__construct( 
			'EPFL_STI_Theme_Widget_FrontRow', // unique id 
			___('EPFL STI Front Row'), // widget title 
			// additional parameters 
			array(
				'description' => ___( 'Shows a blackboard of quick links and useful information' )
			)
        );
    }

    public function widget ($args, $config)
    {
        $cl = get_current_language();
?>
<center>
  <div class="frontrow">
    <div class="frontrowcontainer">
      <div class="frontrowheader">
        RESEARCH<br />
        <span class="frontrowred">NEWS</span>
      </div>
      <div class="frontrowcontent">
<?php
$actu_sti_research_url = 'https://actu.epfl.ch/api/v1/channels/10/news/?format=json&lang='.$cl.'&category=3&faculty=3&themes=4';
$actu_sti_research = get_news_from_actu($actu_sti_research_url);
foreach ($actu_sti_research as $actu) {
  if ($x<3) {
    echo '<div class="frontrownews" style="background-image:url(' . $actu->visual_url . ')">';
    echo '<a class=whitelink href="' . get_actu_link($actu->title) . '">';
    echo '<div class="frontrownewstitle">';
    echo $actu->title;
    echo '</div>';
    echo '</a></div>';
  }
  $x++;
}
?>
      </div>
    </div>

  <div class=frontrowcontainer>
   <div class=frontrowheader>
    SCHOOL OF<br /><span class=frontrowred>ENGINEERING</span>
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
    INSTITUTES<br /><span class=frontrowred>&amp;&nbsp;CENTRES</span>
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
    UPCOMING<br /><span class=frontrowred>EVENTS</span>
   </div>
   <div class=frontrowevents>

<?php
  echo '<table class="slider-event-table">';
  $events = get_events_from_memento($url='https://memento.epfl.ch/api/jahia/mementos/sti/events/en/?category=CONF&format=json', $limit=5);
  $max_len = 52;
  foreach ($events as $event) {
    $event_day = date("d", strtotime($event->event_start_date));
    $event_month = strtolower(date("M", strtotime($event->event_start_date)));
    echo "<tr class='slider-event-row' data-link='$event->absolute_slug'>\n";
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
    echo '<span class="eventsplus"><a href="https://memento.epfl.ch/event/export/' . $event->translation_id . '/" title="Add to calendar" class="eventspluslink">+</a></span>';
    echo "\n</div>\n</td>\n</tr>\n";
  }
  echo "</table>";
?>
    <a href="https://memento.epfl.ch/sti/?period=7"><img class="frontrowmore" align="right" src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/more.png"></a>
  </div>
 </div>
</center>
<?php
  }  // public function widget
}

register_widget(FrontRow::class);
