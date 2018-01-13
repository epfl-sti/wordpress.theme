<!--
    Edit handle for news items.

    Permits deletion, editing (to replace with another news item).
-->

<template>
  <div class="news-item-handle">
    <a :href="editUrl()" target="_blank"><button><i class="fa fa-edit"></i></button></a>
    <button @click="buttonTrash"><i class="fa fa-trash"></i></button>
    <plus-button @click="$data._isShowingAddForm = true" />
    <add-form v-show="$data._isShowingAddForm" @close="$data._isShowingAddForm = false"></add-form>
  </div>
</template>

<script>
import Vue from 'vue'
import AddForm from './AddForm.vue'
import _ from "lodash"

import PlusButton from "./PlusButton.vue"

export default {
  props: {
    postId: {
      type: String,
      required: true
    }
  },
  data: () => ({
    _isShowingAddForm: false
  }),
  components: { PlusButton, AddForm },
  methods: {
    postType: () => "News",
    buttonTrash () {
      console.log(this)
    },
    buttonPlus () {
      console.log("+")
    },
    editUrl () {
      return "wp-admin/post.php?post=" + this.postId + "&action=edit";
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
  position: relative;  /* Acts as the anchor for <add-form> */
}
</style>

<style lang="scss">
</style>
