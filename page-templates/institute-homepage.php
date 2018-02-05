<?php
/**
 * Template Name: Institute homepage
 * Template Post Type: page
 *
 * This template can be used to override the default template and sidebar setup
 *
 * @package epflsti
 */

get_header();
$container = get_theme_mod( 'epflsti_container_type' );
require_once(dirname(__FILE__).'/../inc/epfl.php');
use function \EPFL\STI\get_institute;
?>

<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="container">

			<div class="row">
		<?php dynamic_sidebar( get_institute() . '-homepage' ); ?>

				<?php dynamic_sidebar( 'right' ); ?>

				<div class="col-md-12" id="primary">

					<main class="" id="main" role="main">

						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'loop-templates/content', 'institute-page' ); ?>
							<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
							?>
						<?php endwhile; // end of the loop. ?>

					</main><!-- #main -->

				</div><!-- #primary -->


			</div><!-- .row -->

		</div><!-- .container -->


	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
