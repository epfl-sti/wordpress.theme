<?php
/**
 * The template for displaying the footer.
 *
 * Contains any parting words (or widgets, as it were), then the
 * </body> and </html> tags
 *
 * @package epflsti
 */
require_once(dirname(__FILE__).'/inc/epfl.php');
use function \EPFL\STI\get_institute;
?>
  <div class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <?php
          $institute_code = is_object(get_institute()) ? get_institute()->get_code() : '';
          if ( is_active_sidebar( $institute_code . '-footer-left' ) ) {
            dynamic_sidebar( $institute_code . '-footer-left' );
          } else {
            dynamic_sidebar( 'footer-left' );
          }
          ?>
        </div>
        <div class="col-md-6 d-none d-md-block">
          <?php dynamic_sidebar( 'footer-middle' ); ?>
        </div>
        <div class="col-md-3">
          <?php dynamic_sidebar( 'footer-right' ); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="footnote"><?php wp_nav_menu( array( 'theme_location' => 'footnote' ) );?></div>
<?php
  wp_footer();
?>
</body>
</html>
