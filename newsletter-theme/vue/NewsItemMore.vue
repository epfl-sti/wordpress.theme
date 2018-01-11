<!--
    The widget at the bottom of the news main matter, that lets you add more
-->
<template>
  <plus-button @click="buttonPlus" />
</template>

<script>
import PlusButton from "./PlusButton.vue"

export default {
  components: {
    PlusButton
  },
  methods: {
    buttonPlus () {
      console.log("Moar")
    }
  }
}
</script>
