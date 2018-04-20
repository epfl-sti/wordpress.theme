<?php

/**
 * News collage as a row on white background for the homepage
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
    die( 'Access denied.' );
}

require_once(dirname(__DIR__) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;
use function \EPFL\STI\Theme\__x;

require_once(__DIR__ . "/white-news-common.inc");

class WhiteNews extends WhiteNewsBase
{
    public function __construct ()
    {
        parent::__construct(
            'EPFL_STI_Theme_Widget_WhiteNews', // unique id
            ___('EPFL STI White News Row'), // widget title
            // additional parameters
            array(
                'description' => ___( 'Shows rows of collages of news on white background' )
            )
        );
    }

    public function form ($config)
    {
        $title_id   = $this->get_field_id  ('title');
        $title_name = $this->get_field_name('title');
        printf("<label for=\"%s\">%s</label>", $title_id,
                   __x('Title:', 'white-news-admin'));
        printf("<input type=\"text\" id=\"$title_id\" name=\"$title_name\" value=\"%s\">", esc_html($config["title"]));
        echo "<br />\n";

        $cssclass_id   = $this->get_field_id  ('cssclass');
        $cssclass_name = $this->get_field_name('cssclass');
        printf("<label for=\"%s\">%s</label>", $cssclass_id,
                   __x('CSS class:', 'white-news-admin'));
        printf("<input type=\"text\" id=\"$cssclass_id\" name=\"$cssclass_name\" value=\"%s\">", esc_html($config["cssclass"]));
        echo "<br />\n";

        $category_id   = $this->get_field_id  ('category');
        $category_name = $this->get_field_name('category');
        printf("<label for=\"%s\">%s</label>", $category_id,
               __x("Category:", 'white-news-admin'));
        render_category_chooser($category_id, $category_name,
                                $config["category"]);
        echo "<br />\n";

        $maxcount_id   = $this->get_field_id  ('maxcount');
        $maxcount_name = $this->get_field_name('maxcount');
        printf("<label for=\"%s\">%s</label>", $maxcount_id,
               __x("Max news count:", 'white-news-admin'));
        printf("<input type=\"text\" id=\"$maxcount_id\" name=\"$maxcount_name\" value=\"%s\">", esc_html($config["maxcount"]));
    }

    public function update( $new_config, $old_config ) {
        $config = $old_config;
        $config["title"]    = $new_config["title"];
        $config["cssclass"] = $new_config["cssclass"];
        $config["category"] = $new_config["category"];
        $config["maxcount"] = $new_config["maxcount"];
        return $config;
    }

    public function widget ($args, $config)
    {
        $query = new \WP_Query(array(
            'post_type' => 'any',
            'cat'       => intval($config['category'])));
        $newsitems = array_filter($query->get_posts(), function($post) {
            return !(! get_the_post_thumbnail($post));
        });
        if (! count($newsitems)) return;
        if (count($newsitems) > $config["maxcount"]) {
            $newsitems = array_slice($newsitems, 0, 0 + $config["maxcount"]);
        }
        $has_custom_template = locate_template("loop-templates/white-news");

        echo $args['before_widget'];
        ?>
        <div class="whitenews h-100 align-items-center">
         <div class="container">
            <h1><?php echo esc_html(__x($config["title"], "white-news" )); ?></h1>
            <div class="row">
                    <?php
                    foreach ($newsitems as $the_post) {
                        global $post; $post = $the_post; setup_postdata($post);
                        if ($has_custom_template) {
                            get_template("loop-templates/white-news");
                        } else {
                            $this->render_card($config["cssclass"]);
                        }
                    }  // foreach ($newsitems as $the_post)
                    ?>
                </div>
            </div>
          </div>
        </div>
    <?php
        echo $args['after_widget'];
    }  // public function widget
}  // class WhiteNews

register_widget(WhiteNews::class);
