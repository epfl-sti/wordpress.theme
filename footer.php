<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package epflsti
 */

$the_theme = wp_get_theme();
$container = get_theme_mod( 'epflsti_container_type' );
?>

<?php get_sidebar( 'footerfull' ); ?>

<div class="wrapper" id="wrapper-footer">
<div class="wrapper sti-footer" id="wrapper-footer">

	<div class="<?php echo esc_attr( $container ); ?>">
<div class=footercontact>
 <div class=footerrow>
  <!---div class=footercontainer>
   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2745.44682228478!2d6.564568915650063!3d46.519059370457676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x478c30fdddeca23f%3A0xbbcc34ab7f93b272!2s%C3%89cole+Polytechnique+F%C3%A9d%C3%A9rale+de+Lausanne!5e0!3m2!1sfr!2sch!4v1447795467500" style="border:0" allowfullscreen="" width="353" height="250" frameborder="0"></iframe>
  </div>
  <div class=footercontainer>
   <iframe allowfullscreen="" style="border:0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2760.414406187801!2d6.145566415641781!3d46.22210309057164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x478c651ea1e33b17%3A0x163a5f132e7fbe93!2sCampus+Biotech!5e0!3m2!1sfr!2suk!4v1447796672577" width="353" height="250" frameborder="0"></iframe>
  </div>
  <div class=footercontainer>
   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2721.165867240955!2d6.943764815663466!3d46.99771603783268!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x478e0a247392c74b%3A0x2779be2d98bea5b3!2sMicrocity!5e0!3m2!1sen!2suk!4v1447795778830" style="border:0" allowfullscreen="" width="353" height="250" frameborder="0"></iframe>
  </div--->
 </div>

<div class=contactbox>
CONTACT<br><br>
Faculté des sciences et techniques de l'ingénieur STI<br>
Décanat<br>
EPFL - ELB 114<br>
Station 11<br>
CH-1015 Lausanne<br></div>
</div>
		<div class="row">

			<div class="col-md-12">

				<footer class="site-footer" id="colophon">

					<div class="site-info">
<!---

							<a href="<?php  echo esc_url( __( 'http://wordpress.org/','epflsti' ) ); ?>"><?php printf( 
							/* translators:*/
							esc_html__( 'Proudly powered by %s', 'epflsti' ),'WordPress' ); ?></a>
								<span class="sep"> | </span>
					
							<?php printf( // WPCS: XSS ok.
							/* translators:*/
								esc_html__( 'Theme: %1$s by %2$s.', 'epflsti' ), $the_theme->get( 'Name' ),  '<a href="'.esc_url( __('http://sti.epfl.ch', 'epflsti')).'">sti.epfl.ch</a>' ); ?> 
				
							(<?php printf( // WPCS: XSS ok.
							/* translators:*/
								esc_html__( 'Version: %1$s', 'epflsti' ), $the_theme->get( 'Version' ) ); ?>)

--->


					</div><!-- .site-info -->

				</footer><!-- #colophon -->

			</div><!--col end -->

		</div><!-- row end -->

	</div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->



<?php wp_footer(); ?>

</body>

</html>

