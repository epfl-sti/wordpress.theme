<?php

namespace EPFL\STI\Newsletter;

require_once(dirname(dirname(dirname(__FILE__))) . '/inc/i18n.php');
use function \EPFL\STI\Theme\___;
use function \EPFL\STI\Theme\__x;

/**
 * A category that designates posts that go into a given section of the
 * newsletter
 */
class NewsletterSectionCategory
{
    static function get_sections ()
    {
        return array(
            "news"    => __x("News",              "Newsletter section category"),
            "events"  => __x("Events",            "Newsletter section category"),
            "media"   => __x("In The Media",      "Newsletter section category"),
            "faculty" => __x("Faculty Positions", "Newsletter section category"),
        );
    }

    const SLUG = "epfl_sti_newsletter_section";

    function __construct ($term_id)
    {
        $this->term_id = $term_id;
    }

    function ID ()
    {
        return $this->term_id;
    }

    function get_newsletter_section_slug ()
    {
        return get_term_meta($this->term_id, self::SLUG, true);
    }

    function get_newsletter_section_name ()
    {
        $slug = $this->get_newsletter_section_slug();
        $retval = self::get_sections()[$slug];
        if (! $retval) {
            $retval = __x("(Not set)", "Newsletter section category");
        }
        return $retval;
    }

    static function find_all ($slug, $language_hint = null)
    {
        $terms = get_terms(array(
            'taxonomy'   => 'category',
            'meta_key'   => self::SLUG,
            'meta_value' => $slug,
            'hide_empty' => false
        ));
        if (! count($terms)) return [];
        if (count($terms) > 1 && null !== $language_hint &&
            function_exists("pll_get_term")) {  // Polylang
            // Perhaps the multiple $terms are translations of each other?
            $terms = array_values(array_filter(
                $terms,
                function($term) use ($language_hint) {
                    return ($term->term_id ===
                        pll_get_term($term->term_id, $language_hint));
                }));
        }
        return array_values(array_filter(
            $terms,
            function ($term) {
                return new NewsletterSectionCategory($term->term_id);
            }
        ));
    }
}

class NewsletterSectionCategoryController
{
    static function hook ()
    {
        add_action ( 'category_add_form_fields',
                     array(get_called_class(),
                           'render_select_newsletter_section'));
        /* Since the "edit category" form is full-screen, it's confusing
         * to display only one of the drop-down lists depending on how we
         * arrived to that page. */
        add_action ( 'category_edit_form_fields', array(get_called_class(), 'render_select_newsletter_section'));
        add_action ( 'created_category', array(get_called_class(), 'save_section_slug'), 10, 2);
        add_action ( 'edited_category', array(get_called_class(), 'save_section_slug'), 10, 2);

        add_filter ( "manage_edit-category_columns", array(get_called_class(), 'add_column_newsletter_setion'));
        add_filter ( "manage_category_custom_column", array(get_called_class(), 'get_custom_column_value'), 10, 3);
    }

    static function render_select_newsletter_section () {
        $current_slug = (new NewsletterSectionCategory($_REQUEST['tag_ID']))->get_newsletter_section_slug();
        ?>
        <tr class="form-field epfl-newsletter-section">
            <th scope="row">
                <label for=<?php echo NewsletterSectionCategory::SLUG; ?>>
                    <?php echo ___("Newsletter section:"); ?>
                </label>
            </th>
            <td>
                <select name=<?php echo NewsletterSectionCategory::SLUG; ?> id=<?php echo NewsletterSectionCategory::SLUG; ?> class="postform">
                    <option value="">None</option>
                <?php foreach (NewsletterSectionCategory::get_sections() as $slug => $section_name) { ?>
                    <option class="level-0" value="<?php echo $slug; ?>"<?php selected($current_slug, $slug)  ?>><?php echo $section_name; ?></option>
                <?php } ?>
                </select>
                <p><?php echo ___("Posts that belong to this category may be inserted into that section of the newsletter"); ?></p>
            </td>
        </tr>
        <?php
    }

    static function save_section_slug ($term_id, $unused_taxonomy) {
        $newval = $_REQUEST[NewsletterSectionCategory::SLUG];
        if ( null !== $newval ) {
            delete_term_meta($term_id, NewsletterSectionCategory::SLUG);
            if ($newval) {
                add_term_meta($term_id, NewsletterSectionCategory::SLUG, $newval);
            }
        }
    }

    static function add_column_newsletter_setion ($columns)
    {
        $columns[NewsletterSectionCategory::SLUG] = ___("Newsletter Section");
        return $columns;
    }

    static function get_custom_column_value ($content, $column_name, $term_id)
    {
        if ($column_name !== NewsletterSectionCategory::SLUG) return $content;
        return (new NewsletterSectionCategory($term_id))->get_newsletter_section_name();
    }

}

NewsletterSectionCategoryController::hook();

