<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package epflsti
 */

$the_theme = wp_get_theme();
$container = get_theme_mod( 'epflsti_container_type' );
?>

<?php get_sidebar( 'footerfull' ); ?>

<?php wp_footer(); ?>

</body>

</html>
