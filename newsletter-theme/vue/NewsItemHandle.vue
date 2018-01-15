<!--
    Edit handle for news items.

    Permits deletion, editing (to replace with another news item).
-->

<template>
<div class="news-item-handle">
  <b-btn v-b-toggle="'collapse' + id" variant="primary">
    <i class="fa fa-edit"></i>
    <translate>Edit</translate>
    <span class="arrow">→</span>
  </b-btn>
  <b-collapse ref="theCollapse" :id="'collapse' + id">
    <b-card>
      <select2 v-model="picked" @search="doSearch" :more="more">
        <template slot="results" slot-scope="snitch">
          <option slot="results" v-for="item in items" :value="item.ID">
            <div>
              <img v-if="item.thumbnail_url" :src="item.thumbnail_url">
              <h3><b>#{{item.ID}}</b> {{ item.post_title }}</h3>
              <span v-html="findInContext(searchText, item.post_excerpt, contextWords)"></span><br/>
              <span v-html="findInContext(searchText, item.post_content, contextWords)"></span>
            </div>
          </option>
          {{ snitch.done() }}
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

export default {
  props: {
    postId: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      loading: false,
      picked: null,
      items: [],
      id: this._uid
    }
  },

  methods: {
    editUrl () {
      return "wp-admin/post.php?post=" + this.postId + "&action=edit";
    },
    doSearch(term, page, ops) {
      let vm = this
      if (vm.loading) return
      if (!term || term.length < 3) {
        ops.cancel()
        return
      }

      vm.loading = true
      WPajax("epfl_sti_newsletter_search",
             {
               post_type: "epfl-actu",
               s: term
             })
      .then(response => {
        this.items = response.searchResults
        vm.loading = false
        ops.success()
      })
      .catch(e => {
        alert("Search error: " + e)
        vm.loading = false
        ops.fail(e)
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
      console.log(newVal)
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
