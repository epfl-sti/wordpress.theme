<?php

/**
 * A widget to display the title and text of the page.
 *
 * For pages that consist mostly of widgets (e.g. home page, institute pages) this is the easy way to
 * pick and choose where the "word from the Director" is to appear.
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
  die('Access denied.');
}

require_once(dirname(__DIR__) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

class TextOfPage extends \WP_Widget {
    public function __construct()
    {
        parent::__construct(
            'EPFL_STI_Theme_TextOfPage', // unique id
            ___('Text of the page'), // widget title
            // additional parameters
            array(
                'description' => ___('Shows the normal text of that page (title and content)')
            )
        );
    }

    public function widget ()
    {
        ?>
           <div class="col-md-12" id="primary">
	    <main class="" id="main" role="main">
	     <?php while ( have_posts() ) {
                 the_post();
	         get_template_part('loop-templates/content',
                                   $this->get_loop_template_name());
             } ?>
	    </main>
	   </div>
        <?php
    }

    public function get_loop_template_name ()
    {
        return 'institute-homepage'; // TODO: Make this cleverer, configurable, or both
    }
}

register_widget(TextOfPage::class);
