<?php
/**
 * Declaring widgets
 *
 * @package epflsti
 */

require_once(dirname(__FILE__).'/i18n.php');
use function \EPFL\STI\Theme\___;
use function \EPFL\STI\Theme\__x;

/**
 * Count number of widgets in a sidebar
 * Used to add classes to widget areas so widgets can be displayed one, two, three or four per row
 */
if (! function_exists('slbd_count_widgets')) {
  function slbd_count_widgets($sidebar_id)
  {
    // If loading from front page, consult $_wp_sidebars_widgets rather than options
    // to see if wp_convert_widget_settings() has made manipulations in memory.
    global $_wp_sidebars_widgets;
    if (empty($_wp_sidebars_widgets)) :
      $_wp_sidebars_widgets = get_option('sidebars_widgets', array());
    endif;

    $sidebars_widgets_count = $_wp_sidebars_widgets;

    if (isset($sidebars_widgets_count[ $sidebar_id ])) :
      $widget_count = count($sidebars_widgets_count[ $sidebar_id ]);
    $widget_classes = 'widget-count-' . count($sidebars_widgets_count[ $sidebar_id ]);
    if ($widget_count % 4 == 0 || $widget_count > 6) :
      // Four widgets per row if there are exactly four or more than six
      $widget_classes .= ' col-md-3'; elseif (6 == $widget_count) :
      // If two widgets are published
      $widget_classes .= ' col-md-2'; elseif ($widget_count >= 3) :
      // Three widgets per row if there's three or more widgets
      $widget_classes .= ' col-md-4'; elseif (2 == $widget_count) :
      // If two widgets are published
      $widget_classes .= ' col-md-6'; elseif (1 == $widget_count) :
      // If just on widget is active
      $widget_classes .= ' col-md-12';
    endif;
      return $widget_classes;
    endif;
  }
}

add_action('widgets_init', function () {
  /**
   * Initializes themes widgets.
   */
  register_sidebar(
    array(
      'name'          => ___('Home page'),
      'id'            => 'homepage',
      'description'   => 'Widget area shown on the home page',
      'before_widget' => '<div class="homepage-widgets">',
      'after_widget'  => '</div>',
      'before_title'  => '',
      'after_title'   => '',
    )
  );

  foreach (["iel", "igm", "ibi", "imt", "imx"] as $institute_code) {
      $institute_name = strtoupper($institute_code);
      register_sidebar(
        array(
          'name'          => sprintf(
              __x('%s home page', 'Institute home page'),
              $institute_name
          ),
          'id'            => "${institute_code}-homepage",
          'description'   => sprintf(___('Widget area shown on the %s home page'), $institute_name),
          'before_widget' => "<div class=\"container institute-homepage-widget $institute_code\">",
          'after_widget'  => '</div>',
          'before_title'  => '',
          'after_title'   => '',
      )
    );
  }

  register_sidebar(
    array(
      'name'          => __('Right Sidebar', 'epflsti'),
      'id'            => 'right-sidebar',
      'description'   => 'Right sidebar widget area',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<h3 class="widget-title">',
      'after_title'   => '</h3>',
    )
  );

  register_sidebar(
    array(
      'name'          => __('Left Sidebar', 'epflsti'),
      'id'            => 'left-sidebar',
      'description'   => 'Left sidebar widget area',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<h3 class="widget-title">',
      'after_title'   => '</h3>',
    )
  );

  register_sidebar(
    array(
      'name'          => __('Footer Left', 'epflsti'),
      'id'            => 'footer-left',
      'description'   => 'Widget area at the left of the footer',
      'before_widget' => '<div id="%1$s" class="">',
      'after_widget'  => '</div><!-- .footer-left -->',
      'before_title'  => '<h3 class="widget-title">',
      'after_title'   => '</h3>',
    )
  );

  register_sidebar(
    array(
      'name'          => __('Footer Middle', 'epflsti'),
      'id'            => 'footer-middle',
      'description'   => 'Widget area in the middle of the footer',
      'before_widget' => '<div id="%1$s" class="">',
      'after_widget'  => '</div><!-- .footer-middle -->',
      'before_title'  => '<h3 class="widget-title">',
      'after_title'   => '</h3>',
    )
  );

  register_sidebar(
    array(
      'name'          => __('Footer Right', 'epflsti'),
      'id'            => 'footer-right',
      'description'   => 'Widget area at the right of the footer',
      'before_widget'  => '<div id="%1$s" class="">',
      'after_widget'   => '</div><!-- .footer-right -->',
      'before_title'   => '<h3 class="widget-title">',
      'after_title'    => '</h3>',
    )
  );

  $widgets_dir = dirname(dirname(__FILE__)) . "/widgets";
  foreach (scandir($widgets_dir) as $widget_file) {
    if (preg_match('/^[^.].*\.php$/', $widget_file)) {
        require_once("$widgets_dir/$widget_file");
    }
  }
});  // add_action('widgets_init')
