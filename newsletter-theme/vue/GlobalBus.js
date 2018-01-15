/**
 * Everyone imports this module and uses it as an event bus
 * (https://alligator.io/vuejs/global-event-bus/ explains the
 * pattern). Insert witty Fortnite™ joke here.
 *
 * Events received:
 *
 * - dom_reordered: A drag-and-drop operation has made it necessary
 *                 to re-read the state. Parameter is an idle delay
 *                 after which must_save ought to be sent.
 *
 * Events emitted:
 *
 * - must_save: The state has been modified for a long enough time
 *              (depending on the specifics of the UI); it is time to
 *              POST it and reload the page. Parameter is the new
 *              state.
 */

import _ from "lodash"
import Vue from 'vue'
import NewsItemHandle from "./NewsItemHandle.vue"

function updateNewsOrder (vm) {
  vm.$set(vm, 'news', NewsItemHandle.findUnder(vm))
}

const GlobalBus = new Vue({
  data: () => ({
    /**
     * The serializable state of the entire composer.
     *
     * If different from the original state, will be submitted right away
     * as an AJAX POST followed by page reload
     */
    state: null,
    /**
     * The serializable state of the entire composer, at the time
     * the page was loaded.
     */
    initialState: null
  }),

  methods: {
    registerRootComponent (vm) {
      this._rootComponent = vm
      this.$nextTick(() => {
        // Documentation says children should be mounted too by now.
        this.initialState = this._readState()
        this.state = this.initialState
      })
    },
    _readState () {
      return {
        news: _.map(
          NewsItemHandle.findUnder(this._rootComponent),
          (news) => parseInt(news.postId))
      }
    },
    setIdleDelay (delay) {
      if (this._timeout) clearTimeout(this._timeout)
      if (delay === 0) {
        this._saveNow()
      } else {
        this._timeout = setTimeout(this._saveNow.bind(this), delay)
      }
    },
    _saveNow () {
      if (JSON.stringify(this.initialState) ===
          JSON.stringify(this.state)) return
      this.$emit("must_save", this.state)
    }
  }
})

GlobalBus.$on("dom_reordered", function () {
  this.state = this._readState()
})

export default GlobalBus

