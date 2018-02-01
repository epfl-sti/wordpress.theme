<?php
/**
 * Single post partial template.
 *
 * @package epflsti
 */

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

<?php

$with_menu=1;
$with_red_ribbon=1; //highlights the first box (red ribbon)

if ($with_menu) {
 $listoflinks_main=" col-md-8 content-area";
 $listoflinks_width=" width-main-listoflinks";
 $listoflinks_menu=" sti_righthand_menu col-md-4";
}


$rosesarered="<img class='ribbon-red-top' src='/wp-content/themes/epfl-sti/img/src/topright.png'>
<img class='ribbon-red-bottom' src='/wp-content/themes/epfl-sti/img/src/bottomleft.png'>";

?>
<div class=container><?php # row if there is a box of links on the right ?>
  <div class=row><?php #  container if there is a box of links on the right ?>
    <div class="<?php echo $listoflinks_main; ?>"><?php # column if there is a box of links on the right ?>



	 <div class=container><?php #  main container  ?>
           <div class=row><?php #  main row ?>

	    <card class="<?php echo $listoflinks_width; ?>">
                <?php  // if the choice is made to highlight this box
		if ($with_red_ribbon) {
			echo $rosesarered;
		}
		?>
		<?php the_title( '<h1>', '</h1>' ); ?>
			<div class="entry-body">
      			      <div class="sti_content_prof_photo">
				<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
			      </div><?php #  col   ?>
				<?php the_content(); ?>
			</div><?php #  entry-body  ?>
  	    </div><?php #  .entry-content  ?>

		<?php   
		 // succeeding boxes currently take the_content() too
                ?>
	
	
	    <card class="<?php echo $listoflinks_width; ?>">
		<?php the_title( '<h1>', '</h1>' ); ?>
			<div class="entry-body">
      			      <div class="sti_content_prof_photo">
				<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
			      </div><?php #  col   ?>
				<?php the_content(); ?>
			</div><?php #  entry-body  ?>

  	    </div><?php #  .entry-content  ?>

	 </div><?php #  main row ?>
	</div><?php #  main container ?>

     </div><?php # column in case there is a list of links on the right ?>


		<?php if ($menu):

 $listoflinks_main=" col-md-8 content-area";
 $listoflinks_width=" width-main-listoflinks";
 $listoflinks_menu=" sti_righthand_menu col-md-4";

                ?>
     <aside class="col-md-4">
      <card class="first frontrowmarker">
       <header>
        <?php echo $labname; ?> <span class="sti_people_menu_black"><?php echo $mylabname; ?></span>
        <img src=https://stisrv13.epfl.ch/brochure/img/13/research.png class="sti_people_menu_image">
       </header>
      </card>
      <card class="links">
       <p>Research topics:<br><br>Machine Learning; Optimization; Signal Processing; Information Theory.</p>p>
      </card>
      <card class="prof-nav-menu">
        <ul class="menu">
         <li id="menu-item-128" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-128"><a href="<?php echo $labwebsite; ?>">LAB WEBSITE</a></li>
            <li id="menu-item-132" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-131"><a href="#video">VIDEOS</a></li>
          <li id="menu-item-129" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-129"><a href="https://people.epfl.ch/cgi-bin/people?id=<?php echo $sciper;?>&op=publications&lang=en&cvlang=en">PUBLICATIONS</a></li>
            <li id="menu-item-130" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-131"><a href="#news">NEWS</a></li>
            <li id="menu-item-133" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-130"><a href="https://stisrv13.epfl.ch/collaborations/tube_html5.php?sciper=<?php echo $sciper; ?>&showpublications=1&showpatents=1&showexternals=1&showindustry=1">COLLABORATIONS</a></li>
        </ul>
      </card>
     </aside>
		<?php endif; ?>



  </div><?php # column if there is a box of links on the right ?>
 </div><?php # row if there is a box of links on the right ?>
</div><?php #  container if there is a box of links on the right ?>
		<card class="meta">

		 <p><?php # -?php epflsti_posted_on(); - ?></p>

		</div>
	<footer>

		<?php epflsti_entry_footer(); ?>

	</footer>

</article><?php #  #post-##  ?>
