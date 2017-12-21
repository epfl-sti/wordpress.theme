<?php

namespace EPFL\STI\Newsletter;

if (!defined('ABSPATH'))
    exit;

require_once(dirname(dirname(dirname(__FILE__))) . '/inc/i18n.php');
use function \EPFL\STI\Theme\___;

function get_newsletter_categories ($theme_options)
{
    return array(
        "news"       => new NewsCategory($theme_options),
        "events"     => new EventsCategory($theme_options),
        "faculty"    => new FacultyNewsCategory($theme_options),
        "inthemedia" => new InTheMediaCategory($theme_options)
        );
}

class PostCategory
{
    var $theme_options = null;

    function __construct ($theme_options)
    {
        if (! $theme_options) $theme_options = array();
        $this->theme_options = $theme_options;
    }

    function get_post_filters ()
    {
        $filters = array();

        // Maximum number of post to retrieve
        $filters['posts_per_page'] = (int) $this->theme_options['theme_max_posts'];
        if (! $filters['posts_per_page'])
            $filters['posts_per_page'] = 10;

        // Include only posts from specified categories. Do not filter per category is no
        // one category has been selected.
        if (is_array($this->theme_options['theme_categories'])) {
            $filters['cat'] = implode(',', $this->theme_options['theme_categories']);
        } else {
            $filters['post_type'] = "epfl-actu";
        }

        return $filters;
    }

    function posts ()
    {
        return get_posts($this->get_post_filters());
    }
}


class NewsCategory extends PostCategory
{
    function title () { return ___("News"); }
}

class EventsCategory extends PostCategory
{
    function title () { return ___("Upcoming events"); }
}

class FacultyNewsCategory extends PostCategory
{
    function title () { return ___("Faculty positions"); }
}

class InTheMediaCategory extends PostCategory
{
    function title () { return ___("In the media"); }
}
