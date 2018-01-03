<?php
/**
 * The template for displaying the front page.
 *
 * WordPress innately picks up this file for the front page; see
 * https://developer.wordpress.org/themes/basics/template-hierarchy/#visual-overview
 *
 * @package epflsti
 */

get_header();

// Include various bits and pieces from widgets/, as configured in
// wp-admin for the "homepage" sidebar (not really a sidebar, more
// like a mainbar)
dynamic_sidebar( 'homepage' ); ?>

<?php get_footer(); ?>
