<?php

/**
 * Show snail-mail contact info.
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
  die('Access denied.');
}

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

class Contact extends \WP_Widget
{
  public function __construct()
  {
    parent::__construct(
      'EPFL_STI_Theme_Widget_Contact',   // unique id
      'EPFL STI Contact',                // widget title
      array(
        'description' => ___('Show snail-mail contact info')
      )
    );
  }

  public function widget($args, $config)
  {
    echo $args['before_widget'];
    ?>
    <div class="widget epfl-sti-social">
      <div itemscope itemtype="https://schema.org/ContactPoint">
        <h1><?php echo ___('CONTACT'); ?></h1>
        <address>
          <div itemscope itemtype="schema.org/PostalAddress">
            <strong><span property="name"><?php echo ___('School of Engineering'); ?></span></strong><br />
            <span itemprop="contactType"><?php echo ___('Dean\'s Office'); ?></span><br />
            <span itemprop="streetAddress"><?php echo ___('EPFL - ELB 11'); ?></span><br />
            <span itemprop="postOfficeBoxNumber">Station 11</span><br />
            <span itemprop="addressCountry">CH</span>-<span itemprop="postalCode">1015</span> <span itemprop="addressLocality">Lausanne</span><br />
          </div>
        </address>
      </div>
    </div>
    <?php
    echo $args['after_widget'];
  }  // public function Widget
}  // class Contact

register_widget(Contact::class);
