<!--
  The form that pops up when one clicks on the "+" buttons
-->

<template>
<div class="text-center my-3">
  <b-btn v-b-popover.hover="'I am popover content!'" title="Popover Title">Hover Me</b-btn>
</div>
</template>

<script>
export default {
  methods: {
    close () {
      this.$emit("close")
    }
  }
}
</script>

<style scoped>
form {
}
button {
  position: absolute;
  background-color: red;
  top: 5px;
  right: 5px;
}
</style>
