<?php

/**
 * Models, controllers, hooks and crooks for data served out of stisrv13.epfl.ch
 */

namespace EPFL\STI;

if (! defined('ABSPATH')) {
    die('Access denied.');
}

require_once(__DIR__ . "/i18n.php");
use function \EPFL\STI\Theme\___;

// TODO: This should be refactored into a post-scrape hook
function _stisrv13_metadata ($person_obj) {
    if (! $person_obj->_stisrv13_metadata) {
        $incoming_json = @file_get_contents('https://stisrv13.epfl.ch/cgi-bin/whoop/peoplepage.pl?sciper=' . $person_obj->get_sciper());
        if ($incoming_json) {
            $person_obj->_stisrv13_metadata = json_decode($incoming_json);
        }
    }

    return $person_obj->_stisrv13_metadata;
}

add_filter('epfl_person_bio', function($biography, $person_obj) {
    if ($biography) { return $biography; }
    $s13 = _stisrv13_metadata($person_obj);
    if ($s13 && $s13->bio) { return $s13->bio; }
    return $biography;  // I.e. nothing
}, 10, 3);

class Stisrv13AdminMenu
{
    const SLUG = 'theme-epfl-sti-stisrv13-menu';
    const CAPABILITY = 'manage_options';

    static function hook ()
    {
        add_action('admin_menu', array(get_called_class(), "setup_admin_menu"));
    }

    static function setup_admin_menu ()
    {
        add_menu_page(
            /* $page_title     = */ ___("stisrv13 News"),  // Like the first menu entry
            /* $menu_title     = */ ___('stisrv13 Data'),
            /* $capability     = */ self::CAPABILITY,
            /* $menu_slug      = */ self::SLUG,
            /* $render_func    = */ '',  // There is no main page, instead defer
                                         // to first submenu page (see below)
            /* $icon_url       = */ 'dashicons-tickets',
            /* $position       = */ 51
        );
        self::add_submenu_page(
            self::SLUG,  // Default menu entry
            ___("News"),
            ___("stisrv13 News"),
            array(get_called_class(), "render_stisrv13_news_page"));
    }

    static function add_submenu_page ($menu_slug, $page_title,
                                      $menu_title, $callable = '')
    {
        add_submenu_page(
            self::SLUG,
            $page_title, $menu_title,
            self::CAPABILITY,
            $menu_slug, $callable);
    }

    static private function render_hal ()
    {
        echo "<img src=\"https://stisrv13.epfl.ch/img/hal9000.png\">";
    }

    static function render_stisrv13_news_page ()
    {
            	?>
	<div class="wrap">
            <?php static::render_hal(); ?>
            <h2>stisrv13 at your service</h2>
<p>
	<label for="upload-news-csv">
		Upload CSV file:
	</label>
	<input type="file" id="upload-news-csv" name="upload-news-csv" value="" />
	<?php static::render_nonce("upload-news-csv"); ?>
</p>
	</div>
<?php
    }

    static function render_nonce ($purpose_slug)
    {
         wp_nonce_field($purpose_slug, self::SLUG . "_nonce");
    }

    static function check_nonce ($purpose_slug, $value = null)
    {
        if ($value === null) {
            $value = $_REQUEST[self::SLUG . "_nonce"];
        }
        if ( ! wp_verify_nonce($value, $purpose_slug)) {
            die( '♝ Your nonce is excommunicated ♝' );
        }
    }
}

function get_stisrv13_person ($sciper)
{
    return curl_get_json("https://stisrv13.epfl.ch/cgi-bin/whoop/peoplepage.pl?sciper=$sciper");
}

/**
 * Scrape additional metadata out of stisrv13
 */
add_filter("epfl_person_additional_meta", function ($more_meta, $person) {
    $incoming = get_stisrv13_person($person->get_sciper());
    if (! $incoming->position) return;

    if (! $person->get_bio()) {
        $person->set_bio($incoming->bio);
    }

    if ($incoming->phone) {  $person->set_phone($incoming->phone); }
    if ($incoming->office) { $person->set_room($incoming->office); }

    $more_meta["stisrv13_id"] = $incoming->id;

    $more_meta["stisrv13_news_json"] = json_encode($incoming->news);
    $more_meta["stisrv13_news_rssids_json"] = json_encode(array_map(
        function($news) { return 0 + $news->rssid; },
        array_values($incoming->news)));
    $more_meta["stisrv13_data_json"] = json_encode($incoming);

    return $more_meta;
}, 10, 2);

/**
 * Scrape images for labs out of stisrv13
 */
add_filter("epfl_lab_additional_meta", function ($more_meta, $lab) {
    if (has_post_thumbnail($lab->wp_post())) { return; }
    $mgr = $lab->get_lab_manager();
    if (! $mgr) return;

    $incoming = get_stisrv13_person($mgr->get_sciper());
    $id = $incoming->id;
    if (! $id) return;

    download_featured_image(
        $lab->wp_post(),
        "https://stisrv13.epfl.ch/brochure/img/$id/research.png",
        array("basename" => $incoming->labname . ".png")
    );
    return $more_meta;
}, 10, 2);


Stisrv13AdminMenu::hook();
