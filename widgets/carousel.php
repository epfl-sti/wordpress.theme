<?php

/**
 * Show the carousel for the posts returned by the global $wp_query object.
 *
 * If there is currently no query, set up a default one.
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
    die( 'Access denied.' );
}

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

class Carousel extends \WP_Widget
{
    public function __construct()
    {
		parent::__construct(
			'EPFL_STI_Theme_Widget_Carousel', // unique id
			'EPFL STI News Carousel', // widget title
			// additional parameters
			array(
				'description' => ___( 'Rotates a selection of news with a big picture' )
			)
        );
	}

    function render_carousel_items ()
    {
        $has_carousel_custom_template = locate_template("loop-templates/carousel");
        while($this->carousel_query->have_posts()) {
            $this->carousel_query->the_post();
            if ($has_carousel_custom_template) {
                get_template("loop-templates/carousel");
            } else {
                $link = get_the_permalink();
                global $post;
                $subtitle = function_exists("get_the_subtitle") ? get_the_subtitle($post, "", "", false) : null;
    ?>
    <div class="carousel-item">
        <?php the_post_thumbnail("full"); ?>
        <div class="legend">
                <h1><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h1>
                <?php if ($subtitle) : ?><h2><a href="<?php echo $link; ?>"><?php echo $subtitle; ?></a></h2><?php endif; ?>
        </div>
    </div>
                <?php
            }
        }
        wp_reset_postdata();
    ?>
    <?php
    }

    public function widget ($args, $config)
    {
        $this->carousel_query = new \WP_Query(array(
            'post_type' => 'any',
            'cat'       => intval($config['category'])));
        if (! $this->carousel_query->have_posts()) {
            return;
        }
    ?>
<div id="container-carousel" class="carousel slide" data-ride="carousel">
    <?php $this->render_carousel_items(); ?>
    <a class="sti-carousel-button prev" href="#container-carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="sti-carousel-button next" href="#container-carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<?php # The wave must be outside the carousel, so as not to be clipped. ?>
<div id="redwave">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/waving.png">
</div>
   <?php
    }

    public function form ($config)
    {
        printf("<select id=\"%s\" name=\"%s\">\n",
               $this->get_field_id('category'),
               $this->get_field_name('category'));
        foreach (get_categories() as $category) {
            printf("<option value=\"%d\" %s>%s</option>\n",
                   $category->cat_ID,
                   selected( $config["category"], $category->cat_ID),
                   $category->cat_name);
        }
        print "</select>\n";
    }

	public function update( $new_config, $old_config ) {
        $config = $old_config;
        $config["category"] = $new_config["category"];
        return $config;
    }
}

register_widget(Carousel::class);
