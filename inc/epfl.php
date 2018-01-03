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

function curl_get($url)
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

function get_actu_link($title) {
  $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                              'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                              'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                              'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                              'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
  $title = strtr( $title, $unwanted_array );
  $title = str_replace(" ", "-", $title);
  $title = str_replace("'", "-", $title);
  $title = strtolower($title);
  $title = substr($title, 0, 50);
  return 'https://actu.epfl.ch/news/'. $title;
}
// To return the current institute's acronym. Used to load the relevant menu.
function get_institute() {
  $url = get_permalink();
  $path = parse_url($url, PHP_URL_PATH);
  switch ($path) {
    case '/fr/instituts/ibi/':
    case '/institutes/ibi/':
    case '/sti/institutes/ibi/':
    case '/sti/fr/instituts/ibi/':
      return 'ibi';
      break;
    case '/fr/instituts/iel/':
    case '/institutes/iel/':
    case '/sti/institutes/iel/':
    case '/sti/fr/instituts/iel/':
      return 'iel';
      break;
    case '/fr/instituts/imx/':
    case '/institutes/imx/':
    case '/sti/institutes/imx/':
    case '/sti/fr/instituts/imx/':
      return 'imx';
      break;
    case '/fr/instituts/igm/':
    case '/institutes/igm/':
    case '/sti/institutes/igm/':
    case '/sti/fr/instituts/igm/':
      return 'igm';
      break;
    case '/fr/instituts/imt/':
    case '/institutes/imt/':
    case '/sti/institutes/imt/':
    case '/sti/fr/instituts/imt/':
      return 'imt';
      break;
    default:
      break;
  }
}
