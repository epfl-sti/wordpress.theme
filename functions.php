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
require get_template_directory() . '/inc/setup.php';

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Load functions to secure your WP install.
 */
require get_template_directory() . '/inc/security.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom post metadata and its rendering.
 */
require get_template_directory() . '/inc/metadata.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/pagination.php';

/**
 * Well-known URLs for this theme's assets
 */
require get_template_directory() . '/inc/serve-assets.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom Comments file.
 */
require get_template_directory() . '/inc/custom-comments.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
require get_template_directory() . '/inc/bootstrap-wp-navwalker.php';

/**
 * Load WooCommerce functions.
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * Load Editor functions.
 */
require get_template_directory() . '/inc/editor.php';

/**
 * Work with EPFL environment.
 */
require get_template_directory() . '/inc/epfl.php';

/**
 * Newsletter theme.
 */
require get_template_directory() . '/newsletter-theme/hook.php';

/**
 * Enable shortcodes in text widgets.
 */
add_filter('widget_text','do_shortcode');
