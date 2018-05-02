<?php
/**
 * Render content as part of a list (e.g. category page, search results)
 *
 * @package epflsti
 */

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

<div class="titretromb titretheme whitebg" style="background-color:#fff;">
		<?php the_title( sprintf( '<h4 class=card-title><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
        '</a></h4>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>

			<card class="meta">
				<?php //epflsti_posted_on(); ?>
			</card>

		<?php endif; ?>


<?php 
 $imagestring=get_the_post_thumbnail( $post->ID, 'large' ); 
 if ($imagestring != "") {
  $newsoutput="
   <div class='col-md-4'>
 	$imagestring
   </div>
   <div class='col-md-8'>";
 }
 else {
  $newsoutput="
   <div class='col-md-12'>";
 }
?>

<div class="container">
 <div class="row entry-body">
     <?php echo $newsoutput; ?>
		<?php
		the_excerpt();
		?>
  </div>
 </div>
</div>
</div><br>
		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
			'after'  => '</div>',
		) );
		?>


	<footer>

		<?php epflsti_entry_footer(); ?>

	</footer>

</article><!-- #post-## -->
