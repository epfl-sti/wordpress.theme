<?php
/**
 * Post rendering content according to caller of get_template_part.
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
			</div>

		<?php endif; ?>

	</header>

	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

	<div class="entry-content">

		<?php
		the_excerpt();
		?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
			'after'  => '</div>',
		) );
		?>

	</div><!-- .entry-content -->

	<footer>

		<?php epflsti_entry_footer(); ?>

	</footer>

</article><!-- #post-## -->
