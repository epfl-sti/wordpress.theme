<!--
    Edit handle for news items.

    Permits deletion, editing (to replace with another news item).
-->

<template>
  <div class="news-item-handle">Edit news ID {{postId}}</div>
</template>

<script>
import Vue from 'vue'
import _ from "lodash"

export default {
  props: {
    postId: {
      type: String,
      required: true
    }
  },
  methods: {
    postType: () => "News"
  },
  findUnder (under) {
    if (under instanceof Vue) under = under.$el
    return _.map($("div.news-item-handle", under),
                 (jq) => $(jq).prop("__vue__"))
  }
}
</script>

<style scoped>
div {
  border: 1px solid red;
  padding: 4px;
  display: inline-block;
}
</style>
