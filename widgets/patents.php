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
<?php 
$limit=10; # needs to be an option

$incoming_json=file_get_contents("https://stisrv13.epfl.ch/collaborations/clusters/patents.php?limit=$limit");

$incoming=json_decode($incoming_json);

echo "<table>";
foreach ($incoming as $patent) {

 echo "<table><td valign=top><div id='idshot$patent->patentsid'><img src=https://stisrv13.epfl.ch/profs/img/$patent->imagelink></div></td><td valign=top><a href=$patent->link target=_BLANK title=\"$patent->countryname patent\">$patent->title</a><br>$patent->authors<br>$patent->applicant ($patent->published)<br><a href=$patent->link target=_BLANK title=\"$patent->countryname patent\"><img src=https://stisrv13.epfl.ch/img/$patent->country.gif alt=\"$patent->countryname patent\"></a>$patent->bunting<br><br></td></table>"; 

}
echo "</table>";
?>


    </div>
    <?php
    echo $args['after_widget'];
  }  // public function widget
}

register_widget(Patents::class);
