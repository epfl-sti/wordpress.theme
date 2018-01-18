<!--
    Edit handles for news / memento / in-the-news articles.

    Permits deletion, search-as-you-type insertion.

    The default import an "abstract base class" that *cannot* be
    instantiated directly. Instead, instantiate "subclasses" that
    are available as ItemHandle.News, ItemHandle.Event etc.

-->

<template>
<div :class="getToplevelDivClass()">
  <b-btn @click="doDelete" variant="danger">
    <i class="fa fa-trash"></i>
    <translate v-if="getSize() !== 'small'">Delete</translate>
  </b-btn>
  <b-btn v-b-toggle="'collapse' + id" variant="primary">
    <i class="fa fa-edit"></i>
    <translate v-if="getSize() !== 'small'">Insert</translate>
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
 *   the following additional methods:
 *
 *   - getToplevelDivClass()
 *
 *   - getSearchPromise(term)
 *
 *   - getSize()  // Should return a Bootstrap size e.g. "" or "sm"
 */
let ItemHandleBase = {
  props: {
    postId: {
      type: Number,
      required: true
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

      vm.getSearchPromise(term)
      .then(response => {
        vm.search.items = response.searchResults
        vm.status = "success"
      })
      .catch(e => {
        alert("Search error: " + e)
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
      console.log(parentTr)
      this.$destroy()
      parentTr.remove()
      GlobalBus.$emit("dom_reordered")
    },
    findUnder (under) {
      if (under instanceof Vue) under = under.$el
      return _.map($("div." + this.getToplevelDivClass(), under),
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
 * @param methods Contains the following functions:
 *
 *                - getToplevelDivClass()
 *
 *                  Returns a CSS class name
 *
 *                - getSearchPromise(term)
 *
 *                  Returns a promise of a data structure like
 *                  { searchResults: [item, item...] }
 *                                          
 *
 * @return Something that can be fed into the Vue constructor
 */
function mixinify(methods) {
  methods = _.extend({}, methods)

  let theNewClass = {
    mixins: [ItemHandleBase, { methods }]
  }

  methods.isInstanceOf = (aClass) => theNewClass === aClass

  /* Allow findUnder to be used as a "class method" */
  theNewClass.findUnder = function (under) {
    let fakeThis = methods
    return ItemHandleBase.methods.findUnder.call(fakeThis, under)
  }
  return theNewClass
}

ItemHandleBase.News = mixinify({
    getToplevelDivClass () { return "news-item-handle" },
    getSearchPromise(term) {
        return WPajax(
            "epfl_sti_newsletter_search",
            {
                post_type: "epfl-actu",
                s: term
            })
    },
    getSize() { return "normal" }
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
