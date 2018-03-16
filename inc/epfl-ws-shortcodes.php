<?php

/**
 * Themes for the shortcodes in the epfl-ws plugin
 */

namespace EPFL\STI\WSShortcodes;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

add_filter("epfl_shortcode_memento_list_html", function ($events) {
        $memento.="
     <div class='actu_news_box'>
      <div class='actu_news_contenu'>";
    foreach ($events as $item) {
      $startdate=$item->event_start_date;
      $enddate=$item->event_end_date;
      if ($startdate == $enddate) {
       $enddate="";
      }
      else {
       $enddate=date("l, jS F ", strtotime($enddate));
      }
      $startdate=date("l, jS F", strtotime($startdate));
      $starttime=$item->event_start_time;
      $endtime=$item->event_end_time;
      $starttime=date("H:i", strtotime($starttime));
      $endtime=date("H:i", strtotime($endtime));
      if ($starttime == "00:00") {
       $starttime="";
      }
      if ($endtime == "00:00") {
       $endtime="";
      }

      $outlinkstartdate=str_replace("-","",$item->event_start_date);
      $outlinkenddate=str_replace(":","",$item->event_end_date);

      $outlinkendtime=str_replace(":","",$item->event_end_time);
      if (empty($outlinkendtime)) {
       $outlinkendtime="000000";
      }
      $outlinkstarttime=str_replace(":","",$item->event_start_time);
      if (empty($outlinkstarttime)) {
       $outlinkstarttime="000000";
      }
      $outlinktitle=str_replace(" ","%20",$item->title);

      $outlink="https://stisrv13.epfl.ch/outlink.php?enddate=$outlinkenddate"."T$outlinkendtime&datestring=$outlinkstartdate"."T$outlinkstarttime&speaker=&title=$outlinktitle&room=";

      $description = str_replace("<strong>", "", $item->description);
      $description = str_replace("</strong>", "", $description);

      $memento .= '<div class="actu_news_box" id="' . $item->id . '">';
      $memento .= '<div class="actu_titre_news"><a href="' . $item->absolute_slug . '">' . $item->title . '</a></div>';
      $memento .= '<div class="container">';
      $memento .= '<div class="row entry-body">';
      $memento .= '<div class="col-md-2"><a href="' . $item->absolute_slug . '"><img class=actu_img_news src="' . $item->event_visual_absolute_url . '" title="' . $item->image_description . '"></a>';
      $memento .= $startdate . ' ' . ($starttime ? $starttime : "") ;
      if ($enddate) {
        $memento .= '<br>to<br>' . $enddate . ' ' . ($endtime ? $endtime : "") ;
      }
      $memento .= '<br><a title="add to calendar" href='.$outlink.'><img src=https://stisrv13.epfl.ch/newsdesk/images/2018-03-15calendar.gif alt="add to calendar"></a> <br><br> </div>';
      $memento .= '<div class="col-md-10">' . $description.'<a href="' . $item->absolute_slug . '">Read more</a> <br><br></div>';
      $memento .= "</div>\n";  # row
      $memento .= "</div>\n";  # container
      $memento .= "</div>\n";  # actu_news_box
    }
    $memento .= "</div></div>\n";  # actu_news_box and actu_news_contenu
    return $memento;
});
