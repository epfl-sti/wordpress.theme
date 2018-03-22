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
        $attrs["lab"] = Lab::get_by_abbrev($attrs["abbrev"]);
    }
    $html = epflsti_lab_card($content, $attrs);
    return $html;
});
