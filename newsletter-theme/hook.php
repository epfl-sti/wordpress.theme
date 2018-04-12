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

require_once(dirname(__DIR__) . "/inc/epfl.php");
use function \EPFL\STI\get_theme_relative_uri;

require_once(dirname(__DIR__) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;
use function \EPFL\STI\Theme\__x;

require_once(dirname(__DIR__) . "/wp-admin/ajax.inc");

class NewsletterHook
{
    // PHP only:
    const SLUG = 'epfl_sti_newsletter';

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

    static $endpoint;
    static private function get_ajax_endpoint ()
    {
        if (! self::$endpoint) {
            self::$endpoint = new \EPFL\STI\AJAX\Endpoint("epflsti_newsletter_composer");
        }
        return self::$endpoint;
    }

    /**
     * Register $class as having AJAX handlers
     */
    static function add_ajax_class ($class, $prefix)
    {
        static::get_ajax_endpoint()->register_handlers($class, $prefix);
    }

    static function get_ajax_settings_script ()
    {
        return static::get_ajax_endpoint()->get_script();
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
     * parameter upon every AJAX request; see ../inc/ajax.js), and the
     * browserified Vue app.
     */
    static function serve_composer_app ()
    {
        if ( !isset($_GET["epflsti"])  || $_GET["epflsti"] !== "emails-vue-editor") return;
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
            echo \EPFL\STI\Newsletter\NewsletterHook::get_ajax_settings_script();
            echo sprintf("<link rel=\"stylesheet\" href=\"%s\">\n",
                         get_theme_relative_uri() . "/assets/newsletter-composer.min.css");
            echo sprintf("<script type=\"text/javascript\" src=\"%s\"></script>\n",
                         get_theme_relative_uri() . "/assets/newsletter-composer.min.js");
        }
        include(dirname(__FILE__) . "/theme.php");

        die();
    }
}

NewsletterHook::hook();
// Also need to require_once all files that have AJAX handlers or
// otherwise have hooks of their own:
require_once dirname(__FILE__) . "/inc/newsletter_state.php";
