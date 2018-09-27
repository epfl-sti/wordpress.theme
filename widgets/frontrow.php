<?php

/**
 * "Front row" thing on the homepage and institute pages (after the carousel)
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
  die('Access denied.');
}

require_once(dirname(__DIR__) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

require_once(dirname(__DIR__) . "/inc/epfl.php");
use function EPFL\STI\get_events_from_memento;
use function EPFL\STI\get_news_from_actu;
use function EPFL\STI\get_actu_link;
use function EPFL\STI\get_current_language;
use function EPFL\STI\get_institute;
use function EPFL\STI\get_school_name_parts;
use function EPFL\STI\split_on_underscore;

class FrontRow extends \WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'EPFL_STI_Theme_Widget_FrontRow', // unique id
            ___('EPFL STI Front Row'), // widget title
            // additional parameters
            array(
                'description' => ___('Shows a blackboard of quick links and useful information')
            )
        );
        $this->institute = get_institute();
    }

    public function render_header_1 ()
    {
        if ($this->institute) {
        ?>
            <div class="text-white frontrowtitle epfl-sti-institute-frontrow-header">
                <?php echo ___('News'); ?><br />
            </div>
        <?php
        } else {
            list($firstline, $secondline) = split_on_underscore(___("Some _Highlights"));
            ?>
            <div class="text-white frontrowtitle">
                <span class="firstline"><?php echo $firstline; ?></span><br /><span class="secondline"><?php echo $secondline; ?></span>
            </div>
            <?php
        }
    }

    public function render_header_2 ()
    {
        if ($this->institute) {
        ?>
            <div class="text-white frontrowtitle epfl-sti-institute-frontrow-header">
                <?php echo ___('Faculty'); ?><br />
            </div>
        <?php
        } else {
            list($firstline, $secondline) = split_on_underscore(___("About the _School"));
            ?>
            <div class="text-white frontrowtitle">
                <span class="firstline"><?php echo $firstline; ?></span><br /><span class="secondline"><?php echo $secondline; ?></span>
            </div>
            <?php
        }
    }

    public function render_header_3 ()
    {
        if ($this->institute) {
        ?>
            <div class="text-white frontrowtitle epfl-sti-institute-frontrow-header">
                <?php echo ___('Info'); ?><br />
            </div>
        <?php
        } else {
            list($firstline, $secondline) = split_on_underscore(___("Institutes &amp; _Centres"));
            ?>
            <div class="text-white frontrowtitle">
                <span class="firstline"><?php echo $firstline; ?></span><br /><span class="secondline"><?php echo $secondline; ?></span>
            </div>
            <?php
        }
    }

    public function render_header_4 ()
    {
        if ($this->institute) {
        ?>
            <div class="text-white frontrowtitle epfl-sti-institute-frontrow-header">
                <?php echo ___('EVENTS'); ?><br />
            </div>
        <?php
        } else {
            list($firstline, $secondline) = split_on_underscore(___("Upcoming _Events"));
            ?>
            <div class="text-white frontrowtitle">
                <span class="firstline"><?php echo $firstline; ?></span><br /><span class="secondline"><?php echo $secondline; ?></span>
            </div>
            <?php
        }
    }

    public function render_menu_2 ()
    {
        if ($this->institute) {
            wp_nav_menu(array(
                'theme_location' => sprintf('front-row-%s-faculty-menu', $this->institute->get_code()),
                'container_class' => sprintf('menu-front-row menu-institute-faculty menu-%s', $this->institute->get_code())
            ));
        } else {
            wp_nav_menu(array(
                'theme_location' => 'front-row-school-menu',
                'container_class' => 'menu-front-row  menu-schools'
            ));
        }
    }

    public function render_menu_3 ()
    {
        if ($this->institute) {
            wp_nav_menu(array(
                'theme_location' => sprintf('front-row-%s-info-menu', $this->institute->get_code()),
                'container_class' => sprintf('menu-front-row menu-institute-info menu-%s', $this->institute->get_code())
            ));
        } else {
            wp_nav_menu(array(
                'theme_location' => 'front-row-centres-menu',
                'container_class' => 'menu-front-row menu-centres'
            ));
        }
    }

    public function get_max_actu_count ()
    {
        return 3;
    }

    public function get_actu_research_url ()
    {
        $cl = get_current_language();
        return 'https://actu.epfl.ch/api/v1/channels/10/news/?format=json&lang='.$cl.'&category=3&faculty=3&themes=4';
    }

    public function get_events_from_memento ()
    {
        if ($this->institute) {
            return get_events_from_memento($url='https://memento.epfl.ch/api/jahia/mementos/sti/events/en/?category=CONF&format=json', $limit=2);

        } else {
            return get_events_from_memento($url='https://memento.epfl.ch/api/jahia/mementos/sti/events/en/?category=CONF&format=json', $limit=4);
        }
    }

    /* Here we want to get X cutom_post_type "actu" in the wanted category (WP_CATEGORY_POST_ACTU) */
    public function getActuFromPost($category_ID=215, $limit=3) {
        GLOBAL $post;

        $args = array(
            'post_type' => 'epfl-actu',
            'orderby' => 'post_date',
            'order' => 'DESC',
            'cat' => $category_ID,
            'posts_per_page' => $limit
        );
        $the_query = new \WP_Query($args);
        if($the_query->have_posts()):
            while($the_query->have_posts()): $the_query->the_post();
                // TODO; for now we are getting the image from actu in its thumbnail for, but it would bbe better to get it in a bigger way
                echo "\t<div class=\"frontrownews zoomy\" style=\"background-image:url('" . get_post_meta( $post->ID, 'epfl_external_thumbnail', true ) . "');\">\n";
                echo "\t\t<a class=\"whitelink\" href=\"" . get_post_meta( $post->ID, 'absolute_slug', true ) . "\">\n";
                echo "\t\t\t<div class=\"frontrownewstitle\">\n";
                echo "\t\t\t\t" . the_title() . "\n";
                echo "\t\t\t</div>\n";
                echo "\t\t</a>\n";
                echo "\t</div>\n";
            endwhile;
        endif;
    }

    public function widget($args, $config)
    {
        echo $args['before_widget'];
        $cl = get_current_language();
        ?>
        <div class="frontrow container">
            <div class="row no-gutters">
                <div class="col-xl-3 col-lg-3 col-md-6 frontrowcol">
                    <?php 
                        $this->render_header_1();
                        $this->getActuFromPost($config['cat'], $this->get_max_actu_count());
                    ?>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 frontrowcol">
                    <?php
                        $this->render_header_2();
                        $this->render_menu_2();
                    ?>
                </div>
                <div class="w-100 d-none d-md-block d-lg-none"></div>
                <div class="col-xl-3 col-lg-3 col-md-6 frontrowcol">
                    <?php
                        $this->render_header_3();
                        $this->render_menu_3();
                    ?>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 frontrowcol">
                    <?php $this->render_header_4(); ?>
                    <div class="container">
                        <?php
                        $events = $this->get_events_from_memento();
                        $max_len = 52;
                        foreach ($events as $event) { ?>
                            <div class="row frontrow_event">
                                <div class="col-4 frontrow_event_date">
                                    <!-- the icon as a time element -->
                                    <a href="<?php echo $event->absolute_slug; ?>" title="<?php echo $event->title; ?>">
                                        <time datetime="<?php echo $event->event_start_date; ?>" class="icon">
                                            <em><?php echo date("l", strtotime($event->event_start_date));      // Friday ?></em>
                                            <strong><?php echo date("F", strtotime($event->event_start_date));  // January ?></strong>
                                            <span><?php echo date("d", strtotime($event->event_start_date));    // 19 ?></span>
                                        </time>
                                    </a>
                                </div>
                                <div class="col-8 frontrow_event_text">
                                    <?php //var_dump($event);
                                    $s = $event->title;
                                    if (strlen($event->title) > $max_len) {
                                        $offset = ($max_len - 3) - strlen($event->title);
                                        $s = substr($event->title, 0, strrpos($event->title, ' ', $offset)) . '…';
                                    };
                                    echo $s;
                                    ?>
                                    <div class="add_to_calendar"><a href="https://memento.epfl.ch/event/export/<?php echo $event->translation_id; ?>/" title="Add to calendar"><i class="far fa-calendar-plus"></i></a></div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="row">
                            <div class="col-12 more_events">
                                <a href="https://memento.epfl.ch/sti/?period=7"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/more.png"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }  // public function widget
  
    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) 
    {
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>"><?php esc_attr_e( 'Actu category:', 'text_domain' ); ?></label> 
            <?php wp_dropdown_categories( array(
                      'show_option_none' => __( 'Select category', 'textdomain' ), 
                      'name' => $this->get_field_name( 'cat' ),
                      'selected' => $instance['cat']
                      ) 
                  ); 
            ?>
        </p>
        <?php
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     */
    public function update( $new_instance, $old_instance ) 
    {
        // processes widget options to be saved
        $instance = array();
        $instance['cat'] = ( ! empty( $new_instance['cat'] ) ) ? sanitize_text_field( $new_instance['cat'] ) : '';

        return $instance;    
    }
}

register_widget(FrontRow::class);
