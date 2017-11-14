<?php

?>
<?php
/**
 * Partial template for a person.
 *
 * @package epflsti
 */

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

    <h1>People @ STI presents...</h1>

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">

			<?php epflsti_posted_on(); ?>

		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

	<div class="entry-content">

		<?php the_content(); ?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
			'after'  => '</div>',
		) );
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php epflsti_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
