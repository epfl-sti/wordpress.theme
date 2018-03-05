<?php
/**
 * Template Name: Page with Widget
 * Template Post Type: page
 *
 * This template can be used to override the default template and sidebar setup
 *
 * @package epflsti
 */

get_header();
$container = get_theme_mod( 'epflsti_container_type' );
?>

<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="container">

			<div class="row">

				<?php dynamic_sidebar( 'page-widget' ); ?>

			</div><!-- .row -->

		</div><!-- .container -->

	</div><!-- Container end -->

</div><!-- Wrapper end -->


<?php get_footer(); ?>
<!-- template: pagewithwidget.php -->
