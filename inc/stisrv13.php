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
use function \EPFL\STI\Theme\__x;

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
            <?php Stisrv13UploadArticlesController::render_form() ?>
	</div>
<?php
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

class Stisrv13UploadArticlesController
{
    const SLUG = "stisrv13_upload_articles";

    function hook ()
    {
        $slug = self::SLUG;
        add_action("admin_post_$slug",
                   array(get_called_class(), "handle_upload_json"));
    }

    function render_form ()
    {
        $slug = self::SLUG;
        ?>
            <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">
             <input type="hidden" name="action" value="<?php echo Stisrv13UploadArticlesController::SLUG; ?>">
	<label for="upload-news-json">
		Upload JSON file:
	</label>
	<input type="file" id="upload-news-json" name="<?php echo $slug ?>" value="" />
	<?php static::render_nonce(); ?>
        <button type="submit"><?php echo ___("Submit", "stisrv13 JSON"); ?></button>
        </form>
        <?php
    }

    function handle_upload_json ()
    {
        static::check_nonce();
        $articles = json_decode(file_get_contents($_FILES[self::SLUG]['tmp_name']));
        echo count($articles) . " articles in JSON";  // XXX
    }

    static function render_nonce ()
    {
         wp_nonce_field(self::SLUG, self::SLUG . "_nonce");
    }

    static function check_nonce ($value = null)
    {
        if ($value === null) {
            $value = $_REQUEST[self::SLUG . "_nonce"];
        }
        if ( ! wp_verify_nonce($value, self::SLUG)) {
            die( '♝ Your nonce is excommunicated ($value) ♝' );
        }
    }
}

Stisrv13UploadArticlesController::hook();
