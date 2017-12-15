<?php

/**
 * AJAX handlers for the "compose newsletter" UI
 */

namespace EPFL\STI\Newsletter;
use \WP_Query;

$js_action_prefix = "epfl_sti_newsletter_";

class ContentSearchAjax
{
    static function ajax_search ()
    {
        $query = new WP_Query;
        $results = array();
        foreach ($query->query(array( 'post_type' => $_POST['postType'],
                                        's'       => $_POST['searchTerm'] ))
                 as $result) {
            array_push($results, array(
                "ID"           => $result->ID,
                // TODO: $result->post_author is an int, should dereference it
                "post_author"  => strip_tags($result->post_author),
                "post_date"    => $result->post_date,
                "post_title"   => strip_tags($result->post_title),
                "post_excerpt" => strip_tags($result->post_excerpt),
                "post_content" => strip_tags($result->post_content)
            ));
        }

        return array(
            "status" => "OK",
            "searchResults" => $results
        );
    }
}

require_once dirname(dirname(__FILE__)) . "/hook.php";
NewsletterHook::add_ajax_class(ContentSearchAjax::class, "epfl_sti_newsletter_");
