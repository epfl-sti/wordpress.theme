<?php

/**
 * Assets and WordPress tweaks for the newsletter composer
 *
 * Invoked by epfl-sti/functions.php, itself invoked directly by
 * WordPress early in the rendering cycle
 * (https://codex.wordpress.org/Functions_File_Explained)
 *
 * Can be further hooked with AJAX handlers, see @link add_ajax_class.
 */

namespace EPFL\STI\Newsletter;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

class NewsletterHook
{
    // PHP only:
    const SLUG = 'epfl_sti_newsletter';

    // Must be the same as in inc/ajax.js:
    const GLOBAL_JS_VAR_NAME = 'epflsti_newsletter_composer';

    static function hook ()
    {
        self::register_epfl_newsletter_theme();

        $pagename  = "admin_page_newsletter_emails_new";
        add_action("admin_print_scripts-${pagename}",
                   array(get_called_class(), "hook_core_js"),
                   -1000);  // Very early
        add_action("load-${pagename}",
                   array(get_called_class(), "hook_composer_scripts"));
        add_action("load-${pagename}",
                   array(get_called_class(), "hook_composer_styles"));
    }

    static function register_epfl_newsletter_theme ()
    {
        add_action("epfl_newsletter_init", function() {
            \EPFL\Newsletter\EPFLNewsletterThemes::register(
                "epfl-sti",
                dirname(__FILE__) . '/theme.php');
        });
    }

    /**
     * Hook core.js very early in the page loading
     *
     * core.js is a polyfill gallery that gives us e.g. a working
     * "".endsWith() in IE.
     */
    static function hook_core_js ()
    {
        printf('<script type="text/javascript" src="%s"></script>',
               get_theme_file_uri("/assets/core.min.js"));
    }

    static function hook_composer_scripts ()
    {
        wp_register_script(self::SLUG,
                           get_theme_file_uri("/assets/newsletter-admin.min.js"),
                           "0.1.0");
        wp_enqueue_script(self::SLUG);

        self::hook_xsrf_nonce();
    }

    static function hook_composer_styles ()
    {
        $css_slug = self::SLUG . "_css";
        wp_register_style($css_slug,
                          get_theme_file_uri("/newsletter-theme/newsletter-admin.css"));
        wp_dequeue_style("tnp-admin");
        wp_enqueue_style($css_slug);
    }

    /**
     * Register $class as having AJAX handlers
     *
     * All methods whose name start with ajax_ in $class are set up as
     * handlers for the corresponding "action" in the sense of
     * @link https://codex.wordpress.org/AJAX_in_Plugins . These
     * methods can obtain the details of the AJAX request in $_GET,
     * $_POST and/or $_REQUEST (depending on which HTTP method the
     * JavaScript code decides to use). Handlers should return the
     * data structure that they wish to return to the AJAX caller
     * (typically a PHP associative array).
     *
     * Handlers are protected by a nonce against XSRF attacks:
     * @see hook_xsrf_nonce
     *
     * @param $class The fully qualified class name. Tip: to avoid
     *               messing with PHP namespaces, use
     *               get_called_class() from a method (static or not)
     *               of the class itself.
     *
     * @param $prefix Prefix all method names with this from the JS
     *                side. For instance, if PHP class `$myclass` has
     *                an `ajax_foo` method, calling
     *                `add_ajax_class($myclass, "my_class_")` will
     *                make it possible for JavaScript code to invoke
     *                that method by passing `{ "action":
     *                "my_class_foo", ... }` as part of the AJAX
     *                request's payload.
     */
    static function add_ajax_class ($class, $prefix)
    {
        foreach (get_class_methods($class) as $method_name) {
            $matched = [];
            if (! preg_match("/^ajax_(.*)$/", $method_name, $matched)) continue;
            add_action(
                sprintf("wp_ajax_%s%s", $prefix, $matched[1]),
                function() use ($class, $method_name) {
                    check_ajax_referer(self::SLUG);  // See hook_xsrf_nonce
                    $json_response = call_user_func(
                        array($class, $method_name));
                    echo json_encode($json_response);
                    wp_die();  // That's the way WP AJAX rolls
                });
        }
    }

    /**
     * Arrange for `window.epflsti_newsletter_composer` to be set in JS code.
     *
     * Called at "load-${pagename}" time by PHPNewsletter::hook().
     * This creates a global `window.epflsti_newsletter_composer` JS
     * variable that has a `nonce` field. The JS code is supposed to
     * pass it back as a parameter in every AJAX request (under the
     * name _wp_nonce). Upon receiving the AJAX call, PHP code calls
     * check_ajax_referer() to validate the nonce, thereby thwarting
     * cross-site request forgery (XSRF) attacks.
     */
    static function hook_xsrf_nonce ()
    {
        self::add_global_variable_field(self::GLOBAL_JS_VAR_NAME, "nonce",
                                        wp_create_nonce(self::SLUG));
    }

    static $_declared_vars = array();
    static private function add_global_variable_field ($var_name, $field, $value)
    {
        $json_value = json_encode($value);
        if (! self::$_declared_vars[$var_name]) {
            $payload = sprintf("window.%s = {%s: %s};\n",
                               $var_name, $field, $json_value);
            self::$_declared_vars[$var_name] = true;
        } else {
            $payload = sprintf("window.%s.%s = %s;\n",
                               $var_name, $field, $json_value);
        }
        // For some reason, wp_add_inline_script() only tacks its
        // payload after a <script> that has a src= (even though it
        // creates a new <script> after that one). Calling it on a
        // new, wp_enqueue()d script does nothing. So we piggy-back on
        // our own script instead.
        wp_add_inline_script(self::SLUG, $payload);
    }
}

NewsletterHook::hook();

/**
 * Demo class that handles AJAX.
 *
 * TODO: move to another file.
 */
class ToyHandler
{
    static function hook ()
    {
        $js_action_prefix = "epfl_sti_newsletter_";
        NewsletterHook::add_ajax_class(get_called_class(), $js_action_prefix);
    }
    static function ajax_addten ()
    {
        global $wpdb; // this is how you get access to the database
        $value = $_POST['number'];
        return array("number" => $value + 10);
    }
}

ToyHandler::hook();
