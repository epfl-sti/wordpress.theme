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
import ItemHandle from "./ItemHandle.vue"

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
  components: {
    /* Vue magically maps the NewsItemHandle class
       to <news-item-handle> in the HTML and so on */
    NewsItemHandle: ItemHandle.News
  },
  mounted: function() {
    GlobalBus.registerRootComponent(this)
    GlobalBus.setStandardIdleDelay(this.standardIdleDelay)

    let vm = this, console = window.console
    vm.$nextTick(() => {
      // Documentation says children should be mounted too by now.
      let mainTbody = $("#composer-toplevel > tbody")[0],
          newsTbody = $("#news-main > tbody")[0]
      vm.dragula = dragula(
        [mainTbody, newsTbody],
        {
          invalid (el) {
            // Experience revals that the "invalid" check bubbles up
            // the DOM tree, and stops as soon as something returns
            // true. If el is not a <tr>, we don't want to make a
            // decision now:
            if (! $(el).is("tr")) { return }

            console.log("Attempting to drag", el)
            // Pieces without a NewsItemHandle (e.g. the big image
            // at the top) may not move
            if (! ItemHandle.News.findUnder(el).length) { return true }

            // The big table at the bottom may not move as a whole
            if ($("#news-main", el).length) { return true }
            console.log("Drag permitted")
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
