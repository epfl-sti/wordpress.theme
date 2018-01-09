<?php

?>
<?php
/**
 * Partial template for a person.
 *
 * @package epflsti
 */

$labname="LIONS";
$mylabname="Laboratory for Information and Inference Systems";
$labwebsite="https://lions.epfl.ch";
$keywords="Machine Learning; Optimization; Signal Processing; Information Theory.";
$labimage="https://stisrv13.epfl.ch/brochure/img/13/research.png";
$bio="Prof. Volkan Cevher received his BSc degree (valedictorian) in Electrical Engineering from Bilkent University in 1999, and his PhD degree in Electrical and Computer Engineering from Georgia Institute of Technology in 2005. He held Research Scientist positions at University of Maryland, College Park during 2006-2007 and at Rice University during 2008-2009. Currently, he is an Assistant Professor at Ecole Polytechnique Federale de Lausanne and a Faculty Fellow at Rice University. His research interests include signal processing theory, machine learning, graphical models, and information theory.";
$position="ASSOCIATE PROFESSOR";
$surname="Cevher";
$firstname="Volkan";
$epflname="volkan.cevher";
$phone="+41 21 693 1101";
$office="ELE233";


$epfl_positions="
<br><br>
Associate Professor:
<ul>
 <li>EPFL STI IEL LIONS 
 <li>Laboratory for Information and Inference Systems
 <li>Institute of Electrical Engineering
</ul>
Associate Professor:
<ul>
 <li>SEL Teaching
 <li>STI - Electrical Engineering Section
</ul>
Associate Professor:
<ul>
 <li>EDEE - Doctoral Program in Electrical Engineering
</ul>
CCE Member:
<ul>
 <li>CCE - Teaching Conference
</ul>";


$newstitle1="Three Prestigious Consolidator Grants";
$newstitle2="Volkan Cevher wins an ERC starting grant";
$newstitle3="Algorithms are all around";

$newsimage1="https://stisrv13.epfl.ch/newsdesk/covershots/el/left2017.png";
$newsimage2="https://stisrv13.epfl.ch/newsdesk/covershots/el/left1263.png";
$newsimage3="https://stisrv13.epfl.ch/newsdesk/covershots/el/left1180.png";

$newslink1="http://sti.epfl.ch/page-140428.html#anchor2017";
$newslink2="http://sti.epfl.ch/page-67196.html#anchor1263";
$newslink3="http://sti.epfl.ch/page-57268.html";
?>




<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
 <header class="entry-header">
  <div class="entry-meta"> </div><!-- .entry-meta -->
 </header><!-- .entry-header -->

<div class="container" id="content">
 <div class="row">
  <div class="col-sm-8 content-area" id="primary" style="">
   <main class="site-main sti_content_maincolumn" id="main" role="main">
    <header class="entry-header">
     <div class="sti_content_title"><?php the_title(); ?></div>
    </header><!-- .entry-header -->

    <div class="entry-content holding">
     <div class="sti_content_prof_photo">
		<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
     </div>
     <div class="sti_content_prof_text">
		<?php //the_content(); ?>
<p style="width: 400px" ><?php echo "$position<br><br>$bio"; ?></p><?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
			'after'  => '</div>',
		) );
		?>
     </div>
    </div><!-- .entry-content -->
   </main><!-- #main -->
   <br>
   <main class="site-main sti_content_maincolumn" id="main" role="main">

    <div class="entry-content holding">
     <div class="sti_content_prof_text">
     <p style="width: 400px" ><?php echo $epfl_positions ?></p>
     </div>
    </div><!-- .entry-content -->
   </main><!-- #main -->
  </div><!-- #primary -->

  <!-- NAV MENU START -->
  <div class="sti_people_righthand_menu col-md-4">
   <div class="sti_people_menu_title frontrowmarker">
    <?php echo $labname; ?> <span class="sti_people_menu_black"><?php echo $mylabname; ?></span>
    <img src=<?php echo $labimage; ?> class="sti_people_menu_image">
   </div>
   <div class="sti_people_box">
    <div class="sti_people_menu_white"><?php echo$keywords; ?></div>
    <div class="menu-ibi-nav-menu-container">
     <ul id="menu-ibi-nav-menu" class="menu">
      <li id="menu-item-128" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-128"><a href="<?php echo $labwebsite; ?>">LAB WEBSITE</a></li>
	<li id="menu-item-129" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-129"><a href="#">PUBLICATIONS</a></li>
	<li id="menu-item-131" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-131"><a href="#">NEWS</a></li>
	<li id="menu-item-132" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-132"><a href="#">COURSES</a></li>
	<li id="menu-item-130" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-130"><a href="#">COLLABORATIONS</a></li>
	<li id="menu-item-132" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-132"><a href="#">FULL CONTACT DETAILS</a></li>
     </ul>
     <div class="sti_people_menu_white">
     <?php 
echo "$firstname $surname<br>$position<br>Office: <a class=whitelink href=https://maps.epfl.ch/?q=$office>ELE233</a><br><a class=whitelink href=mailto:$epflname@epfl.ch>$epflname@epfl.ch</a><br><a class=whitelink href=https://people.epfl.ch/$epflname>https://people.epfl.ch/$epflname</a><br>Tel: <a class=whitelink href=\"tel:$phone\">$phone</a><br>";
?>
     </div>
    </div>
   </div>
  </div><!-- .sti_righthand_menu -->
  <!-- NAV MENU END -->
 </div><!-- .row -->
 <div class="row">
  <div class="col-md-8 content-area" id="primary">
   <main class="site-main sti_content_maincolumn" id="main" role="main">
    <div class="entry-content holding">
     <div class="sti_content_prof_text">
	NEWS<br><br>	
<?php
		echo "<a href=$newslink1><img src=$newsimage1><br>$newstitle1</a>";
		echo "<a href=$newslink2><img src=$newsimage2><br>$newstitle2</a>"; 
		echo "<a href=$newslink3><img src=$newsimage3><br>$newstitle3</a>"; 
?>

     </div>
    </div><!-- .entry-content -->
   </main><!-- #main -->
  </div><!-- #primary -->

</div><!-- Container end -->


			<?php epflsti_posted_on(); ?>


<footer class="entry-footer">
 <?php epflsti_entry_footer(); ?>
</footer><!-- .entry-footer -->

</article><!-- #post-## -->


