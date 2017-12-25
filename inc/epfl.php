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
function get_news_from_actu($url='https://actu.epfl.ch/api/jahia/channels/sti/news/en/?format=json', $limit=4)
{
  $data = curl_get($url . '&limit=' . $limit);
  $data = json_decode($data);
  return $data;
}

// To return the current institute's acronym. Used to load the relevant menu.
function get_institute() {
  $url = get_permalink();
  $path = parse_url($url, PHP_URL_PATH);
  switch ($path) {
    case '/fr/recherche/ibi/':
    case '/research/ibi/':
    case '/sti/research/ibi/':
    case '/fr/sti/research/ibi/':
      return 'ibi';
      break;
    case '/fr/recherche/iel/':
    case '/research/iel/':
    case '/sti/research/iel/':
    case '/fr/sti/research/iel/':
      return 'iel';
      break;
    case '/fr/recherche/imx/':
    case '/research/imx/':
    case '/sti/research/imx/':
    case '/fr/sti/research/imx/':
      return 'imx';
      break;
    case '/fr/recherche/igm/':
    case '/research/igm/':
    case '/sti/research/igm/':
    case '/fr/sti/research/igm/':
      return 'igm';
      break;
    case '/fr/recherche/imt/':
    case '/research/imt/':
    case '/sti/research/imt/':
    case '/fr/sti/research/imt/':
      return 'imt';
      break;
    default:
      break;
  }
}
