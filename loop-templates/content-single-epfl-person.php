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
} else {
    error_log("Class not exists");
    die();
}

$givenname     = $person->get_given_name();
$surname       = $person->get_surname();
$surname_uc    = strtoupper($surname);
$email         = $person->get_mail();
$profile_url   = $person->get_profile_url();
$biography     = $person->get_bio();
$sciper        = $person->get_sciper();
$phone         = $person->get_phone();
$office        = $person->get_room();
list($pa_unit, $pa_office, $pa_station, $pa_postcode) = explode("$", $person->get_postaladdress());

if ($title = $person->get_title()) {
    $officialtitle = $title->as_short_greeting();
    $position      = $title->localize();
}

if ($lab = $person->get_lab()) {
    $mgr = $lab->get_lab_manager();
    if ($mgr && $mgr->ID === $person->ID) {
        $labwebsite = $lab->get_website_url();
        $labname    = $lab->get_abbrev();
        $labimage   = get_the_post_thumbnail($lab->wp_post());
        $mylabname  = $lab->get_name();
    }
}

$keywords = $person->get_research_keywords();
$research = $person->get_research_interests();

$related_results = array();
foreach (array($person->attributions(), $person->mentioned()) as $related) {
    $related_results = array_merge($related_results, $related->get_posts(
        function_exists('pll_current_language') ?
        array('lang' => pll_current_language()) :
        null));
}

$news = array();

foreach ($related_results as $related_result) {
    if (get_post_format($related_result) === 'video') {
        $youtube_id = get_post_meta($related_result->ID, "youtube_id", true);
    } else {
        array_push($news, array(
            'image' => get_the_post_thumbnail($related_result),
            'url'   => get_the_permalink($related_result),
            'title' => $related_result->post_title));
    }
}


?>
<!-- epflsti:loop-templates/content-single-epfl-person.php -->
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

  <header ></header>

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
           <?php the_title( '<h1>' . $officialtitle . ' ', '</h1>' ); ?>
            </header>
           <main>
            <div class="person-photo">
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

          <?php if ($youtube_id): ?>
          <card class="<?php echo $listoflinks_width; ?>">
           <a name=video></a>
           <div style="margin: 20px 0px 40px 0px; float:left; width:100%; height:285px; ">
            <iframe src="https://www.youtube.com/embed/<?php echo $youtube_id; ?>?enablejsapi=1&amp;autoplay=0&amp;rel=0" allowscriptaccess="always" allowfullscreen="" width="100%" height="280" frameborder="0"></iframe>
           </div>
          </card>
          <?php endif; # card of videos ?>

          <?php if ($news): ?>
          <card class="person-news <?php echo $listoflinks_width; ?>">
            <header>
              <a name="news"></a>
              <h2>News</h2>
            </header>
            <main class="frontrowcontent">
            <?php foreach ($news as $piece): ?>
              <div class="mini-news">
               <div class="mini-news-image zoomy">
                <?php # The span is for https://stackoverflow.com/a/7310398/435004
                      # (Search for that URL in the SASS files for the other
                      # half of the trick)
                      # Be careful to put everything on one line — We don't want
                      # a significant (non-zero-width) space between the <span> and
                      # the WordPress-generated <img> ?>
                <span></span><?php echo $piece["image"]; ?>
               </div>
                <div class="mini-news-title">
                  <a class=whitelink href=<?php echo $piece["url"]; ?>><?php echo $piece["title"]; ?></a>
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
          <card class="card-person-publications <?php echo $listoflinks_width; ?>">
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
            <h5><br><?php echo "$givenname $surname_uc"; ?></h5>
            <div class="container">
              <div class="row entry-body">
                <div class="person-contact col-md-4">
                 <p class="office">
                  <?php echo 'Office: <a class="office" href="https://plan.epfl.ch/?q=' . $office . '">' . $office . '</a>'; ?>
                 <p class="email">
                  <?php echo '<a href="mailto:' . $email . '">' . $email . '</a>'; ?></p>
                 <p class="bottin">
                  <a href="<?php echo $profile_url; ?>"><?php
                           $profile_url_splittable = preg_replace("|(/+)|", '$1<wbr>', $profile_url);
                           echo $profile_url_splittable; ?></a></p>
                 <p class="telephone">
                  <?php
                  if ($phone != '') {
                    echo 'Tel: <a href="tel:+' . $phone . '">+' . $phone . '</a>';
                  }
                  ?>
                </p>
                </div>
                <div class="col-md-3">
                  <?php echo "<b>$pa_unit</b><br />$pa_office<br />$pa_station<br />$pa_postcode"; ?>
                </div>
                <div class="col-md-5 embed-responsive embed-responsive-4by3">
                  <?php if ($office != '') { ?>
                    <!-- <iframe class="embed-responsive-item" src="https://plan.epfl.ch/iframe/?map_zoom=12&q=<?php echo $person->get_sciper(); ?>" ></iframe> -->
                    <iframe class="embed-responsive-item" src="https://plan.epfl.ch/iframe/?map_zoom=12&room==<?php echo $office; ?>" ></iframe>
                  <?php
                  }  ?>
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
      <card class="person-lab first">
       <header>
        <h2><abbr><?php echo $labname; ?></abbr> <?php echo $mylabname; ?></span>
        </h2>
       </header>
       <?php if ($labimage) { echo $labimage; } ?>
      </card>
      <card class="links">
       <div class="research-topics">
        Research topics:<br><br><?php echo $keywords; ?>
       </div>
       <div class="person-nav-menu">
        <ul class="menu">
         <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="<?php echo $labwebsite; ?>">LAB WEBSITE</a></li>

         <?php if ($youtube_id) { ?>
          <li id="menu-item" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-131"><a href="#video">VIDEOS</a></li>
         <?php } ?>

         <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="#research">RESEARCH</a></li>
         <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="https://people.epfl.ch/cgi-bin/people?id=<?php echo $sciper; ?>&op=publications&lang=en&cvlang=en">PUBLICATIONS</a></li>
         <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="#news">NEWS</a></li>
         <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="https://stisrv13.epfl.ch/collaborations/tube_html5.php?sciper=<?php echo $sciper; ?>&showpublications=1&showpatents=1&showexternals=1&showindustry=1">COLLABORATIONS</a></li>
        </ul>
       </div><?php # person-nav-menu ?>
      </card>
     </aside>
    <?php endif; ?>

  </div><?php # row if there is a box of links on the right ?>
</div><?php #  container if there is a box of links on the right ?>
</article>
