<?php
/**
* Single post partial template.
*
* @package epflsti
*/

?>
<!-- epflsti:loop-templates/content-single.php -->
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header >

		<?php the_title( '<h1>', '</h1>' ); ?>

		<card class="meta">

			<?php epflsti_posted_on(); ?>

		</div>

	</header>

	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

	<div>

		<?php the_content(); ?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'understrap' ),
			'after'  => '</div>',
		) );
		?>

	</div>

</article><!-- #post-## -->
