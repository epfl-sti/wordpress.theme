<?php
/**
 * This include the style for Max Mega Menu.
 *
 * @package epflsti
 */

/* Development tip: when making improvements here, open any page in
 * your browser whose PHP code somehow calls wp_nav_menu(); then,
 * visit the wp-admin area in another browser tab to verify that the
 * corresponding menu (designated by "theme_location", "slug" etc.
 * from the wp_nav_menu() invoking code) is configured to use Mega
 * Menu with the "EPFL STI Mega Menu" theme. From then on, every time
 * you edit this file you must go to the wo admin-area again to force
 * the Max Mega Menu plug-in to regenerate the CSS: Mega Menu (right
 * bar) -> Tools -> Clear CSS Cache. Finally, you will need to reload
 * the front-end page by hand - in that situation browser-sync is not
 * helpful, because it will reload too soon (before the CSS cache has
 * been regenerated) */

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
      /* We make sure to respect the same selectors used by
       * megamenu.scss, except with a leading "megamenu" so
       * that we win according to the CSS priority rules. */
      megamenu #{$wrap} {
        /** Push menu onto new line **/
        clear: both;

        #{$menu} {
          /* We don\'t want a teramenu: */
          max-width: 800px;
          /* Keep it centered (despite not occupying the full width): */
          margin: 0 auto;

          > li.mega-menu-item {
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
        }
      }',
  );
  return $themes;
}
add_filter("megamenu_themes", "\\EPFL\\STI\\megamenu_setup_theme");
