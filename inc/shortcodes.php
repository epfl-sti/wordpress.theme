<?php

/**
 * Shortcodes for EPFL STI pages
 */

namespace EPFL\STI;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

require_once(__DIR__ . "/template-tags.php");
use function \epflsti_lab_card;

use EPFL\WS\Labs\Lab;

add_shortcode("lab-card", function ($attrs, $content) {
    if ($attrs["abbrev"] && class_exists('\\EPFL\\WS\\Labs\\Lab')) {
        error_log("Looking up by abbrev on " . $attrs["abbrev"]);
        $attrs["lab"] = Lab::get_by_abbrev($attrs["abbrev"]);
        error_log("Result: " . var_export($attrs["lab"], true));  // XXX
    }
    $html = epflsti_lab_card($content, $attrs);
    return $html;
});
