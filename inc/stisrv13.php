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

use \Exception;
use \Error;

use \WP_Query;
use \WP_User_Query;

/* The following creates unwanted coupling with the EPFL-WS plugin. It is also
 * temporary, as the entire process of importing stisrv13 articles becomes dead
 * code as soon as it has succeeded once. I'm lazy that way.
 */
require_once(__DIR__ . "/../../../plugins/epfl-ws/inc/base-classes.inc");
use \EPFL\WS\Base\Post;


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

    static function get_upload_page_url ()
    {
        return admin_url('admin.php?page=' . self::SLUG);
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
        add_action('admin_notices',
                   array(get_called_class(), '_render_admin_errors'));
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
        $filename = $_FILES[self::SLUG]['tmp_name'];
        if (! $filename) return;

        set_time_limit(600);
        static::check_nonce();

        $payload = json_decode(file_get_contents($filename));

        foreach ($payload->articles as $article) {
            Stisrv13Article::sync($article);
        }
        if (Stisrv13Article::$missing_categories) {
            foreach (Stisrv13Article::$missing_categories as $category_slug) {
                static::admin_error(sprintf(___("Category not found (by slug): %s, ignored"), $category_slug));
            }
        }
        wp_redirect(Stisrv13AdminMenu::get_upload_page_url());
    }

    static function admin_error ($text)
    {
        $key = static::_get_error_transient_key();
        $error = get_transient($key);
        if (! $error) {
            $error = $text;
        } else {
            $error = $error . "<br/>" . $text;
        }
        set_transient($key, $error, 45);  // Seconds before it self-destructs
    }

    static function _get_error_transient_key ()
    {
        return "stisrv13-import-errors-" . wp_get_current_user()->user_login;
    }

    static function _render_admin_errors ()
    {
        $key = static::_get_error_transient_key();
        if ($error = get_transient($key)) {
            delete_transient($key);
            ?>
            <div class="notice notice-error is-dismissible">
            <p><?php echo $error; ?></p>
            </div>
            <?php
        }
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

/**
 * Model class for articles
 */
class DuplicateStisrv13ArticleException extends Exception {}
class Stisrv13ImportError extends Exception {}

class Stisrv13Article extends Post
{
    function _belongs ()
    {
        return $this->get_rss_id() !== null;
    }

    const RSS_ID_META = "stisrv13_rss_id";

    static function sync ($json)
    {
        $rss_id = $json->rss_id;
        $lang   = $json->lang;
        if (! $rss_id) {
            throw new Exception("No rss_id! ($rss_id)");
        }
        
        $search_query = new WP_Query(array(
           'post_type'  => 'post',
           'lang'       => $lang,
           'meta_query' => array(array(
               'key'     => self::RSS_ID_META,
               'value'   => $rss_id,
               'compare' => '='
            ))));
        $results = $search_query->get_posts();
        if (sizeof($results) > 1) {
            throw new DuplicateStisrv13ArticleException(
                "Found " . sizeof($results) . " results for RSS ID $rss_id and language $lang");
        } elseif (sizeof($results) == 1) {
            $that = static::get($results[0]);
        } else {
            $id_or_error = wp_insert_post(
                array(
                    "post_type"    => "post",
                    'post_title'   => $json->title,  # Get the slug right the first time
                    'post_content' => $json->body,   # Still, some articles have no title
                    'post_status' => 'publish',
                    'meta_input'   => array(
                        self::RSS_ID_META => $rss_id
                    )),
                /* $wp_error = */ true);
            if (is_wp_error($id_or_error)) {
                $error = $id_or_error;
                throw new Error("Unable to create new post for (rss_id=$rss_id, lang=$lang): " . $error->get_error_message());
            } else {
                $that = static::get($id_or_error);
                pll_set_post_language($that->ID, $lang);
            }
        }

        $that->_update($json);
        return $that;
    }

    function get_rss_id ()
    {
        return get_post_meta($this->ID, self::RSS_ID_META, true);
    }

    function get_language ()
    {
        return pll_get_post_language($this->ID);
    }

    static $missing_categories;
    function _update ($json)
    {
        $update_data = array(
            'ID'            => $this->ID,
            'post_title'    => $json->title,
            'post_content'  => $json->body,
            'post_date'     => $this->_mysql_time($json->pubdate),
            'post_date_gmt' => $this->_mysql_time($json->pubdate, true),
        );

        if ($author_login = $json->webmaster_author) {
            $user_query = new WP_User_Query(array('user_login' => $ $author_login));
            $authors = $user_query->get_results();
            if (count($authors) === 1) {
                $update_data['post_author'] = $authors->ID;
            }
        }

        wp_update_post($update_data);

        $categories = array();
        if ($json->categories) {
            foreach ($json->categories as $category_slug) {
                $category = get_category_by_slug($category_slug);
                if ($category) {
                    array_push($categories, $category->term_id);
                } else {
                    if (! self::$missing_categories) self::$missing_categories = array();
                    if (false === array_search($category_slug, self::$missing_categories)) {
                        error_log("Unknown category: $category_slug");
                        array_push(self::$missing_categories, $category_slug);
                    }
                }
            }
        }
        wp_set_post_categories($this->ID, $categories, false);
    }

    /**
     * Like `current_time('mysql')`, except not on the current time
     */
    function _mysql_time ($timestamp, $is_gmt = false)
    {
        if ($is_gmt) {
            $timestamp += get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
        }
        return gmdate('Y-m-d H:i:s', $timestamp);
    }
}


Stisrv13UploadArticlesController::hook();
