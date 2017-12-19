<?php
/**
 * Sidebar setup for footer full.
 *
 * @package epflsti
 */

$container   = get_theme_mod( 'epflsti_container_type' );

?>

<?php if ( is_active_sidebar( 'footerfull' ) ) : ?>
	<!-- ******************* The Footer Full-width Widget Area ******************* -->
	<div class="wrapper" id="wrapper-footer-full">

		<div class="<?php echo esc_attr( $container ); ?>" id="footer-full-content" tabindex="-1">

			<div class="row">

				<?php dynamic_sidebar( 'footerfull' ); ?>

			</div>

		</div>

	</div><!-- #wrapper-footer-full -->
<?php else:    # Default footer is a map and contact info ?>

<div class="footer">

<div class="<?php echo esc_attr( $container ); ?>">
  <script src="<?= get_template_directory_uri() . '/js/google-maps.js' ?>"></script>
  <div id="googlemap"></div>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-KB1byyUR6AEWVI1B8cdGFIDI1v8g8YY&libraries=places&callback=initMap" async defer></script>
</div>

<div class="contactbox <?php echo esc_attr( $container ); ?>">
CONTACT<br><br>
Faculté des sciences et techniques de l'ingénieur STI<br>
Décanat<br>
EPFL - ELB 114<br>
Station 11<br>
CH-1015 Lausanne<br></div>
</div>

</div>

<?php endif; ?>
