<!--
    Edit handle for news items.

    Permits deletion, editing (to replace with another news item).
-->

<template>
<div class="news-item-handle">
  <b-btn v-b-toggle.collapse variant="primary">
    <i class="fa fa-edit"></i>
    <translate>Edit</translate>
    <span class="arrow">→</span>
  </b-btn>
  <b-collapse ref="theCollapse" id="collapse">
    <b-card>
      <select2-demo :offline="false"></select2-demo>
    </b-card>
  </b-collapse>
</div>
</template>

<script>
import _ from 'lodash'
import Vue from 'vue'
import select2 from "./Select2.vue"
import Select2Demo from "./tests/Select2Demo.vue"

export default {
  props: {
    postId: {
      type: String,
      required: true
    }
  },

  methods: {
    editUrl () {
      return "wp-admin/post.php?post=" + this.postId + "&action=edit";
    },
    doSearch () {
    }
  },

  components: {select2, 'select2-demo': Select2Demo},

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
