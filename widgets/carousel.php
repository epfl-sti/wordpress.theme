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

    function render_carousel_items ($config)
    {
    ?>
    <div class="carousel-item">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ProfCamilleBres.jpg');">
        <div class="legend">
                <h1><a href="https://sti.epfl.ch/page-108381.html#anchor2019">Early career award in photonics </a></h1>
                <h2><a href="https://sti.epfl.ch/page-108381.html#anchor2019"></a></h2>
        </div>
    </div>

    <div class="carousel-item">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/LacourTeam.jpg');">
        <div class="legend">
                <h1><a href="XXX">A long-term implant to restore walking</a></h1>
                <h2><a href="XXX">Prof. Lacour’s team</a></h2>
        </div>
    </div>
    <?php
    }

    public function widget ($args, $config)
    {
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
