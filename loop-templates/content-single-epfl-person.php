<?php


$with_menu=1;
$with_red_ribbon=1; //highlights the first box (red ribbon)

if ($with_menu) {
 $listoflinks_main=" col-md-8 content-area";
 $listoflinks_width=" width-main-listoflinks";
 $listoflinks_menu=" sti_righthand_menu col-md-4";
}

/**
 * Partial template for a person.
 *
 * @package epflsti
 */

use \EPFL\WS\Persons\Person;

// TODO: we should eliminate more and more of this, and move it into accessors
// of the Person instance, below
$incoming_json=file_get_contents('https://stisrv13.epfl.ch/cgi-bin/whoop/peoplepage.pl?sciper='.$post->post_name);
$incoming=json_decode($incoming_json);
$labname=$incoming->labname;
$mylabname=$incoming->mylabname;
$labwebsite=$incoming->labwebsite;
$keywords=$incoming->keywords;
$biography=$incoming->bio;
$research=$incoming->interests;
$position=$incoming->position;
$id=$incoming->id;
$surname=$incoming->surname;
$firstname=$incoming->firstname;
$epflname=$incoming->epflname;
$phone=$incoming->phone;
$office=$incoming->office;
$sciper=$incoming->sciper;
$videoeng=$incoming->videoeng;
$news=$incoming->news;
$labimage="https://stisrv13.epfl.ch/brochure/img/$id/research.png";

if (class_exists('\\EPFL\\WS\\Persons\\Person')) {
    global $post;
    $person = Person::get($post);
    $biography = $person->get_bio();
} else {
    error_log("Class not exists");
}

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

  <header ></header>

<?php

if ($position == 'PO') { $officialtitle='Prof. ';
$position='Full Professor'; }
else if ($position == 'PA') { $officialtitle='Prof. '; $position='Associate Professor'; }
else if ($position == 'SNF') { $officialtitle='Prof. '; $position='SNF Funded Assistant Professor'; }
else if ($position == 'PATT') { $officialtitle='Prof. '; $position='Tenure Track Assistant Professor'; }
else if ($position == 'PT') { $officialtitle='Prof. '; $position='Adjunct Professor'; }
else if ($position == 'MER') {$officialtitle='Dr. '; $position='Senior Scientist'; }
else {$officialtitle=$position; }

//the rest must come from other sources

$epfl_positions="
<br>
Associate Professor:<br><br>
<ul>
  <li>Laboratory for Information and Inference Systems
  <li>Institute of Electrical Engineering
  <li>School of Engineering
</ul>
<ul>
  <li>EDEE - Doctoral Program in Electrical Engineering
</ul>
";

$news_raw = array(
    array("title" => $incoming->newstitle1,
          "link"  => $incoming->newslink1,
          "image" => $incoming->newsimage1),
    array("title" => $incoming->newstitle2,
          "link"  => $incoming->newslink2,
          "image" => $incoming->newsimage2),
    array("title" => $incoming->newstitle3,
          "link"  => $incoming->newslink3,
          "image" => $incoming->newsimage3),
    array("title" => $incoming->newstitle4,
          "link"  => $incoming->newslink4,
          "image" => $incoming->newsimage4)
);

$news = [];
foreach ($news_raw as $piece) {
    if ($piece["title"]) {
        array_push($news, $piece);
    }
}

?>
<div class="container"><?php # row if there is a box of links on the right ?>
  <div class=row><?php #  container if there is a box of links on the right ?>
    <div class="<?php echo $listoflinks_main; ?>"><?php # column if there is a box of links on the right ?>
      <div class="container"><?php # main container ?>
        <div class="row main-matter"><?php #  main row ?>
          <card class="ribbon-red <?php echo $listoflinks_width; ?>">
           <?php if ($with_red_ribbon): ?>
            <header>
             <img class="ribbon-red-top" src="/wp-content/themes/epfl-sti/img/src/topright.png"> 
             <img class="ribbon-red-bottom" src="/wp-content/themes/epfl-sti/img/src/bottomleft.png">
            </header>
           <?php endif;  ?>
           <?php the_title( '<h1>'.$officialtitle, '</h1>' ); ?>
           <main>
            <div class="sti_content_prof_photo">
             <?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
            </div><?php # prof_photo ?>
            <?php echo "<b>$position</b><br><br>"; ?>
            <?php
              echo "\n" . '<biography class="person-bio" id="person-bio-' . $post->post_name . '">' . "\n";
              echo "\t" . $biography . "\n";
              echo "</biography>\n";
            ?>
           </main>
          </card>
          <card class="<?php echo $listoflinks_width; ?>">
           <h2>Contact</h2>
           <h5><br><?php echo "$firstname $surname"; ?></h5>
           <div class="container">
            <div class="row entry-body">
              <div class="col-md-4">
                <?php echo 'Office: <a href="https://maps.epfl.ch/?q=' . $office . '">$office</a><br><a href="mailto:' . $epflname . '@epfl.ch>' . $epflname . '@epfl.ch</a><br><a href="https://people.epfl.ch/' . $epflname . '">https://people.epfl.ch/' . $epflname . '</a><br>Tel: <a href="tel:+' . $phone . '">' . $phone . '</a><br><br>'; ?>
              </div>
              <div class="col-md-3">
                <?php echo "$labname<br> $office<br> Station 11<br> 1015 Lausanne<br> Switzerland"; ?>
              </div>
              <div class="col-md-5 embed-responsive embed-responsive-4by3">
                <iframe class="embed-responsive-item" src="https://plan.epfl.ch/iframe/?map_zoom=12&q=<?php echo $person->get_sciper(); ?>" ></iframe>
              </div>
            </div>
           </div>
          </card>

          <?php if ($videoeng != ""): ?>
          <card class="<?php echo $listoflinks_width; ?>">
           <a name=video></a>
           <div style="margin: 20px 0px 40px 0px; float:left; width:100%; height:285px; ">
            <iframe src="https://www.youtube.com/embed/<?php echo $videoeng; ?>?enablejsapi=1&amp;autoplay=0&amp;rel=0" allowscriptaccess="always" allowfullscreen="" width="100%" height="280" frameborder="0"></iframe>
           </div>
          </card>
          <?php endif; # card of videos ?>
          <card class="<?php echo $listoflinks_width; ?>"><a name=research></a>
           <header>
            <h2>Research Area</h2>
           </header>
           <main><?php echo $research; ?></main>
          </card>

          <?php if ( get_post_meta( $post->ID, 'publication_link', true) ): ?>
          <card class="card-prof-publications <?php echo $listoflinks_width; ?>">
           <header>
            <h2>Recent Publications</h2>
           </header>
           <main>
            <?php
              // get publication through the shortcode
              $tmp = do_shortcode( '[infoscience url=' . get_post_meta( $post->ID, 'publication_link', true) . ']' );
              $dom = new DOMDocument();
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
           </main>
          </card>
          <?php endif; ?>

          <?php if ($news): ?>
          <card class="prof-news <?php echo $listoflinks_width; ?>">
           <header>
            <a name="news"></a>
            <h2>News</h2>
           </header>
           <main class="frontrowcontent">
            <?php foreach ($news as $piece): ?>
             <div class="mini-news zoomy" style="background-image:url('<?php echo $piece["image"]; ?>');">
              <div class=peoplenewstitle><a class=whitelink href=<?php echo $piece["link"]; ?>><?php echo $piece["title"]; ?></a>
              </div>
             </div>
            <?php endforeach; ?>
           </main>
          </card>
          <?php endif; ?>
        </div><?php # main row ?>
      </div><?php #  main container ?>
    </div><?php # column in case there is a list of links on the right ?>

    <?php
    // this box is a list of links
    if ($with_menu):
    ?>
     <aside class="col-md-4">
      <card class="first">
       <header>
        <h2 class="sti_people_menu_black"><abbr><?php echo $labname; ?></abbr> <?php echo $mylabname; ?></span>
        </h2>
       </header>
       <img src="<?php echo $labimage; ?>" class="sti_people_menu_image">
      </card>
      <card class="links">
       <div class="research-topics">
        Research topics:<br><br><?php echo $keywords; ?>
       </div>
       <div class="prof-nav-menu">
        <ul class="menu">
         <li id="menu-item-128" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-128"><a href="<?php echo $labwebsite; ?>">LAB WEBSITE</a></li>

         <?php if ($videoeng != "") { ?>
          <li id="menu-item-132" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-131"><a href="#video">VIDEOS</a></li>
         <?php } ?>

         <li id="menu-item-134" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-131"><a href="#research">RESEARCH</a></li>
         <li id="menu-item-129" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-129"><a href="https://people.epfl.ch/cgi-bin/people?id=<?php echo $sciper; ?>&op=publications&lang=en&cvlang=en">PUBLICATIONS</a></li>
         <li id="menu-item-130" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-131"><a href="#news">NEWS</a></li>
         <li id="menu-item-133" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-130"><a href="https://stisrv13.epfl.ch/collaborations/tube_html5.php?sciper=<?php echo $sciper; ?>&showpublications=1&showpatents=1&showexternals=1&showindustry=1">COLLABORATIONS</a></li>
        </ul>
       </div><?php # prof-nav-menu ?>
      </card>
     </aside>
    <?php endif; ?>

  </div><?php # row if there is a box of links on the right ?>
</div><?php #  container if there is a box of links on the right ?>
<footer>
 <?php epflsti_entry_footer(); ?>
</footer>
</article>
