<?php
/**
 * Understrap modify editor
 *
 * @package epflsti
 */

// Add TinyMCE style formats.
add_filter( 'mce_buttons_2', 'epflsti_tiny_mce_style_formats' );

function epflsti_tiny_mce_style_formats( $styles ) {

    array_unshift( $styles, 'styleselect' );
    return $styles;
}

add_filter( 'tiny_mce_before_init', 'epflsti_tiny_mce_before_init' );

function epflsti_tiny_mce_before_init( $settings ) {

  $style_formats = array(
      array(
          'title' => 'Lead Paragraph',
          'selector' => 'p',
          'classes' => 'lead',
          'wrapper' => true
          ),
      array(
          'title' => 'Small',
          'inline' => 'small'
      ),
      array(
          'title' => 'Blockquote',
          'block' => 'blockquote',
          'classes' => 'blockquote',
          'wrapper' => true
      ),
			array(
          'title' => 'Blockquote Footer',
          'block' => 'footer',
          'classes' => 'blockquote-footer',
          'wrapper' => true
      ),
			array(
          'title' => 'Cite',
          'inline' => 'cite'
      )
  );
  
    if ( isset( $settings['style_formats'] ) ) {
      $orig_style_formats = json_decode($settings['style_formats'],true);
      $style_formats = array_merge($orig_style_formats,$style_formats);
    }

    $settings['style_formats'] = json_encode( $style_formats );
    return $settings;
}
