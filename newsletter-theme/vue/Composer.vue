<template>
  <div>
    <p>{{ greeting }} World!</p>
    <input type="text" v-model="number" @change="numberChanged"/>
    <p>+ 10 = {{ plusten }}</p>
    <ul v-if="errors && errors.length">
      <li v-for="error of errors">
        {{error.message}}
      </li>
    </ul>
  </div>
</template>

<script>
  import WPajax from "./inc/ajax.js"

  export default {
    data () {
      return {
        greeting: "hello",
        number: 0,
        plusten: 0,
        errors: []
      }
    },
    methods: {
      numberChanged () {
        var self = this;
        WPajax({
          "action": "epfl_sti_newsletter_addten",
          "number": this.number
        })
        .then(response => {
          self.plusten = response.number
        })
        .catch(e => {
          self.errors.push(e)
        })
      }
    }
  }
</script>

<style scoped>
  p {
    color: blue;
  }
</style>
