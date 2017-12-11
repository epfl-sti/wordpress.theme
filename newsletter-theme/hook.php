<?php

namespace EPFL\STI\Newsletter;

/**
 * Register this theme with the EPFL-newsletter plugin
 *
 * Invoked by epfl-sti/functions.php, itself invoked directly by
 * WordPress early in the rendering cycle
 * (https://codex.wordpress.org/Functions_File_Explained)
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

add_action("epfl_newsletter_init", function() {
    \EPFL\Newsletter\EPFLNewsletterThemes::register(
        "epfl-sti",
        dirname(__FILE__) . '/theme.php');
});

add_action("load-admin_page_newsletter_emails_new", function() {
    $script_slug = "epfl_sti_newsletter_composer";
    wp_register_script( $script_slug,
                       get_theme_file_uri("/assets/newsletter-admin.min.js"),
                       "0.1.0" );
    wp_enqueue_script($script_slug);
    $css_slug = $script_slug . "_css";
    wp_register_style($css_slug,
                       get_theme_file_uri("/newsletter-theme/newsletter-admin.css"));
    wp_dequeue_style("tnp-admin");
    wp_enqueue_style($css_slug);
});

/* Hook up core.js (gives us e.g. a working "".endsWith() in IE) very
 * early in the page loading: */
add_action("admin_print_scripts-admin_page_newsletter_emails_new",
           function () {
               printf('<script type="text/javascript" src="%s"></script>',
                      get_theme_file_uri("/assets/core.min.js"));
           },
           -1000);  // Very early

/**
 * Custom AJAX services for the Vue applet
 */
class AJAXNewsletter
{
    const SLUG = "epfl_sti_newsletter";
    const METHODS = ["search", "set", "addten"];

    static function hook ()
    {
        foreach (self::METHODS as $method) {
            add_action(
                sprintf("wp_ajax_%s_%s", self::SLUG, $method),
                function() use ($method) {
                   $json_response = call_user_func(
                       array(get_called_class(), "ajax_" . $method));
                   echo json_encode($json_response);
                    wp_die();  // That's the way WP AJAX rolls
                });
        }
    }

    static function ajax_addten () {
        global $wpdb; // this is how you get access to the database
        $value = $_POST['number'];
        return array("number" => $value + 10);
    }
}

AJAXNewsletter::hook();
