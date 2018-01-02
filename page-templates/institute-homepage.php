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

		<div class="row">

			<div class="col-md-8 content-area" id="primary">

				<main class="site-main" id="main" role="main">

					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'loop-templates/content', 'page' ); ?>
						<?php
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
						?>
					<?php endwhile; // end of the loop. ?>

				</main><!-- #main -->

			</div><!-- #primary -->

			<?php dynamic_sidebar( 'right' ); ?>
			<!-- NAV MENU START -->
			<div class="sti_righthand_menu col-md-4">
				<div class="frontrowlist_title frontrowmarker">
					<?php
					global $post;
					/* Get an array of Ancestors and Parents if they exist */
					$parents = get_post_ancestors( $post->ID );
					/* Get the top Level page->ID count base 1, array base 0 so -1 */
					$id = ($parents) ? $parents[count($parents)-1]: $post->ID;
					/* Get the parent and set the $class with the page slug (post_name) */
					$parent = get_post( $id );
					$class = $parent->post_name;
					?>
					<a class="frontrowtitle_link" href=#><?php echo strtoupper($class); ?></a> - <?php echo strtoupper(get_institute()); ?></span>
				</div>
				<div class="frontrowlistbox rollup">
					<?php wp_nav_menu( array( 'theme_location' => sprintf('institutenavmenu-%s', get_institute()) ) ); ?>
				</div>
			</div><!-- .sti_righthand_menu -->
			<!-- NAV MENU END -->
		</div><!-- .row -->

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
