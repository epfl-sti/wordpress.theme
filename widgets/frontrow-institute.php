<?php

/**
 * "Front row" thing on the homepage, immediately after the carousel
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
  die('Access denied.');
}

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

require_once(dirname(dirname(__FILE__)) . "/inc/epfl.php");
use function EPFL\STI\get_events_from_memento;
use function EPFL\STI\get_news_from_actu;
use function EPFL\STI\get_actu_link;
use function EPFL\STI\get_current_language;

class FrontRowInstitute extends \WP_Widget
{
  public function __construct()
  {
    parent::__construct(
      'EPFL_STI_Institute_Theme_Widget_FrontRow', // unique id
      ___('EPFL STI Institute Front Row'), // widget title
      // additional parameters
      array(
        'description' => ___('Shows a blackboard of quick links and useful information')
      )
    );
  }

  public function widget($args, $config)
  {
    echo $args['before_widget'];
    $cl = get_current_language();
    ?>
    <div class="container">
      <div class="row no-gutters">
        <div class="col-xl-3 col-lg-3 col-md-6 frontrowcol">
          <div class="text-white frontrowtitle epfl-sti-institute-frontrow-header">
            <?php echo ___('NEWS'); ?><br />
          </div>
          <?php
            $actu_sti_research_url = 'https://actu.epfl.ch/api/v1/channels/10/news/?format=json&lang='.$cl.'&category=3&faculty=3&themes=4';
            $actu_sti_research = get_news_from_actu($actu_sti_research_url);
            foreach ($actu_sti_research as $actu) {
                if ($x<2) {
                    echo '<div class="frontrownews zoomy" style="background-image:url(' . $actu->visual_url . ');">';
                    echo '  <a class="whitelink" href="' . get_actu_link($actu->title) . '">';
                    echo '    <div class="frontrownewstitle">';
                    echo         $actu->title;
                    echo '    </div>';
                    echo '  </a>';
                    echo '</div>';
                }
                $x++;
            } ?>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 frontrowcol">
          <div class="text-white frontrowtitle epfl-sti-institute-frontrow-header">
            <?php echo ___('FACULTY'); ?><br />
          </div>
            <?php wp_nav_menu(array(
                'theme_location' => 'front-row-school-menu',
                'container_class' => 'menu-front-row  menu-schools'
            )); ?>
        </div>
        <div class="w-100 d-none d-md-block d-lg-none"></div>
        <div class="col-xl-3 col-lg-3 col-md-6 frontrowcol">
          <div class="text-white frontrowtitle epfl-sti-institute-frontrow-header">
            <?php echo ___('INFO'); ?><br />
          </div>
          <?php wp_nav_menu(array(
              'theme_location' => 'front-row-centres-menu',
              'container_class' => 'menu-front-row menu-centres'
          )); ?>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 frontrowcol">
          <div class="text-white frontrowtitle epfl-sti-institute-frontrow-header">
            <?php echo ___('EVENTS'); ?><br />
          </div>
          <div class="container">
            <?php
              $events = get_events_from_memento($url='https://memento.epfl.ch/api/jahia/mementos/sti/events/en/?category=CONF&format=json', $limit=2);
              $max_len = 52;
              foreach ($events as $event) { ?>
                <div class="row frontrow_event">
                  <div class="col-4 frontrow_event_date">
                    <!-- the icon as a time element -->
                    <a href="<?php echo $event->absolute_slug; ?>" title="<?php echo $event->title; ?>">
                      <time datetime="<?php echo $event->event_start_date; ?>" class="icon">
                        <em><?php echo date("l", strtotime($event->event_start_date));      // Friday ?></em>
                        <strong><?php echo date("F", strtotime($event->event_start_date));  // January ?></strong>
                        <span><?php echo date("d", strtotime($event->event_start_date));    // 19 ?></span>
                      </time>
                    </a>
                  </div>
                  <div class="col-8 frontrow_event_text">
                    <?php //var_dump($event);
                      $s = $event->title;
                      if (strlen($event->title) > $max_len) {
                          $offset = ($max_len - 3) - strlen($event->title);
                          $s = substr($event->title, 0, strrpos($event->title, ' ', $offset)) . '…';
                      };
                      echo $s;
                    ?>
                    <div class="add_to_calendar"><a href="https://memento.epfl.ch/event/export/<?php echo $event->translation_id; ?>/" title="Add to calendar"><i class="far fa-calendar-plus"></i></a></div>
                  </div>
                </div>
            <?php
              }
            ?>
            <div class="row">
              <div class="col-12 more_events">
                <a href="https://memento.epfl.ch/sti/?period=7"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/more.png"></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
    echo $args['after_widget'];
  }  // public function widget
}

register_widget(FrontRowInstitute::class);
