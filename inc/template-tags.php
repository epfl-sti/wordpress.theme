<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package epflsti
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

if ( ! function_exists( 'epflsti_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function epflsti_posted_on() {
	$time_string = '<time class="published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
	$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'epflsti' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);
	echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
}
endif;

if ( ! function_exists( 'epflsti_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function epflsti_entry_footer() {
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'epflsti' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function epflsti_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'epflsti_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );
		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );
		set_transient( 'epflsti_categories', $all_the_cool_cats );
	}
	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so components_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so components_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in epflsti_categorized_blog.
 */
function epflsti_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'epflsti_categories' );
}
add_action( 'edit_category', 'epflsti_category_transient_flusher' );
add_action( 'save_post',     'epflsti_category_transient_flusher' );

/**
 * The template for the [lab-card] shortcode (see shortcodes.php)
 */
function epflsti_lab_card ($body, $opts)
{
    $html = "<card class=\"sti-lab\">\n";

    $title = $opts["title"];
    if ($title) {
        $html .= "<header><h1>$title</h1></header>";
    }
    $img_href = $opts["img"];
    if ($img_href) {
        $img_html = "<img src=\"$img_href\" />";
        if ($opts["href"]) {
            $img_html = "<a href=\"". $opts["href"] . "\">$img_html</a>";
        }
        $html .= $img_html;
    }
    if ($body) {
        $body_has_link = preg_match("/<a/", $body);
        $body_has_p = preg_match("/^<p>/", trim($body));
        if ($body_has_p) {
            $html .= $body;
        } else {
            if ($opts["href"] && ! $body_has_link) {
                $body = "<a href=\"". $opts["href"] . "\">$body</a>";
            }
            $html .= "<p>$body</p>";
        }
    }
    $html .= "\n</card>\n";
    return $html;
}
