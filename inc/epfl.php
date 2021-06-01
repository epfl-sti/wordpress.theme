<?php
/**
 * EPFL-specific functions
 */

namespace EPFL\STI;

require_once(__DIR__ . "/i18n.php");
use function \EPFL\STI\Theme\___;

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

function curl_get_json ($url) {
  return json_decode(curl_get($url));
}

/* Retrieve events from the Memento REST api */
// Note: idk what's the "published" entry, but it would be nicer to be sure
//       to only fetch those one.
function get_events_from_memento($url='https://memento.epfl.ch/api/jahia/mementos/sti/events/en/?format=json', $limit=4)
{
    return curl_get_json($url . '&limit=' . $limit);
}

// To adapt to your school, change from https://actu.epfl.ch/feeds/rss/STI/en/ to e.g. http://actu.epfl.ch/api/jahia/channels/enac/news/fr/?format=json
// 'https://actu.epfl.ch/api/v1/channels/10/news/?format=json&lang='.$lang.'&category=3&faculty=3&themes=4';
function get_news_from_actu($url='https://actu.epfl.ch/api/jahia/channels/sti/news/en/?format=json', $limit=3)
{
    return curl_get_json($url . '&limit=' . $limit)->results;
}

// To return the current institute's acronym. Used to load the relevant menu.
// https://regexr.com/3j35i
function get_institute ()
{
  if ( is_admin() ) { return; }

  $url = $_SERVER['REQUEST_URI'];
  $path = parse_url($url, PHP_URL_PATH);
  $re = '/\/institute?s\/([^\/]*)/';
  preg_match_all($re, $path, $matches, PREG_SET_ORDER, 0);
  return isset($matches[0][1]) ? new Institute($matches[0][1]) : null;
}

class Institute {
    function __construct ($institute_code)
    {
        $this->_code = $institute_code;
    }

    function get_code ()
    {
        return $this->_code;
    }

    function get_name_parts ()
    {
        $all_names = array(
            # Note to translators: the underscore indicates where to split
            # when displaying in a multiline setting (e.g. in the page
            # header)
            "igm" => ___("Mechanical _Engineering"),
            "ibi" => ___("Bio_engineering"),
            "imt" => ___("Electrical and Micro _Engineering"),
	    "imx" => ___("Materials _"), // They now want only one word. kept a trailing underscore to make sure that splitting functions do not break
	    "iel" => ___("Electrical and Micro _Engineering"),
            "iem" =>  ___("Electrical and Micro _Engineering")
        );
        return split_on_underscore($all_names[$this->_code]);
    }
}

function get_school_name_parts ()
{
    # Note to translators: the underscore indicates where to split
    # when displaying in a multiline setting (e.g. in the page
    # header)
    return split_on_underscore(___("School of _Engineering"));
}

function split_on_underscore ($name)
{
    $matched = array();
    if (preg_match('@^(.*?)_(.*)$@', $name, $matched)) {
        return array($matched[1], $matched[2]);
    } else {
        throw new \Error("Cannot find name parts in $name");
    }
}


class DownloadError extends \Exception
{
    function __construct($message) {
        parent::__construct("$message - " . var_export(error_get_last(), true));
    }
}

function download_featured_image($wp_post, $image_url, $opts)
{
    // Inspired from https://wordpress.stackexchange.com/a/41300/132235
    $image_bytes = file_get_contents($image_url);
    if (! $image_bytes) {
        throw new DownloadError("Unable to download $image_url");
    }

    $upload_dir = wp_upload_dir();
    $basename = $opts["basename"];
    $target_path = $upload_dir['path'] . '/epfl-sti-auto/';
    if(wp_mkdir_p($target_path))  {
        $file = $target_path . '/' . $basename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $basename;
    }
    if (false === file_put_contents($file, $image_bytes)) {
        @unlink($file);
        throw new DownloadError("Unable to create $file from $url");
    }

    $wp_filetype = wp_check_filetype($basename, null);
    if (! $wp_filetype['type']) {
        @unlink($file);
        throw new DownloadError("Cannot guess file type of $basename");
    }
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($basename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $post_id = $wp_post->ID;
    $attach_id = wp_insert_attachment($attachment, $file, $post_id);
    if (! $attach_id) {
        @unlink($file);
        throw new DownloadError("wp_insert_attachment failed for $image_url -> $file");
    }
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    if (! ($attach_data && $attach_data["file"])) {
        @unlink($file);
        throw new DownloadError("wp_generate_attachment_metadata failed for $image_url -> $file -> $attach_id");
    }
    wp_update_attachment_metadata($attach_id, $attach_data);  // Can return FALSE if no change
    if (! set_post_thumbnail($post_id, $attach_id)) {
        @unlink($file);
        throw new DownloadError("set_post_thumbnail failed for $image_url -> $file -> $attach_id");
    }
}
