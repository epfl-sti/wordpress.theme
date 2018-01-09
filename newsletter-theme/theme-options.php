<?php
/*
 * Single-page Web app for the EPFL-STI newsletter editor.
 *
 * The app is in an iframe taking up most of the real estate in the
 * wp-admin UI. See serve_composer_app in hook.php for how the app is
 * served into the iframe.
 */

if (!defined('ABSPATH'))
    exit;

$our_iframe_src = wp_nonce_url(
    sprintf("%s?epflsti=emails-vue-editor&ts=%s&mode=editing",
            home_url('/', is_ssl() ? 'https' : 'http'),
            time()),
    'view');
?>

<iframe id="emails-vue-editor" src="<?php echo $our_iframe_src ?>" height="700" style="width: 100%; border: 1px solid #ccc"></iframe>
<script type="text/javascript">
<?php # Trim down the "traditional" composer UI, and keep just the iframe. ?>
if (!String.prototype.endsWith) {
  String.prototype.endsWith = function(searchStr, Position) {
      // This works much better than >= because
      // it compensates for NaN:
      if (!(Position < this.length))
        Position = this.length;
      else
        Position |= 0; // round position
      return this.substr(Position - searchStr.length,
                         searchStr.length) === searchStr;
  };
}

jQuery(function($){
    // Remove the "Theme options are saved etc." blurb
    $("p", $("div#tnp-heading")).remove();

    // Remove various other pieces of UI we don't need
    var button_row = $("tr", $("form > table"))[0];
    $('.button-primary', button_row).filter(function(unused_index, e) {
        var elt = $(e),
            onclick = elt.attr("onclick");
        return (onclick && onclick.includes("'save'"));
    }).remove();
    $('img', button_row).filter(function(unused_index, e) {
        var elt = $(e),
            src = elt.attr("src");
        return (src.endsWith("arrow.png"));
    }).remove();

    // iframe is front and center
    var iframe_to_keep = $("iframe#emails-vue-editor");
    var main_ui_tr = iframe_to_keep.closest("tr");
    $("form").append(iframe_to_keep);
    main_ui_tr.remove();
});
</script>
