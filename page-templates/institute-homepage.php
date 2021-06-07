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
<!-- epflsti:page-templates/institute-homepage.php -->
<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="container">

			<div class="row">
				<?php
					// HACK: quick and dirty way to manage iel being renamed as iem
					$institute = get_institute()->get_code();
					if ($institute == "iem") {
							$institute  = "iel";
					}
                            
				?>
				<?php dynamic_sidebar( $institute . '-homepage' ); ?>

				<?php dynamic_sidebar( 'right' ); ?>

			</div><!-- .row -->

		</div><!-- .container -->

		<footer>
			<?php edit_post_link( __( 'Edit', 'epflsti' ), '<span class="edit-link">', '</span>' ); ?>
		</footer>

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
<!-- template: institute-homepage.php -->
