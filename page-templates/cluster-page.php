<?php
/**
 * Template Name: Cluster page
 * Template Post Type: page
 *
 * This template adds an automatically curated list of people, labs,
 * news (and posts), events, courses and video/media at the bottom
 * of the page (after the main matter, editable in TinyMCE). These
 * are just tag-based searches in the various types
 *
 * @package epflsti
 */

namespace EPFL\STI\ClusterPage;

get_header();

function have_cluster_people ()
{
    return true;
}

function have_cluster_labs ()
{
    return false;
}

function have_cluster_news ()
{
    return false;
}

function have_cluster_events ()
{
    return false;
}

function have_cluster_courses ()
{
    return false;
}

function have_cluster_media ()
{
    return false;
}

function render_cluster_items ()
{
  ?><img src="https://www.codeschool.com/assets/custom/geocities/underconstruction-72327f17c652569bab9a33536622841bf905d145ee673a3e9d065fae9cabfe4f.gif"><?php
}

?>

<article id="post-<?php the_ID(); ?>" class="cluster-page">
		<header class="sti-textured">
			<?php the_title( '<h1>', '</h1>' ); ?>
		</header>

	    <?php while (have_posts()): the_post(); # The Loop ?>
		<div class="article-content page-whitebg">

			<?php
                          epflsti_render_featured_image();
                        the_content();
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
				'after'  => '</div>',
			) );
			?>

                 <?php if(have_cluster_people()): ?>
                  <h2>People</h2>
                  <?php render_cluster_items();  ?>
                 <?php endif; ?>

                 <?php if(have_cluster_labs()): ?>
                  <h2>Labs</h2>
                  <?php render_cluster_items();  ?>
                 <?php endif; ?>

                 <?php if(have_cluster_news()): ?>
                  <h2>News</h2>
                  <?php render_cluster_items();  ?>
                 <?php endif; ?>

                 <?php if(have_cluster_events()): ?>
                  <h2>Events</h2>
                  <?php render_cluster_items();  ?>
                 <?php endif; ?>

                 <?php if(have_cluster_courses()): ?>
                  <h2>Courses</h2>
                  <?php render_cluster_items();  ?>
                 <?php endif; ?>

                 <?php if(have_cluster_media()): ?>
                  <h2>Media</h2>
                  <?php render_cluster_items();  ?>
                 <?php endif; ?>

		</div>
	    <?php endwhile; # The Loop ?>

		<footer>
			<?php edit_post_link( __( 'Edit', 'epflsti' ), '<span class="edit-link">', '</span>' ); ?>
		</footer>
</article><!-- #post-## -->

<?php get_footer(); ?>
