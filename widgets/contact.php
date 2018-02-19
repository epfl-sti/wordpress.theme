<?php

/**
 * Show snail-mail contact info.
 * Example:
 *   School of Engineering   // name
 *   Dean's Office           // contactType
 *   EPFL - ELB 11           // streetAddress
 *   Station 11              // postOfficeBoxNumber
 *   CH-1015 Lausanne        // addressCountry - postalCode addressLocality
 *   dean.sti@epfl.ch        // email
 *   +41 21 6936961          // telephone
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

  CONST FIELDS = array( "title",
                        "name",
                        "contactType",
                        "email",
                        "telephone",
                        "streetAddress",
                        "postOfficeBoxNumber",
                        "addressCountry",
                        "postalCode",
                        "addressLocality"
                      );

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
    if ( $instance ) {
      foreach(self::FIELDS as $field) {
        $$field = esc_attr($instance[$field]);
      }
    } else {
      foreach(self::FIELDS as $field) {
        $$field = '';
      }
    }
    $this->render_form_entry('title', $title, 'Title:', 'e.g. "Contact"');
    $this->render_form_entry('name', $name, 'Name:', 'e.g. "School of Engineering"');
    $this->render_form_entry('contactType', $contactType, 'Type:', 'e.g. "Dean\'s office"');
    $this->render_form_entry('email', $email, 'Email:', 'e.g. "dean.sti@epfl.ch"');
    $this->render_form_entry('telephone', $telephone, 'Phone:', 'e.g. "+41 21 69 36961"');
    $this->render_form_entry('streetAddress', $streetAddress, 'Address:', 'e.g. "EPFL - ELB 11"');
    $this->render_form_entry('postOfficeBoxNumber', $postOfficeBoxNumber, 'PO Box:', 'e.g. "Station 11"');
    $this->render_form_entry('addressCountry', $addressCountry, 'Country Code:', 'e.g. "CH"');
    $this->render_form_entry('postalCode', $postalCode, 'ZIP Code:', 'e.g. "1015"');
    $this->render_form_entry('addressLocality', $addressLocality, 'Locality:', 'e.g. "Lausanne"');
  }

  private function render_form_entry ($nid, $value, $text, $help=null) {
    print vsprintf("<p><label for=\"%s\">%s<input class=\"widefat\" id=\"%s\" name=\"%s\" type=\"text\" value=\"%s\" /></label><br><small>%s</small></p>",
                    array(
                      $this->get_field_id($nid),
                      __x($text, 'epfl_sti'),
                      $this->get_field_id($nid),
                      $this->get_field_name($nid),
                      $value,
                      __x($help, 'epfl_sti'))
                    );
  }

  // Update widget settings
  public function update( $new_instance, $old_instance )
  {
    $instance = $old_instance;
    foreach(self::FIELDS as $field) {
      $instance[$field] = isset( $new_instance[$field] ) ? wp_strip_all_tags( $new_instance[$field] ) : '';
    }
    return $instance;
  }

  // Display the widget
  public function widget( $args, $instance )
  {
    foreach(self::FIELDS as $field) {
      $$field = isset( $instance[$field] ) ? apply_filters( 'widget_text', $instance[$field] ) : '';
    }
    echo $args['before_widget'];
    ?>
    <div class="widget epfl-sti-contact">
      <div itemscope itemtype="https://schema.org/ContactPoint">
        <?php if ($title) { echo $args["before_title"] . $title . $args["after_title"] ; } ?>
        <address>
          <div itemscope itemtype="schema.org/PostalAddress">
          <?php if ($name): ?>
            <strong><span property="name"><?php echo $name; ?></span></strong><br />
          <?php endif; ?>
          <?php if ($contactType): ?>
            <span property="contactType"><?php echo $contactType; ?></span><br />
          <?php endif; ?>
          <?php if ($streetAddress): ?>
            <span property="streetAddress"><?php echo $streetAddress; ?></span><br />
          <?php endif; ?>
          <?php if ($postOfficeBoxNumber): ?>
            <span property="postOfficeBoxNumber"><?php echo $postOfficeBoxNumber; ?></span><br />
          <?php endif; ?>
          <?php if ($addressLocality): ?>
            <?php if ($addressLocality): ?><span itemprop="addressCountry"><?php echo $addressCountry; ?></span>-<?php endif; ?><span itemprop="postalCode"><?php echo $postalCode; ?></span>
            <span itemprop="addressLocality"><?php echo $addressLocality; ?></span><br />
          <?php endif; ?>
          <?php if ($email): ?>
            <span property="email">&#128231; <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></span><br />
          <?php endif; ?>
          <?php if ($telephone): ?>
            <span property="telephone">&#128222; <a href="tel:<?php echo $telephone; ?>"><?php echo $telephone; ?></a></span><br />
          <?php endif; ?>
          </div>
        </address>
      </div>
    </div>
    <?php
    echo $args['after_widget'];
  }  // public function Widget
}  // class Contact

register_widget(Contact::class);
