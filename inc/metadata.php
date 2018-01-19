<?php // -*- web-mode-code-indent-offset: 4; -*-

/**
 * Manage post metadata using so-called "custom fields."
 *
 * Custom fields are available in the wp-admin "Add New Post"
 * and "Edit Post" screens under "Screen Options" near the top.
 * The following custom fields are given a meaning in the EPFL-STI
 * theme:
 *
 * - external_url  The URL to be used as a permalink for this post
 *
 * - epfl_author   The SCIPER number of the person who did the work.
 *                 Can be repeated multiple times
 *
 * - published_in  The locale-native name of the publication this post
 *                 appeared in, as a string
 *                 appeared in, as a string
 */

namespace EPFL\STI;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EPFLPost
{
    const EXTERNAL_URL_SLUG  = "external_url";

    const AUTHOR_SLUG        = "epfl_author";

    const PUBLISHED_IN_SLUG  = "epfl_media_publication_name";

    const PUBLISHED_ON_SLUG  = "epfl_media_publication_date";

    function __construct ($post_or_post_id)
    {
        if (is_object($post_or_post_id)) {
            $this->_wp_post = $post_or_post_id;
            $this->ID       = $post_or_post_id->ID;
        } else {
            $this->ID       = $post_or_post_id;
        }
    }

    function wp_post ()
    {
        if (! $this->_wp_post) {
            $this->_wp_post = get_post($this->ID);
        }
        return $this->_wp_post;
    }

    function get_authors ()
    {
        if (! class_exists('EPFL\Persons\Person')) { return []; }

        $authors = [];
        foreach (get_post_meta($this->ID, self::AUTHOR_SLUG)
            as $sciper_text) {
            $author = EPFL\Persons\Person::find_by_sciper((int) $sciper_text);
            if ($author) { array_push($authors, $author); }
        }
        return $authors;
    }

    function get_external_url ()
    {
        return get_post_meta($this->ID, self::EXTERNAL_URL_SLUG, true);
    }

    function get_published_in ()
    {
        return get_post_meta($this->ID, self::PUBLISHED_IN_SLUG, true);
    }

    function get_the_publication_date ()
    {
        return get_post_meta($this->ID, self::PUBLISHED_ON_SLUG, true);
    }
}

/**
 * Allow a post to have a main URL that is not inside WordPress.
 *
 * To use this feature, set a so-called "custom field" on a post
 * with key "external_url", and the desired URL as the value.
 */
class EPFLPostController
{
    static function hook () 
    {
        add_filter(
            'post_link',
            array(get_called_class(), 'filter_external_permalink'),
            10, 2);
    }

    static function filter_external_permalink ($link, $post) {
        if ($post->post_type !== 'post') { return $link; }
        $epfl_post = new EPFLPost($post);
        $newlink = $epfl_post->get_external_url();
        return $newlink ? $newlink : $link;
    }
}

EPFLPostController::hook();
