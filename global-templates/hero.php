<?php
/**
 * Hero setup.
 *
 * @package epflsti
 */

?>

<?php if ( is_active_sidebar( 'hero' ) || is_active_sidebar( 'statichero' ) ) : ?>

	<div class="wrapper" id="wrapper-hero">
	
		<?php dynamic_sidebar( 'hero' ); ?>
		
		<?php dynamic_sidebar( 'statichero' ); ?>

	</div>

<?php endif; ?>
