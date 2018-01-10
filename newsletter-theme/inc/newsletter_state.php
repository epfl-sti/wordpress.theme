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
    function __construct($theme_options)
    {
    }

    function is_set ($kind)
    {
        return false;
    }

    function get_list_of_posts ($kind)
    {
        return null;
    }
}

NewsletterHook::add_ajax_class(NewsletterDraftState::class, "epfl_sti_newsletter_draft_");

class PostQuery
{
    var $theme_options = null;
    protected $filter;

    function __construct ($state)
    {
        $this->state = $state;
    }

    function posts ()
    {
        $this->filter = array();
        $this->_setup_filter($this->state);
        return get_posts($this->filter);
    }

    private function _setup_filter ($state)
    {
        if (! $state->is_set($this->KIND)) {
            $this->_set_default_filter();
        } else {
            $this->filter['post_type'] = 'any';
            $this->filter['post__in'] = $state->get_list_of_posts($this->KIND);
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
