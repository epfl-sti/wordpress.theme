<?php
/**
 * Partial template for content in page.php
 *
 * @package epflsti
 */

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
 <div class=sti_content_maincolumn>
	<header class="entry-header">

		<?php the_title( '<div class="sti_content_title">', '</div>' ); ?>

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
<!-- this is probably not where you want to put this menu
 <div class=sti_righthand_menu>
  <div class="frontrowlist_title frontrowmarker"><a class="frontrowtitle_link" href=#>RESEARCH</a> - IEL</span></div>
  <div class="frontrowlistbox rollup">
   <ul class=frontrowlist>
	<li><a href=#>Welcome from the Director</a></li>
	<li><a href=#>Faculty Members</a></li>
	<li><a href=#>EE Laboratories</a></li>
	<li><a href=#>Lab Videos</a></li>
	<li><a href=#>Facts and Figures</a></li>
	<li><a href=#>Main Research Topics</a></li>
	<li><a href=#>EPFL centers and EE</a></li>
	<li><a href=#>Facilities</a></li>
	<li><a href=#>Campus</a></li>
	<li><a href=#>News</a></li>
	<li><a href=#>Agenda</a></li>
   </ul>
  </div>
 </div>
-->

</article><!-- #post-## -->
