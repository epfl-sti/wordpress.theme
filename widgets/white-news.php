<?php

/**
 * News collage as a row on white background for the homepage
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
    die( 'Access denied.' );
}

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;
use function \EPFL\STI\Theme\__x;

require_once(dirname(dirname(__FILE__)) . "/inc/epfl.php");
use function EPFL\STI\curl_get;

class WhiteNews extends \WP_Widget
{
    public function __construct ()
    {
		parent::__construct( 
			'EPFL_STI_Theme_Widget_WhiteNews', // unique id 
			___('EPFL STI White News Row'), // widget title 
			// additional parameters 
			array(
				'description' => ___( 'Shows rows of collages of news on white background' )
			)
        );
    }

    public function form ($config)
    {
        $title_id   = $this->get_field_id  ('title');
        $title_name = $this->get_field_name('title');
        printf("<label for=\"%s\">%s</label>", $title_id,
                   ___('Title:'));
        printf("<input type=\"text\" id=\"$title_id\" name=\"$title_name\" value=\"%s\">", esc_html($config["title"]));
        echo "<br />\n";

        $cssclass_id   = $this->get_field_id  ('cssclass');
        $cssclass_name = $this->get_field_name('cssclass');
        printf("<label for=\"%s\">%s</label>", $cssclass_id,
                   ___('CSS class:'));
        printf("<input type=\"text\" id=\"$cssclass_id\" name=\"$cssclass_name\" value=\"%s\">", esc_html($config["cssclass"]));
        echo "<br />\n";

        // TODO: see other TODO below
        $urls_id   = $this->get_field_id  ('urls');
        $urls_name = $this->get_field_name('urls');
        printf("<label for=\"%s\">%s</label>", $urls_id,
                   ___('URLs to fetch:'));
        printf("<textarea id=\"$urls_id\" name=\"$urls_name\">%s</textarea>",
               $config["urls"]);
        echo "<br />\n";
    }

	public function update( $new_config, $old_config ) {
        $config = $old_config;
        $config["title"]    = $new_config["title"];
        $config["cssclass"] = $new_config["cssclass"];
        $config["urls"]     = $new_config["urls"];
        return $config;
    }

    public function widget ($args, $config)
    {
?>
<center>
 <div class='secondaryrow whitebg'>
  <div class=secondarytitle><?php echo esc_html(__x($config["title"], "white-news" )); ?></div>
   <?php
        // TODO: This is a poor excuse for a proper news feed.
        foreach (explode("\n",$config["urls"]) as $url) {
            $url = trim($url);
            if (! $url) continue;
            echo str_replace("educationbg", $config["cssclass"], curl_get($url));
        }
   ?>
 </div>
</center>
<?php
    }  // public function widget
}  // class WhiteNews

register_widget(WhiteNews::class);
