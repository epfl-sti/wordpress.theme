<!--
    Newsletter composer's top-level component.

    The top-level component doesn't have a template or a render
    function. Instead, it uses the DOM that is already under
    #composer-toplevel as its template, substituting the elements
    listed under "components:" below This allows for the Vue composer
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

export default {
  el: '#composer-toplevel',
  data: () => ({
    news: []
  }),
  components: {
    /* Vue magically maps the NewsItemHandle class
       to <news-item-handle> in the HTML */
    NewsItemHandle
  },
  mounted: function() {
    let $children = this.$children
    this.$nextTick(() => {
      // Documentation says children should be mounted too now.
      // It also says that there is no guarantee on ordering, so
      // we should perhaps sort by DOM order...
      let newsChildren = _.filter($children, (child) => (child.postType && (child.postType() === "News")))
      this.news = _.map(newsChildren, (child) => child.postId)

      this.dragula = dragula([$("tbody", $("#composer-toplevel"))[0]])
    })
  }
}
</script>

<style lang="scss">
@import "dragula/dist/dragula"
</style>
