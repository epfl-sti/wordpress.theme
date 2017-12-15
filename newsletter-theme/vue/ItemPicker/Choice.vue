<template>
    <div>
    <b>#{{item.ID}}</b>
    <abbr>{{ item.title }}</abbr>
    <span v-html="find_in_context(searchText, item.post_excerpt, contextWords)"></span><br/>
    <span v-html="find_in_context(searchText, item.post_content, contextWords)"></span>
  </div>
</template>
<script>
import highlightKeywordHTML from "../../inc/highlight.js"

export default {
  props: {
    item: { required: true },
    searchText: { required: true },
    contextWords: {
        type: Number,
        default: 3
    }
  },
  methods: {
    find_in_context: (kw, text, contextWords) => {
        if (! kw || ! text || ! contextWords) {
            debugger
            return text
        }
        return highlightKeywordHTML(
            text, kw, {contextWords})
    }
  }
}
</script>
