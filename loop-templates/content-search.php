<?php
/**
 * Search results partial template.
 *
 * @package epflsti
 */

?>
<!-- epflsti:loop-templates/content-search.php -->
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header>

		<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
		'</a></h2>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>

			<div class="search-result-meta">

				<?php epflsti_posted_on(); ?>

			</div>

		<?php endif; ?>

	</header>

	<div class="search-result-summary">

		<?php the_excerpt(); ?>

	</div>

	<footer>

		<?php epflsti_entry_footer(); ?>

	</footer>

</article><!-- #post-## -->
