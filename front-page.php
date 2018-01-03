<?php
/**
 * The template for displaying the front page.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package epflsti
 */

get_header();

// Include various bits and pieces from widgets/, as configured in
// wp-admin for the "homepage" sidebar (not really a sidebar, more
// like a mainbar)
dynamic_sidebar( 'homepage' ); ?>

<?php get_footer(); ?>
