<?php

/**
 * Themes for the shortcodes in the epfl-ws plugin
 */

namespace EPFL\STI\WSShortcodes;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

require_once(__DIR__ . "/template-tags.php");
use function \epflsti_lab_card;

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

        $description = str_replace("<strong>", "", $item->description);
        $description = str_replace("</strong>", "", $description);

        $memento .= '<div class="fullwidth-list-item" id="' . $item->id . '">';
        $memento .= '  <h2><a href="' . $item->absolute_slug . '">' . $item->title . '</a><span class="top-right"><a href="https://memento.epfl.ch/event/export/' . $item->translation_id . '" title="Add to calendar"><i class="fas fa-calendar-alt"></i></a><span></h2>';
        $memento .= '  <div class="container">';
        $memento .= '    <div class="row entry-body">';
        $memento .= '      <div class="col-md-2 memento-details">';
        $memento .= '        <div class="text-center">';
        $memento .= '          <a href="' . $item->absolute_slug . '"><img src="' . $item->event_visual_absolute_url . '" title="' . $item->image_description . '"></a>';
        $memento .= '        </div>';
        $memento .= '        <div class="memento-date">';
        $memento .= '          <a href="https://memento.epfl.ch/event/export/' . $item->translation_id . '" title="Add to calendar" class="font-weight-normal"><i class="fas fa-calendar-alt"></i>&nbsp;';
        $memento .= '        ' . $startdate . ($starttime ? ', ' . $starttime : '') ;
        if ($enddate) {
            $memento .= 'to ' . $enddate . ($endtime ? ', ' . $endtime : '') ;
        } else {
            $memento .= ($endtime ? ' - ' . $endtime : '') ;
        }
        $memento .= '          </a>';
        $memento .= '        </div>';
        $memento .= '        <div class="memento-room">';
        $memento .= '          <a href="' . $item->event_url_place_and_room . '" class="text-uppercase font-weight-normal"><i class="fas fa-map-marker-alt"></i> ' . $item->event_place_and_room . '</a>';
        $memento .= '        </div>';
        $memento .= '        <br />'; # Displayed in mobile only - comfortable space to read.
        $memento .= '      </div>';
        $memento .= '      <div class="col-md-10">';
        $memento .= '     ' . ($item->event_speaker) ? $item->event_speaker . '<br /><br />' : '';
        $memento .= '     ' . $description;
        $memento .= '        <br /><span class="float-right"><a href="' . $item->absolute_slug . '">Read more</a></span>';
        $memento .= '      </div>';
        $memento .= '    </div>';  # row
        $memento .= '  </div>';  # container
        $memento .= '</div>';  # fullwidth-list-item

        return $memento;

    }, 10, 3);


function renderAuthors($authors_array) {
    return implode(', ', array_map( function($ary){ return $ary->first_name . ' ' . $ary->last_name; }, $authors_array));
}

add_filter("epfl_shortcode_actu_list_html_item", function ($unused_html, $unused_shortcode_attrs, $item) {

    //print "<pre>";var_dump($item); print "</pre>";
    //var_dump($item->authors);

    $publication_date = date("jS F, Y", strtotime($item->publish_date));

    return "<div class=\"fullwidth-list-item\" id=\"" . $item->id . "\">
      <h2><a href=\"" . $item->news_url . "\">" . strtoupper($item->title) . "</a><span class=\"top-right\"> $publication_date</span></h2>
      <div class=\"container\">
        <div class=\"row entry-body\">
          <div class=\"col-md-2\">
            <div class=\"text-center actu-details\">
              <a href=\"" . $item->news_url . "\"><img src=\"" . $item->visual_url . "\" title=\"" . $item->visual_description . "\" /></a>
            </div>
          </div>
          <div class=\"col-md-10 actu-item-content\">
            " . $item->subtitle . "
            <!-- <div class=\"actu-authors text-right font-italic\">
            " . renderAuthors($item->authors) . "
            </div>
            <span class=\"float-right\"><a href=\"" . $item->news_url . "\">Read more</a></span>
            <a href=\"#\" title=\"Channel: " . $item->channel->name  . ", Category: " . $item->category->en_label . "\"><i class=\"fas fa-tags\"></i></a> -->
          </div>
        </div>
      </div>
    </div>";
}, 10, 3);

add_filter("epfl_shortcode_labs_list_html_item", function ($unused_html, $unused_shortcode_attrs, $lab) {
    return epflsti_lab_card(null, array("lab" => $lab));
}, 10, 3);
