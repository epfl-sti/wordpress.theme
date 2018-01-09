<?php
/**
 * The template for displaying the footer.
 *
 * Contains any parting words (or widgets, as it were), then the
 * </body> and </html> tags
 *
 * @package epflsti
 */

echo '<div class="footer">';
  dynamic_sidebar( 'footer' );
echo '</div>';
echo '<div class="footnote">';
  wp_nav_menu( array( 'theme_location' => 'footnote' ) );
echo '</div>';
wp_footer();
?>
</body>
</html>
