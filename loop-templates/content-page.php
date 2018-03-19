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

			<?php if (has_post_thumbnail($post)): ?>
			<div class="sti_content_prof_photo" >
				<figure>
					<?php echo get_the_post_thumbnail( $post->ID, 'medium', array( 'class' => 'img-responsive' ) ); ?>
					<figcaption><?php echo the_post_thumbnail_caption( $post->ID ); ?></figcaption>
				</figure>
			</div>
			<?php endif; ?>

			<?php the_content(); ?>

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
