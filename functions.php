<?php
/**
 * Understrap functions and definitions
 *
 * As explained in
 * https://codex.wordpress.org/Functions_File_Explained, functions.php
 * (not index.php) is the main entry point of a theme. All global
 * setup should happen here or from one of the files required below.
 *
 * @package epflsti
 */

/**
 * Theme setup and custom theme supports.
 */
require_once __DIR__ . '/inc/setup.php';

/**
 * Register widgets.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
require_once __DIR__ . '/inc/widgets.php';

/**
 * Register shortcodes.
 */
require_once __DIR__ . '/inc/shortcodes.php';

/**
 * Load functions to secure your WP install.
 */
require_once __DIR__ . '/inc/security.php';

/**
 * Enqueue scripts and styles.
 */
require_once __DIR__ . '/inc/enqueue.php';

/**
 * Custom template tags for this theme.
 */
require_once __DIR__ . '/inc/template-tags.php';

/**
 * Custom post metadata and its rendering.
 */
require_once __DIR__ . '/inc/metadata.php';

/**
 * Custom pagination layout.
 */
require_once __DIR__ . '/inc/pagination.php';

/**
 * Well-known URLs for this theme's assets
 */
require_once __DIR__ . '/inc/serve-assets.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require_once __DIR__ . '/inc/extras.php';

/**
 * Customizer additions.
 */
require_once __DIR__ . '/inc/customizer.php';

/**
 * Custom Comments file.
 */
require_once __DIR__ . '/inc/custom-comments.php';

/**
 * Customization of the allowed HTML5 elements
 */
require_once __DIR__ . '/inc/html5-tags.php';

/**
 * Load Jetpack compatibility file.
 */
require_once __DIR__ . '/inc/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
require_once __DIR__ . '/inc/bootstrap-wp-navwalker.php';

/**
 * Load WooCommerce functions.
 */
require_once __DIR__ . '/inc/woocommerce.php';

/**
 * Load Editor functions.
 */
require_once __DIR__ . '/inc/editor.php';

/**
 * Work with EPFL environment.
 */
require_once __DIR__ . '/inc/epfl.php';

/**
 * Set up theming for the epfl-ws shortcodes
 */
require_once __DIR__ . '/inc/epfl-ws-shortcodes.php';

/**
 * Work with the historical STI database.
 */
require_once __DIR__ . '/inc/stisrv13.php';

/**
 * Newsletter theme.
 */
require_once __DIR__ . '/newsletter-theme/hook.php';

/**
 * Max Mega Menu theme.
 */
require_once __DIR__ . '/inc/maxmegamenu.php';

/**
 * wp-admin customization
 */
if (is_admin()) {
    require_once __DIR__ . '/inc/wp-admin.php';
}

/**
 * Enable shortcodes in text widgets.
 */
add_filter('widget_text','do_shortcode');
