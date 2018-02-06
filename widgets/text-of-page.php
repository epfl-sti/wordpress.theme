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

require_once(__DIR__ . "/loop-template-chooser.inc");

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

    public function widget ($args, $config)
    {
        echo $args['before_widget'];
        ?>
           <div class="col-md-12" id="primary">
	    <main class="" id="main" role="main">
	     <?php while ( have_posts() ) {
                 the_post();
	         get_template_part('loop-templates/content',
                                   $config['page_template']);
             } ?>
	    </main>
	   </div>
        <?php
        echo $args['after_widget'];
    }

    public function form ($config)
    {
        $field_id   = $this->get_field_id('page_template');
        $field_name = $this->get_field_name('page_template');

        printf('<label for="%s">%s</label>', $field_id, ___("Loop template to use:"));
        render_loop_template_chooser($field_id, $field_name,
                                     $config["page_template"]);
    }

    public function update( $new_config, $old_config ) {
        $config = $old_config;
        $config["page_template"] = $new_config["page_template"];
        return $config;
    }
}

register_widget(TextOfPage::class);
