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
import Debouncer from "./Debouncer.js"

import dragula from "dragula"
import NewsItemHandle from "./NewsItemHandle.vue"
import NewsItemMore from "./NewsItemMore.vue"

function updateNewsOrder (vm) {
  vm.$set(vm, 'news', NewsItemHandle.findUnder(vm))
}

export default {
  el: '#composer-toplevel',
  props: {
    /**
     * The time after the last user input when the page will reload
     */
    commitDelay: {
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
    NewsItemHandle, NewsItemMore
  },
  mounted: function() {
    this._priv = {}
    this._priv.debouncer = new Debouncer()
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
  },
  watch: {
    // After a while, _tmpServerState flows to serverState
    _tmpServerState (newState, oldState) {
      console.log("New state!", newState, "(was ", oldState, ")")

      if (this.serverState === null) {
        // First time around: propagate right away
        // Note that the serverState watch detects this case and
        // does nothing
        this.serverState = _.cloneDeep(newState)
      } else {
        // Same, but after a small delay
        let $this = this
        $this._priv.debouncer.after($this.commitDelay, function() {
          console.log(newState, "now flows to serverState")
          $this.serverState = { news: newState.news }
        })
      }
    }
  }
}
</script>

<style lang="scss">
@import "~dragula/dist/dragula";
</style>
