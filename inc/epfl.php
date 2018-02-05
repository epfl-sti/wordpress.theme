<?php
/**
 * EPFL-specific functions
 */

namespace EPFL\STI;

/**
 * @return The name of the directory the epfl-sti theme is installed at.
 */
function get_theme_basename() {
  return basename(dirname(dirname(__FILE__)));
}

/**
 * @return The top URI of the theme (relative to content_url())
 */
function get_theme_relative_uri() {
  return "wp-content/themes/" . get_theme_basename();
}

/**
 * @return The language code name (e.g. en, fr)
 * Fall back to english (needs polylang)
 */
function get_current_language() {
  return (function_exists( 'pll_current_language' )) ? pll_current_language( 'slug' ) : 'en';
}

function curl_get ($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

/**
 * @return The contents of $url, converted to UTF-8 (including any meta
 *         markers for the content type that might be embedded in the HTML)
 */
function curl_get_utf8 ($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
    $output = curl_exec($ch);

    // https://stackoverflow.com/a/2513938/435004
    unset($charset);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    /* 1: HTTP Content-Type: header */
    preg_match( '@([\w/+]+)(;\s*charset=(\S+))?@i', $content_type, $matches );
    if ( isset( $matches[3] ) )
        $charset = $matches[3];

    /* 2: <meta> element in the page */
    if (!isset($charset)) {
        preg_match( '@<meta\s+http-equiv="Content-Type"\s+content="[\w/]+\s*;\s*charset=([^\s"]+)@i', $output, $matches );
        if ( isset( $matches[1] ) ) {
            $charset = $matches[1];
            // In case we want do do further processing downstream:
            $output = preg_replace('@(<meta\s+http-equiv="Content-Type"\s+content="[\w/]+\s*;\s*charset=)([^\s"]+)@i', '$1utf-8', $output, 1);
        }
    }

    /* 3: <xml> element in the page */
    if (!isset($charset)) {
        preg_match( '@<\?xml.+encoding="([^\s"]+)@si', $output, $matches );
        if ( isset( $matches[1] ) ) {
            $charset = $matches[1];
            // In case we want do do further processing downstream:
            $output = preg_replace('@(<\?xml.+encoding=")([^\s"]+)@si', '$1utf-8', $output, 1);
        }
    }

    /* 4: PHP's heuristic detection */
    if (!isset($charset)) {
        $encoding = mb_detect_encoding($output);
        if ($encoding)
            $charset = $encoding;
    }

    /* 5: Default for HTML */
    if (!isset($charset)) {
        if (strstr($content_type, "text/html") === 0)
            $charset = "ISO 8859-1";
    }

    if (isset($charset) && strtoupper($charset) != "UTF-8") {
        return iconv($charset, 'UTF-8', $output);
    } else {
        return $output;
    }
}

/* Retrieve events from the Memento REST api */
// Note: idk what's the "published" entry, but it would be nicer to be sure
//       to only fetch those one.
function get_events_from_memento($url='https://memento.epfl.ch/api/jahia/mementos/sti/events/en/?format=json', $limit=4)
{
  $data = curl_get($url . '&limit=' . $limit);
  $data = json_decode($data);
  return $data;
}

// To adapt to your school, change from https://actu.epfl.ch/feeds/rss/STI/en/ to e.g. http://actu.epfl.ch/api/jahia/channels/enac/news/fr/?format=json
// 'https://actu.epfl.ch/api/v1/channels/10/news/?format=json&lang='.$lang.'&category=3&faculty=3&themes=4';
function get_news_from_actu($url='https://actu.epfl.ch/api/jahia/channels/sti/news/en/?format=json', $limit=3)
{
  $data = curl_get($url . '&limit=' . $limit);
  $data = json_decode($data);
  return $data->results;
}

// To return the current institute's acronym. Used to load the relevant menu.
// https://regexr.com/3j35i
function get_institute() {
  $url = $_SERVER['REQUEST_URI'];
  $path = parse_url($url, PHP_URL_PATH);
  $re = '/\/institute?s\/([^\/]*)/';
  preg_match_all($re, $path, $matches, PREG_SET_ORDER, 0);
  return $matches[0][1];
}
