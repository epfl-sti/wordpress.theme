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

<?php

$menu=1;
$highlight=1;

$rosesarered="<img class='ribbon-red-top' src='/wp-content/themes/epfl-sti/img/src/topright.png'>
<img class='ribbon-red-bottom' src='/wp-content/themes/epfl-sti/img/src/bottomleft.png'>";

if ($menu) {

 $listoflinks_main=" col-md-8 content-area";
 $listoflinks_width=" width-main-listoflinks";
 $listoflinks_menu=" sti_righthand_menu col-md-4";

 $menu='
<?PHP # LIST OF LINKS START ?>
 <div class="col-md-4">
  <div class="sti_people_menu_title frontrowmarker">
   <?php echo $labname; ?> <span class="sti_people_menu_black"><?php echo $mylabname; ?></span>
   <img src=https://stisrv13.epfl.ch/brochure/img/13/research.png class="sti_people_menu_image">
  </div><!-- menutitle-->
  <div class="sti_people_box">
   <div class="sti_people_menu_white">
    Research topics:<br><br>Machine Learning; Optimization; Signal Processing; Information Theory. 
   </div><!--menuwhite-->
   <div class="prof-nav-menu">
    <ul class="menu">
     <li id="menu-item-128" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-128"><a href="<?php echo $labwebsite; ?>">LAB WEBSITE</a></li>
        <li id="menu-item-132" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-131"><a href="#video">VIDEOS</a></li>
      <li id="menu-item-129" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-129"><a href="https://people.epfl.ch/cgi-bin/people?id=<?php echo $sciper;?>&op=publications&lang=en&cvlang=en">PUBLICATIONS</a></li>
        <li id="menu-item-130" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-131"><a href="#news">NEWS</a></li>
        <li id="menu-item-133" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-130"><a href="https://stisrv13.epfl.ch/collaborations/tube_html5.php?sciper=<?php echo $sciper; ?>&showpublications=1&showpatents=1&showexternals=1&showindustry=1">COLLABORATIONS</a></li>
</ul>
   </div><!-- menucontainer-->
  </div><!-- peoplebox-->
 <?PHP # LIST OF LINKS END ?>
 ';
}
?>
<div class=container><!--row if there is a box of links on the right-->
  <div class=row><!-- container if there is a box of links on the right-->
    <div class="<?php echo $listoflinks_main; ?>"><!--column if there is a box of links on the right-->



	 <div class=container><!-- main container -->
           <div class=row><!-- main row-->

	    <div class="entry-content standard-content-box <?php echo $listoflinks_width; ?>">
                <?php  // if the choice is made to highlight this box
		if ($highlight) {
			echo $rosesarered;
		}
		?>
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<div class="entry-body">
      			      <div class="sti_content_prof_photo">
				<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
			      </div><!-- col  -->
				<?php the_content(); ?>
			</div><!-- entry-body -->
  	    </div><!-- .entry-content -->

		<?php   
		 // succeeding boxes currently take the_content() too
                ?>
	
	
	    <div class="entry-content standard-content-box <?php echo $listoflinks_width; ?>">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<div class="entry-body">
      			      <div class="sti_content_prof_photo">
				<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
			      </div><!-- col  -->
				<?php the_content(); ?>
			</div><!-- entry-body -->

  	    </div><!-- .entry-content -->

	 </div><!-- main row-->
	</div><!-- main container-->

     </div><!--column in case there is a list of links on the right-->


		<?php   
			// this box is a list of links 
			if ($menu) { echo $menu; }
                ?>
	



  </div><!--column if there is a box of links on the right-->
 </div><!--row if there is a box of links on the right-->
</div><!-- container if there is a box of links on the right-->
		<div class="entry-meta">

			<!---?php epflsti_posted_on(); --->

		</div><!-- .entry-meta -->
	<footer class="entry-footer">

		<?php epflsti_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
