<?php
/* Template Name: Welcome from the Director */
/**
 * Partial template for content in page.php
 *
 * @package epflsti
 */

 get_header();

 $container   = get_theme_mod( 'epflsti_container_type' );
 $sidebar_pos = get_theme_mod( 'epflsti_sidebar_position' );

 ?>

 <div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<?php if ( esc_attr($container) == "container-fluid" ) : ?>
		<div class="offset-md-1 col-md-10">
		<?php endif; ?>

			<div class="row">

				<div class="col-md-12 content-area" id="primary">

					<main class="site-main" id="main">

						<?php while ( have_posts() ) : the_post(); ?>


							<article id="post-<?php the_ID(); ?>" >
									<header class="sti-textured">
										<?php the_title( '<h1>', '</h1>' ); ?>
									</header>

									<div class="article-content page-whitebg">

										<div class="container">
											<div class="row">
												<?php if (has_post_thumbnail($post)): ?>
													<div class="col-md-3 col-xl-3">
														<div class="sti_decanat_portrait">
															<?php echo get_the_post_thumbnail( $post->ID, 'medium', array( 'class' => 'img-responsive mx-auto d-block', 'style' => '' ) ); ?>
															<div class="sti_caption text-center" style="border-left: solid 1px #bbb;border-right: solid 1px #bbb;border-bottom: solid 1px #bbb;padding: 10px;">
																<?php echo "<b>" . get_the_post_thumbnail_caption( $post->ID ) . "</b>"; ?>
																<?php
																	$thumbnail_image_data = get_posts(array('p' => get_post_thumbnail_id($post->ID), 'post_type' => 'attachment'))[0];
																	if ($thumbnail_image_data->post_content != '') {
																		echo "<br />" . $thumbnail_image_data->post_content;
																	}
																?>
															</div>
														</div>
													</div>

												<?php endif; ?>

												<?php if (has_post_thumbnail($post)): ?>
													<div class="col-md-9 col-xl-9">
												<?php else: ?>
													<div class="col-md-12 col-xl-12">
												<?php endif; ?>
													<?php the_content(); ?>
													</div>
												</div> <?php # end dic content ?>
											</div> <?php # end row ?>


										<?php
										wp_link_pages( array(
											'before' => '<div class="page-links">' . __( 'Pages:', 'epflsti' ),
											'after'  => '</div>',
										) );
										?>

									</div> <?php # end container ?>

									<footer>

										<?php edit_post_link( __( 'Edit', 'epflsti' ), '<span class="edit-link">', '</span>' ); ?>

									</footer>
							</article><!-- #post-## -->

 						<?php endwhile; // end of the loop. ?>

 					</main><!-- #main -->

 				</div><!-- #primary -->

 			</div><!-- .row -->

 		<?php if ( esc_attr($container) == "container-fluid" ) : ?>
 		</div>
 		<?php endif; ?>

 	</div><!-- #content -->

 </div><!-- Wrapper end -->

 <?php get_footer(); ?>
