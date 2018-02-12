<?php

/**
 * Serve select URLs under WP_SITEURL/theme-epfl-sti/
 *
 * We cannot always use the get_theme_relative_uri() and friends
 * mechanism for this; for instance, while compiling the Vue
 * newsletter composer we need to know the URLs fonts etc. will be
 * served at. Here we guarantee a well-known *relative* URI that will
 * work regardless of how the theme is installed.
 */

namespace EPFL\STI\Theme\Assets;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

add_action('template_redirect', function() {
    $matched = array();
    if (preg_match('@/theme-epfl-sti/((node_modules|assets)/[^?]*)@',
                   $_SERVER['REQUEST_URI'], $matched)) {
        serve_file($matched[1]);
    }
});

function serve_file($path_relative)
{
    $path = get_theme_file_path();
    if (! preg_match('@/$@', $path)) $path .= '/';
    $path .= $path_relative;

    if (! file_exists($path)) {
        http_response_code(404);
        echo "$path_relative was not found";
        die();
    }

    if (preg_match('@[.]css$@', $path_relative)) {
        $type = "text/css";
    } else {
        $type = mime_content_type($path);
    }
    http_response_code(200);
    header("Content-Type: " . $type);
    readfile($path);
    die();
}
