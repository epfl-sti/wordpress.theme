<?php
/**
 * EPFL-specific functions
 */

function curl_get( $url ) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);       
  curl_close($ch);
  return $output;
}

function html_body( $html ) {
    $d = new DOMDocument;
    $d->loadHTML($html);
    $body = $d->getElementsByTagName('body')->item(0);
    $ret = "";
    foreach ($body->childNodes as $childNode) {
        $ret .= $d->saveHTML($childNode);
    }
    return $ret;
}

?>
