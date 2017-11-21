<?php

namespace EPFL\STI\Theme;

if (!defined('ABSPATH'))
    exit;

require_once(dirname(dirname(dirname(__FILE__))) . '/inc/i18n.php');  // For ___()

function get_newsletter_categories ($theme_options)
{
    return array(
        new NewsCategory($theme_options),
        new EventsCategory($theme_options),
        new FacultyNewsCategory($theme_options)
        );
}

class PostCategory
{
    var $theme_options = null;

    function __construct ($theme_options)
    {
        $this->theme_options = $theme_options;
    }

    function get_post_filters ()
    {
        $filters = array();

        // Maximum number of post to retrieve
        $filters['posts_per_page'] = (int) $this->theme_options['theme_max_posts'];
        if ($filters['posts_per_page'] == 0)
            $filters['posts_per_page'] = 10;

        // Include only posts from specified categories. Do not filter per category is no
        // one category has been selected.
        if (is_array($this->theme_options['theme_categories'])) {
            $filters['cat'] = implode(',', $this->theme_options['theme_categories']);
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
    function title () { return ___("News") ; }
}

class EventsCategory extends PostCategory
{
    function title () { return ___("Upcoming events") ; }
}

class FacultyNewsCategory extends PostCategory
{
    function title () { return ___("Faculty positions") ; }
}

function img_data_base64 ($path) {
    $imageData = base64_encode(file_get_contents($path));
    return 'data: '.mime_content_type($path).';base64,'.$imageData;
}

function img_tag_data_base64 ($path, $attributes = array()) {
    $attributes['src'] = img_data_base64($path);
    return '<img '. implode(' ',
                              array_map(function ($k, $v) {
                                  return $k .'="'. htmlspecialchars($v) .'"';
                              }, array_keys($attributes), $attributes))
                    .' />';
}
