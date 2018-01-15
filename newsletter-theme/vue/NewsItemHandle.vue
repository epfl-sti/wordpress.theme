<!--
    Edit handle for news items.

    Permits deletion, editing (to replace with another news item).
-->

<template>
<div class="news-item-handle">
  <b-btn v-b-toggle="'collapse' + id" variant="primary">
    <i class="fa fa-edit"></i>
    <translate>Insert</translate>
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
import WPajax from "../inc/ajax.js"
import highlightKeywordHTML from "../inc/highlight.js"
import GlobalBus from "./GlobalBus.js"

export default {
  props: {
    postId: {
      type: String,
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

      WPajax("epfl_sti_newsletter_search",
             {
               post_type: "epfl-actu",
               s: term
             })
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
    }
  },

  components: {select2},

  watch: {
    picked (newVal) {
      if (! newVal) return
      GlobalBus.$emit("insert_news_after", this, Number(newVal))
    }
  },

  /**
   * "Class method" available to parent Component
   *
   * @return A list of NewsItemHandle instances under @param under
   */
  findUnder (under) {
    if (under instanceof Vue) under = under.$el
    return _.map($("div.news-item-handle", under),
                 (jq) => $(jq).prop("__vue__"))
  }
}
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
