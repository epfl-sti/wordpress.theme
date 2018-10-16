<?php
/**
 * Render content as part of a list (e.g. category page, search results)
 *
 * @package epflsti
 */
?>
<!-- epflsti:loop-templates/content.php -->
<?php
	$url = esc_url( get_permalink() ); 
	$lang = EPFL\STI\get_current_language();
	$date = ($lang == 'fr') ? get_the_date( 'l n F, Y' ) : get_the_date( 'jS F, Y' );
?>
<div class="fullwidth-list-item" id="<?php the_ID(); ?>">
	<h2><a href="<?php echo $url; ?>" title="<?php echo $url; ?>"><?php the_title(); ?></a><span class="top-right"><?php echo $date; ?></span></h2>
	<div class="container">
		<div class="row entry-body">
			<div class="col-md-2">
				<div class="text-center actu-details">
					<a href="<?php echo $url; ?>"><?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?></a>
				</div>
			</div>
			<div class="col-md-10 actu-item-content">
				<?php echo get_the_excerpt(); ?>
			</div>
		</div>
	</div>
</div>
<br>
<?php
wp_link_pages( array(
	'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
	'after'  => '</div>',
) );
?>
<!-- end of epflsti:loop-templates/content.php -->