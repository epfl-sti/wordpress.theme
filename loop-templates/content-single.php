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

         <div class=container>
           <div class=row>

	    <div class="entry-content standard-content-box">
     <img class="ribbon-red-top" src="/wp-content/themes/epfl-sti/img/src/topright.png">
     <img class="ribbon-red-bottom" src="/wp-content/themes/epfl-sti/img/src/bottomleft.png">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
 		    <div class="container">
			<div class="row entry-body">
			      <div class="col-xs-6 standard-margin">
				<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
			      </div><!-- col  -->
			      <div class="col-xs-6 standard-margin">
				<?php the_content(); ?>
			      </div><!-- col  -->
			</div><!-- row -->
				<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
					'after'  => '</div>',
				) );
				?>
		     </div> <!-- entry-body-->
  	    </div><!-- .entry-content -->

	    <div class="entry-content standard-content-box">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
 		    <div class="container">
			<div class="row entry-body">
			      <div class=col-xs-6>
				<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
			      </div><!-- col  -->
			      <div class=col-xs-6>
				<?php the_content(); ?>
			      </div><!-- col  -->
			</div><!-- row -->
				<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
					'after'  => '</div>',
				) );
				?>
		     </div> <!-- entry-body-->
  	    </div><!-- .entry-content -->
	 </div><!-- row-->
        </div><!-- container-->

		<div class="entry-meta">

			<!---?php epflsti_posted_on(); --->

		</div><!-- .entry-meta -->
	<footer class="entry-footer">

		<?php epflsti_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
