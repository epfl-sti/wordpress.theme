<?php

if (!defined('ABSPATH'))
    exit;

function get_newsletter_posts ($theme_options)
{
    $filters = array();

    // Maximum number of post to retrieve
    $filters['posts_per_page'] = (int) $theme_options['theme_max_posts'];
    if ($filters['posts_per_page'] == 0)
        $filters['posts_per_page'] = 10;


    // Include only posts from specified categories. Do not filter per category is no
    // one category has been selected.
    if (is_array($theme_options['theme_categories'])) {
        $filters['cat'] = implode(',', $theme_options['theme_categories']);
    }
    
    // Retrieve the posts asking them to WordPress
    $posts = get_posts($filters);
    return $posts;
}

