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
