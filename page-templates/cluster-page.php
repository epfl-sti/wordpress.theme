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

function debug ($message)
{
    // error_log($message);
}

function _get_term_by_slug ($slug)
{
    if (! $slug) return null;
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
        'lang'            => false
    ));

    if (count($term_array) > 1) {
        error_log('Found ' . count($term_array) . ' terms for slug ' . $slug);
    }

    if (! ($term = $term_array[0])) return null;

    // Still, for those tags that have been Polylangified indeed, we
    // want automagic translation to occur:
    if (! (function_exists('pll_get_term_translations') &&
           function_exists('pll_get_current_language')  &&
           $translations = pll_get_term_translations($term->term_id))) {
        return $term;
    }
    if ($translated = $translations[pll_get_current_language()]) {
        return $translated;
    } else {
        return $term;
    }
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
    $q = _wpquery($additional_wp_query_params);

    global $epflsti_cluster_items;
    $epflsti_cluster_items = $q->get_posts();
    debug("\$epflsti_cluster_items has " . count($epflsti_cluster_items) . " items");
    return (count($epflsti_cluster_items) > 0);
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
        "post_type" => array("epfl-actu", "post")
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
        "tax_query" => array(
            'taxonomy' => 'post_format',
            'field'    => 'slug',
            'terms'    => array( 'post-format-video' )
        )
    ));
}

function render_cluster_items ()
{
  ?><img src="https://www.codeschool.com/assets/custom/geocities/underconstruction-72327f17c652569bab9a33536622841bf905d145ee673a3e9d065fae9cabfe4f.gif"><?php
}

###############################################################################

get_header();

?>

<article id="post-<?php the_ID(); ?>" class="cluster-page">
		<header class="sti-textured">
			<?php the_title( '<h1>', '</h1>' ); ?>
		</header>

	    <?php while (have_posts()): the_post(); # The Loop ?>
		<div class="article-content page-whitebg">

			<?php
                          epflsti_render_featured_image();
                        the_content();
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
				'after'  => '</div>',
			) );
			?>

                 <?php if(have_cluster_people()): ?>
                  <h2>People</h2>
                  <?php render_cluster_items();  ?>
                 <?php endif; ?>

                 <?php if(have_cluster_labs()): ?>
                  <h2>Labs</h2>
                  <?php render_cluster_items();  ?>
                 <?php endif; ?>

                 <?php if(have_cluster_news()): ?>
                  <h2>News</h2>
                  <?php render_cluster_items();  ?>
                 <?php endif; ?>

                 <?php if(have_cluster_events()): ?>
                  <h2>Events</h2>
                  <?php render_cluster_items();  ?>
                 <?php endif; ?>

                 <?php if(have_cluster_courses()): ?>
                  <h2>Courses</h2>
                  <?php render_cluster_items();  ?>
                 <?php endif; ?>

                 <?php if(have_cluster_media()): ?>
                  <h2>Media</h2>
                  <?php render_cluster_items();  ?>
                 <?php endif; ?>

		</div>
	    <?php endwhile; # The Loop ?>

		<footer>
			<?php edit_post_link( __( 'Edit', 'epflsti' ), '<span class="edit-link">', '</span>' ); ?>
		</footer>
</article><!-- #post-## -->

<?php get_footer(); ?>
