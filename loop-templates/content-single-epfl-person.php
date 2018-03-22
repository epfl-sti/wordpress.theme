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

if (class_exists('\\EPFL\\WS\\Persons\\Person')) {
    global $post;
    $person = Person::get($post);
    $biography = $person->get_bio();
    $officialtitle = $person->get_title()->as_short_greeting();
    $sciper = $person->get_sciper();
    $phone  = $person->get_phone();
    $office = $person->get_room();
} else {
    error_log("Class not exists");
    die();
}

$lab = $person->get_lab();
if ($lab) {
    $mgr = $lab->get_lab_manager();
    if ($mgr && $mgr->ID === $person->ID) {
        $labwebsite = $lab->get_website_url();
        $labname    = $lab->get_abbrev();
        $mylabame   = $lab->get_name();
        
    }
}

// For the foreseeable future, this data only exists within the School
// of Engineering.  (See inc/epfl.php for how it gets scraped.)
$stisrv13data = json_decode(get_post_meta($person->wp_post()->ID, "stisrv13_data_json", true));
$keywords = $stisrv13data->keywords;
$research = $stisrv13data->interests;
$videoeng = $stisrv13data->videoeng;
$news     = json_decode(get_post_meta($person->wp_post()->ID, "stisrv13_news_json"));

// TODO: The following should be obtained from the Person and their
// Title instead.
$position=$stisrv13data->position;
$surname=$stisrv13data->surname;
$firstname=$stisrv13data->firstname;
$epflname=$stisrv13data->epflname;

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



?>
<div class="container">
  <div class=row>
    <div class="<?php echo $listoflinks_main; ?>">
      <div class="container"><?php # main container ?>
        <div class="row main-matter"><?php # main row ?>
          <card class="ribbon-red <?php echo $listoflinks_width; ?>">
            <header>
           <?php if ($with_red_ribbon): ?>
             <img class="ribbon-red-top" src="/wp-content/themes/epfl-sti/img/src/topright.png">
             <img class="ribbon-red-bottom" src="/wp-content/themes/epfl-sti/img/src/bottomleft.png">
           <?php endif;  ?>
           <?php the_title( '<h1>' . $officialtitle, '</h1>' ); ?>
            </header>
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

          <?php if ($videoeng != ""): ?>
          <card class="<?php echo $listoflinks_width; ?>">
           <a name=video></a>
           <div style="margin: 20px 0px 40px 0px; float:left; width:100%; height:285px; ">
            <iframe src="https://www.youtube.com/embed/<?php echo $videoeng; ?>?enablejsapi=1&amp;autoplay=0&amp;rel=0" allowscriptaccess="always" allowfullscreen="" width="100%" height="280" frameborder="0"></iframe>
           </div>
          </card>
          <?php endif; # card of videos ?>

          <?php if ($news): ?>
          <card class="prof-news <?php echo $listoflinks_width; ?>">
            <header>
              <a name="news"></a>
              <h2>News</h2>
            </header>
            <main class="frontrowcontent">
            <?php foreach ($news as $piece): ?>
              <div class="mini-news zoomy" style="background-image:url('<?php echo $piece["image"]; ?>');">
                <div class=peoplenewstitle>
                  <a class=whitelink href=<?php echo $piece["link"]; ?>><?php echo $piece["title"]; ?></a>
                </div>
              </div>
            <?php endforeach; ?>
            </main>
          </card>
          <?php endif; ?>

          <?php # Research Aera ?>
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

          <?php # Contact ?>
          <card class="<?php echo $listoflinks_width; ?>">
            <h2>Contact</h2>
            <h5><br><?php echo "$firstname $surname"; ?></h5>
            <div class="container">
              <div class="row entry-body">
                <div class="col-md-4">
                  <?php echo 'Office: <a href="https://maps.epfl.ch/?q=' . $office . '">' . $office . '</a>'; ?><br />
                  <?php echo '<a href="mailto:' . $epflname . '@epfl.ch">' . $epflname . '@epfl.ch</a>'; ?><br />
                  <?php echo '<a href="https://people.epfl.ch/' . $epflname . '">https://people.epfl.ch/' . $epflname . '</a>'; ?><br />
                  <?php echo 'Tel: <a href="tel:+' . $phone . '">' . $phone . '</a>'; ?><br />
                </div>
                <div class="col-md-3">
                  <?php echo "<b>$labname</b><br> $office<br> Station 11<br> 1015 Lausanne<br> Switzerland"; ?>
                </div>
                <div class="col-md-5 embed-responsive embed-responsive-4by3">
                  <iframe class="embed-responsive-item" src="https://plan.epfl.ch/iframe/?map_zoom=12&q=<?php echo $person->get_sciper(); ?>" ></iframe>
                </div>
              </div>
            </div>
          </card>

        </div><?php # main row ?>
      </div><?php # main container ?>
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
       <?php if ($labimage) { ?><img src="<?php echo $labimage; ?>" class="sti_people_menu_image"><?php } ?>
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
