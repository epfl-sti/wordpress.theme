<?php
/**
 * This include the style for Max Mega Menu.
 *
 * @package epflsti
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function megamenu_add_theme_epfl_sti_mega_menu_1516816083($themes) {
  $themes["epfl_sti_mega_menu_1516816083"] = array(
    'title' => 'EPFL STI Mega Menu',
    'container_background_from' => 'rgb(255, 255, 255)',
    'container_background_to' => 'rgb(255, 255, 255)',
    'menu_item_align' => 'center',
    'menu_item_background_hover_from' => 'rgb(136, 136, 136)',
    'menu_item_background_hover_to' => 'rgb(136, 136, 136)',
    'menu_item_link_font' => 'Comic Sans MS, cursive, sans-serif',
    'menu_item_link_color' => 'rgb(74, 201, 70)',
    'menu_item_highlight_current' => 'off',
    'panel_header_border_color' => '#555',
    'panel_font_size' => '14px',
    'panel_font_color' => '#666',
    'panel_font_family' => 'inherit',
    'panel_second_level_font_color' => '#555',
    'panel_second_level_font_color_hover' => '#555',
    'panel_second_level_text_transform' => 'uppercase',
    'panel_second_level_font' => 'inherit',
    'panel_second_level_font_size' => '16px',
    'panel_second_level_font_weight' => 'bold',
    'panel_second_level_font_weight_hover' => 'bold',
    'panel_second_level_text_decoration' => 'none',
    'panel_second_level_text_decoration_hover' => 'none',
    'panel_second_level_border_color' => '#555',
    'panel_third_level_font_color' => '#666',
    'panel_third_level_font_color_hover' => '#666',
    'panel_third_level_font' => 'inherit',
    'panel_third_level_font_size' => '14px',
    'flyout_link_size' => '14px',
    'flyout_link_color' => '#666',
    'flyout_link_color_hover' => '#666',
    'flyout_link_family' => 'inherit',
    'line_height' => 'inherit',
    'z_index' => '10',
    'toggle_background_from' => '#222',
    'toggle_background_to' => '#222',
    'toggle_font_color' => 'rgb(74, 201, 70)',
    'mobile_background_from' => '#222',
    'mobile_background_to' => '#222',
    'mobile_menu_item_link_font_size' => '14px',
    'mobile_menu_item_link_color' => '#ffffff',
    'mobile_menu_item_link_text_align' => 'left',
    'custom_css' => '/** Push menu onto new line **/
      megamenu #{$wrap} {
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
add_filter("megamenu_themes", "megamenu_add_theme_epfl_sti_mega_menu_1516816083");
