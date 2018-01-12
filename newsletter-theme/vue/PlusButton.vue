<!--
    A button to add more things

    This is behavior-free; caller provides the @click.
-->
<template>
    <button @click="propagateClick"><i class="fa fa-plus-square"></i></button>
</template>

<script>
export default {
  methods: {
    // I assume there has to be a better way than having to repeat
    // every single event to be propagated upwards. But this will do
    // for us, as we only ever @click on a PlusButton.
    propagateClick () {
      this.$emit("click")
    }
  }
}
</script>
