<template>
  <div>
    <item-picker></item-picker>
  </div>
</template>

<script>
  import WPajax from "../inc/ajax.js"
  import ItemPicker from "./ItemPicker.vue"

  export default {
    data () {
      return {
      }
    },
    components: {
        'item-picker': ItemPicker
    }
  }
</script>

<style scoped>
  p {
    color: blue;
  }
</style>
