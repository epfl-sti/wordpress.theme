<template>
  <div>
    <item-picker></item-picker>
    <refresh-button :state="$data"></refresh-button>
  </div>
</template>

<script>
  import WPajax from "../inc/ajax.js"
  import ItemPicker from "./ItemPicker.vue"
  import RefreshButton from "./RefreshButton.vue"

  export default {
    data () {
      return {
        hero: undefined,
        volumeNo: 1,
        issueNo: 1,
        news: [],
        events: [],
        openPositions: [],
        inTheMedia: []
      }
    },
    components: {
        'item-picker': ItemPicker,
        'refresh-button': RefreshButton
    }
  }
</script>

<style scoped>
  p {
    color: blue;
  }
</style>
