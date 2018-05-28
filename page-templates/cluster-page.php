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
    // Caution: this assumes that all relevant post types have
    // Polylang turned on.
    return get_term_by('slug', $slug, 'post_tag');
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
        "tax_query" => array(array(
            'taxonomy' => 'post_format',
            'operator' => 'NOT IN',
            'field'    => 'slug',
            'terms'    => array( 'post-format-video' )
        ))
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
                  <?php while (next_item()) {
                      get_template_part('loop-templates/content', 'search');
                  }
                  endif; ?>

                 <?php if(have_cluster_labs()): ?>
                  <h2>Labs</h2>
                  <?php while (next_item()) {
                      get_template_part('loop-templates/content', 'search');
                  }
                  endif; ?>

                 <?php if(have_cluster_news()): ?>
                  <h2>News</h2>
                  <?php while (next_item()) {
                      get_template_part('loop-templates/content', 'search');
                  }
                  endif; ?>

                 <?php if(have_cluster_events()): ?>
                  <h2>Events</h2>
                  <?php while (next_item()) {
                      get_template_part('loop-templates/content', 'search');
                  }
                  endif; ?>

                 <?php if(have_cluster_courses()): ?>
                  <h2>Courses</h2>
                  <?php while (next_item()) {
                      get_template_part('loop-templates/content', 'search');
                  }
                  endif; ?>

                 <?php if(have_cluster_media()): ?>
                  <h2>Media</h2>
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
