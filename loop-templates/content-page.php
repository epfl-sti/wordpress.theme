<?php
/**
 * Partial template for content in page.php
 *
 * @package epflsti
 */

?>
<article id="post-<?php the_ID(); ?>" >
		<header class="sti-textured">
			<?php the_title( '<h1>', '</h1>' ); ?>
		</header>

		<div class="article-content page-whitebg">

			<?php epflsti_render_featured_image(); the_content(); ?>

			<?php
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
