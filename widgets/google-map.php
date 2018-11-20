<?php

/**
 * Show a Google Map with pins for all the EPFL STI sites.
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
  die( 'Access denied.' );
}

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

class GoogleMap extends \WP_Widget
{
  public function __construct()
  {
    parent::__construct(
      'EPFL_STI_Theme_Widget_GoogleMap',   // unique id
      'EPFL STI Google Map',               // widget title
      array(
        'description' => ___( 'Show a Google Map with pins for all the EPFL STI sites' )
      )
    );
  }

  public function widget ($args, $config)
  {
    echo $args['before_widget'];
?>
    <script src="<?= get_template_directory_uri() . '/js/google-maps.js' ?>"></script>
    <div id="googlemap"></div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCID6xvAJxu1kpOxoYpH9F5ZUBjK2xD39c&libraries=places&callback=initMap" async defer></script>
<?php
    echo $args['after_widget'];
  }  // public function Widget
}  // class GoogleMap

register_widget(GoogleMap::class);
