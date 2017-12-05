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
  module.exports = {
    data () {
      return {
        greeting: "hello",
        number: 0,
        plusten: 0,
        errors: []
      }
    },
    props: ['wpAjaxUrl'],
    methods: {
      numberChanged () {
        var self = this;
        console.log("numberChanged() for " + this.number + ", calling " + self.wpAjaxUrl);
        $.post({
          url: self.wpAjaxUrl,
          dataType: "json"
        }, {
          "action": "epfl_sti_newsletter_addten",
          "number": this.number
        })
        .done(response => {
          console.log(response);
          console.log(response.number);
          self.plusten = response.number
        })
        .fail(e => {
          self.errors.push(e)
        })
      }
    }
  }
</script>

<style scoped>
  p {
    text-color: blue;
  }
</style>
