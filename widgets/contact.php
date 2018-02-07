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
use function \EPFL\STI\Theme\__x;

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

  // The widget form (for the backend )
  function form($instance) {
    // Check values
    if( $instance) {
      $title = esc_attr($instance['title']);
    } else {
      $title = '';
    }
    print vsprintf("<p><label for=\"%s\">%s<input class=\"widefat\" id=\"%s\" name=\"%s\" type=\"text\" value=\"%s\" /></label></p>",
                    array(
                      $this->get_field_id('title'),
                      __x('Title:', 'epfl_sti'),
                      $this->get_field_id('title'),
                      $this->get_field_name('title'),
                      $title)
                    );
  }

  // Update widget settings
  public function update( $new_instance, $old_instance )
  {
    $instance = $old_instance;
    $instance['title'] = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
    return $instance;
  }

  // Display the widget
  public function widget( $args, $instance )
  {
    extract( $args );
    $title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';

    echo $args['before_widget'];
    ?>
    <div class="widget epfl-sti-social">
      <div itemscope itemtype="https://schema.org/ContactPoint">
        <?php echo $before_title . $title . $after_title; ?>
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
