<?php
/**
 * Single post partial template.
 *
 * @package epflsti
 */

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

<?php

?>
<div class=container><?php # row if there is a box of links on the right ?>
  <div class="row entry-body" style="background-color:white"><?php #  container if there is a box of links on the right ?>
    <div class="col-md-8">
	  <card>
	    <?php the_title( '<h1>', '</h1>' ); ?>
	    <main>
	    <?php the_content(); ?>
	    </main>
          </card>
</div>
<div class="col-md-4">
<div class="bdr"> 	      <?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?></div>
</div>


    </div><?php #  main row ?>
      </div><?php #  main container ?>


	<footer>

		<?php epflsti_entry_footer(); ?>

	</footer>

</article><?php #  #post-##  ?>
