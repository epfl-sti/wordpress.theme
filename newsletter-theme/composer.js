import Vue from 'vue'
import Composer from "./vue/Composer.vue"
import _ from 'lodash'

$(($) => {
  let composer = new Vue(Composer)
  composer.$watch("serverState", function(newState, oldState) {
    if (oldState === null || _.isEqual(newState, oldState)) return
    alert("Would send data now")
  })
})
