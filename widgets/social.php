<?php

/**
 * Show some social buttons.
 * Usage: <?php the_widget( 'EPFL_STI_Theme_Widget_Social' ); ?>
 * Note: you can either use a side_bar to display the widets through the admin
 *       aera or use the widget id (the_widget( 'EPFL_STI_Theme_Widget_Social' );)
 *       to call it directly from the theme.
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
    die('Access denied.');
}

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

class Social extends \WP_Widget
{
  public function __construct()
  {
    parent::__construct(
      'EPFL_STI_Theme_Widget_Social',   // unique id
      'EPFL STI Social',                // widget title
      array(
        'description' => ___('Show some social buttons')
      )
    );
  }

  public function widget($args, $config)
  {
    echo $args['before_widget']; ?>
    <div class="widget epfl-sti-social">
      <div itemscope itemtype="https://schema.org/ContactPoint">
        <a href="mailto:secretariat.sti@epfl.ch" class="epfl-sti-social-item" target="_blank" title="email: STI Secretary Officel"><i class="fas fa-2x fa-envelope-square"></i></a>
        <a href="https://twitter.com/epfl_sti" class="epfl-sti-social-item" target="_blank" title="EPFL STI Twitter"><i class="fab fa-2x fa-twitter-square"></i></a>
        <a href="https://plus.google.com/epfl_sti" class="epfl-sti-social-item" target="_blank" title="EPFL STI Google plus"><i class="fab fa-2x fa-google-plus-square"></i></a>
        <a href="https://instagram.com/epfl_sti" class="epfl-sti-social-item" target="_blank" title="EPFL STI Instagram"><i class="fab fa-2x fa-instagram"></i></a>
        <a href="https://facebook.com/epfl_sti" class="epfl-sti-social-item" target="_blank" title="EPFL STI Facebook"><i class="fab fa-2x fa-facebook-square"></i></a>
        <a href="https://youtube.com/epfl_sti" class="epfl-sti-social-item" target="_blank" title="EPFL STI Youtube"><i class="fab fa-2x fa-youtube"></i></a>
      </div>
    </div>
    <?php
    echo $args['after_widget'];
  }  // public function Widget
}  // class Social

register_widget(Social::class);
