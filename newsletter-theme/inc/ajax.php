<?php

/**
 * AJAX handlers for the "compose newsletter" UI
 */

namespace EPFL\STI\Newsletter;

$js_action_prefix = "epfl_sti_newsletter_";

class ContentSearchAjax
{
    static function ajax_addten ()
    {
        global $wpdb; // this is how you get access to the database
        $value = $_POST['number'];
        return array("number" => $value + 10);
    }
}

require_once dirname(dirname(__FILE__)) . "/hook.php";
NewsletterHook::add_ajax_class(ContentSearchAjax::class, "epfl_sti_newsletter_");
