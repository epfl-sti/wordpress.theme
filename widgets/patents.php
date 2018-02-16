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

class Patents extends \WP_Widget
{
  public function __construct()
  {
    parent::__construct(
      'EPFL_STI_Patents', // unique id
      ___('EPFL STI Patents'), // widget title
      // additional parameters
      array(
        'description' => ___('Shows a list of images, info  and links for patents published by Faculty members - must send limit (how many to display), can send institute, year, searchcluster (keyword)')
      )
    );
  }

  public function widget($args, $config)
  {
    echo $args['before_widget'];
    $cl = get_current_language();
    ?>
    <div class="container">
	    <?php $inst_code=get_institute()->get_code(); ?>
<?php 
$limit=10; # needs to be an option

$incoming_json=file_get_contents("https://stisrv13.epfl.ch/collaborations/clusters/patents.php?limit=$limit");

$incoming=json_decode($incoming_json);

echo "<table>";
foreach ($incoming as $thesis) {
 echo "<tr><td valign=top><a href=https://stisrv13.epfl.ch/masters/img/$thesis->master_id.pdf><img width=180 src='https://stisrv13.epfl.ch/masters/img/$thesis->master_id.png'> </a></td><td valign=top><a href=https://stisrv13.epfl.ch/masters/img/$thesis->master_id.pdf>".$thesis->title."</a><br>$thesis->firstname $thesis->surname<br>$thesis->description</td></tr>"; 
}
echo "</table>";
?>


    </div>
    <?php
    echo $args['after_widget'];
  }  // public function widget
}

register_widget(Patents::class);
