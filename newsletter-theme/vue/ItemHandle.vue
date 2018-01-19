<!--
    Edit handles for news / memento / in-the-news articles.

    Permits search-as-you-type insertion, and deletion (unless the
    `postId` prop is undefined, in which case an insert-only widget is
    rendered)

    The default import an "abstract base class" that *cannot* be
    instantiated directly. Instead, instantiate "subclasses" that
    are available as ItemHandle.News, ItemHandle.Event etc.

-->

<template>
<div :class="toplevelDivClass">
  <b-btn v-if="postId !== undefined" @click="doDelete"
         :size="bootstrapSize" variant="danger">
    <i class="fa fa-trash"></i>
    <translate v-if="bootstrapSize !== 'sm'">Delete</translate>
  </b-btn>
  <b-btn v-b-toggle="'collapse' + id"
         :size="bootstrapSize" variant="primary">
    <i class="fa fa-edit"></i>
    <translate v-if="(postId === undefined) || (bootstrapSize !== 'sm')">Insert</translate>
    <span class="arrow">→</span>
  </b-btn>
  <b-collapse ref="theCollapse" :id="'collapse' + id">
    <b-card>
      <select2 v-model="picked" @singlesearch="doSearch" :search="search" :status="status">
        <template slot="results" slot-scope="select2" v-if="select2.search">
          <option v-for="item in select2.search.items" :value="item.ID">
            <div>
              <img v-if="item.thumbnail_url" :src="item.thumbnail_url">
              <b>#{{item.ID}} {{ item.post_title }}</b>
              <br/>
            <!--              <p v-html="findInContext(searchText, item.post_excerpt, contextWords)"></p>
                              <p v-html="findInContext(searchText, item.post_content, contextWords)"></p>
-->
            </div>
          </option>
        </template>
      </select2>
    </b-card>
  </b-collapse>
</div>
</template>

<script>
import _ from 'lodash'
import Vue from 'vue'
import select2 from "./Select2.vue"
import highlightKeywordHTML from "../inc/highlight.js"
import GlobalBus from "./GlobalBus.js"
import WPajax from "../inc/ajax.js"

/**
 * The "base class"
 *
 *
 *   In order to be useable, this component need to be **mixed in** with
 *   additional props and methods — See mixinify()
 */
let ItemHandleBase = {
  props: {
    postId: {
      type: Number,
      // If missing, this is an insert-only handle
    }
  },

  data() {
    return {
      search: null,
      status: null,
      picked: null,
      items: [],
      id: this._uid
    }
  },

  methods: {
    editUrl () {
      return "wp-admin/post.php?post=" + this.postId + "&action=edit";
    },
    doSearch(term, page) {
      let vm = this

      if (!term || term.length < 3) return
      // let Select2 know that we are starting an asynchronous search
      vm.search = {}
      vm.status = null

      WPajax("epfl_sti_newsletter_" + vm.modelMoniker + "_search", { s: term })
      .then(response => {
        vm.search.items = response.searchResults
        vm.status = "success"
      })
      .catch(e => {
        console.log(e)
        alert("Search error: " + e.statusText)
        vm.search = null
      })
    },
    findInContext (kw, text, contextWords) {
      if (! kw || ! text || ! contextWords) {
        return text
      }
      return highlightKeywordHTML(
        text, kw, {contextWords})
    },
    doDelete () {
      let parentTr = $(this.$el).closest('tr')
      this.$destroy()
      parentTr.remove()
      GlobalBus.$emit("dom_reordered")
    },
    findUnder (under) {
      if (under instanceof Vue) under = under.$el
      return _.map($("div." + this.toplevelDivClass, under),
                   (jq) => $(jq).prop("__vue__"))
    }
  },

  components: {select2},

  watch: {
    picked (newVal) {
      if (! newVal) return
      GlobalBus.$emit("insert_after", this, Number(newVal))
    }
  },
}


/**
 * Factory that makes working "concrete subclasses"
 *
 * @param methodsAndProps shall ontain the following:
 *
 *                - toplevelDivClass: a CSS class name as a string
 *
 *                - modelMoniker: a string in plural form e.g. "news", "events"
 *
 *                - bootstrapSize: "md", "sm" etc. or "" to use the default
 *                                          
 *
 * @return Something that can be fed into the Vue constructor
 */
function mixinify(methodsAndProps) {
  let methods = _.pickBy(methodsAndProps, (f) => f instanceof Function),
      props   = _.pickBy(methodsAndProps, (f) => !(f instanceof Function))

  let propsWithDefaults = _.mapValues(props, (prop) => ({ default: prop }))

  let theNewClass = _.extend(
    {mixins: [ItemHandleBase, { methods, props: propsWithDefaults }]},
    props)   // Make them available as "class props"

  methods.isInstanceOf = (aClass) => theNewClass === aClass

  /* Allow findUnder to be used as a "class method" */
  let fakeThis = _.extend({}, methods, props)
  theNewClass.findUnder = function (under) {
    return ItemHandleBase.methods.findUnder.call(fakeThis, under)
  }
  return theNewClass
}

ItemHandleBase.News = mixinify({
  toplevelDivClass: "news-item-handle",
  modelMoniker: "news",
  bootstrapSize: ""
})

ItemHandleBase.Event = mixinify({
  toplevelDivClass: "event-handle",
  modelMoniker: "events",
  bootstrapSize: "sm"
})

ItemHandleBase.Media = mixinify({
  toplevelDivClass: "media-item-handle",
  modelMoniker: "media",
  bootstrapSize: "sm"
})

ItemHandleBase.Faculty = mixinify({
  toplevelDivClass:  "faculty-position-handle",
  modelMoniker: "faculty",
  bootstrapSize: ""
})

export default ItemHandleBase  // But don't try to instantiate it

</script>

<style lang="scss" scoped>
div {
  margin-top: 1em;
}
span.arrow {
  transition: transform 150ms ease;
  display: inline-block;
  transform: rotate( -90deg );
}

button.collapsed span.arrow {
  transform: rotate( 0deg );
}

</style>
