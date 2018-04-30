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

function debug ($msg) {
    // error_log($msg);
}

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
            <a href="<?php echo Stisrv13PageTableJSON::get_action_uri(); ?>"><?php echo ___("Imported articles permalink table (JSON)"); ?></a>
	</div>
<?php
    }

    static function get_upload_page_url ()
    {
        return admin_url('admin.php?page=' . self::SLUG);
    }
}

abstract class JSONDownloadHelperBase
{
    static function hook ()
    {
        // See bottom of /wp-admin/admin.php
        add_action('admin_action_' . static::action_slug(),
                   array(get_called_class(), "handle_GET"));
    }
    static function action_slug ()
    {
        return Stisrv13AdminMenu::SLUG . "-json-" . static::SLUG;
    }

    const DEFAULT_FILENAME = null;  // For overriding by subclasses

    /**
     * @return e.g. "/wp-admin/admin.php/whatever.json?action=theme-epfl-sti-stisrv13-menu-csv-page-table"
     */
    static function get_action_uri ($desired_filename)
    {
        if ((! $desired_filename) && static::DEFAULT_FILENAME) {
            $desired_filename = static::DEFAULT_FILENAME;
        }
        if ($desired_filename && substr( $string_n, 0, 1 ) != "/") {
            $desired_filename = "/$desired_filename";
        }
        return "/wp-admin/admin.php$desired_filename?action=" . static::action_slug();
    }

    static function handle_GET ()
    {
        http_response_code(200);
        header("Content-Type: text/json");
        print json_encode(static::get_payload());
        die();
    }

    static abstract function get_payload();
}

/**
 * Export the mapping from (import ID, language) pairs to permalinks as JSON.
 *
 * This mapping is a necessary input for a third-party Perl / whatever
 * script to construct a redirect table, e.g. for the Redirection
 * plugin.
 */
class Stisrv13PageTableJSON extends JSONDownloadHelperBase
{
    const SLUG = "page-table";

    const DEFAULT_FILENAME = "imported-permalinks.json";

    static function get_payload ()
    {
        $payload = array();
        $q = new WP_Query(array(
            "post_type"      => "post",
            "posts_per_page" => -1));
        while ($q->have_posts()) {
            $q->the_post();
            $post = $q->post;
            $import_id = get_post_meta($post->ID, Stisrv13Base::IMPORT_ID_META, true);
            if (! $import_id) continue;

            $lang = get_post_meta($post->ID, "language", true);

            array_push($payload, array(
                'import_id' => $import_id,
                'lang'      => $lang,
                'permalink' => get_the_permalink($post),
                'title'     => get_the_title($post)));
        }
        return $payload;
    }
}

Stisrv13PageTableJSON::hook();

function get_stisrv13_person ($sciper)
{
    return curl_get_json("https://stisrv13.epfl.ch/cgi-bin/whoop/peoplepage.pl?sciper=$sciper");
}

/**
 * Scrape additional metadata out of stisrv13
 */
add_filter("epfl_person_additional_meta", function ($more_meta, $person) {
    $sciper = $person->get_sciper();
    $incoming = get_stisrv13_person($sciper);
    if (! $incoming->position) return;

    if (! $person->get_bio()) {
        $person->set_bio($incoming->bio);
    }

    if ($incoming->phone) {  $person->set_phone($incoming->phone); }
    if ($incoming->office) { $person->set_room($incoming->office); }

    $more_meta["stisrv13_id"] = $incoming->id;

    $more_meta["stisrv13_news_json"] = json_encode($incoming->news);  // OBSOLESCENT
    foreach ($incoming->news as $article) {
        if (! ($rss_id = $article->rssid)) continue;
        $q = new \WP_Query(array('meta_query' => array(array(
			'key'     => Stisrv13Base::IMPORT_ID_META,
			'value'   => "rss-$rss_id",
			'compare' => '=',
		))));
        while ( $q->have_posts() ) {
            $q->the_post();
            $tag = sprintf('ATTRIBUTION=SCIPER:%d', $sciper);
            ensure_tag_exists_in_languages($tag, array("fr", "en"));
            debug("Tagging " . $q->post->ID . " (imported as $rss_id) with $tag");
            wp_set_post_terms($q->post->ID, $tag, 'post_tag', /* $append = */ true);
        }
    }

    $more_meta["stisrv13_data_json"]      = json_encode($incoming);  // OBSOLESCENT
    if (! $person->get_research_keywords) {
        $more_meta["research_keywords"]       = $incoming->keywords;
    }
    if (! $person->get_research_interests) {
        $more_meta["research_interests_html"] = $incoming->interests;
    }

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
                   array(get_called_class(), '_render_admin_notices'));
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
            error_log("Ingesting article " . $article->import_id);
            Stisrv13Article::sync($article);
        }
        if (Stisrv13Article::$missing_categories) {
            foreach (Stisrv13Article::$missing_categories as $category_slug) {
                static::admin_notice("error", sprintf(___("Category not found (by slug): %s, ignored"), $category_slug));
            }
        }
        foreach ($payload->videos as $video) {
            try {
                error_log("Ingesting video " . $video->import_id);
                Stisrv13Video::sync($video);
            } catch (Stisrv13ImportError $e) {
                error_log($e->getMessage() . " " . $e->getTraceAsString());
                continue;
            }
        }
        static::admin_notice("success", "JSON ingestion successful");
        error_log("stisrv13.php: JSON ingestion successful");
        wp_redirect(Stisrv13AdminMenu::get_upload_page_url());
    }

    static function admin_notice ($level, $text)
    {
        $key = static::_get_error_transient_key($color);
        $error = get_transient($key);
        if (! $error) {
            $error = $text;
        } else {
            $error = $error . "<br/>" . $text;
        }
        set_transient($key, $error, 45);  // Seconds before it self-destructs
    }

    static function _get_error_transient_key ($level)
    {
        return "stisrv13-import-$level-" . wp_get_current_user()->user_login;
    }

    static function _render_admin_notices ()
    {
        foreach (array("error", "info", "warning", "success") as $level) {
            $key = static::_get_error_transient_key($level);
            if ($error = get_transient($key)) {
                delete_transient($key);
                ?>
                <div class="notice notice-<?php echo $level; ?> is-dismissible">
                <p><?php echo $error; ?></p>
                </div>
                <?php
            }
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

Stisrv13UploadArticlesController::hook();

/**
 * Model class for articles
 */
class Stisrv13ImportError extends Exception {}
class DuplicateStisrv13ArticleException extends Stisrv13ImportError {}
class DuplicateStisrv13ImageException extends Stisrv13ImportError {}

abstract class Stisrv13Base extends Post
{
    const IMPORT_ID_META = "stisrv13_import_id";

    function get_import_id ()
    {
        return get_post_meta($this->ID, self::IMPORT_ID_META, true);
    }

    static function _single_instance($wp_query)
    {
        $results = $wp_query->get_posts();
        if (! $results) {
            return;
        } elseif (sizeof($results) > 1) {
            $ids = array_map(function($r) { return $r->ID; }, $results);
            throw new DuplicateStisrv13ArticleException(
                "Found " . sizeof($ids) . " results (" . implode(", ", $ids) . ") for query " . var_export($wp_query->query, true));
        } else {
            return static::get($results[0]);
        }
    }

    static function sync ($json)
    {
        $that = static::get_by_import_id_and_lang($json->import_id, $json->lang);
        if (! $that) {
            $id_or_error = static::_insert_unique($json);
            $that = static::get($id_or_error);
        }

        $that->_update($json);
        return $that;
    }

    static function _insert_unique ($json)
    {
        $id_or_error = wp_insert_post(
            array(
                "post_type"    => "post",
                'post_title'   => $json->title,  # Get the slug right the first time
                'post_content' => static::_extract_body($json),  # Is NOT NULL in-database
                'post_status'  => 'publish',
                'meta_input'   => array(
                    self::IMPORT_ID_META => $json->import_id,
                    'language'        => $json->lang
                )));
        if (is_wp_error($id_or_error)) {
            $error = $id_or_error;
            throw new Error(sprintf("Unable to create new post for (import_id=%s, lang=%s): %s", $json->import_id, $json->lang, $error->get_error_message()));
        }
        $id = $id_or_error;
        $lang = $json->lang;
        if ($lang && function_exists('pll_set_post_language')) {
            pll_set_post_language($id, $lang);
        }
        return $id;
    }

    function get_language ()
    {
        if (function_exists("pll_get_post_language")) {
            return pll_get_post_language($this->ID);
        } else {
            return get_post_meta($this->ID, "language", true);
        }
    }

    static $missing_categories;
    /**
     * Extract all the information out of $json for this stisrv13 article, which
     * already exists in-database, using the WordPress API.
     */
    function _update ($json)
    {
        # Basics
        $update_data = array(
            'ID'            => $this->ID,
            'post_title'    => $json->title,
            'post_content'  => $this->_extract_body($json),
        );

        # Author
        if ($author_login = $json->webmaster_author) {
            $user_query = new WP_User_Query(array(
                search_columns => array('user_login'),
                search         => $author_login));
            $authors = $user_query->get_results();
            if (count($authors) === 1) {
                $update_data['post_author'] = $authors[0]->ID;
            }
        }

        if ($json->pubdate) {
            $update_data['post_date']     = $this->_mysql_time($json->pubdate);
            $update_data['post_date_gmt'] = $this->_mysql_time($json->pubdate, true);
        }

        wp_update_post($update_data);

        $this->_update_categories($json);
        $this->_update_tags($json);
        $this->_link_translations($json);
    }

    function _update_categories ($json)
    {
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

    function _update_tags ($json)
    {
        if (! $json->tags) return;
        // wp_set_post_terms() auto-creates tags *in the default language only.*
        foreach ($json->tags as $tag_name) {
            ensure_tag_exists_in_languages($tag_name, array("fr", "en"));
        }
        wp_set_post_terms($this->ID, $json->tags);
    }

    function _link_translations ($json)
    {
        if (! function_exists('pll_save_post_translations')) return;
        $import_id    = $json->import_id;
        $lang         = $json->lang;
        if (! $lang) return;
        $other_lang   = ($lang == "fr") ? "en" : "fr";
        $other_translation = static::get_by_import_id_and_lang($import_id, $other_lang);
        if ($other_translation) {
            pll_save_post_translations(array(
                $lang       => $this->ID,
                $other_lang => $other_translation->ID
            ));
        }
    }

    function _update_post ($update_struct)
    {
        $update_struct['ID'] = $this->ID;
        $status = wp_update_post($update_struct);
        if (is_wp_error($status)) {
                throw new Error("Unable to update post ID " . $this->ID . ": " . $status->get_error_message());
        }
    }

    function _update_meta ($meta)
    {
        $this->_update_post(array('meta_input' => $meta));
    }

    function _decorate_with_lang ($query_params, $lang)
    {
        if (function_exists('pll_get_post_language')) {
            $query_params['lang'] = $lang;
        } else {
            array_push($query_params['meta_query'], array(
                'key'     => 'language',
                'value'   => $lang,
                'compare' => '='
            ));
        }
        return $query_params;
    }

    protected static function _extract_body ($json)
    {
        return $json->body;
    }

    function _get_moniker ()
    {
        return sprintf("%s<import_id=%d lang=%s>",
                       get_called_class(), $this->get_import_id(), $this->get_language());
    }

    static function get_by_import_id_and_lang ($import_id, $lang = null)
    {
        $query_params = array(
           'post_type'  => 'post',
           'meta_query' => array(array(
               'key'     => self::IMPORT_ID_META,
               'value'   => $import_id,
               'compare' => '='
            )));

        if ($lang) {
            $query_params = static::_decorate_with_lang($query_params, $lang);
        }
        return static::_single_instance(new WP_Query($query_params));
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

class Stisrv13Article extends Stisrv13Base
{
    function _belongs ()
    {
        return $this->get_import_id() !== null;
    }

    function _update ($json)
    {
        parent::_update($json);
        $this->_add_featured_image($json);
    }

    /**
     * Add a featured image if this stisrv13 article in this language
     * doesn't already have one.
     *
     * Image files are expected to be found in WP_CONTENT_DIR .
     *
     */

    function _add_featured_image ($json)
    {
        if (get_post_thumbnail_id($this->ID)) return;

        $import_id     = $json->import_id;
        $images_dir = static::get_images_dir();

        // If image is already ingested (typically, on behalf of the
        // post in the other language), re-use it
        $q = new WP_Query(array(
            'post_type'   => 'attachment',
            'post_status' => 'any',
            'meta_query'  => array(array(
                'key'     => self::IMPORT_ID_META,
                'value'   => $import_id,
                'compare' => '='
            ))));
        $results = $q->get_posts();
        if (sizeof($results) > 1) {
            throw new DuplicateStisrv13ImageException(
                "Found " . sizeof($results) . " images with RSS ID $import_id");
        } elseif (sizeof($results) === 1) {
            $this->_debug("Re-using image " . $results[0]->ID);
            set_post_thumbnail($this->ID, $results[0]->ID);
            return;
        }

        $our_image  = "$images_dir/$import_id.png";
        if (! file_exists($our_image)) {
            $this->_debug("_update_featured_image($import_id): $our_image does not exist");
            return;
        }
        $this->_debug("_update_featured_image($import_id) with $our_image");
        $file_struct = $this->_mock_file_structure($our_image);
        if (! $file_struct) return;  // ->_mock_file_structure() will have complained already

        $image_meta = wp_read_image_metadata($our_image);
        if ($image_meta === false) {
            error_log("Unable to read image metadata out of $our_image");
            return;
        }
        $result = media_handle_sideload(
            $file_struct,
            $this->ID,  // The media will have $this as its post_parent, which
                        // looks nice in the wp-admin media list view.  There appears
                        // to be no referential integrity trouble, even if one trashes
                        // $this.
            $json->covershot_alt,
            array(
                'post_status'    => 'inherit',
                'meta_input'   => array(
                    self::IMPORT_ID_META => $import_id
                )));
        if (is_integer($result)) {
            set_post_thumbnail($this->ID, $result);
        } else {
            throw new \Error($result["error"]);
        }
    }

    function _mock_file_structure ($path)
    {
        // https://stackoverflow.com/q/21462093/435004 FWIW
        $stat = @stat($path);
        if (! stat) {
            error_log("Unable to stat($path): " . var_export(error_get_last(), true));
            return;
        }
        $mimetype = mime_content_type ($path);
        if (! $mimetype) {
            error_log("Unable to determine MIME type of $path");
            return;
        }
        return array(
            'name'     => basename($path),
            'tmp_name' => $path,
            'type'     => $mimetype,
            'error'    => 0,
            'size'     => $stat['size']
        );
    }

    function get_images_dir ()
    {
        return WP_CONTENT_DIR . "/sideloads/stisrv13";
    }

    function _debug ($msg)
    {
        return;  // Comment out this line to get debugging going
        if (! (is_string($msg) || is_numeric($msg))) {
            $msg = var_export($msg, true);
        }
        error_log($this->_get_moniker() . ": " . $msg);
    }
}

function ensure_tag_exists_in_languages ($tag_name, $languages)
{
    foreach (array('pll_get_term_language', 'pll_set_term_language',
                   'pll_save_term_translations') as $functionname) {
        if (! function_exists($functionname)) {
            # Still ensure that the term exists in English
            wp_insert_term(
                $tag_name, 'post_tag',
                array('slug' => sanitize_title($tag_name) . "-en"));
            return;
        }
    }

    $terms = array();
    foreach (get_terms(array(
        'taxonomy'   => 'post_tag',
        'name'       => $tag_name,
        'hide_empty' => false
    )) as $term) {
        $term_id = $term->term_id;
        $lang = pll_get_term_language($term_id);
        if ((! $lang) && preg_match('/-en$/', $term->slug)) {
            # Assume it was created as part of the no-Polylang code above
            pll_set_term_language($term_id, "en");
            $lang = "en";
        }
        debug("Found term ID $term_id of language $lang for $tag_name");
        $terms[$lang] = $term_id;
    }
    debug("$tag_name has " . count($terms) . " pre-existing translations: " . var_export($terms, true));
    foreach ($languages as $lang) {
        if ($terms[$lang]) continue;
        $term_or_error = wp_insert_term(
            $tag_name, 'post_tag',
            array('slug' => sanitize_title($tag_name) . "-" . $lang));
        if (is_wp_error($term_or_error)) {
            // All you wanted to know about WP_Error, but were afraid to ask,
            // is at https://wordpress.stackexchange.com/a/11143/132235
            throw new Exception($term_or_error->get_error_message());
        }
        $term_id = (int) $term_or_error["term_id"];
        pll_set_term_language($term_id, $lang);
        $terms[$lang] = $term_id;
    }
    pll_save_term_translations($terms);
}

class Stisrv13Video extends Stisrv13Base
{
    const YOUTUBE_ID_META = "youtube_id";

    function get_youtube_id ()
    {
        return get_post_meta($this->ID, self::YOUTUBE_ID_META, true);
    }

    function _belongs ()
    {
        return $this->get_youtube_id() !== null;
    }

    protected static function _extract_body ($json)
    {
        return sprintf("https://www.youtube.com/watch?v=%s&rel=0\n\n",
                       $json->youtube_id) . parent::_extract_body($json);
    }

    function _update ($json)
    {
        parent::_update($json);
        $this->_update_meta(array(self::YOUTUBE_ID_META => $json->youtube_id));
        set_post_format($this->ID, 'video');
    }
}
