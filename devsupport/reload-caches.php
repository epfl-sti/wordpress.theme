<?php

/**
 * Invalidate caches on demand.
 *
 * Authentication is performed by the existence of a file
 * named "wp-content/uploads/theme-epfl-sti-reload-caches.OK". If whomever
 * is calling this page is not in a position to create that file, we
 * bail out.
 */

namespace EPFL\STI\Devsupport;

include __DIR__ . '/../../../../wp-load.php';

if (! unlink(__DIR__ . '/../../../../wp-content/uploads/theme-epfl-sti-reload-caches.OK')) {
    wp_die("Access denied");
}

function wants_to_clear ($feature) {
    if (! $_GET["clear[]"]) { return true; }  // Clear everything
    return (false !== array_search($feature, $_GET["clear[]"]));
}

if (wants_to_clear("megamenu")) {
    do_action("megamenu_delete_cache");
}
