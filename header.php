<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package understrap
 */

$container = get_theme_mod( 'understrap_container_type' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <!-- Custom -->
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/normalize.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/megamenu.css">
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300' rel='stylesheet' type='text/css'>
    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script>
        window.Modernizr || document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"><\/script>')
    </script>
    <!-- end custom -->

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!-- Ugly hard coded menu -->
<div class="menu-container">
    <div class="menu">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="http://marioloncarek.com">About</a>
                <ul>
                    <li><a href="http://marioloncarek.com">School</a>
                        <ul>
                            <li><a href="http://marioloncarek.com">Lidership</a></li>
                            <li><a href="#">History</a></li>
                            <li><a href="#">Locations</a></li>
                            <li><a href="#">Careers</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Study</a>
                        <ul>
                            <li><a href="#">Undergraduate</a></li>
                            <li><a href="#">Masters</a></li>
                            <li><a href="#">International</a></li>
                            <li><a href="#">Online</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Research</a>
                        <ul>
                            <li><a href="#">Undergraduate research</a></li>
                            <li><a href="#">Masters research</a></li>
                            <li><a href="#">Funding</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Something</a>
                        <ul>
                            <li><a href="#">Sub something</a></li>
                            <li><a href="#">Sub something</a></li>
                            <li><a href="#">Sub something</a></li>
                            <li><a href="#">Sub something</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="http://marioloncarek.com">News</a>
                <ul>
                    <li><a href="http://marioloncarek.com">Today</a></li>
                    <li><a href="#">Calendar</a></li>
                    <li><a href="#">Sport</a></li>
                </ul>
            </li>
            <li><a href="http://marioloncarek.com">Contact</a>
                <ul>
                    <li><a href="#">School</a>
                        <ul>
                            <li><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/header.jpg" /> </li>
                            <li><a href="#">History</a></li>
                            <li><a href="#">Locations</a></li>
                            <li><a href="#">Careers</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Study</a>
                        <ul>
                            <li><a href="#">Undergraduate</a></li>
                            <li><a href="#">Masters</a></li>
                            <li><a href="#">International</a></li>
                            <li><a href="#">Online</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Study</a>
                        <ul>
                            <li><a href="#">Undergraduate</a></li>
                            <li><a href="#">Masters</a></li>
                            <li><a href="#">International</a></li>
                            <li><a href="#">Online</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Empty sub</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- End ugly hard coded menu -->

<div class="hfeed site" id="page">

	<!-- ******************* The Navbar Area ******************* -->
	<div class="wrapper-fluid wrapper-navbar" id="wrapper-navbar">

		<a class="skip-link screen-reader-text sr-only" href="#content"><?php esc_html_e( 'Skip to content',
		'understrap' ); ?></a>



		<nav class="navbar navbar-expand-md navbar-dark bg-dark">

		<?php if ( 'container' == $container ) : ?>
        <div class="container">
        <?php endif; ?>

					<!-- Your site title as branding in the menu -->
					<?php if ( ! has_custom_logo() ) { ?>

						<?php if ( is_front_page() && is_home() ) : ?>

							<h1 class="navbar-brand mb-0"><a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>

						<?php else : ?>

							<a class="navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a>

						<?php endif; ?>


					<?php } else {
						the_custom_logo();
					} ?><!-- end custom logo -->

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<!-- The WordPress Menu goes here -->
				<?php wp_nav_menu(
					array(
						'theme_location'  => 'primary',
						'container_class' => 'collapse navbar-collapse',
						'container_id'    => 'navbarNavDropdown',
						'menu_class'      => 'navbar-nav',
						'fallback_cb'     => '',
						'menu_id'         => 'main-menu',
						'walker'          => new WP_Bootstrap_Navwalker(),
					)
				); ?>
			<?php if ( 'container' == $container ) : ?>
			</div><!-- .container -->
			<?php endif; ?>

		</nav><!-- .site-navigation -->

	</div><!-- .wrapper-navbar end -->
