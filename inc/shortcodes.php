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

add_shortcode("lab-card", function ($attrs, $content) {
    $html = epflsti_lab_card($content, $attrs);
    # Avoid the Toggle wpautop grim reaper:
    $html = preg_replace('|</p>|', '</p >', $html);
    return $html;
});
