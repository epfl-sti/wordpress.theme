<?php
/**
 * This include the style for Max Mega Menu.
 *
 * @package epflsti
 */

namespace EPFL\STI;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function megamenu_setup_theme ($themes) {
    error_log("Setting up megamenu theme");
  $themes["epfl_sti_mega_menu"] = array(
    'title' => 'EPFL STI Mega Menu',
    'menu_item_align' => 'center',
    'menu_item_highlight_current' => 'off',
    'z_index' => '10',
    'panel_second_level_text_transform' => 'uppercase',
    'mobile_menu_item_link_text_align' => 'left',

    /* Cut back on the plug-in's micromanagement of dimensions, fonts
     * and colors: */
    'panel_second_level_font'                  => 'inherit',
    'panel_second_level_font_size'             => 'inherit',
    'panel_second_level_font_weight'           => 'inherit',
    'panel_second_level_font_weight_hover'     => 'inherit',
    'panel_second_level_text_decoration'       => 'inherit',
    'panel_second_level_text_decoration_hover' => 'inherit',
    'toggle_background_from'                   => 'inherit',
    'toggle_background_to'                     => 'inherit',
    'mobile_menu_item_link_font_size'          => 'inherit',
    'mobile_menu_item_link_color'              => 'inherit',
    'panel_third_level_font'                   => 'inherit',
    'flyout_link_family'                       => 'inherit',
    'flyout_link_size'                         => 'inherit',
    'line_height'                              => 'inherit',
    'panel_third_level_font_size'              => 'inherit',

    /* We can safely apply the same treatment to colors, except if
     * they are part of a gradient: */
    'toggle_font_color'                        => 'inherit',
    'panel_second_level_border_color'          => 'inherit',
    'panel_third_level_font_color'             => 'inherit',
    'panel_third_level_font_color_hover'       => 'inherit',
    'flyout_link_color'                        => 'inherit',
    'flyout_link_color_hover'                  => 'inherit',

    /* Colors that we want to set: */
    'container_background_from' => 'white',
    'container_background_to' => 'white',
    'mobile_background_from' => '#222',
    'mobile_background_to' => '#222',
    'menu_item_background_hover_from' => 'rgb(238, 238, 238)',
    'menu_item_background_hover_to' => 'rgb(238, 238, 238)',
    'menu_item_link_color' => 'rgb(74, 201, 70)',


    'custom_css' => '
      megamenu #{$wrap} {
        /** Push menu onto new line **/
        clear: both;
        #{$menu} > li.mega-menu-item {
          > a.mega-menu-link {
            color: black;
            font: 600 13pt \"Open Sans Regular\", sans-serif;
            background-image: url(/wp-content/themes/epfl-sti/img/src/sideshadow.gif);
            background-repeat: no-repeat;
            background-position-x: -10px;
            height: 20pt;
            text-transform: uppercase;
            &:after {
              vertical-align: baseline;
            }
          }
        }
      }',
  );
  return $themes;
}
add_filter("megamenu_themes", "\\EPFL\\STI\\megamenu_setup_theme");
