<?php
/**
 * The template for displaying all single posts.
 *
 * @package epflsti
 */

echo "<!-- epflsti:single.php -->";
get_header();
?><div class="wrapper" id="single-wrapper"><?php 

while ( have_posts() ) {
    the_post();
    $single_post_template_name = 'single-' . get_post_type();
    $has_typespecific_loop_template = locate_template("loop-templates/content-${single_post_template_name}.php");
    get_template_part( 'loop-templates/content', $has_typespecific_loop_template ? $single_post_template_name : 'single');
}

?></div><?php

get_footer();
