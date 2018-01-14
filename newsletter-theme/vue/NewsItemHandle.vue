<!--
    Edit handle for news items.

    Permits deletion, editing (to replace with another news item).
-->

<template>
<div class="news-item-handle">
  <b-dropdown id="ddown-aria" :text="$gettext('Edit')" variant="primary">
    <template slot="button-content">
      <i class="fa fa-edit"></i>
      <translate>Edit</translate>
    </template>
  </b-dropdown>
</div>
</template>

<script>
import _ from 'lodash'
import Vue from 'vue'
import AddForm from './AddForm.vue'

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
  margin-top: 1em;
}
</style>
