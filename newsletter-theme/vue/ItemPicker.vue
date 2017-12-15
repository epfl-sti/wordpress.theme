<template>
  <v-autocomplete :items="items" :get-label="getLabel" :component-item='componentItemTemplate' @update-items="updateItems">
  </v-autocomplete>
</template>

<script>
  import Vue from 'vue'
  import Autocomplete from 'v-autocomplete'
  Vue.use(Autocomplete)

  import WPajax from "../inc/ajax.js"
  import ItemPickerCandidate from './ItemPicker/Choice.vue'

  export default {
      data () {
          return {
              item: null,
              items: [],
              componentItemTemplate: ItemPickerCandidate
          }
      },
      methods: {
          getLabel: item => (item ? item.name : "..."),
          updateItems (text) {
              WPajax({
                  "action": "epfl_sti_newsletter_search",
                  "postType": "epfl-actu",
                  "searchTerm": text
              })
              .then( response => {
                  this.items = response.searchResults
              })
              .catch( e => {
                  alert("Search error: " + e)
              })
          }
      }
  }
</script>

