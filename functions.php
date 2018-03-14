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
require_once get_template_directory() . '/inc/setup.php';

/**
 * Register widgets.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
require_once get_template_directory() . '/inc/widgets.php';

/**
 * Load functions to secure your WP install.
 */
require_once get_template_directory() . '/inc/security.php';

/**
 * Enqueue scripts and styles.
 */
require_once get_template_directory() . '/inc/enqueue.php';

/**
 * Custom template tags for this theme.
 */
require_once get_template_directory() . '/inc/template-tags.php';

/**
 * Custom post metadata and its rendering.
 */
require_once get_template_directory() . '/inc/metadata.php';

/**
 * Custom pagination layout.
 */
require_once get_template_directory() . '/inc/pagination.php';

/**
 * Well-known URLs for this theme's assets
 */
require_once get_template_directory() . '/inc/serve-assets.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require_once get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require_once get_template_directory() . '/inc/customizer.php';

/**
 * Custom Comments file.
 */
require_once get_template_directory() . '/inc/custom-comments.php';

/**
 * Customization of the allowed HTML5 elements
 */
require_once get_template_directory() . '/inc/html5-tags.php';

/**
 * Load Jetpack compatibility file.
 */
require_once get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
require_once get_template_directory() . '/inc/bootstrap-wp-navwalker.php';

/**
 * Load WooCommerce functions.
 */
require_once get_template_directory() . '/inc/woocommerce.php';

/**
 * Load Editor functions.
 */
require_once get_template_directory() . '/inc/editor.php';

/**
 * Work with EPFL environment.
 */
require_once get_template_directory() . '/inc/epfl.php';

/**
 * Work with the historical STI database.
 */
require_once get_template_directory() . '/inc/stisrv13.php';

/**
 * Newsletter theme.
 */
require_once get_template_directory() . '/newsletter-theme/hook.php';

/**
 * Max Mega Menu theme.
 */
require_once get_template_directory() . '/inc/maxmegamenu.php';

/**
 * Enable shortcodes in text widgets.
 */
add_filter('widget_text','do_shortcode');
