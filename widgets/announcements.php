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
    ?>
    <div class="container">
	    <?php $inst=get_institute(); ?>
<?php 

if ($cl != "en") {
 $lang="fra";
}

$incoming_json=file_get_contents("https://stisrv13.epfl.ch/cgi-bin/newtowncrier.cgi?lang=$lang&inst=$inst&ayearagoplease=1");
$incoming=json_decode($incoming_json);
$output=$incoming->announcements;
echo $output; 
?>


    </div>
    <?php
    echo $args['after_widget'];
  }  // public function widget
}

register_widget(Announcements::class);
