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
use function EPFL\STI\get_current_language;
use function EPFL\STI\get_institute;

class Announcements extends \WP_Widget
{
  public function __construct()
  {
    parent::__construct(
      'EPFL_STI_Announcements', // unique id
      ___('EPFL STI Announcements'), // widget title
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
    echo "<div class=\"container\">";

    if (get_institute() === null) {
      $inst_code = '';
    } else {
      $inst_code = get_institute()->get_code();
    }

    $lang = ($cl == "en") ? "eng" : "fra";

    $incoming_url = "https://stisrv13.epfl.ch/cgi-bin/newtowncrier.cgi?lang=" . $lang . "&inst=" . $inst_code . "&ayearagoplease=1";
    echo "<!--  Announcement url = " . $incoming_url . " -->";
    $incoming_json = file_get_contents($incoming_url);
    $incoming = json_decode(utf8_encode($incoming_json), true);
    echo $incoming["announcements"];
    echo "</div>";
    echo $args['after_widget'];
  }  // public function widget
}

register_widget(Announcements::class);
