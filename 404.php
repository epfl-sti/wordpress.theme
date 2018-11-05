<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package epflsti
 */

get_header();
?>
<!--
!!! WHOOPS SORRY !!!
……………………………………..________
………………………………,.-‘”……………….“~.,
………………………..,.-“……………………………..”-.,
…………………….,/………………………………………..”:,
…………………,?………………………………………………\,
………………./…………………………………………………..,}
……………../………………………………………………,:`^`..}
……………/……………………………………………,:”………/
…………..?…..__…………………………………..:`………../
…………./__.(…..”~-,_…………………………,:`………./
………../(_….”~,_……..”~,_………………..,:`…….._/
……….{.._$;_……”=,_…….”-,_…….,.-~-,},.~”;/….}
………..((…..*~_…….”=-._……”;,,./`…./”…………../
…,,,___.\`~,……”~.,………………..`…..}…………../
…………(….`=-,,…….`……………………(……;_,,-”
…………/.`~,……`-………………………….\……/\
………….\`~.*-,……………………………….|,./…..\,__
,,_……….}.>-._\……………………………..|…………..`=~-,
…..`=~-,_\_……`\,……………………………\
……………….`=~-,,.\,………………………….\
…………………………..`:,,………………………`\…………..__
……………………………….`=-,……………….,%`>–==“
…………………………………._\……….._,-%…….`\
……………………………..,<`.._|_,-&“…………….`
-->
<?php
$container   = get_theme_mod( 'epflsti_container_type' );
$sidebar_pos = get_theme_mod( 'epflsti_sidebar_position' );

?>
<!-- epflsti:404.php -->

<div class="wrapper" id="error-404-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<div class="col-md-12 content-area" id="primary">

				<main class="site-main page-whitebg" id="main">

					<section class="error-404 not-found">

						<header class="page-header sti-textured">

							<h1><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'epflsti' ); ?></h1>

						</header><!-- .page-header -->

						<!-- <div class="page-content"> -->

							<div class="article-content page-whitebg">
								
								<div class="container">

									<div class="row">

										<div class="col-md-8">

											<br />
											<p>
												<?php 
													esc_html_e( 'Please accept our apologies — this page is not currently available.', 'epflsti' );
												?>
											</p>
											<p>
												<?php 
													esc_html_e( 'If you would like to report this missing content, please write to the following address: ', 'epflsti' ); 
												?>
												<a href="mailto:stiitweb@groupes.epfl.ch">STI Web Team</a>.
											</p>
											<p>
												<?php 
													esc_html_e( 'You may also search for similar content on our website:', 'epflsti' ); 
												?>
												<?php get_search_form(); ?>
											</p>
											<p>&nbsp;</p>
											<p>
												While you are here, feel free to browse our information about the 
												<a href="/the-school/">EPFL School of Engineering</a>, its 
												<a href="/research/faculty-members/">faculty members</a>, 
												the five institutes (<a href="https://bioengineering.epfl.ch/">Bioengineering</a>, 
												<a href="/research/institutes/iel/">Electrical Engineering</a>, 
												<a href="/research/institutes/imx/">Materials Science & Engineering</a>, 
												<a href="/research/institutes/igm/">Mechanical Engineering</a> & 
												<a href="/research/institutes/imt/">Microengineering</a>), 
												the <a href="/news">latest news</a> or the <a href="/themes/">themes</a> we cover.
											</p>

										</div>

										<div class="col-md-4">
											&nbsp;
											<img class="bdr" src="/wp-content/themes/epfl-sti/assets/epfl-rolex-photo-by-maartendanial-flickr.jpg" alt="Photo by Maarten Danial (flickr)" title="Photo by Maarten Danial (flickr)"/><!-- https://www.flickr.com/photos/maartendanial/4413618516/in/photolist-7J1X1Y-7J1gDR-8sAL4h-8zKu7U-dTJZvb-o86heo-eig5dY-8FGxPK-Fnv8eS-ei3nNF-8ySVVJ-WYPgqm-ei9Qwx-8AR1Qp-eifxFf-eia8Li-eiamF2-eig63d-8FGqBt-eifxq5-8zKu7Y-eig68Y-eiadex-eifBbb-eifMrm-eifxYJ-eig6t9-eig3uq-ei9j2J-8LyUYF-eifXBN-ei9ToM-eiajgz-eifzB1-ei8ZKd-eiah14-ei9QMT-eia1ek-eiafSr-ei3jqe-8FGwoX-ei3Gfv-eifWQh-eifHMu-eifBxy-8sAJe7-8FKLgJ-eifW7u-eifydh-eiaf72 -->
										</div>

									</div>

								</div>
								<?php // the_widget( 'WP_Widget_Recent_Posts' ); ?>

								<?php /*if ( epflsti_categorized_blog() ) : // Only show the widget if site has multiple categories. ?>

									<div class="widget widget_categories">

										<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'epflsti' ); ?></h2>

										<ul>
											<?php
											wp_list_categories( array(
												'orderby'    => 'count',
												'order'      => 'DESC',
												'show_count' => 1,
												'title_li'   => '',
												'number'     => 10,
											) );
											?>
										</ul>

									</div><!-- .widget -->

								<?php endif; */ ?>

								<?php

								/* translators: %1$s: smiley */
								/*$archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'epflsti' ), convert_smilies( ':)' ) ) . '</p>';
								the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );

								the_widget( 'WP_Widget_Tag_Cloud' );*/
								?>

							</div><!-- .page-content -->

						<!-- </div> --><!-- .page-content -->

					</section><!-- .error-404 -->

				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .row -->

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
