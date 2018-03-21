<?php

/**
 * Shortcodes for EPFL STI pages
 */

add_shortcode("lab-card", function ($attrs, $content) {
    $html = "<card class=\"sti-lab\">\n";

    $title = $attrs["title"];
    if ($title) {
        $html .= "<header><h1>$title</h1></header>";
    }
    $img_href = $attrs["img"];
    if ($img_href) {
        $img_html = "<img src=\"$img_href\" />";
        if ($attrs["href"]) {
            $img_html = "<a href=\"". $attrs["href"] . "\">$img_html</a>";
        }
        $html .= $img_html;
    }
    if ($content) {
        $content_has_link = preg_match("<a", $content);
        $content_has_p = preg_match("^<p>", trim($content));
        if ($content_has_p) {
            $html .= $content;
        } else {
            if ($attrs["href"] && ! $content_has_link) {
                $content = "<a href=\"". $attrs["href"] . "\">$content</a>";
            }
            $html .= "<p>$content</p >";
        }
    }
    $html .= "\n</card>\n";
    return $html;
});
