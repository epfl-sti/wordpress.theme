<?php
/**
 * The template for displaying the footer.
 *
 * Contains any parting words (or widgets, as it were), then the
 * </body> and </html> tags
 *
 * @package epflsti
 */

echo "<div class=\"footer\">\n";
dynamic_sidebar( 'footer' );
echo "</div>\n";
wp_footer();
?>
</body>
</html>
