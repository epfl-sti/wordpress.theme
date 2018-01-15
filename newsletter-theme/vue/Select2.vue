<!--

select2 in vue!

Use like this:

Events:

- input: A new result has been selected

- search: The user is typing into the search box. Params are
          (query, pageno, that). Calling that.success
          with arguments provides the data to jQuery's select2 like
          returning from an AJAX search with custom transport would.
          Calling that.success with no arguments (or not calling it at
          all) results in the results being parsed out of the DOM
          again when `update` Vue lifecycle hook fires. Calling that.fail
          (with optional argument) displays an error.

Known bugs:

- There is support for displaying custom messages (with the "message"
  slot), but it doesn't work reliably so it is turned off

More info:

- select2: https://select2.org/

- Embedding in Vue: https://vuejs.org/v2/examples/select2.html

 -->

<template>
<div>
  <select ref="select">
    <slot name="results" :done="resultsAreRendered()"></slot>
  </select>
</div>
</template>

<script>
import select2 from 'select2/dist/js/select2.full.js';
import _ from 'lodash';

/**
 * Do what select2 does at the initialization in the AJAX-less case
 *
 * Inspired from SelectAdapter.prototype.query
 */
function parseSelect (el) {
    var data = [];

    $(el).children().each(function () {
      // Filtering options and optgroups is done in parseItem

      // Original code: var option = self.item($option);
      var option = parseItem($(this));

      // We don't need the matcher business here.
      if (option !== null) {
        data.push(option);
      }
    });
    return data;
}

/**
 * Parse an <option> or <optgroup> like select2 does
 *
 * Inspired from SelectAdapter.prototype.item
 */
function parseItem ($option) {
    if (!$option.is('option') && !$option.is('optgroup')) {
      return;
    }

    var data = {};

    // Cache read (Utils.GetData) removed

    if ($option.is('option')) {
      data = {
        id: $option.val(),
        disabled: $option.prop('disabled'),
        selected: $option.prop('selected'),
        text:     $option.text(),
        contents: $option.children()
      };
    } else if ($option.is('optgroup')) {
      data = {
        text: $option.prop('label'),
        children: [],
        title: $option.prop('title'),
        contents: $option.children(':not(option)')
      };

      var $children = $option.children('option');
      var children = [];

      for (var c = 0; c < $children.length; c++) {
        var $child = $($children[c]);

        var child = parseItem($child);

        children.push(child);
      }

      data.children = children;
    }

    // Assuming this is not needed:
    // data = this._normalizeItem(data);
    data.element = $option[0];

    // Cache write (Utils.StoreData) removed

    return data;
}

const VueResultsAdapter = {
  hook (amd) {
    amd.define(
      'VueResultsAdapter',
      ['select2/utils', 'select2/results', 'select2/dropdown/infiniteScroll',
       'select2/dropdown/hidePlaceholder', 'select2/dropdown/selectOnClose'],
      function (Utils, Results, InfiniteScroll,
                HidePlaceholder, SelectOnClose) {

        function VueResultsDecorator (decorated, $element, options, dataAdapter) {
          decorated.call(this, $element, options, dataAdapter)
          var self = this
          $element.data('vue-select2-message', function ($msg) {
            // This sort-of works, but calling it closes the search
            // box so we don't use it.
            if (! self.$results) {
              return  // Too soon
            } else if ($msg) {
              self.displayMessage({message: $msg})
            } else {
              self.hideMessages()
            }
          })
        }

        VueResultsDecorator.prototype.bind = function (decorated, container, $container) {
          decorated.call(this, container, $container)
          // We manage messages ourselves:
          container.listeners['results:message'] = []
        }

        VueResultsDecorator.prototype.template = function (decorated, result, container) {
          if (! result.contents) return
           $(container).append(result.contents.clone());
        }

        let adapter = Utils.Decorate(Results, VueResultsDecorator);
        adapter     = Utils.Decorate(adapter, InfiniteScroll);
        // adapter     = Utils.Decorate(adapter, HidePlaceholder);
        // adapter     = Utils.Decorate(adapter, SelectOnClose);
        return adapter;
      })
  }
}

export default {
  props: {
    value: String,
    theme: {
      type: String,
      default: "bootstrap4"
    },
    /* Whether the last search has more */
    more: {
      type: Boolean,
      default: false
    },
    /* The time to wait for the user to stop typing, before calling
     * the "search" function above */
    delay: {
      type: Number,
      default: 250
    }
  },
  mounted: function () {
    if (! $.fn.select2) {
      // Load the jQuery plug-in. Inferred from reading the source:
      require("select2")(
        /* root = */ null,  // I have no idea what that parameter is for
        $)
      VueResultsAdapter.hook($.fn.select2.amd)
    }
    let vm = this
    $(this.$refs.select)
      // init select2
      .select2({
        theme: this.theme,
        data: this.options,
        resultsAdapter: $.fn.select2.amd.require("VueResultsAdapter"),
        ajax: {
          delay: this.delay,
          url: (element, data) => data,
          transport(params, success, fail) {
            success = _.once(success)
            fail    = _.once(fail)
            vm._currentSearch = {
              success () {
                if (arguments.length) {
                  delete vm._currentSearch
                  success.apply({}, arguments)
                } else {
                  // Parent had the courtesy to call success() but
                  // did not provide results; expect them in
                  // the next DOM update (see "update", below)
                  // In the mean time the search remains active, i.e.
                  // we do nothing
                }
              },
              fail () {
                delete vm._currentSearch
                fail.apply({}, arguments)
              },
              cancel () {
                success([])
                delete vm._currentSearch
              }
            }
            vm.$nextTick(() => {
              if (vm._currentSearch) {
                vm.$emit("search", params.data.term, params.data.page,
                         vm._currentSearch)
              }
            })
            return vm._currentSearch
          }
        }
      })
      .val(this.value)
      .trigger('change')
      // emit event on change.
      .on('change', function () {
        vm.$emit('input', this.value)
      })
  },

  methods: {
    messageIsRendered () {
      return function () {
          console.log("Message is rendered");
      }
    },
    resultsAreRendered () {
      var vm = this
      return function () {
        if (! vm._currentSearch) return

        vm.$nextTick(() => {
          const results = parseSelect(vm.$refs.select)
          vm._currentSearch.success({results, pagination: {more: vm.more}})
        })
      }
    }
  },

  watch: {
    /**
     * When parent component updates value, let the select2 jQuery code know
     */
    value (value) {
      $(this.$el).val(value)
    }
  },
  destroyed: function () {
    $(this.$el).off().select2('destroy')
  }
}
</script>

<style lang="scss">
@import "~select2/dist/css/select2";
@import "~select2-bootstrap4-theme/dist/select2-bootstrap4";
</style>
