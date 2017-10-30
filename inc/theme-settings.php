<?php
/**
 * Check and setup theme's default settings
 *
 * @package epflsti
 *
 */

if ( ! function_exists( 'setup_theme_default_settings' ) ) :
	function setup_theme_default_settings() {

		// check if settings are set, if not set defaults.
		// Caution: DO NOT check existence using === always check with == .
		// Latest blog posts style.
		$epflsti_posts_index_style = get_theme_mod( 'epflsti_posts_index_style' );
		if ( '' == $epflsti_posts_index_style ) {
			set_theme_mod( 'epflsti_posts_index_style', 'default' );
		}

		// Sidebar position.
		$epflsti_sidebar_position = get_theme_mod( 'epflsti_sidebar_position' );
		if ( '' == $epflsti_sidebar_position ) {
			set_theme_mod( 'epflsti_sidebar_position', 'right' );
		}

		// Container width.
		$epflsti_container_type = get_theme_mod( 'epflsti_container_type' );
		if ( '' == $epflsti_container_type ) {
			set_theme_mod( 'epflsti_container_type', 'container' );
		}
	}
endif;
