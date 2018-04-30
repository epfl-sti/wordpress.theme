<?php // -*- web-mode-code-indent-offset: 4; -*-

/**
 * Manage post metadata using so-called "custom fields" and tags.
 *
 * Custom fields are available in the wp-admin "Add New Post"
 * and "Edit Post" screens under "Screen Options" near the top.
 *
 * @see "sti-test.epfl.ch User Manual", https://docs.google.com/document/d/1pqJBH9qj6plmFdK604ssMsX7vCyQlpyq9t26zw3opzk/edit#
 */

namespace EPFL\STI;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EPFLPost
{
    const EXTERNAL_URL_SLUG  = "external_url";

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

    const OBSOLETE_AUTHOR_META        = "epfl_work_by";

    function get_attribution ()
    {
        if (! class_exists('\\EPFL\\WS\\Persons\\Person')) { return []; }

        $author_scipers = [];
        if ($author_list_from_meta = get_post_meta($this->ID, self::OBSOLETE_AUTHOR_META)) {
            $author_scipers =  $author_list_from_meta;
        } else {
            foreach (wp_get_post_terms($this->ID, 'post_tag', array('fields' => 'names'))
                as $post_tag) {
                $matched = array();
                if (preg_match('/^ATTRIBUTION=(SCIPER:|sciper:|)(\d+)$/', $post_tag, $matched)) {
                    array_push($author_scipers, $matched[1]);
                }
            }
        }
        $authors = [];
        foreach ($author_scipers as $sciper) {
            $author = \EPFL\WS\Persons\Person::find_by_sciper((int) $sciper);
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

    function get_publication_date ()
    {
        $date_raw = get_post_meta($this->ID, self::PUBLISHED_ON_SLUG, true);
        if (! $date_raw) return;
        return \DateTime::createFromFormat('Y-m-d', $date_raw);
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
        add_filter(
            'post_type_link',
            array(get_called_class(), 'filter_external_permalink'),
            10, 2);
    }

    static function filter_external_permalink ($link, $post) {
        $epfl_post = new EPFLPost($post);
        $newlink = $epfl_post->get_external_url();
        return $newlink ? $newlink : $link;
    }
}

EPFLPostController::hook();
