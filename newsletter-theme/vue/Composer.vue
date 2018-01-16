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
import _ from "lodash"
import GlobalBus from "./GlobalBus.js"

import dragula from "dragula"
import NewsItemHandle from "./NewsItemHandle.vue"

export default {
  el: '#composer-toplevel',
  props: {
    /**
     * The time after the last user action when the page will reload
     *
     * This acts as a default value only, as some operations (e.g. inserts)
     * are not made in the DOM faithfully enough (or at all). These will
     * instead require  an immediate reload.
     */
    standardIdleDelay: {
      type: Number,
      default: 2000
    }
  },
  data: () => ({
    /**
     * The ordered list of NewsItemHandle children
     */
    news: null,
    /**
     * The serializable state of the composer.
     *
     * If different from the original state, will be submitted right away
     * as an AJAX POST followed by page reload
     */
    serverState: null
  }),
  computed: {
    /**
     * The most recent server-side state of the composer.
     *
     * Flows to serverState after user is inactive for `commitDelay`
     * milliseconds
     */
    _tmpServerState () {
      if (this.news === null) return null   // Not fully initialized yet
      let newsIds = _.map(this.news, (n) => parseInt(n.postId))
      return { news: newsIds }
    }
  },
  components: {
    /* Vue magically maps the NewsItemHandle class
       to <news-item-handle> in the HTML and so on */
    NewsItemHandle
  },
  mounted: function() {
    GlobalBus.registerRootComponent(this)
    GlobalBus.setStandardIdleDelay(this.standardIdleDelay)

    let vm = this
    vm.$nextTick(() => {
      // Documentation says children should be mounted too by now.
      let newsletterTbody = $("#composer-toplevel > tbody")
      vm.dragula = dragula(
        newsletterTbody.toArray(),
        {
          invalid (el) {
            // Pieces without a NewsItemHandle (e.g. the big image
            // at the top) may not move
            return ! NewsItemHandle.findUnder(el).length
          }
        }
      )
      vm.dragula.on("drop", () => {
        GlobalBus.$emit("dom_reordered")
      })
    })
  }
}
</script>

<style lang="scss">
@import "~dragula/dist/dragula";
</style>
