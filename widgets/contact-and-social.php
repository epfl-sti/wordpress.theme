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
        echo $args['before_widget'];
?>
  <div class="widget epfl-sti-social">
    <div itemscope itemtype="https://schema.org/ContactPoint">
      <h1>CONTACT</h1>
      <address>
        <div itemscope itemtype="schema.org/PostalAddress">
          <strong><span property="name">School of Engineering</span></strong><br />
          <span itemprop="contactType">Dean's Office</span><br />
          <span itemprop="streetAddress">EPFL - ELB 11</span><br />
          <span itemprop="postOfficeBoxNumber">Station 11</span><br />
          <span itemprop="addressCountry">CH</span>-<span itemprop="postalCode">1015</span> <span itemprop="addressLocality">Lausanne</span><br />
        </div>
      </address>
      <br />
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
}      // class ContactAndSocial

register_widget(ContactAndSocial::class);
