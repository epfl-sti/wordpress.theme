<?php
/**
 * Single post partial template.
 *
 * @package epflsti
 */

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">



	</header><!-- .entry-header -->


	<div class="entry-content sti_content_maincolumn">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
<ul>
		<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
		<?php the_content(); ?>
</ul>
		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
			'after'  => '</div>',
		) );
		?>

	</div><!-- .entry-content -->

		<div class="entry-meta">

			<?php epflsti_posted_on(); ?>

		</div><!-- .entry-meta -->
	<footer class="entry-footer">

		<?php epflsti_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
