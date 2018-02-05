<?php
/**
 * Partial template for content in page.php
 *
 * @package epflsti
 */

?>
<article id="post-<?php the_ID(); ?>">
		<header class="entry-header">

			<?php the_title( '<div class="sti_content_title epfl-sti-institute-title">', '</div>' ); ?>

		</header><!-- .entry-header -->

		<div class="entry-content epfl-sti-institute-content">
                   <div class="sti_content_prof_photo epfl-sti-institute-imageframe">
                      <?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
                  </div><?php # prof_photo ?>


			<?php the_content(); ?>

			<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
				'after'  => '</div>',
			) );
			?>

		</div><!-- .entry-content -->

		<footer class="entry-footer">

			<?php edit_post_link( __( 'Edit', 'epflsti' ), '<span class="edit-link">', '</span>' ); ?>

		</footer><!-- .entry-footer -->
</article><!-- #post-## -->
