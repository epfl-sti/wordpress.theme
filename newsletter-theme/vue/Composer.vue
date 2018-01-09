<!--
    Newsletter composer's top-level component.

    The top-level component doesn't have a template or a render
    function. Instead, it uses the DOM that is already under
    #composer-toplevel as its template, substituting the elements
    listed under "components:" below. This allows for the Vue composer
    to work directly off the "real" HTML newsletter (as rendered by
    ../theme.php), leaving only minimally intrusive markup in the HTML
    that goes out through email (PHP needs only make sure to remove
    the <script> section at the top).

    This trick is called "in-DOM templates" and is documented at
    https://vuejs.org/v2/api/#Options-DOM (second tip). It requires
    the Vue template compiler to be available browser-side; since we
    use Browserify, this is achieved via an alias in the "browser":
    section of ../../package.json (as per the instructions at the
    bottom of https://github.com/vuejs/vue/blob/dev/dist/README.md)
    -->

<script>
import NewsItemHandle from "./NewsItemHandle.vue"
import _ from "lodash"
import dragula from "dragula"

function updateNewsOrder (vm) {
  vm.$set(vm, 'news', NewsItemHandle.findUnder(vm))
}

export default {
  el: '#composer-toplevel',
  data: () => ({
    news: []  // Kept in DOM order across drags
  }),
  computed: {
    newsIds () {
      let newsIds = _.map(this.news, (n) => n.postId)
      return newsIds
    }
  },
  components: {
    /* Vue magically maps the NewsItemHandle class
       to <news-item-handle> in the HTML */
    NewsItemHandle
  },
  mounted: function() {
    this.$nextTick(() => {
      let $this = this

      // Documentation says children should be mounted too by now.
      updateNewsOrder($this)

      let newsletterTbody = $("#composer-toplevel > tbody")
      this.dragula = dragula(
        newsletterTbody.toArray(),
        {
          invalid (el) {
            // Pieces without a NewsItemHandle (e.g. the big image
            // at the top) may not move
            return ! NewsItemHandle.findUnder(el).length
          }
        }
      )
      this.dragula.on("drop", () => {
        updateNewsOrder($this)
      })
    })
  }
}
</script>

<style lang="scss">
@import "~dragula/dist/dragula";
// See ../inc/serve-assets.php for how this URL came to be.
// There is no other way, since we need to know the URL at compile time.
$fa-font-path: 'theme-epfl-sti/node_modules/font-awesome/fonts';
@import "~font-awesome/scss/font-awesome";
</style>
