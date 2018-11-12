<?php


namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
    die( 'Access denied.' );
}

require_once(dirname(__DIR__) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;
use function \EPFL\STI\Theme\__x;

require_once(__DIR__ . "/category-chooser.inc");

class WhiteNewsBase extends \WP_Widget
{
    protected function _render_form_field ($config, $slug, $label, $type = "text")
    {
        $id   = $this->get_field_id  ($slug);
        $name = $this->get_field_name($slug);
        printf("<label for=\"%s\">%s</label>", $id, $label);
        if ($type === "text") {
            printf("<input type=\"text\" id=\"$id\" name=\"$name\" value=\"%s\">", esc_html($config[$slug]));
        } elseif ($type === "category") {
            render_category_chooser($id, $name, $config[$slug]);
        }
        echo "<br />\n";
    }

    protected function render_card ($additional_classes)
    {
        global $post;
    ?>
        <div class="secondarycontainer <?php echo $additional_classes; ?>">
            <a class="secondarylink" href="<?php echo get_the_permalink(); ?>">
                <div>
                    <div class="wp-post-image-container">
                    <?php
                        if (get_post_meta($post->ID)["news_has_video"][0] === "True") {
                            echo '<object class="epfl-actu-video-in-new" data="https://www.youtube.com/embed/'.get_post_meta($post->ID)["youtube_id"][0].'"></object>';
                        } else {
                            the_post_thumbnail("full");
                        }
                    ?>
                    </div>
                    <img style="position: absolute; top:-11px; right: -10px;" src="/wp-content/themes/epfl-sti/img/src/topright.png">
                    <img style="position: absolute; top:153px; right: 295px;" src="/wp-content/themes/epfl-sti/img/src/bottomleft.png">
                </div>
            </a>
            <div class="secondarycontent">
                <a href="<?php echo get_the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
                <br />
                <a href="<?php echo get_the_permalink(); ?>">
                    <img class="whitenewsmore" src="/wp-content/themes/epfl-sti/img/src/yetmore.png" align="right">
                </a>
            </div>
        </div>
    <?php
    }
}
