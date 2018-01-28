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
$sciper="199128";
$labvideo="blIMmx5oh7o";
$labvideotitle="Turning data into information...";

$epfl_positions="
<br>
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
$newstitle4="IEEE Signal Processing Best Paper Award";

$newsimage1="https://stisrv13.epfl.ch/newsdesk/covershots/el/left2017.png";
$newsimage2="https://stisrv13.epfl.ch/newsdesk/covershots/el/left1263.png";
$newsimage3="https://stisrv13.epfl.ch/newsdesk/covershots/el/left1180.png";
$newsimage4="https://i.ytimg.com/vi/blIMmx5oh7o/maxresdefault.jpg";

$newslink1="http://sti.epfl.ch/page-140428.html#anchor2017";
$newslink2="http://sti.epfl.ch/page-67196.html#anchor1263";
$newslink3="http://sti.epfl.ch/page-57268.html";
$newslink4="http://sti.epfl.ch/page-108381.html#anchor2025";

$publicationtext1="C. Aprile, A. Cevrero, P. A. Francese, C. Menolfi and M. Braendli et al. An Eight lanes 7Gb/s/pin Source Synchronous Single-Ended RX with Equalization and Far-End Crosstalk Cancellation for Backplane Channels, in IEEE Journal of Solid State Circuits, vol. PP, num. 99, p. 1-12, 2018";
$publicationlink1="https://infoscience.epfl.ch/record/233712/files/08246724.pdf?version=1";
$publicationrecord1="https://infoscience.epfl.ch/record/233712?ln=en";

$publicationtext2="S. Mitrovic, I. Bogunovic, A. Norouzi Fard, J. Tarnawski and V. Cevher. Streaming Robust Submodular Maximization: A Partitioned Thresholding Approach. Conference on Neural Information Processing Systems (NIPS), Long Beach, 2017";
$publicationlink2="https://infoscience.epfl.ch/record/232540/files";
$publicationrecord2="https://infoscience.epfl.ch/record/232540?ln=en";

$publicationtext3="A. Alacaoglu, Q. Tran-Dinh, O. Fercoq and V. Cevher. Smooth Primal-Dual Coordinate Descent Algorithms for Nonsmooth Convex Optimization. 31st Conference on Neural Information Processing Systems (NIPS 2017), Long Beach, CA, USA, 2017";
$publicationlink3="https://infoscience.epfl.ch/record/232391/files/SMOOTH-CD_MAIN.pdf?version=1";
$publicationrecord3="https://infoscience.epfl.ch/record/232391?ln=en";

$publicationtext4="I. Bogunovic, S. Mitrovic, J. Scarlett and V. Cevher. A Distributed Algorithm for Partitioned Robust Submodular Maximization. IEEE International Workshop on Computational Advances in Multi-Sensor Adaptive Processing (CAMSAP), 2017";
$publicationlink4="https://infoscience.epfl.ch/record/232383/files/A%20Distributed%20Algorithm%20for%20Partitioned%20Robust%20Submodular%20Maximization.pdf?version=1";
$publicationrecord4="https://infoscience.epfl.ch/record/232383?ln=en";

$fullcontact="
<table>
 <tr>
  <td colspan=2>Associate Professor</td>
 </tr>
 <tr>
  <td colspan=2><a href=$labwebsite>$mylabname ($labname)</a><br><br></td>
 </tr>
 <tr>
  <td>Phone:</td><td><a href=tel:'$phone'>$phone</a></td>
 </tr>
 <tr>
  <td></td><td><a href=#>WebCall <img src=https://people.epfl.ch/images/info.png></a></td>
 </tr>
 <tr>
  <td valign=top>Postal address:</td><td valign=top>EPFL STI IEL LIONS<br>
				ELE 233 (Bâtiment ELE)<br>
				Station 11<br>
				CH-1015 Lausanne<br>
				Office	ELE 233</td>
 </tr>
</table>
<br><br>
";
?>

<div class="container">
 <div class="row">
  <div class="col-9 content-area" id="primary">
    <div class="entry-content sti_content_maincolumn">
     <header class="entry-header">
      <h1 class="people_titles temp_people_title"><?php the_title(); ?></h1>
     </header><?php # .entry-header ?>
     <div class=sti_beige>
      <img style="position: absolute; top:-12px; right: -8px;" src="/wp-content/themes/epfl-sti/img/src/topright.png">
      <img style="position: absolute; bottom:-12px; left: -4px;" src="/wp-content/themes/epfl-sti/img/src/bottomleft.png">
      <div class="sti_content_prof_text">
       <div class="sti_content_prof_photo">
    	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
       </div><?php # prof_photo ?>
       <b><?php echo "$position</b><br><br>$bio<br><br>"; ?>
      </div><?php # prof_text ?>
     </div><?php # beige ?>
    </div><?php # .entry-content ?>
   <br>
    <div class="entry-content sti_content_maincolumn">
      <h1 class="people_titles">Positions</h1>
     <div class="sti_content_prof_text">
       <?php echo $epfl_positions ?>
     </div><?php # prof_text ?>
    </div><?php # .entry-content ?>
  </div><?php # #primary ?>

  <?PHP # NAV MENU START ?>
  <div class="sti_righthand_menu col-md-3 d-xs-none d-md-block">
   <div class="sti_people_menu_title frontrowmarker">
    <?php echo $labname; ?> <span class="sti_people_menu_black"><?php echo $mylabname; ?></span>
    <img src=<?php echo $labimage; ?> class="sti_people_menu_image">
   </div><!-- menutitle-->
   <div class="sti_people_box">
   <div class="sti_people_menu_white">
    <?php echo$keywords; ?>
   </div><!--menuwhite-->
   <div class="prof-nav-menu">
    <ul class="menu">
     <li id="menu-item-128" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-128"><a href="<?php echo $labwebsite; ?>">LAB WEBSITE</a></li>
      <li id="menu-item-129" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-129"><a href="https://people.epfl.ch/cgi-bin/people?id=<?php echo $sciper;?>&op=publications&lang=en&cvlang=en">PUBLICATIONS</a></li>
	<li id="menu-item-130" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-131"><a href="#news">NEWS</a></li>
	<li id="menu-item-132" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-131"><a href="#video">VIDEO</a></li>
	<li id="menu-item-133" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-130"><a href="https://stisrv13.epfl.ch/collaborations/tube_html5.php?sciper=<?php echo $sciper; ?>&showpublications=1&showpatents=1&showexternals=1&showindustry=1">COLLABORATIONS</a></li>
	<li id="menu-item-134" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-132"><a href="#fullcontact">FULL CONTACT DETAILS</a></li>
</ul>
    <div class="sti_people_menu_white">
    <?php
echo "<h5>$firstname $surname</h5>$position<br>Office: <a class=whitelink href=https://maps.epfl.ch/?q=$office>ELE233</a><br><a class=whitelink href=mailto:$epflname@epfl.ch>$epflname@epfl.ch</a><br><a class=whitelink href=https://people.epfl.ch/$epflname>https://people.epfl.ch/$epflname</a><br>Tel: <a class=whitelink href=\"tel:$phone\">$phone</a><br>";
?>
    </div><!-- menu_white-->
   </div><!-- menucontainer-->
  </div><!-- peoplebox-->
 </div><?php # .sti_righthand_menu ?>
</div><?php # .row ?>
<?PHP # NAV MENU END ?>

<?php
if ( get_field( 'publications' ) ) { ?>
  <div class="row">
    <div class="col-md-9 content-area" id="primary">
      <div class="entry-content sti_content_maincolumn">
      <h1 class=people_titles>Recent Publications</h1>
        <div class="sti_beige">
          <?php
            // get publication through the shortcode
            $tmp = do_shortcode( '[infoscience url=' . get_field('publications') . ']' );
            $dom=new domDocument;
            // be sure to load the encoding
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $tmp);
            // let's use XPath
            $finder = new DomXPath($dom);
            // set the limit
            $limit = 10; $cnt = 0;
            // and remove unwanted elements
            foreach($finder->query("//*[contains(@class, 'infoscience_record')]") as $elm ) {
              if ($cnt >= $limit)
                $elm->parentNode->removeChild($elm);
              $cnt++;
            }
            // finally, echo
            echo $dom->saveHTML($dom->documentElement);
          ?>
        </div><?php # beige ?>
      </div><?php # .entry-content ?>
    </div><?php # #primary ?>
  </div><?php # .row ?>
<?php
} ?>
 <div class="row">
  <div class="col-md-9 content-area" id="primary">
    <div class="entry-content holding sti_content_maincolumn">
     <h1 class=people_titles>News</h1>
     <div class="sti_content_prof_text">
      <a name=news></a>
      <div class="frontrowcontent">
 <?php
    	echo "<div class='sti_people_news' style='background-image:url(\"$newsimage1\");'><div class=peoplenewstitle><a class=whitelink href=$newslink1>$newstitle1</a></div></div>";
    	echo "<div class='sti_people_news' style='background-image:url(\"$newsimage2\");'><div class=peoplenewstitle><a class=whitelink href=$newslink2>$newstitle2</a></div></div>";
    	echo "<div class='sti_people_news' style='background-image:url(\"$newsimage3\");'><div class=peoplenewstitle><a class=whitelink href=$newslink3>$newstitle3</a></div></div>";
    	echo "<div class='sti_people_news' style='background-image:url(\"$newsimage4\");'><div class=peoplenewstitle><a class=whitelink href=$newslink4>$newstitle4</a></div></div>";
 ?>

      </div><?php # frontrowcontent ?>
     </div><?php # prof_text ?>
   </div><?php # .sti_content_maincolumn ?>
  </div><?php # #primary ?>
 </div><?php # .row ?>

 <div class="row">
  <div class="col-md-9 content-area" id="primary">
   <div class="entry-content sti_content_maincolumn holding">
     <h1 class=people_titles><?php echo "$labvideotitle"; ?></h1>
     <div class="sti_content_prof_text">
      <a name=video></a>

      <div style="margin: 20px 0px 40px 0px; float:left; max-width:680; width: 100%; height:285px; "><iframe src="https://www.youtube.com/embed/<?php echo $labvideo; ?>?enablejsapi=1&amp;autoplay=0&amp;rel=0" allowscriptaccess="always" allowfullscreen="" width="680" height="280" frameborder="0"></iframe></div>

     </div><!--prof_text-->
   </div><?php # .sti_content_maincolumn ?>
  </div><?php # #primary ?>
 </div><?php # .row ?>

 <div class="row">
  <div class="col-md-9 content-area" id="primary">
   <div class="sti_content_maincolumn">
    <div class="entry-content holding">
     <h1 class=people_titles><?php echo "$firstname $surname"; ?></h1>
     <div class="sti_content_prof_text">
      <a name=fullcontact></a>
       <?php echo $fullcontact; ?>
     </div><!--prof_text-->
   </div><?php # .sti_content_maincolumn ?>
  </div><?php # #primary ?>
 </div><?php # .row ?>
</div><?php # .container ?>
