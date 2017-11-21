<?php
/**
 * EPFL-specific functions
 */

namespace EPFL\STI;

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

/* Fix https://github.com/epfl-sti/wordpress.theme/issues/9 */
// Change from https://actu.epfl.ch/feeds/rss/STI/en/ to http://actu.epfl.ch/api/jahia/channels/enac/news/fr/?format=json
function get_news_from_actu($url='https://actu.epfl.ch/api/jahia/channels/sti/news/en/?format=json', $limit=4)
{
  $data = curl_get($url . '&limit=' . $limit);
  $data = json_decode($data);
  return $data;
}
