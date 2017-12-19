<?php
/**
 * Partial template for content in page.php
 *
 * @package epflsti
 */

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
 <center>
  <div class=secondaryrow> 
	<header class="entry-header">

		<?php the_title( '<h1 class="sti-content-title">', '</h1>' ); ?>

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

		<?php edit_post_link( __( 'Edit', 'epflsti' ), '<span class="edit-link">', '</span>' ); ?>

	</footer><!-- .entry-footer -->
  </div>
 </center>
</article><!-- #post-## -->
