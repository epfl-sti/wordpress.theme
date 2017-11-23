<?php

namespace EPFL\STI\Newsletter;
use \Newsletter;
use \NewsletterThemes;
use \NewsletterEmails;

/**
 * Hooking logic for the EPFL-STI newsletter theme
 *
 * Invoked by epfl-sti/functions.php, itself invoked directly by
 * WordPress early in the rendering cycle
 * (https://codex.wordpress.org/Functions_File_Explained)
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

$newsletter_plugin_dir = WP_PLUGIN_DIR . "/newsletter";
$newsletter_plugin_entrypoint = $newsletter_plugin_dir . "/plugin.php";

if (! file_exists($newsletter_plugin_entrypoint)) {
    return;
}
require_once($newsletter_plugin_entrypoint);
require_once("$newsletter_plugin_dir/emails/emails.php");

/**
 * Like the newsletter plugin's NewsletterThemes, except that there is
 * only one theme: ours.
 */
class EPFLSTINewsletterThemes extends NewsletterThemes {
    function __construct() {
        parent::__construct('emails');
    }

    function get_all() {
        // This is dead code as of version 5.1.1 afaict
        return array("epfl-sti" => "epfl-sti");
    }

    function get_all_with_data() {
        $data = get_file_data(dirname(__FILE__) . '/theme.php',
                              array('name' => 'Name', 'type' => 'Type', 'description'=>'Description'));
        $data['id'] = "epfl-sti";
        $data['name'] = "epfl-sti";
        if (empty($data['type'])) {
            $data['type'] = 'standard';
        }
        $data["screenshot"] = $this->get_theme_url(false) . "/screenshot.png";

        return array("epfl-sti" => $data);
    }
    function get_file_path($theme, $file) {
        return dirname(__FILE__) . "/" . $file;
    }
    function get_theme_url($unused_theme) {
        return get_theme_root_uri() . "/" . $this->_get_theme_basename() . "/newsletter-theme";
    }
    function _get_theme_basename() {
        return basename(dirname(dirname(__FILE__)));
    }
}

$emails = NewsletterEmails::instance();
$emails->themes = new EPFLSTINewsletterThemes();
