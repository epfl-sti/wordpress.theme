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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/megamenu.css">
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300' rel='stylesheet' type='text/css'>
    <link href='<?php echo get_stylesheet_directory_uri(); ?>/css/firststep.css' rel='stylesheet' type='text/css'>
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
        <a href=https://www.epfl.ch><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/epfl.svg" width=180 style="float:left; margin: 10px" /> </a>
        <ul>
            <li><a class="mylink" style="font-size: 22pt; top: 41px; padding-bottom: 46px" href="#">School of Engineering</a>
                <ul>
                    <li><a href="#">News</a>
                        <ul>
                            <li><a href="#">STI in the News</a></li>
                            <li><a href="#">News Archives</a></li>
                            <li></li>
                        </ul>
                    </li>
                    <li><a href="#">Seminars</a>
                        <ul>
                            <li><a href="#">Events for Future Students</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Centres</a>
                        <ul>
                            <li><a href="#">National Research Centres</a></li>
                            <li><a href="#">Research Centres</a></li>
                            <li><a href="#">Platforms and Services</a></li>
                            <li><a href="#">Centres in the News</a></li>
                            <li><a href="#">Centres Events</a></li>
                            <li></li>
                        </ul>
                    </li>
                    <li><a href="#">Campuses</a>
                        <ul>
                            <li><a href="#">Main campus</a></li>
                            <li><a href="#">Sion</a></li>
                            <li><a href="#">Geneva</a></li>
                            <li><a href="#">Martigny</a></li>
                            <li><a href="#">Neuchâtel</a></li>
                            <li><a href="#">Lausanne</a></li>
                        </ul>
                    </li>
                    <li><div style="float:left"><img width=220 src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/sti.jpg" /></div></li>
                    <li><a href="#">Administration</a>
                        <ul>
                            <li><a href="#">Faculty Direction</a></li>
                            <li><a href="#">Organisational Chart</a></li>
                            <li><a href="#">Dean's Office</a></li>
                            <li><a href="#">IT Services</a></li>
                            <li></li>
                        </ul>
                    </li>
                    <li><a href="#">Contact</a>
                    </li>
                </ul>
            </li>
            <li><a href="#" class=mylink>Teaching</a>
                <ul>
                    <li> <ul> <li><div style="float:left"><img width=220 src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/education.png" /></div></li> </ul> </li>
                    <li><a href="#">Bachelor</a>
                        <ul>
                    	    <li><a href="#">Semester Projects</a></li>
			</ul>
                    </li>
                    <li><a href="#">Master</a>
			<ul>
                    	    <li><a href="#">Master Projects</a></li>
                    	    <li><a href="#">Placements in Industry</a></li>
			</ul>
		    </li>
                    <li><a href="#">PhD</a>
			<ul>
                    	    <li><a href="#">Doctoral Programs</a></li>
		            <li></li>
			</ul>
                    </li>
                    <li><a href="#">Testimonials</a></li>
                </ul>
            </li>
            <li><a href="#" class=mylink>Research</a>
                
                <ul>
                    <li> <ul> <li><div style="float:left"><img width=220 src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/research.png" /></div></li> </ul> </li>

                    <li><a href="">Faculty</a>
                        <ul>
                            <li><a href="#">Open Faculty Positions</a></li>
                            <li><a href="#">Faculty Announcements</a></li>
                            <li><a href="#">Emeriti Professors</a></li>
                            <li><a href="#">Honrary and Inaugural Lectures</a></li>
                            <li><a href="#">Funding Sources</a></li>
                            <li><a href="#">Faculty Affairs</a></li>
                        </ul>
                    </li>

                    <li><a href="#">Our Institutes</a>
                     <ul>
                      <li><a href="#">Bioengineering</a></li>
                      <li><a href="#">Electrical Engineering</a></li>
                      <li><a href="#">Materials Science and Engineering</a></li>
                      <li><a href="#">Mechanical Engineering</a></li>
                      <li><a href="#">Microengineering</a></li>
                     </ul>
                    </li>
                </ul>
            </li>
            <li><a href="#" class=mylink>Innovation</a>
                <ul>
                    <li> <ul> <li><div style="float:left"><img width=220 src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/innovation.png" /></div></li> </ul> </li>
			
                    <li><a href="#">Technology Transfer</a>
			<ul>
                    	    <li><a href="#">Features</a></li>
			    <li><a href="#">Patents</a></li>
			</ul>
		    </li>
                    <li><a href="#">Master Projects in Industry</a>
			<ul>
 				<li><a href="#">Definition</a></li>
 				<li><a href="#">Participating sections</a></li>
 				<li><a href="#">Financial Support to Industry</a></li>
 				<li><a href="#">Submit a project</a></li>
 				<li><a href="#">Master Placements Archive</a></li>
			</ul>
		    </li>
		    <li><a href="#">Industry Day</a>
			<ul>
			<li><a href="#">2017 Event Pictures</a></li>
				<li><a href="#">Filmed Talks 2017</a></li>
				<li><a href="#">2017 Program</a></li>
				<li><a href="#">2016 archive</a></li>
			</ul>
		    </li>
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



                <nav class="navbar navbar-expand-md">

                <?php if ( 'container' == $container ) : ?>
        <div class="container">
        <?php endif; ?>

                                        <!-- Your site title as branding in the menu -->
                                        <?php if ( ! has_custom_logo() ) { ?>

                                                <?php if ( is_front_page()): ?>

                                                        <!---h1 class="navbar-brand mb-0"><a style="color:black" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1--->

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


