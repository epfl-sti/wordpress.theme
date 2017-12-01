<template>
  <p>{{ greeting }} World!</p>
</template>

<script>
  module.exports = {
    data () {
      return {
        greeting: "hello"
      }
    }
  }
</script>

<style scoped>
  p {
    text-color: blue;
  }
</style>
