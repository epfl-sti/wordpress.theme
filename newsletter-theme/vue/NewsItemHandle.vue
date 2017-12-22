<!--
    Edit handle for news items.

    Permits deletion, editing (to replace with another news item).
-->

<template>
  <div>Edit news ID {{postId}}</div>
</template>

<script>
export default {
  props: {
    postId: {
      type: String,
      required: true
    }
  },
  methods: {
    postType: () => "News"
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
