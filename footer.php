<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package epflsti
 */

?>

<div class="footer">

  <div class="footercontainer">
    <span class="footertitle">CONTACT</span>
    <br />
    <br />
    School of Engineering<br />
    Dean's Office<br />
    EPFL - ELB 114<br />
    Station 11<br />
    CH-1015 Lausanne<br />
    <br />
    <a href="mailto:secretariat.sti@epfl.ch" taget="_blank" title="email: STI Secretary Officel"><i class="fas fa-2x fa-envelope-square"></i></a>
    <a href="https://twitter.com/epfl_sti" taget="_blank" title="EPFL STI Twitter"><i class="fab fa-2x fa-twitter-square"></i></a>
    <a href="https://plus.google.com/epfl_sti" taget="_blank" title="EPFL STI Google plus"><i class="fab fa-2x fa-google-plus-square"></i></a>
    <a href="https://instagram.com/epfl_sti" taget="_blank" title="EPFL STI Instagram"><i class="fab fa-2x fa-instagram"></i></a>
    <a href="https://facebook.com/epfl_sti" taget="_blank" title="EPFL STI Facebook"><i class="fab fa-2x fa-facebook-square"></i></a>
    <a href="https://youtube.com/epfl_sti" taget="_blank" title="EPFL STI Youtube"><i class="fab fa-2x fa-youtube"></i></a>
  </div>

  <script src="<?= get_template_directory_uri() . '/js/google-maps.js' ?>"></script>
  <div id="googlemap"></div>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-KB1byyUR6AEWVI1B8cdGFIDI1v8g8YY&libraries=places&callback=initMap" async defer></script>

  <div class="footercontainer">
    <span class="footertitle">NEWSLETTER</span>
      <br /><br />
      Sign up for our email bulletin:
    <form>
      <input name="subscriber">
        <br /><br />
        <input type="submit" value="GO">
      </form>
  </div>

</div>
<?php wp_footer(); ?>
<!-- footer --->
</body>

</html>
