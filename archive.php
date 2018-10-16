<?php
/**
 * The template for displaying "archive" pages (i.e. category or
 * taxonomy tables of contents).
 *
 * "Archives" are more like (quite inflexible) views for the WP_Query
 * functionality. For instance, the /category/faculty-positions/ URL
 * will be served by this file.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package epflsti
 */

get_header();
?>

<?php
$container   = get_theme_mod( 'epflsti_container_type' );
$sidebar_pos = get_theme_mod( 'epflsti_sidebar_position' );
?>
<!-- epflsti:archive.php -->
<div class="container wrapper" id="archive-wrapper">

	<div class="row">
		<div class="col-md-12">
			<main class="list-main" id="list-main" role="main">

				<?php if ( have_posts() ) : ?>

					<header class="sti-textured page-header">
						<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						?>
					</header><!-- .page-header -->
					
					<div class="epfl-shortcode-list epfl-shortcode-actu has-results">
						<?php
							the_archive_description( '<div class="taxonomy-description"><p class="archive-description">', '</p></div>' );
						?>

						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>

							<?php
							/*
							 * Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							 echo "<!-- TEMPLATE ARCHIVE POST FORMAT: " . get_post_format() . "-->";
							//get_template_part( 'loop-templates/content', get_post_format() );

							//get_template_part( 'loop-templates/content', get_post_format() );
							get_template_part( 'loop-templates/content', 'archive' );
							?>

						<?php endwhile; ?>
					</div>

				<?php else : ?>

					<!-- TEMPLATE ARCHIVE POST FORMAT:  none -->
					<?php get_template_part( 'loop-templates/content', 'none' ); ?>

				<?php endif; ?>

			</main><!-- #main -->

		</div> <!-- col-md -->
		
		<!-- The pagination component -->
		<div class="col-md-12">
			<?php epflsti_pagination(); ?>
		</div>

	</div><!-- .row -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
