<?php

/**
 * Show an input box to subscribe to the newsletter through e-mail.
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
    die( 'Access denied.' );
}

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

class SubscribeNewsletter extends \WP_Widget
{
    public function __construct()
    {
		parent::__construct(
			'EPFL_STI_Theme_Widget_SubscribeNewsletter', // unique id
			'EPFL STI Subscribe to Newsletter',          // widget title
			array(
				'description' => ___( 'Shows an input form to subscribe to the EPFL-STI newsletter through e-mail' )
			)
        );
	}

    public function widget ($args, $config)
    {
        echo $args['before_widget'];
?>
  <div class="widget">
    <h1>NEWSLETTER</h1>
      <br /><br />
      Sign up for our email bulletin:
    <form>
      <input name="subscriber">
        <br /><br />
        <input type="submit" value="GO">
      </form>
  </div>
<?php
        echo $args['after_widget'];
    }  // public function Widget
}      // class SubscribeNewsletter

register_widget(SubscribeNewsletter::class);
