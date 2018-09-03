<?php
/**
 * Partial template for content in page.php
 *
 * @package epflsti
 */

?>
<!-- epflsti:loop-templates/content-institute-page.php -->
<article id="post-<?php the_ID(); ?>">
		<header>

			<?php the_title( '<div class="sti_content_title epfl-sti-institute-title">', '</div>' ); ?>

		</header>

		<div class="epfl-sti-institute-content">
			<?php
                        epflsti_render_featured_image("epfl-sti-institute-imageframe");
                        the_content();
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
				'after'  => '</div>',
			) );
			?>

		</div>

		<footer>

			<?php edit_post_link( __( 'Edit', 'epflsti' ), '<span class="edit-link">', '</span>' ); ?>

		</footer>
</article><!-- #post-## -->
