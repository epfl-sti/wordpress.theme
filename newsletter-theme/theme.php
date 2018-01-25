<?php // -*- web-mode-code-indent-offset: 4; -*-

/*
Theme Name: EPFL STI
Description: Newsletter theme for the School of Engineering
*/

namespace EPFL\STI\Newsletter;

if (!defined('ABSPATH'))
    exit;

require_once(dirname(dirname(__FILE__)) . "/inc/epfl.php");
use function \EPFL\STI\get_theme_relative_uri;

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;
use function \EPFL\STI\Theme\__x;

require_once(dirname(__FILE__) . '/inc/newsletter_state.php');

function has_vue_app ()
{
    return function_exists("\\EPFL\\STI\\Newsletter\\render_editor_scripts");
}

function render_css()
{ ?>
<style type="text/css">
.newsletter-title {
 background-color:#039;
 font-size: 20px;
 font-family: Verdana, sans-serif;
 color: #feea45;
 padding:4px;
}
<?php # https://stackoverflow.com/a/18721938/435004 ?>
td > img, td > a > img {
    vertical-align: top;
}
p {
 padding: 0px;
 margin: 0px;
}

a {
 text-decoration: none;
 color: black !important;   <?php # Gmail thinks different ?>
}

.volume-info {
    padding: 0px 8px 0px 0px;
    margin: 0px;
    font: bold 9px verdana;
    background-color:white;
}

a.outlink.more {
    font-size:12px;
}

.redtitle {
    padding: 0px;
}

.redtitle h1 {
    padding: 4px 0px 4px 8px;
    margin-bottom: 0px;
    margin-top: 0px;
    text-align: left;
    font-family: verdana,sans-serif;
    font-size: medium;
    font-weight: regular;
    color:white;
    background-color: #c50813;
}

body, td.main-matter > a {
  color: #666;
  font-family:Tahoma,Verdana,sans-serif;
}

td#right-sidebar table td {
    background-color:white;
}

td#right-sidebar .divider {
    border-bottom:2px solid #c50813;
    font-size:1px;
}

#in-the-media .title,
#events .title {
    font-size:12px;
    width:100%;
    padding: 0px 0px 10px 0px;
}

#in-the-media .details {
    font-size: 10px;
}

#events .details {
    font-size:12px;
}

td.main-matter.news {
    padding: 20px 10px 20px 10px;
}

td.main-matter {
  background-color:#fff;
  font-size: 13px;
}

.date {
    border:3px solid #aaa;
    background-color:#eee;
    width: 36px;
    padding:0px 5px 0px 5px;
    text-align: center;
}

.date.double {
    white-space: nowrap;
    width: auto;
}

.date .day {
    font-size:20px;
}

.date .month {
    font-weight: bold;
    color:#c50813;
    vertical-align:top;
}

.main-matter h2.hero {
    font-size: 18px;
}

.main-matter h2 {
    font-size: 14px;
    margin: 0px;
}

#news-main td, #faculty {
    border-bottom: 14px solid #d6d6d6;
}

#footer td {
    border-top:10px solid #c50813;
    background-color:white;
    padding: 5px;
    text-align: center;
    font-size:10px;
    font-family:verdana,sans-serif
}

#footer a {
    color: #c50813;
    text-decoration: none;
    font-weight: bold;
}

</style>
<?php }


// <table>s everywhere is the way to go - Ancient versions of Outlook
// probably don't like HTML5 stuff much.

function render_frame_table ($render_inside_func) {
    ?>
    <table bgcolor="white" width="100%" cellpadding="4" cellspacing="0" border="0">
    <tr>
        <td align="center">
            <table id="composer-toplevel" width="737" align="center" cellspacing="14" <?php echo get_main_grid_table_attributes(); ?>>
               <?php call_user_func($render_inside_func); ?>
             </table>
        </td>
    </tr>
    </table>
    <?php
}

function get_main_grid_table_attributes ()
{
    return 'bgcolor="#d6d6d6" cellpadding="0" border="0"';
}

function render_header_tr ($volumeno)
{
    ?>
            <tr>
                <td colspan=2>
				<table cellpadding=0 cellspacing=0 border=0>
				 <tr>
                                  <td><a href=http://sti.epfl.ch><img src="<?php echo get_theme_relative_uri() . "/newsletter-theme/banner.png"; ?>"/></a></td>
				 </tr>
				 <tr>
                                  <td align=right class="volume-info"><?php echo $volumeno; ?></td>
				 </tr>
				 <tr>
                                  <td><img src="<?php echo get_theme_relative_uri() . "/newsletter-theme/outrider.gif"; ?>" style="display: block;"/></td>
				 </tr>
				</table>
                </td>
            </tr>
			<tr>
				<td colspan=2>
					<img src="<?php echo get_theme_relative_uri() . "/newsletter-theme/feature.png"; ?>"/></td>
			</tr>
    <?php
}

function render_red_title_tr ($name)
{
    echo "<tr><th class=\"redtitle\"><h1>$name</h1></th> </tr>";
}

function get_righthand_column_table_attributes () {
    return "width=\"100%\" cellpadding=\"8\" cellspacing=\"0\" border=\"0\"";
}

function render_news_item_td ($style)
{
    if ($style === "hero") {
        $colspan="colspan=2"; #first article only
    } else {
        $colspan="";
    }
    if ($style === "hero" or $style === "large") {
        $h2='<h2 class="hero">';
        $imagesize="width:250px";
    } else {
        $h2='<h2>';
        $imagesize="";
  }

    printf("<td class=\"main-matter news\" %s>", $colspan);

    $img = get_the_post_thumbnail(get_the_id(), 'post-thumbnail', array(
        "style"  => $imagesize,
        "hspace" => "18",
        "alt"    => "picture",
        "align"  => "left"
    ));
    if ($img) {
        echo sprintf('<a target="_blank" href="%s">%s</a>', get_permalink(), $img);
    }
    echo sprintf('%s<a target="_blank" href="%s">%s</a></h2>',
                 $h2,
                 get_permalink(),
                 get_the_title());
    echo sprintf('<a target="_blank" href="%s">%s</a>',
                 get_permalink(), strip_tags(get_the_excerpt()));
    echo sprintf("<news-item-handle :post-id=\"%d\"></news-item-handle>", get_the_id());
    echo "</td>";
}

function render_footer_tr ()
{
    $archive_url = site_url("/newsletter-archive");
    ?>
            <tr>
			    <td colspan=2>
			    <table id='footer' cellpadding=0 cellspacing=0 border=0 width=100%>
				 <tr>
				  <td>
                         <a href="https://sti.epfl.ch">School of Engineering</a>, &Eacute;cole Polytechnique F&eacute;d&eacute;rale de Lausanne (<a href="https://www.epfl.ch">EPFL</a>), Switzerland<br>You can access previous versions of the newsletter <a href="<?php echo $archive_url; ?>">here</a>
<br>
Unsubscribe by clicking <a target="_blank" href="{unsubscription_url}">here</a>
				 </td>
				 </tr>
			    </table>
                </td>
            </tr>
    <?php
}

function render_faculty_positions_table ($positions) {
    if (!count($positions) && !has_vue_app()) { return; }

    echo "<table id=\"faculty\" cellpadding=\"8\" cellspacing=0 border=0 style=\"width: 100%;\">";
    render_red_title_tr(___("OPEN POSITIONS"));

    if (! count($positions)) {
        echo "<tr><td class=\"main-matter\">";
        echo ___('No faculty positions yet.');
        echo "<faculty-position-handle></faculty-position-handle>";
        echo "</td></tr>";
    }

    foreach ($positions as $p) {
        global $post;
        $post = $p;
        echo "<tr>";
        echo "<td class=\"main-matter\">\n";
        echo sprintf("<h2><a target='_blank' href=\"%s\" class=\"positiontitle\">%s</a></h2>",
                 get_permalink(),
                 get_the_title());
        the_excerpt();
        echo sprintf("<faculty-position-handle :post-id=\"%d\"></faculty-position-handle>", get_the_id());
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>\n";
}

function render_events_table ($events)
{
    if (!count($events) && !has_vue_app()) { return; }
    $table_attributes = get_righthand_column_table_attributes();
    echo "<table $table_attributes id=\"events\">";
    render_red_title_tr(___("EVENTS"));

    if (! count($events)) {
        echo "<tr><td>";
        echo ___('No events yet.');
        echo '<event-handle></event-handle>';
        echo "</td></tr>";
    }

    foreach ($events as $event) {
        global $post;
        $post = $event;  // We aren't in The Loop so there is nothing else to do
        $title = get_the_title();
        $link  = get_permalink($post);
        $subtitle = function_exists("get_the_subtitle") ? get_the_subtitle($post, "", "", false) : null;
        $day   = null;
        $month = null;
        $doubleday_class = "";
        if (class_exists('EPFL\\WS\\Memento\\Memento')) {
            $epfl_memento = \EPFL\WS\Memento\Memento::get($post);

            $start = $epfl_memento->get_start_datetime();
            $end   = $epfl_memento->get_end_datetime();
            if ($start) {
                if ($end &&
                    ($start->format('Y m') === $end->format('Y m')) &&
                    ($start->format('j')   !== $end->format('j'))) {
                    $day = sprintf("%d-%d", $start->format('j'),
                                   $end->format('j'));
                    $doubleday_class = " double";
                } else {
                    $day = sprintf("%d", $start->format('j'));
                }
                $month = strtolower($start->format('M'));
            }
            $venue = $epfl_memento->get_venue();
            if ($venue) {
                if (preg_match("/swisstech/i", $venue) ||
                    preg_match("/stcc/i", $venue)) {
                    $venue = ___("SwissTech Convention Center");
                } elseif (preg_match("/RLC/", $venue) ||
                          preg_match("/Rolex/i", $venue)) {
                    $venue = ___("Rolex Learning Center");
                } elseif (preg_match("/^MC/", $venue)) {
                    $venue = ___("Microcity Neuchâtel");
                } else {
                  $venue = ___("EPFL campus");
                }
            }

            $ical_link = $epfl_memento->get_ical_link();
        } else {  // No epfl-ws plugin
            $ical_link = $link;
        }
?>
 <tr>
  <td>
   <event-handle :post-id="<?php echo get_the_id(); ?>"></event-handle>
   <table>
    <tr>
     <td class="event title" colspan=2>
      <a href="<?php echo $link; ?>"><?php echo $title; ?></a>
      <?php if ($subtitle) { echo "<br/>"; echo $subtitle; } ?>
     </td>
    </tr>
    <tr>
     <?php if ($day && $month): ?>
      <td style="padding: 0px 0px 10px 0px;" width="50" align="left" valign="top">
       <div class="date<?php echo $doubleday_class ?>"><a href="<?php echo $link; ?>"><span class="day"><?php  echo $day; ?></span><br><span class="month"><?php echo $month; ?></span></a></div>
      </td>
     <?php endif; ?>
     <td class="details" align=right>
      <?php echo $venue; ?>
      <br>
      <a href="<?php echo $ical_link; ?>">Add to calendar</a>
     </td>
    </tr>
    <tr><td colspan="2" class="divider">&nbsp;</td></tr>
   </table>
  </td>
 </tr>
<?php
    }
    ?>
 <tr>
  <td align="right"><a href="https://sti.epfl.ch/seminars" class="outlink more">More...</a></td>
 </tr>
</table>
    <?php
}

function render_in_the_media_table ($media_list)
{
    if (!count($media_list) && !has_vue_app()) { return; }
    $table_attributes = get_righthand_column_table_attributes();
    echo "<table $table_attributes id=\"in-the-media\">";
    render_red_title_tr(___("IN THE MEDIA"));

    if (! count($media_list)) {
        echo "<tr><td>";
        echo ___('No media excerpts yet.');
        echo '<media-item-handle></media-item-handle>';
        echo "</td></tr>";
    }

    foreach ($media_list as $media) {
        global $post; $post = $media;
        $article = strip_tags(get_the_title());
        $link    = get_permalink($post);
        if (class_exists('\\EPFL\\STI\\EPFLPost')) {
            $epfl_post = new \EPFL\STI\EPFLPost($post);
            $source    = $epfl_post->get_published_in();
            $date      = $epfl_post->get_publication_date();
            $dateformat = __x("Y-m-d",
                              "Date format for an \"in the media\" entry in the newsletter");
            if ($source && $date) {
                $bibentry = "$source, " . $date->format($dateformat);
            } elseif ($source) {
                $bibentry = "source";
            } else {
                $bibentry = "";
            }
        }
    ?>
     <tr>
      <td>
       <media-item-handle :post-id="<?php echo get_the_id(); ?>"></media-item-handle>
       <table>
        <tr>
         <td class="title">
          <a href="<?php echo $link; ?>"><?php echo "$article"; ?></a>
         </td>
        </tr>
        <tr>
         <td class="details" align=right>
          <?php echo $bibentry;
            $authors = $epfl_post->get_authors();
            if ($authors) {
              $labname = sprintf(___("%s's lab"), $authors[0]->get_full_name());
              $laburl = $authors[0]->get_lab_website_url();
              echo "<br>";
              printf('<a href="%s">%s</a>', $laburl, $labname);
          ?>
         </td>
        </tr>
        <?php
            }  // end if ($authors)
        ?>
        <tr>
         <td class="divider">&nbsp;</td>
        </tr>
       </table>
      </td>
     </tr>
    <?php
    }
    ?>
     <tr>
      <td align="right"><a href="https://sti.epfl.ch/news" class="outlink more">More...</a></td>
     </tr>
    </table>
    <?php 
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <?php
        render_css();
        if (has_vue_app()) {
            render_editor_scripts();
        }
    ?>
    </head>

    <body>
<?php
render_frame_table(function() {
    $volumeno = (new \DateTime())->format("F Y");
    render_header_tr($volumeno);
    $posts = get_newsletter_posts($theme_options);
    global $post;

    $news = $posts["news"]->posts();
    $post = $news[0];  // We aren't in The Loop so there is nothing else to do
    echo "<tr id=\"news-hero\">";
    render_news_item_td("hero");
    echo " </tr>";

    echo "<tr>";
    echo "<td width=\"520\" valign=\"top\">";
    printf('<table id="news-main" width="100%%" cellspacing="0" %s>',
           get_main_grid_table_attributes());
    for ($i = 1; $i < count($news); $i++) {
        $post = $news[$i];  // See comment above
        echo "<tr>";
        render_news_item_td("normal");
        echo " </tr>";
    }
    echo "</table>\n";

    render_faculty_positions_table($posts["faculty"]->posts());
    echo "</td>\n";  // End of main matter

    printf("<td valign=\"top\" id=\"right-sidebar\">");
    render_in_the_media_table($posts["media"]->posts());
    echo "<br>";
    render_events_table($posts["events"]->posts());
    echo "</td>";
    echo "</tr>\n";

    render_footer_tr();
});  // end of function passed to render_frame_table
    ?>
    </body>
</html>
