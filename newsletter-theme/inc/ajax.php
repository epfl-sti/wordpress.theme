<?php

/**
 * AJAX handlers for the "compose newsletter" UI
 */

namespace EPFL\STI\Newsletter;
use \WP_Query;

use \EPFL\Actu\Actu;
function has_actus() { return class_exists('\\EPFL\\Actu\\Actu'); }

$js_action_prefix = "epfl_sti_newsletter_";

class ContentSearchAjax
{
    static function ajax_search ()
    {
        $query = new WP_Query;
        $results = array();
        $post_type = $_POST['postType'];
        foreach ($query->query(array( 'post_type' => $post_type,
                                      's'         => $_POST['searchTerm'] ))
                 as $result) {
            $details = array(
                "ID"           => $result->ID,
                // TODO: $result->post_author is an int, should dereference it
                "post_author"  => strip_tags($result->post_author),
                "post_date"    => $result->post_date,
                "post_title"   => strip_tags($result->post_title),
                "post_excerpt" => strip_tags($result->post_excerpt),
                "post_content" => strip_tags($result->post_content)
            );
            if (has_actus() && $post_type === Actu::get_post_type()) {
                $actu = new Actu($details["ID"]);
                $thumbnail_url = $actu->get_external_thumbnail_url();
                if ($thumbnail_url) {
                    $details["thumbnail_url"] = $thumbnail_url;
                }
            }
            array_push($results, $details);
        }

        return array(
            "status" => "OK",
            "searchResults" => $results
        );
    }
}

require_once dirname(dirname(__FILE__)) . "/hook.php";
NewsletterHook::add_ajax_class(ContentSearchAjax::class, "epfl_sti_newsletter_");
