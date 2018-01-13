import Vue from 'vue'
import Composer from "./vue/Composer.vue"
import Loading from "./vue/Loading.vue"
import WPajax from "./inc/ajax.js"
import _ from 'lodash'

// Make all <b-foo> elements available everywhere:
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

$(($) => {
  let composer = new Vue(Composer)
  composer.$watch("serverState", function(newState, oldState) {
    if (oldState === null || _.isEqual(newState, oldState)) return

    let loading = new Vue(Loading).$mount($('<div />').appendTo('body')[0])
    WPajax("epfl_sti_newsletter_draft_save", newState)
      .then(response => {
        location.reload()
      })
      .catch(e => {
        console.log("AJAX: Unable to save:", e)
        alert("AJAX: Unable to save: " + e.status + " " + e.statusText)
        let loadingEl = loading.$el
        loading.$destroy()
        $(loadingEl).remove()
      })
  })
})
