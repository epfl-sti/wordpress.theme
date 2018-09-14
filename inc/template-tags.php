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
function epflsti_posted_on($brief) {
	$time_string = '<time class="published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="published" datetime="%1$s">%2$s</time>';
        if ($brief != 1) {
            $time_string.='<time class="updated" datetime="%3$s">%4$s</time>';
        }
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
    if ($lab = $opts["lab"]) {
        $title    = $lab->get_abbrev();
        $link     = $lab->get_website_url();
        $img_html = get_the_post_thumbnail($lab->wp_post());
        if (! $body) { $body = $lab->get_name(); }
    }
    // If both $opts["lab"] and individual opts are stipulated,
    // the latter override the former.
    if ($opts["title"]) { $title = $opts["title"]; }
    if ($opts["href"])  { $link  = $opts["href"]; }
    if ($img_href = $opts["img"]) {
        $img_html = "<img src=\"$img_href\" />";
    }

    // Image is clicky iff we have a link.
    if ($link) {
        $img_html = "<a href=\"$link\">$img_html</a>";
    }

    $html = "<card class=\"sti-lab\">\n";
    if ($title) {
        $html .= "<header><h1>$title</h1></header>";
    } else {
      # TODO: put this in scss
      $html .= "<header style=\"border-bottom: 4px solid #cc161d;\"></header>";
    }
    if ($img_html) { $html .= $img_html; }
    if ($body) {
        $body_has_link = preg_match("/<a/", $body);
        $body_has_p = preg_match("/^<p>/", trim($body));
        if ($body_has_p) {
            $html .= $body;
        } else {
            if ($link && ! $body_has_link) {
                // Body is only clicky if there aren't any links in it
                // already.
                $body = "<a href=\"". $link . "\">$body</a>";
            }
            $html .= "<p>$body</p>";
        }
    }
    $html .= "\n</card>\n";
    return $html;
}

/**
 * The template for the [person-card] shortcode (see shortcodes.php)
 */
function epflsti_person_card ($body, $opts)
{
    if ($person = $opts["person"]) {
			//var_dump($person);
        $title    = $person->get_full_name();
        $link     = $person->get_profile_url();
        $img_html = "<img src=\"".$person->get_image_url()."\" />";
        //if (! $body) { $body = $person->get_full_name(); }
    }
    // If both $opts["lab"] and individual opts are stipulated,
    // the latter override the former.
    //if ($opts["title"]) { $title = $opts["title"]; }
    if ($opts["href"])  { $link  = $opts["href"]; }
    if ($img_href = $opts["img"]) {
        $img_html = "<img src=\"$img_href\" />";
    }

    // Image is clicky iff we have a link.
    if ($link) {
        $img_html = "<a href=\"$link\">$img_html</a>";
    }

    $html = "<card class=\"sti-lab\">\n";
    if ($title) {
        $html .= "<header><h1>$title</h1></header>";
    }
    if ($img_html) { $html .= $img_html; }
    if ($body) {
        $body_has_link = preg_match("/<a/", $body);
        $body_has_p = preg_match("/^<p>/", trim($body));
        if ($body_has_p) {
            $html .= $body;
        } else {
            if ($link && ! $body_has_link) {
                // Body is only clicky if there aren't any links in it
                // already.
                $body = "<a href=\"". $link . "\">$body</a>";
            }
            $html .= "<p>$body</p>";
        }
    }
    $html .= "\n</card>\n";
    return $html;
}

function epflsti_render_featured_image ($extra_css_classes = "")
{
    global $post;
    if (! has_post_thumbnail($post)) return;
?>
			<div class="<?php echo $extra_css_classes ?>" >
				<figure>
					<?php echo get_the_post_thumbnail( $post->ID, 'medium', array( 'class' => 'img-responsive' ) ); ?>
					<figcaption><?php echo the_post_thumbnail_caption( $post->ID ); ?></figcaption>
				</figure>
			</div>
<?php
}
