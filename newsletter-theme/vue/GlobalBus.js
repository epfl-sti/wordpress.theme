/**
 * Everyone imports this module and uses it as an event bus
 * (https://alligator.io/vuejs/global-event-bus/ explains the
 * pattern). Insert witty Fortnite™ joke here.
 *
 * Events emitted:
 *
 * - must_save: The state has been modified for a long enough time
 *              (depending on the specifics of the UI); it is time to
 *              POST it and reload the page. Parameter is the new
 *              state.
 *
 * Events received: see docstrings on "$on" invocations, below.
 */

import _ from "lodash"
import Vue from 'vue'
import ItemHandle from "./ItemHandle.vue"

const stateTraits = {
    news:    ItemHandle.news,
    events:  ItemHandle.Event,
    media:   ItemHandle.Media,
    faculty: ItemHandle.Media,
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
    initialState: null,
    /**
     * The time after the last user action when the page will reload
     *
     * This acts as a default value only, as some operations (e.g. inserts)
     * are not made in the DOM faithfully enough (or at all). These will
     * instead require  an immediate reload.
     */
    standardIdleDelay: null
  }),

  methods: {
    registerRootComponent (vm) {
      this._rootComponent = vm
      this.$nextTick(() => {
        // Documentation says children should be mounted too by now.
        this.initialState = this._readState()
        this.state = _.cloneDeep(this.initialState)
      })
    },
    setStandardIdleDelay (delayMs) {
      this.standardIdleDelay = delayMs
    },
    _readState () {
      const vm = this
      return _.mapValues(
          stateTraits,
          (theClass, key) => _.map(
              theClass.findUnder(vm._rootComponent),
              (item) => parseInt(item.postId)
          )
      )
    },
    _setIdleDelay (delay) {
      if (this._timeout) clearTimeout(this._timeout)
      if (delay === 0) {
        this._saveNow()
      } else {
        this._timeout = setTimeout(this._saveNow.bind(this), delay)
      }
    },
    _saveNow () {
      if (_.isEqual(this.initialState, this.state)) return
      this.$emit("must_save", this.state)
    }
  }
})

/**
 * A drag-and-drop operation has made it necessary to re-read the
 * state. Parameter is an idle delay after which must_save ought to be
 * sent.
 */
GlobalBus.$on("dom_reordered", function () {
  this._setIdleDelay(this.standardIdleDelay)
  this.state = this._readState()
})

/**
 * A new article / news / event with ID @new_id is being added right
 * after @param vm. We should reload immediately.
 */
GlobalBus.$on("insert_after", function (vm, new_id) {
    let i = _.indexOf(vm.findUnder(this._rootComponent), vm)

    _.mapKeys(stateTraits, function(theClass, key) {
        if (vm.isInstanceOf(theClass)) {
            this.state.splice(i + 1, 0, new_id)
        }
    })
    this._setIdleDelay(0)
})

export default GlobalBus

