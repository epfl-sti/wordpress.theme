<?php
/**
 * Theme basic setup.
 *
 * @package epflsti
 */

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

require get_template_directory() . '/inc/theme-settings.php';

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'epflsti_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function epflsti_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on epflsti, use a find and replace
		 * to change 'epflsti' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'epfl-sti-theme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary'                    => ___( 'Primary Menu'),
			'primary-igm'                => ___( 'Primary Menu for IGM'),
			'primary-ibi'                => ___( 'Primary Menu for IBI'),
			'primary-imt'                => ___( 'Primary Menu for IMT'),
			'primary-imx'                => ___( 'Primary Menu for IMX'),
			'primary-iel'                => ___( 'Primary Menu for IEL'),
			'langmenu'                   => ___( 'Lang Menu'),
			'front-row-school-menu'      => ___( 'Front Row School Menu'),
			'front-row-centres-menu'     => ___( 'Front Row Centres Menu'),
			'front-row-igm-faculty-menu' => ___( 'Front Row IGM Faculty Menu'),
			'front-row-ibi-faculty-menu' => ___( 'Front Row IBI Faculty Menu'),
			'front-row-imt-faculty-menu' => ___( 'Front Row IMT Faculty Menu'),
			'front-row-imx-faculty-menu' => ___( 'Front Row IMX Faculty Menu'),
			'front-row-iel-faculty-menu' => ___( 'Front Row IEL Faculty Menu'),
			'front-row-igm-info-menu'    => ___( 'Front Row IGM Info Menu'),
			'front-row-ibi-info-menu'    => ___( 'Front Row IBI Info Menu'),
			'front-row-imt-info-menu'    => ___( 'Front Row IMT Info Menu'),
			'front-row-imx-info-menu'    => ___( 'Front Row IMX Info Menu'),
			'front-row-iel-info-menu'    => ___( 'Front Row IEL Info Menu'),
			'footnote'                   => ___( 'Footnote'),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Adding Thumbnail basic support
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Adding support for Widget edit icons in customizer
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'epflsti_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Set up the WordPress Theme logo feature.
		add_theme_support( 'custom-logo' );

		// Check and setup theme default settings.
		setup_theme_default_settings();
	}
endif; // epflsti_setup.
add_action( 'after_setup_theme', 'epflsti_setup' );

if ( ! function_exists( 'custom_excerpt_more' ) ) {
	/**
	 * Removes the ... from the excerpt read more link
	 *
	 * @param string $more The excerpt.
	 *
	 * @return string
	 */
	function custom_excerpt_more( $more ) {
		return '';
	}
}
add_filter( 'excerpt_more', 'custom_excerpt_more' );

