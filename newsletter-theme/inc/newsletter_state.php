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

if (!defined('ABSPATH')) { exit; }

require_once(dirname(dirname(dirname(__FILE__))) . '/inc/i18n.php');
use function \EPFL\STI\Theme\___;

use \EPFL\WS\Actu\Actu;  // If it exists

require_once(dirname(__FILE__) . "/newsletter_section_categories.inc");

function get_newsletter_posts ($theme_options)
{
    $state = new NewsletterDraftState($theme_options);
    $retval = array();
    foreach (array(new NewsQuery($state),
                   new EventsQuery($state),
                   new FacultyNewsQuery($state),
                   new InTheMediaQuery($state)) as $query) {
        $retval[$query::KIND] = $query;
    }
    return $retval;
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
        return is_array($this->saved_state[$kind]);
    }

    function get_list_of_posts ($kind)
    {
        return $this->saved_state[$kind];
    }

    static function ajax_draft_save ($state)
    {
        set_site_transient(self::_get_transient_key(),
                           json_encode($state),
                           self::TIMEOUT_SECS);
    }

    static function clear ()
    {
        delete_site_transient(self::_get_transient_key());
    }

    static private function _get_transient_key ()
    {
        return sprintf("epfl-newsletter-draft-state_%d",
                       get_current_user_id());
    }
}

NewsletterHook::add_ajax_class(NewsletterDraftState::class, "epfl_sti_newsletter_");

/**
 * A PostQuery instance enumerates posts for the newsletter.
 *
 * There are three main use cases:
 *
 * a) Rendering the HTML for the pristine newsletter
 *
 * b) Rendering the HTML for the newsletter after the user has mutated
 *    its state and saved it (using an AJAX POST through the
 *    NewsletterDraftState class)
 *
 * c) Returning results for a search-as-you-type AJAX query
 *
 * In case b) the list of post IDs is known precisely. In the other two
 * cases, a "starter" set of filters is computed by the _get_query_filter
 * abstract method so as to only return the posts of interest in the
 * context of the corresponding newsletter section.
 */

abstract class PostQuery
{
    var $theme_options = null;
    protected $state;
    protected $query;
    protected $post_list;

    function __construct ($state = null, $query = null)
    {
        $this->state = $state;
        $this->query = $query;
    }

    function posts ()
    {
        if ($this->state && $this->state->is_set(static::KIND)) {
            return $this->_get_posts_in_order($this->state->get_list_of_posts(static::KIND));
        } else {
            return $this->_get_posts_by_query($this->query);
        }
    }

    private function _get_posts_in_order($post_list) {
        $posts = get_posts(array(
            'post_type'           => 'any',
            'post__in'            => $post_list,
            'posts_per_page'      => count($post_list),
            'ignore_sticky_posts' => true
        ));

        // Reorder like $post_list
        $ordered_posts = array();
        foreach ($post_list as $id) {
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

    /**
     * @return The post list, in the a) and c) cases
     *
     * The base class only applies filtering by NewsletterSectionCategory.
     * Subclasses for types that have a dedicated post type (i.e. NewsQuery
     * and EventsQuery) will want to override this to add to the result set.
     */
    protected function _get_posts_by_query ($query)
    {
        $language_hint = null;
        if (function_exists("pll_get_language")) {
            $language_hint = \pll_get_language();
        }
        $categories = array_map(
            function($cat) { return $cat->ID(); },
            NewsletterSectionCategory::find_all(static::KIND, $language_hint));
        if (! count($categories)) return;

        $criteria = array("category__in" => $categories);
        if ($query["s"]) {
            $criteria["s"] = $query["s"];
        }
        return get_posts($criteria);
    }

    /**
     * For use by subclasses overriding _get_posts_by_query()
     */
    protected function _get_posts_by_post_type ($post_type, $query = null, $count = 20) {
        $criteria = array();
        $criteria['post_type'] = $post_type;
        $criteria['posts_per_page'] = $count;

        if ($query && $query["s"]) {
            $criteria["s"] = $query["s"];
        }
        return get_posts($criteria);
    }

    static function ajax_search ($query)
    {
        $thisclass = get_called_class();
        return (new $thisclass(null, $query))->_do_ajax_search($query);
    }

    private function _do_ajax_search ($query)
    {
        $results = array();
        foreach ($this->_get_posts_by_query($query) as $post) {
            array_push($results, $this->_post2result($post));
        }
        return array(
            "status" => "OK",
            "searchResults" => $results
        );
    }

    protected function _post2result ($post)
    {
        return array(
            "ID"           => $post->ID,
            // TODO: $post->post_author is an int, should dereference it
            "post_author"  => strip_tags($post->post_author),
            "post_date"    => $post->post_date,
            "post_title"   => strip_tags($post->post_title),
            "post_excerpt" => strip_tags($post->post_excerpt),
            "post_content" => strip_tags($post->post_content),
        );
    }
}

class NewsQuery extends PostQuery
{
    // IMPORTANT: here and in the other subclasses, KIND must match the key in
    // the stateTraits object in ../vue/GlobalBus.js
    const KIND = "news";

    function title () { return ___("News"); }

    protected function _get_posts_by_query ($query)
    {
        return array_merge(
            parent::_get_posts_by_query($query),
            $this->_get_posts_by_post_type("epfl-actu", $query));
        // Note that the behavior degrades nicely if the epfl-ws
        // plugin is not active.
    }

    protected function _post2result ($post)
    {
        $result = parent::_post2result($post);
        if (class_exists('\\EPFL\\WS\\Actu\\Actu') &&
            $post->post_type === Actu::get_post_type()) {
            $actu = Actu::get($post->ID);
            if ($actu) {
                $thumbnail_url = $actu->get_image_url();
                if ($thumbnail_url) {
                    $result["thumbnail_url"] = $thumbnail_url;
                }
            }
        }

        return $result;
    }
}

class EventsQuery extends PostQuery
{
    const KIND = "events";

    function title () { return ___("Upcoming events"); }

    protected function _get_posts_by_query ($query)
    {
        return array_merge(
            parent::_get_posts_by_query($query),
            $this->_get_posts_by_post_type("epfl-memento", $query));
        // Note that the behavior degrades nicely if the epfl-ws
        // plugin is not active.
    }

}

class FacultyNewsQuery extends PostQuery
{
    const KIND = "faculty";

    function title () { return ___("Faculty positions"); }
}

class InTheMediaQuery extends PostQuery
{
    const KIND = "media";

    function title () { return ___("In the media"); }
}

foreach (array(NewsQuery::class, EventsQuery::class,
               FacultyNewsQuery::class, InTheMediaQuery::class)
         as $theclass) {
    NewsletterHook::add_ajax_class(
        $theclass,
        sprintf("epfl_sti_newsletter_%s_", $theclass::KIND));
}
