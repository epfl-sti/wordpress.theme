<?php
/**
 * EPFL-specific functions
 */

function curl_get($url)
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);
  return $output;
}

function html_body($html)
{
  $d = new DOMDocument;
  $d->loadHTML($html);
  $body = $d->getElementsByTagName('body')->item(0);
  $ret = "";
  foreach ($body->childNodes as $childNode) {
    $ret .= $d->saveHTML($childNode);
  }
  return $ret;
}

/* Retrieve events from the Memento REST api */
// Note: idk what's the "published" entry, but it would be nicer to be sure
//       to only fetch those one.
function get_memento_events($url='https://memento.epfl.ch/api/jahia/mementos/sti/events/en/?format=json', $limit=4)
{
  $data = curl_get($url . '&limit=' . $limit);
  $data = json_decode($data);
  return $data;
}
