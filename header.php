<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package epflsti
 */

$container = get_theme_mod( 'epflsti_container_type' );
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
    <link href='<?php echo get_stylesheet_directory_uri(); ?>/css/firststep.css' rel='stylesheet' type='text/css'>
    <!-- JS -->
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/modernizr.js"></script>
    <!-- end custom -->

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!-- Ugly hard coded menu -->
<div class="menu-container">
    <div class="menu">
        <!-- Your site logo -->
        <a href="https://www.epfl.ch">
        <?php
        if (! has_custom_logo()) {  ?>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/epfl.svg" width=180 style="float:left; margin: 10px" />
        <?php
        } else {
            the_custom_logo();
        } ?><!-- end custom logo -->
        </a>
        <a class="sti_menu_link"  href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <acronym title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" style="text-decoration: none;">
                <?php bloginfo( 'name' ); ?>
            </acronym>
        </a>
        <ul class="sti-megamenu">
            <li>
                <a href="#" class="sti_menu_link">About</a>
                <ul>
                    <li>
                        <a href="#">News</a>
                        <ul>
                            <li><a href="#">STI in the News</a></li>
                            <li><a href="#">News Archives</a></li>
                            <li></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Seminars</a>
                        <ul>
                            <li><a href="#">Events for Future Students</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Centres</a>
                        <ul>
                            <li><a href="#">National Research Centres</a></li>
                            <li><a href="#">Research Centres</a></li>
                            <li><a href="#">Platforms and Services</a></li>
                            <li><a href="#">Centres in the News</a></li>
                            <li><a href="#">Centres Events</a></li>
                            <li></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Campuses</a>
                        <ul>
                            <li><a href="#">Main campus</a></li>
                            <li><a href="#">Sion</a></li>
                            <li><a href="#">Geneva</a></li>
                            <li><a href="#">Martigny</a></li>
                            <li><a href="#">Neuchâtel</a></li>
                            <li><a href="#">Lausanne</a></li>
                        </ul>
                    </li>
                    <li>
                        <div style="float:left">
                            <img width=220 src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/sti<?php echo rand(1,4); ?>.jpg" />
                        </div>
                    </li>
                    <li>
                        <a href="#">Administration</a>
                        <ul>
                            <li><a href="#">Faculty Direction</a></li>
                            <li><a href="#">Organisational Chart</a></li>
                            <li><a href="#">Dean's Office</a></li>
                            <li><a href="#">IT Services</a></li>
                            <li></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="sti_menu_link">Teaching</a>
                <ul>
                    <li>
                        <ul>
                            <li>
                                <div style="float:left">
                                    <img width=220 src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/education<?php echo rand(1,2); ?>.jpg" />
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Bachelor</a>
                        <ul>
                    	    <li><a href="#">Semester Projects</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Master</a>
                        <ul>
                    	    <li><a href="#">Master Projects</a></li>
                    	    <li><a href="#">Placements in Industry</a></li>
                    	</ul>
                    </li>
                    <li>
                        <a href="#">PhD</a>
                        <ul>
                    	    <li><a href="#">Doctoral Programs</a></li>
                            <li>...</li>
                            <li>...</li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Testimonials</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="sti_menu_link">Research</a>
                <ul>
                    <li>
                        <ul>
                            <li>
                                <div style="float:left">
                                    <img width=220 src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/research<?php echo rand(1,2); ?>.jpg" />
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="">Faculty</a>
                        <ul>
                            <li><a href="#">Open Faculty Positions</a></li>
                            <li><a href="#">Faculty Announcements</a></li>
                            <li><a href="#">Emeriti Professors</a></li>
                            <li><a href="#">Honrary and Inaugural Lectures</a></li>
                            <li><a href="#">Funding Sources</a></li>
                            <li><a href="#">Faculty Affairs</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Our Institutes</a>
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
            <li>
                <a href="#" class="sti_menu_link">Innovation</a>
                <ul>
                    <li>
                        <ul>
                            <li>
                                <div style="float:left">
                                    <img width=220 src="<?php echo get_stylesheet_directory_uri(); ?>/img/src/innovation<?php echo rand(1,2); ?>.jpg" />
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#">Patents</a></li>
                    <li><a href="#">Master Projects in Industry</a></li>
                    <li><a href="#">Features</a></li>
                    <li>
                        <ul>
                            <li>
                                <div style="float:left"></div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Technology Transfer</a>
                        <ul>
                            <li><a href="#">Features</a></li>
                            <li><a href="#">Patents</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Master Projects in Industry</a>
                        <ul>
                            <li><a href="#">Definition</a></li>
                            <li><a href="#">Participating sections</a></li>
                            <li><a href="#">Financial Support to Industry</a></li>
                            <li><a href="#">Submit a project</a></li>
                            <li><a href="#">Master Placements Archive</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Industry Day</a>
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
