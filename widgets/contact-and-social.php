<?php

/**
 * Show snail-mail contact info and some social buttons.
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
    die( 'Access denied.' );
}

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

class ContactAndSocial extends \WP_Widget
{
    public function __construct()
    {
		parent::__construct(
			'EPFL_STI_Theme_Widget_ContactAndSocial',   // unique id
			'EPFL STI Contact and Social',              // widget title
			array(
				'description' => ___( 'Show snail-mail contact info and some social buttons' )
			)
        );
	}

    public function widget ($args, $config)
    {
?>
  <div class="widget">
    <h1>CONTACT</h1>
    <br />
    <br />
    School of Engineering<br />
    Dean's Office<br />
    EPFL - ELB 114<br />
    Station 11<br />
    CH-1015 Lausanne<br />
    <br />
    <a href="mailto:secretariat.sti@epfl.ch" target="_blank" title="email: STI Secretary Officel"><i class="fas fa-2x fa-envelope-square"></i></a>
    <a href="https://twitter.com/epfl_sti" target="_blank" title="EPFL STI Twitter"><i class="fab fa-2x fa-twitter-square"></i></a>
    <a href="https://plus.google.com/epfl_sti" target="_blank" title="EPFL STI Google plus"><i class="fab fa-2x fa-google-plus-square"></i></a>
    <a href="https://instagram.com/epfl_sti" target="_blank" title="EPFL STI Instagram"><i class="fab fa-2x fa-instagram"></i></a>
    <a href="https://facebook.com/epfl_sti" target="_blank" title="EPFL STI Facebook"><i class="fab fa-2x fa-facebook-square"></i></a>
    <a href="https://youtube.com/epfl_sti" target="_blank" title="EPFL STI Youtube"><i class="fab fa-2x fa-youtube"></i></a>
  </div>
<?php
    }  // public function Widget
}      // class ContactAndSocial

register_widget(ContactAndSocial::class);
