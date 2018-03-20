<?php

/**
 * Themes for the shortcodes in the epfl-ws plugin
 */

namespace EPFL\STI\WSShortcodes;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

add_filter(
    "epfl_shortcode_memento_list_html_item",
    function ($unused_html, $unused_shortcode_attrs, $item) {
        $startdate = $item->event_start_date;
        $enddate = $item->event_end_date;
        if ($startdate == $enddate) {
            $enddate = "";
        } else {
            $enddate = date("l, jS F ", strtotime($enddate));
        }
        $startdate = date("l, jS F", strtotime($startdate));
        $starttime = $item->event_start_time;
        $endtime = $item->event_end_time;
        $starttime = date("H:i", strtotime($starttime));
        $endtime = date("H:i", strtotime($endtime));
        if ($starttime == "00:00") {
            $starttime = "";
        }
        if ($endtime == "00:00") {
            $endtime = "";
        }

        $outlinkstartdate = str_replace("-","",$item->event_start_date);
        $outlinkenddate = str_replace(":","",$item->event_end_date);

        $outlinkendtime = str_replace(":","",$item->event_end_time);
        if (empty($outlinkendtime)) {
            $outlinkendtime = "000000";
        }
        $outlinkstarttime = str_replace(":","",$item->event_start_time);
        if (empty($outlinkstarttime)) {
            $outlinkstarttime = "000000";
        }
        $outlinktitle = str_replace(" ","%20",$item->title);

        $outlink = "https://stisrv13.epfl.ch/outlink.php?enddate=$outlinkenddate"."T$outlinkendtime&datestring=$outlinkstartdate"."T$outlinkstarttime&speaker=&title=$outlinktitle&room=";

        $description = str_replace("<strong>", "", $item->description);
        $description = str_replace("</strong>", "", $description);

        $memento .= '<div class="fullwidth-list-item" id="' . $item->id . '">';
        $memento .= '<h2><a href="' . $item->absolute_slug . '">' . $item->title . '</a></h2>';
        $memento .= '<div class="container">';
        $memento .= '<div class="row entry-body">';
        $memento .= '<div class="col-md-2 memento-details"><a href="' . $item->absolute_slug . '"><img src="' . $item->event_visual_absolute_url . '" title="' . $item->image_description . '"></a>';
        $memento .= $startdate . ' ' . ($starttime ? $starttime : "") ;
        if ($enddate) {
            $memento .= '<br>to<br>' . $enddate . ' ' . ($endtime ? $endtime : "") ;
        }
        $memento .= '<br><a title="add to calendar" href='.$outlink.'><img src=https://stisrv13.epfl.ch/newsdesk/images/2018-03-15calendar.gif alt="add to calendar"></a> <br><br> </div>';
        $memento .= '<div class="col-md-10">' . $description.'<a href="' . $item->absolute_slug . '">Read more</a> <br><br></div>';
        $memento .= "</div>\n";  # row
        $memento .= "</div>\n";  # container
        $memento .= "</div>\n";  # fullwidth-list-item

        return $memento;
    }, 10, 3);

function title2anchor ($title)
{
    $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                                'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                                'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                                'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                                'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
    $anchor = strtr( $title, $unwanted_array );
    $anchor = str_replace(" ", "-", $anchor);
    $anchor = str_replace("'", "-", $anchor);
    $anchor = strtolower($anchor);
    $anchor = substr($anchor, 0, 50);
    return $anchor;
}

add_filter("epfl_shortcode_actu_list_html_item", function ($unused_html, $unused_shortcode_attrs, $item) {
        $link_to_article = "<a href=\"https://actu.epfl.ch/news/" . title2anchor($item->title) . "\">";
       return "<div class=\"fullwidth-list-item\">
        <h2>$link_to_article".strtoupper($item->title)."</a></h2>
        <div class=\"actu-details\">
         <img src=\"".$item->visual_url."\" width=\"170\" height=\"100\">
         <span>".$item->subtitle."</span>
        </div>
       </div>";
}, 10, 3);

