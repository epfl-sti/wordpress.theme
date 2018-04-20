<?php

/**
 * News collage as a row on white background for an institute homepage
 *
 * Unlike the "main" white news widget, this is supposed to fit all three items
 * on a single row (with a separate title on top of each)
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
    die( 'Access denied.' );
}

require_once(dirname(__DIR__) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;
use function \EPFL\STI\Theme\__x;

require_once(__DIR__ . "/category-chooser.inc");

require_once(__DIR__ . "/white-news-common.inc");

class WhiteNewsCondensed extends WhiteNewsBase
{
    public function __construct ()
    {
        parent::__construct(
            'EPFL_STI_Theme_Widget_WhiteNewsCondensed', // unique id
            ___('EPFL STI White News Condensed Row'), // widget title
            // additional parameters
            array(
                'description' => ___( 'Shows a single row of news on white background' )
            )
        );
    }

    public function form ($config)
    {
        foreach (array(1, 2, 3) as $card_id) {
            $this->_render_form_field(
                $config,
                "title-$card_id",
                sprintf(__x('Title for card #%d:', 'white-news-condensed-admin'), $card_id));
            $this->_render_form_field(
                $config,
                "category-$card_id",
                sprintf(__x('Category for card #%d:', 'white-news-condensed-admin'), $card_id),
                "category");
        }
    }

    public function update( $new_config, $old_config ) {
        $config = $old_config;
        $config["title-1"]    = $new_config["title-1"];
        $config["category-1"] = $new_config["category-1"];
        $config["title-2"]    = $new_config["title-2"];
        $config["category-2"] = $new_config["category-2"];
        $config["title-3"]    = $new_config["title-3"];
        $config["category-3"] = $new_config["category-3"];
        return $config;
    }

    public function widget ($args, $config)
    {
        echo $args['before_widget'];
        ?>
        <div class="whitenews h-100 align-items-center">
         <div class="container">
          <div class="row">
        <?php
        foreach (array(1, 2, 3) as $card_id) {
            $query = new \WP_Query(array(
                'post_type' => 'any',
                'cat'       => intval($config["category-$card_id"])));
            $newsitems = array_filter($query->get_posts(), function($post) {
                return !(! get_the_post_thumbnail($post));
            });

            if (! count($newsitems)) continue;
            global $post; $post = $newsitems[0]; setup_postdata($post);
            if ($has_custom_template) {
                get_template("loop-templates/white-news");
            } else {
                ?><div><h1><?php echo $config["title-$card_id"]; ?></h1><?php
                $this->render_card("whitenews-condensed whitenews-$card_id");
                ?></div><?php
            }
        }
        ?>
          </div>
         </div>
        </div>
        <?php
        echo $args['after_widget'];
    }  // public function widget
}  // class WhiteNews

register_widget(WhiteNewsCondensed::class);
