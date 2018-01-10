<?php
/**
 * Calendar widget for upcoming events
 *
 * Intended for overlaying on top of the news carousel, hence height zero.
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
    die( 'Access denied.' );
}

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

require_once(dirname(dirname(__FILE__)) . "/inc/epfl.php");
use function EPFL\STI\get_events_from_memento;

class OverlayCalendar extends \WP_Widget
{
    public function __construct()
    { 
		parent::__construct( 
			'EPFL_STI_Theme_Widget_OverlayCalendar', // unique id 
			___('EPFL STI Overlay Events Calendar'), // widget title 
			// additional parameters 
			array(
				'description' => ___( 'Shows a calendar of upcoming events. Zero-height, intended for overlaying on top of the carousel. (Must come immediately before it in the widget lineup.)' )
			)
        );
	}

    public function widget ($args, $config)
    {
        echo $args['before_widget'];
?>

<div id="containercalendar" style="height: 0px;">
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
<?php
        echo $args['after_widget'];
    }
}

register_widget(OverlayCalendar::class);
