<?php

/**
 * Manage the state of the draft newsletter
 *
 * If there is no state (i.e. the previous one expired), use some default
 * settings to show a template of a newsletter. Accept AJAX calls to update
 * the state, and thereafter serve the posts that correspond to said
 * saved state.
 */

namespace EPFL\STI\Newsletter;

if (!defined('ABSPATH'))
    exit;

require_once(dirname(dirname(dirname(__FILE__))) . '/inc/i18n.php');
use function \EPFL\STI\Theme\___;

require_once(dirname(__FILE__) . '/ajax.php');

function get_newsletter_posts ($theme_options)
{
    $state = new NewsletterDraftState($theme_options);
    return array(
        "news"       => new NewsQuery($state),
        "events"     => new EventsQuery($state),
        "faculty"    => new FacultyNewsQuery($state),
        "inthemedia" => new InTheMediaQuery($state)
        );
}

class NewsletterDraftState
{
    const TIMEOUT_SECS = 45 * 60;

    private $saved_state;

    function __construct($theme_options)
    {
        $this->saved_state = JSON_decode(get_site_transient(self::_get_transient_key()), true);
    }

    function is_set ($kind)
    {
        if (! $this->saved_state) return false;
        return !(! $this->saved_state[$kind]);
    }

    function get_list_of_posts ($kind)
    {
        return $this->saved_state[$kind];
    }

    static function ajax_save ($state)
    {
        set_site_transient(self::_get_transient_key(),
                           json_encode($state),
                           self::TIMEOUT_SECS);
    }

    static private function _get_transient_key ()
    {
        return sprintf("epfl-newsletter-draft-state_%d",
                       get_current_user_id());
    }

}

NewsletterHook::add_ajax_class(NewsletterDraftState::class, "epfl_sti_newsletter_draft_");

class PostQuery
{
    var $theme_options = null;
    protected $filter;
    protected $post_list;

    function __construct ($state)
    {
        $this->state = $state;
    }

    function posts ()
    {
        $this->filter = array();
        $this->_setup_filter($this->state);
        $posts = get_posts($this->filter);
        if (! $this->post_list) return $posts;

        // Reorder like $this->post_list
        $ordered_posts = array();
        foreach ($this->post_list as $id) {
            end($posts); $last_post_key = key($posts);
            foreach ($posts as $key => $post) {
                if ($post->ID == $id) {
                    array_push($ordered_posts, $post);
                    break;
                }
                if ($key === $last_post_key) {
                    error_log("Couldn't find ID $id in get_posts() results");
                }
            }
        }
        assert(count($posts) === count($ordered_posts));
        return $ordered_posts;
    }

    private function _setup_filter ($state)
    {
        if (! $state->is_set(static::KIND)) {
            $this->_set_default_filter();
        } else {
            $this->filter['post_type'] = 'any';
            $this->post_list = $state->get_list_of_posts(static::KIND);
            $this->filter['post__in'] = $this->post_list;
            $this->filter['posts_per_page'] = count($this->post_list);
            $this->filter["ignore_sticky_posts"] = true;
        }
    }

    protected function _set_default_filter () {}
}


class NewsQuery extends PostQuery
{
    const KIND = "news";

    function title () { return ___("News"); }

    protected function _set_default_filter () {
        $this->filter['post_type'] = "epfl-actu";
        $this->filter['posts_per_page'] = 10;
    }
}

class EventsQuery extends PostQuery
{
    const KIND = "events";

    function title () { return ___("Upcoming events"); }
}

class FacultyNewsQuery extends PostQuery
{
    const KIND = "faculty";

    function title () { return ___("Faculty positions"); }

    protected function _set_default_filter ()
    {
        $this->filter["category_name"] = "faculty-positions";
    }

}

class InTheMediaQuery extends PostQuery
{
    const KIND = "inthemedia";

    function title () { return ___("In the media"); }
}
