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
 <header class="entry-header">
  <div class="entry-meta"> </div><!-- .entry-meta -->
 </header><!-- .entry-header -->

<div> <!---  class="wrapper" id="page-wrapper" puts lots of padding --->
 <div class="container" id="content">
  <div class="row">
   <div class="col-md-8 content-area whitebg sti_content_maincolumn" id="primary">
    <main class="site-main" id="main" role="main">
     <header class="entry-header">
      <div class="sti_content_title"><?php the_title(); ?></div>
     </header><!-- .entry-header -->

     <div class="entry-content">
		<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

		<?php the_content(); ?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
			'after'  => '</div>',
		) );
		?>

			<?php epflsti_posted_on(); ?>
     </div><!-- .entry-content -->
    </main><!-- #main -->
   </div><!-- #primary -->
<!-- NAV MENU START -->
<div class="sti_righthand_menu col-md-4">
 <div class="frontrowlist_title frontrowmarker">
  MORE INFO
 </div>
 <div class="frontrowlistbox rollup">
  <div class="menu-ibi-nav-menu-container"><ul id="menu-ibi-nav-menu" class="menu"><li id="menu-item-128" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-128"><a href="#">Publications</a></li>
<li id="menu-item-129" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-129"><a href="#">Lab</a></li>
<li id="menu-item-130" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-130"><a href="#">Collaborations</a></li>
<li id="menu-item-131" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-131"><a href="#">News</a></li>
<li id="menu-item-132" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-132"><a href="#">Contact Details</a></li>
</ul></div>	
 </div>
</div><!-- .sti_righthand_menu -->
<!-- NAV MENU END -->
											
  </div><!-- .row -->
 </div><!-- Container end -->
</div><!-- Wrapper end -->




<footer class="entry-footer">
 <?php epflsti_entry_footer(); ?>
</footer><!-- .entry-footer -->

</article><!-- #post-## -->


