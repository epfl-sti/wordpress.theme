<!--
    Edit handle for news items.

    Permits deletion, editing (to replace with another news item).
-->

<template>
  <div class="news-item-handle">
    News ID {{postId}}
    <button @click="buttonTrash"><i class="fa fa-trash"></i></button>
    <button @click="buttonPlus"><i class="fa fa-plus-square"></i></button>
  </div>
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
    postType: () => "News",
    buttonTrash () {
      console.log(this)
    },
    buttonPlus () {
      console.log("+")
    }
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

<style lang="scss">
</style>
