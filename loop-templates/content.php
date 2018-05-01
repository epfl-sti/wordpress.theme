<?php
/**
 * Render content as part of a list (e.g. category page, search results)
 *
 * @package epflsti
 */

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header>

		<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
		'</a></h2>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>

			<card class="meta">
				<?php epflsti_posted_on(); ?>
			</card>

		<?php endif; ?>

	</header>

	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

	<div>

		<?php
		the_excerpt();
		?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
			'after'  => '</div>',
		) );
		?>

	</div>

	<footer>

		<?php epflsti_entry_footer(); ?>

	</footer>

</article><!-- #post-## -->
