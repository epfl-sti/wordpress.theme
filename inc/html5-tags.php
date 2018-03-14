<?php
/**
 * Tweak the list of allowed tags to accept some of the ones mentioned
 * in ../sass/theme/_cards.scss
 *
 * This lets power users (the ones that can use the "Text" view of
 * TinyMCE) put cards in their pages.
 *
 * Warning: in order to prevent Wordpress' @link wpautop from doing
 * silly things to the block elements that it doesn't understand, it
 * is recommended to install and use the "Toggle wpautop" plugin.
 *
 * @package epflsti
 */
add_filter('init', function() {
    global $allowedposttags;
    $allowedposttags['card'] = array();
    $allowedposttags['header'] = array();
});

add_filter('tiny_mce_before_init', function($config) {
    $config['extended_valid_elements'] = 'card[id|class],header[id|class]';
    return $config;
});
