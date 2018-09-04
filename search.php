<?php
/**
 * The template for displaying search results pages.
 *
 * @package epflsti
 */

get_header();

$container   = get_theme_mod( 'epflsti_container_type' );
$sidebar_pos = get_theme_mod( 'epflsti_sidebar_position' );
?>
<!-- epflsti:search.php -->
<div class="row">

	<div class="col-md-12 content-area" id="primary">

		<main class="site-main" id="main">

			<div class="wrapper" id="search-wrapper">

				<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

				<?php if ( esc_attr($container) == "container-fluid" ) : ?>
				<div class="offset-md-1 col-md-10">
				<?php endif; ?>

					<div class="row">

						<div class="col-md-12 content-area" id="primary">

							<main class="site-main" id="main">

							<?php if ( have_posts() ) : ?>
								<header class="page-header">
									<h1 class="page-title"><?php printf(
									/* translators:*/
									 esc_html__( 'Search Results for: %s', 'epflsti' ),
										'<span>' . get_search_query() . '</span>' ); ?></h1>
								</header><!-- .page-header -->
								<?php /* Start the Loop */ ?>
								<?php while ( have_posts() ) : the_post(); ?>
									<?php
									/**
									 * Run the loop for the search to output the results.
									 * If you want to overload this in a child theme then include a file
									 * called content-search.php and that will be used instead.
									 */
									get_template_part( 'loop-templates/content', 'search' );
									?>
								<?php endwhile; ?>
							<?php else : // not have_posts() ?>
								<?php get_template_part( 'loop-templates/content', 'none' ); ?>
							<?php endif; ?>

							</main><?php # end main ?>

						</div><?php # end col ?>

					</div><?php # end row ?>

				<?php if ( esc_attr($container) == "container-fluid" ) : ?>
				</div>
				<?php endif; ?>

				<?php epflsti_pagination(); ?>

				</div><?php # end #content ?>

			</div><?php # end wrapper ?>
		</main><?php # end main ?>
	</div><?php # end primary ?>
</div><?php # end row ?>
<?php get_footer(); ?>
