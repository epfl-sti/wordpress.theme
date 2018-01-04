<?php

/**
 * News collage as a row on white background for the homepage
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
    die( 'Access denied.' );
}

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;
use function \EPFL\STI\Theme\__x;

require_once(dirname(__FILE__) . "/category-chooser.inc");

class WhiteNews extends \WP_Widget
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

?>
<center>
 <div class="secondaryrow whitebg">
  <div class="secondarytitle"><?php echo esc_html(__x($config["title"], "white-news" )); ?></div>
   <?php
        foreach ($newsitems as $the_post) {
            global $post; $post = $the_post; setup_postdata($post);
            if ($has_custom_template) {
                get_template("loop-templates/white-news");
            } else {
                ?>
<div class="secondarycontainer <?php echo $config["cssclass"]; ?>">
  <a class="secondarylink" href="<?php echo get_the_permalink(); ?>">
    <div>
      <div class="wp-post-image-container">
        <?php
        if (get_post_meta($the_post->ID)["news_has_video"][0] === "True") {
          echo '<object class="epfl-actu-video-in-new" data="https://www.youtube.com/embed/'.get_post_meta($the_post->ID)["youtube_id"][0].'"></object>';
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
  <a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a>
  <br />
   <a href="#"><img class="frontrowmore" src="/wp-content/themes/epfl-sti/img/src/yetmore.png" align="right"></a>
  </div>
</div>
                <?php
            }
        }  // foreach ($newsitems as $the_post)
   ?>
 </div>
</center>
<?php
    }  // public function widget
}  // class WhiteNews

register_widget(WhiteNews::class);
