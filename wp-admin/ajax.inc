<?php

/**
 * Support for AJAX client-side apps (including, but not limited to,
 * the newsletter plugin)
 */

namespace EPFL\STI\AJAX;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

/**
 * An AJAX endpoint with XSRF protection
 */
class Endpoint
{
    function __construct ($slug)
    {
        $this->slug = $slug;
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
    function register_handlers ($class, $prefix)
    {
        foreach (get_class_methods($class) as $method_name) {
            $matched = [];
            if (! preg_match("/^ajax_(.*)$/", $method_name, $matched)) continue;
            add_action(
                sprintf("wp_ajax_%s%s", $prefix, $matched[1]),
                function() use ($class, $method_name) {
                    check_ajax_referer($this->slug);  // Nonce provided by @link get_script
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
     * Like @link register_handlers (), except that the handlers
     * only honor the GET method and are available without using
     * a nonce.
     */
    function register_public_handlers ($class, $prefix)
    {
        foreach (get_class_methods($class) as $method_name) {
            $matched = [];
            if (! preg_match("/^ajax_(.*)$/", $method_name, $matched)) continue;
            add_action(
                sprintf("wp_ajax_%s%s", $prefix, $matched[1]),
                function() use ($class, $method_name) {
                    $json_response = call_user_func(
                            array($class, $method_name));
                    echo json_encode($json_response, JSON_PRETTY_PRINT);
                    wp_die();  // That's the way WP AJAX rolls
                });
        }
    }

    /**
     * @return Some JS code that sets AJAX constants in window[$slug]
     *
     * window[$slug] will contain fields .ajaxurl (self-explanatory)
     * and .nonce, which serves to thwart cross-site request forgery
     * (XSRF) attacks. Vue.js code that performs AJAX requests is
     * supposed to pass that nonce back as the _wp_nonce key in the
     * request payload. (This is handled in @file ajax.js) Upon
     * receiving the AJAX call, PHP code calls check_ajax_referer() to
     * validate the nonce.
     */
    function get_script ()
    {
        return sprintf("
            <script>
               window.%s = {};
               window.%s.nonce = \"%s\";
               window.%s.ajaxurl = \"%s\";
            </script>
",
                       $this->slug,
                       $this->slug, wp_create_nonce($this->slug),
                       $this->slug, admin_url('admin-ajax.php'));
    }

    /**
     * Arrange for @link get_script() to be served on some admin page(s)
     *
     * @param $when If set to a string, render only to the admin page
     * with the same "$hook_suffix" (in the sense of the
     * Wordpress @link admin_enqueue_scripts action). If set to a
     * callable, use the Boolean return value of same to decide
     * whether to render the script. (The callable will likewise be
     * passed the $hook_suffix as its sole argument)
     */
    function admin_enqueue ($when = null)
    {
        $self = $this;
        add_action('admin_enqueue_scripts',
                   function($hook_suffix) use ($self, $when) {
                       if ($when) {
                           if (is_callable($when)) {
                               if (! call_user_func($when, $hook_suffix)) return;
                           } else {
                               if ($when !== $hook_suffix) return;
                           }
                       }
                       echo $self->get_script();
                   });
    }
}
