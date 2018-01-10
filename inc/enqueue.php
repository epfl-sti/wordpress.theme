<?php
/**
 * Understrap enqueue scripts
 *
 * @package epflsti
 */

if ( ! function_exists( 'epflsti_scripts' ) ) {
	/**
	 * Load theme's JavaScript sources.
	 */
	function epflsti_scripts() {
		// Get the theme data.
		$the_theme = wp_get_theme();
		wp_enqueue_style( 'epflsti-styles', get_stylesheet_directory_uri() . '/assets/theme.min.css', array(), $the_theme->get( 'Version' ), false );
		wp_deregister_script('jquery');
		wp_register_script('jquery', (get_template_directory_uri() . '/assets/jquery.min.js'), true, '3.2.1');
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script('jquery-touchswipe', (get_template_directory_uri() . '/assets/jquery.touchSwipe.js'), true, '1.6.14');
		wp_enqueue_script( 'epflsti-scripts', get_template_directory_uri() . '/assets/theme.min.js', array(), $the_theme->get( 'Version' ), true );
		wp_register_script( 'anchorjs', get_template_directory_uri() . '/js/anchor.js', true, '4.1.0', true );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
} // endif function_exists( 'epflsti_scripts' ).

add_action( 'wp_enqueue_scripts', 'epflsti_scripts' );
