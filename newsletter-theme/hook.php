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

require_once(dirname(dirname(__FILE__)) . "/inc/epfl.php");
use function \EPFL\STI\get_theme_relative_uri;

class NewsletterHook
{
    // PHP only:
    const SLUG = 'epfl_sti_newsletter';

    // Must be the same as in inc/ajax.js:
    const TOPLEVEL_JS_VAR_NAME = 'epflsti_newsletter_composer';

    static function hook ()
    {
        self::register_epfl_newsletter_theme();

        $pagename  = "admin_page_newsletter_emails_new";
        add_action("wp_loaded", array(get_called_class(), "serve_composer_app"));

        add_action("load-${pagename}",
                   array(get_called_class(), "hook_composer_style"));
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
     * Use our custom CSS for the "new newsletter" admin page
     *
     * @file ./newsletter-admin.css
     */
    static function hook_composer_style ()
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
     * methods can obtain the details of a GET AJAX request in $_GET,
     * and/or $_REQUEST; in the case of a proper POST request (with
     * Content-Type: application/json in the request), the decoded
     * JSON will be passed as a parameter to the handler instead.
     *
     * Handlers should return the
     * data structure that they wish to return to the AJAX caller
     * (typically a PHP associative array).
     *
     * Handlers are protected by a nonce against XSRF attacks:
     * @see serve_composer_app
     *
     * @param $class The fully qualified class name. Tip: you can
     *               use the form `MyClass::class` to get a fully
     *               qualified class name.
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
                    check_ajax_referer(self::SLUG);  // Nonce provided by serve_composer_app
                    if ($_SERVER['REQUEST_METHOD'] === "POST" &&
                        $_SERVER["CONTENT_TYPE"] === "application/json") {
                        $json_response = call_user_func(
                            array($class, $method_name),
                            json_decode(file_get_contents('php://input'), true));
                    } else {
                        $json_response = call_user_func(
                            array($class, $method_name));
                    }
                    echo json_encode($json_response, JSON_PRETTY_PRINT);
                    wp_die();  // That's the way WP AJAX rolls
                });
        }
    }

    /**
     * @return Some JS code that sets window.epflsti_newsletter_composer
     *
     * window.epflsti_newsletter_composer contains fields .ajaxurl
     * (self-explanatory) and .nonce, which serves to thwart
     * cross-site request forgery (XSRF) attacks. Vue.js code that
     * performs AJAX requests is supposed to pass that nonce back as
     * the _wp_nonce key in the request payload. (This is handled
     * in @file inc/ajax.js) Upon receiving the AJAX call, PHP code
     * calls check_ajax_referer() to validate the nonce.
     */
    static function script_pass_params ()
    {
        return sprintf("
            <script>
               window.%s = {};
               window.%s.nonce = \"%s\";
               window.%s.ajaxurl = \"%s\";
            </script>
",
                       self::TOPLEVEL_JS_VAR_NAME,
                       self::TOPLEVEL_JS_VAR_NAME, wp_create_nonce(self::SLUG),
                       self::TOPLEVEL_JS_VAR_NAME, admin_url( 'admin-ajax.php' ));
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

    /**
     * Serve the Composer Vue app under ?epflsti=emails-vue-editor
     *
     * This is done by invoking theme.php just like The Newsletter
     * Plugin would, except that a function render_editor_scripts() is
     * made available. (theme.php is expected to call that function in
     * <head> iff it exists.) render_editor_scripts() in turn ensures
     * that all the bells and whistles are available to the
     * single-page Web app in the iframe, namely: js-core polyfills,
     * window.epflsti_newsletter_composer.nonce for XSRF protection
     * (which is supposed to be POSTed back as the _wp_nonce JSON
     * parameter upon every AJAX request; see inc/ajax.js), and the
     * browserified Vue app.
     */
    static function serve_composer_app ()
    {
        if ($_GET["epflsti"] !== "emails-vue-editor") return;
        if (!current_user_can('manage_categories')) {
            die('Not enough privileges');
        }
        if (!check_admin_referer('view')) {
            die();
        }

        header('Content-Type: text/html;charset=UTF-8');

        function render_editor_scripts () {
            echo sprintf("<script type=\"text/javascript\" src=\"%s\"></script>\n",
                         get_theme_relative_uri() . "/assets/core.min.js");
            echo sprintf("<script type=\"text/javascript\" src=\"%s\"></script>\n",
                         get_theme_relative_uri() . "/assets/jquery.min.js");
            echo NewsletterHook::script_pass_params();
            echo sprintf("<script type=\"text/javascript\" src=\"%s\"></script>\n",
                         get_theme_relative_uri() . "/assets/newsletter-composer.min.js");
        }
        include(dirname(__FILE__) . "/theme.php");

        die();
    }
}

NewsletterHook::hook();

// Need to require_once all files that have AJAX handlers:
require_once dirname(__FILE__) . "/inc/ajax.php";
require_once dirname(__FILE__) . "/inc/newsletter_state.php";
