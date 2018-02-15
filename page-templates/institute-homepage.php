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
		<?php dynamic_sidebar( get_institute()->get_code() . '-homepage' ); ?>

				<?php dynamic_sidebar( 'right' ); ?>

			</div><!-- .row -->

		</div><!-- .container -->


	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
<!-- template: institute-homepage.php -->
