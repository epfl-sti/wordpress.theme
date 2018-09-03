<?php
/**
 * Template Name: List Page
 *
 * Template for displaying a page that consists of a list of results of some sort
 *
 * @package epflsti
 */

get_header();
$container = get_theme_mod( 'epflsti_container_type' );
?>
<!-- epflsti:page-templates/list-page.php -->
<div class="container wrapper">
 <div class="row">
  <div class="col-md-12">
   <main class="list-main" id="list-main" role="main">
    <?php while ( have_posts() ) {
        the_post();

        get_template_part( 'loop-templates/content', 'list' );
    } ?>
   </main>
  </div>
 </div>
 <footer>
   <?php edit_post_link( __( 'Edit', 'epflsti' ), '<span class="edit-link">', '</span>' ); ?>
 </footer>
</div>
<?php get_footer(); ?>
