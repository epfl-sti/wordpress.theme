<?php
/**
 * Template Name: Cluster page
 * Template Post Type: page
 *
 * This template adds an automatically curated list of people, labs,
 * news (and posts), events, courses and video/media at the bottom
 * of the page (after the main matter, editable in TinyMCE). These
 * are just tag-based searches in the various types
 *
 * @package epflsti
 */

namespace EPFL\STI\ClusterPage;

const CLUSTER_TAG_SLUG_META = "cluster_tag_slug";

require_once(dirname(__DIR__) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

require_once(__DIR__ . "/../inc/epfl.php");
use function \EPFL\STI\get_current_language;

require_once(__DIR__ . "/../../../plugins/epfl-ws/Lab.php");
use \EPFL\WS\Labs\Lab;

require_once(__DIR__ . "/../../../plugins/epfl-ws/Person.php");
use \EPFL\WS\Persons\Person;

function debug ($message)
{
    error_log($message);
}

function _get_term_by_slug ($slug)
{
    if (! $slug) return null;
    // Caution: this assumes that all relevant post types have
    // Polylang turned on.
    #return get_term_by('slug', $slug, 'post_tag');

    // We can't just
    //
    //   return get_term_by('slug', $slug, 'post_tag');
    //
    // because Polylang insists (through request filtering) that tags
    // fetched in this way have the current language. The thing is,
    // when one creates-as-one-types a tag from a Post or Person etc
    // wp-admin edit page, Polylang will not attach a language to it.
    // We want to make such language-neutral tags Just Work™, not
    // force the user to come around and Polylangify them.
    $term_array = get_terms(array(
        'get'             => 'all',
        'taxonomy'        => 'post_tag',
        'slug'            => $slug,
        'suppress_filter' => true,
        // Rein in Polylang request filtering
        //'lang'            => \EPFL\STI::get_current_language()
        'lang'            => get_current_language()
    ));
    // get_terms() can return more that a item (https://developer.wordpress.org/reference/functions/get_terms/)
    return (isset($term_array[0]->term_id)) ? $term_array[0] : $term_array;
}

function _get_cluster_tag ()
{
    static $cluster_tag;
    if (! $cluster_tag) {
        global $post;
        $cluster_tag_slug = get_post_meta($post->ID, CLUSTER_TAG_SLUG_META, true);
        debug("Cluster tag slug is $cluster_tag_slug");
        $cluster_tag = _get_term_by_slug($cluster_tag_slug);
        debug('$cluster_tag is ' . var_export($cluster_tag, true));
    }
    return $cluster_tag;
}

function _wpquery ($additional_wp_query_params)
{
    if (! ($cluster_tag = _get_cluster_tag())) {
        // https://wordpress.stackexchange.com/a/140727/132235
        return new \WP_Query(array('post__in' => array(0)));
    }
    // debug ("Cluser tag: " . var_export($cluster_tag, true));
    $query_params = array_merge(
        array(
            "tag_id"         => $cluster_tag->term_id,
            "posts_per_page" => -1
        ),
        $additional_wp_query_params);

    debug("Query params: " . var_export($query_params, true));

    return new \WP_Query($query_params);
}

function _have_cluster_items ($additional_wp_query_params)
{
    global $epflsti_cluster_page_subquery;
    $epflsti_cluster_page_subquery = _wpquery($additional_wp_query_params);
    return $epflsti_cluster_page_subquery->have_posts();
}

function have_cluster_people ()
{
    return _have_cluster_items(array(
        "post_type" => "epfl-person"
    ));
}

function have_cluster_labs ()
{
    return _have_cluster_items(array(
        "post_type" => "epfl-lab"
    ));
}

function have_cluster_news ()
{
    return _have_cluster_items(array(
        "post_type" => array("epfl-actu", "post"),
    ));
}

function have_cluster_events ()
{
    return _have_cluster_items(array(
        "post_type" => "epfl-memento"
    ));
}

function have_cluster_courses ()
{
    return _have_cluster_items(array(
        "post_type" => "epfl-course"
    ));
}

function have_cluster_media ()
{
    return _have_cluster_items(array(
        "post_type" => "post",
        "tax_query" => array(array(
            'taxonomy' => 'post_format',
            'operator' => 'NOT IN',
            'field'    => 'slug',
            'terms'    => array( 'post-format-video' )
        ))
    ));
}

function next_item ()
{
    global $epflsti_cluster_page_subquery;

    if (! $epflsti_cluster_page_subquery) return false;

    if (! $epflsti_cluster_page_subquery->have_posts()) {
        $epflsti_cluster_page_subquery = null;
        return false;
    }

    global $post;

    $epflsti_cluster_page_subquery->setup_postdata(
        $post = $epflsti_cluster_page_subquery->next_post());

    return true;
}

###############################################################################

get_header();

?>

<article id="post-<?php the_ID(); ?>" class="cluster-page">
    <header class="sti-textured">
        <?php
        // If the tag name is set (+ the according custom field "cluster_tag_slug")
        // we display it as the title. Otherwise, let's use the default WP one.
        if (_get_cluster_tag()->name):
            echo "<h1>" . _get_cluster_tag()->name . " #cluster</h1>";
        else:
            echo the_title( '<h1>', '</h1>' );
        endif;
        ?>
    </header>

    <?php while (have_posts()): the_post(); # The Loop ?>
        <div class="article-content page-whitebg">
        <?php
            epflsti_render_featured_image();
            the_content();
            wp_link_pages( array(
                'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
                'after'  => '</div>',
                )
            );
            ?>

        <?php if (have_cluster_people()): ?>
            <h2><?= ___("People related to this cluster", "epfl-sti-cluster-page"); ?></h2>
            <?php while (next_item()) {
                $currentPerson = Person::get($post);
                //var_dump($currentPerson);
                echo epflsti_person_card('',  array("person" => $currentPerson));
            }
        endif; ?>

        <?php if (have_cluster_labs()): ?>
            <h2><?= ___("Units related to this cluster", "epfl-sti-cluster-page"); ?></h2>
            <?php while (next_item()) {
                $currentLab = Lab::get($post);
                echo epflsti_lab_card('', array("lab" => $currentLab));
                }
        endif; ?>

        <?php if (have_cluster_news()): ?>
            <h2><?= ___("News related to this cluster", "epfl-sti-cluster-page"); ?></h2>
            <?php while (next_item()) {
                get_template_part('loop-templates/content', 'search');
            }
        endif; ?>

        <?php if (have_cluster_events()): ?>
            <h2><?= ___("Events related to this cluster", "epfl-sti-cluster-page"); ?></h2>
            <?php while (next_item()) {
                get_template_part('loop-templates/content', 'search');
            }
        endif; ?>

        <?php if (have_cluster_courses()): ?>
            <h2><?= ___("Courses related to this cluster", "epfl-sti-cluster-page"); ?></h2>
            <?php while (next_item()) {
                get_template_part('loop-templates/content', 'search');
            }
        endif; ?>

        <?php if (have_cluster_media()): ?>
            <h2><?= ___("Media related to this cluster", "epfl-sti-cluster-page"); ?></h2>
            <?php while (next_item()) {
                get_template_part('loop-templates/content', 'search');
            }
        endif; ?>

        </div>
    <?php endwhile; # The Loop ?>

    <footer>
        <?php edit_post_link( __( 'Edit', 'epflsti' ), '<span class="edit-link">', '</span>' ); ?>
    </footer>
</article><!-- #post-## -->

<?php get_footer(); ?>
