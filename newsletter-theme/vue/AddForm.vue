<!--
  The form that pops up when one clicks on the "+" buttons
-->

<template>
<form>
  HELLO
  <i class="fa fa-times" aria-hidden="true"></i>
</form>
</template>

<script>
export default {
  methods: {
    close () {
      this.$emit("close")
    }
  }
}
<script>
